<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    public function home()
    {
        $featuredRoomTypes = RoomType::withCount('rooms')
            ->orderBy('base_price')
            ->limit(3)
            ->get();

        return view('customer.home', [
            'featuredRoomTypes' => $featuredRoomTypes,
        ]);
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'check_in' => ['nullable', 'date', 'after_or_equal:today'],
            'check_out' => ['nullable', 'date', 'after:check_in'],
            'guests' => ['nullable', 'integer', 'min:1', 'max:12'],
            'room_type_id' => ['nullable', 'exists:room_types,id'],
        ]);

        $checkIn = $validated['check_in'] ?? null;
        $checkOut = $validated['check_out'] ?? null;

        $rooms = Room::with('roomType')
            ->where('status', 'available')
            ->when(!empty($validated['guests']), function ($query) use ($validated) {
                $query->where('max_guests', '>=', $validated['guests']);
            })
            ->when(!empty($validated['room_type_id']), function ($query) use ($validated) {
                $query->where('room_type_id', $validated['room_type_id']);
            })
            ->when($checkIn && $checkOut, function ($query) use ($checkIn, $checkOut) {
                $query->whereDoesntHave('bookings', function ($bookQuery) use ($checkIn, $checkOut) {
                    $bookQuery
                        ->where(function ($statusQuery) {
                            $statusQuery->whereIn('status', ['confirmed', 'checked_in'])
                                ->orWhere(function ($pendingQuery) {
                                    $pendingQuery->where('status', 'pending')
                                        ->where(function ($expiryQuery) {
                                            $expiryQuery->whereNull('payment_expires_at')
                                                ->orWhere('payment_expires_at', '>', now());
                                        });
                                });
                        })
                        ->whereDate('check_in', '<', $checkOut)
                        ->whereDate('check_out', '>', $checkIn);
                });
            })
            ->orderBy('room_number')
            ->paginate(9)
            ->withQueryString();

        $roomTypes = RoomType::orderBy('name')->get();

        return view('customer.search-results', [
            'rooms' => $rooms,
            'roomTypes' => $roomTypes,
            'checkIn' => $checkIn,
            'checkOut' => $checkOut,
            'guests' => $validated['guests'] ?? null,
        ]);
    }

    public function roomDetails(Room $room, Request $request)
    {
        $room->load('roomType');

        return view('customer.room-details', [
            'room' => $room,
            'checkIn' => $request->query('check_in'),
            'checkOut' => $request->query('check_out'),
            'guests' => $request->query('guests'),
        ]);
    }

    public function bookingForm(Room $room, Request $request)
    {
        $room->load('roomType');

        $checkIn = $request->query('check_in');
        $checkOut = $request->query('check_out');

        $nights = $this->calculateNights($checkIn, $checkOut);
        $total = $nights > 0 ? $nights * (float) $room->roomType->base_price : 0;

        return view('customer.booking-form', [
            'room' => $room,
            'checkIn' => $checkIn,
            'checkOut' => $checkOut,
            'guests' => $request->query('guests', 1),
            'nights' => $nights,
            'total' => $total,
        ]);
    }

    public function book(Request $request, Room $room)
    {
        $validated = $request->validate([
            'guest_name' => ['required', 'string', 'max:120'],
            'guest_email' => ['required', 'email', 'max:120'],
            'guest_phone' => ['required', 'string', 'max:30'],
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guests' => ['required', 'integer', 'min:1', 'max:12'],
            'special_requests' => ['nullable', 'string', 'max:1000'],
        ]);

        $book = DB::transaction(function () use ($room, $validated, $request) {
            $lockedRoom = Room::with('roomType')->lockForUpdate()->findOrFail($room->id);

            $this->ensureRoomIsAvailable($lockedRoom, $validated['check_in'], $validated['check_out']);

            $nights = max(1, Carbon::parse($validated['check_in'])->diffInDays(Carbon::parse($validated['check_out'])));
            $total = $nights * (float) $lockedRoom->roomType->base_price;

            $book = Booking::create([
                'user_id' => $request->user()?->id,
                'room_id' => $lockedRoom->id,
                'guest_name' => $validated['guest_name'],
                'guest_email' => $validated['guest_email'],
                'guest_phone' => $validated['guest_phone'],
                'check_in' => $validated['check_in'],
                'check_out' => $validated['check_out'],
                'guests' => $validated['guests'],
                'special_requests' => $validated['special_requests'] ?? null,
                'status' => 'pending',
                'total_amount' => $total,
                'payment_status' => 'pending',
                'payment_reference' => $this->mockPaymentReference(),
                'payment_expires_at' => now()->addMinutes(15),
            ]);

            $lockedRoom->update(['status' => 'booked']);

            return $book;
        });

        return redirect()->route('customer.booking.payment', $book);
    }

    public function paymentPage(Booking $book, Request $request)
    {
        if ((int) $book->user_id !== (int) $request->user()->id) {
            abort(403, 'You are not allowed to access this payment.');
        }

        if ($book->payment_status === 'paid') {
            return redirect()->route('customer.booking.confirmation', $book)
                ->with('success', 'Payment already completed.');
        }

        if ($book->status === 'cancelled' || in_array($book->payment_status, ['failed', 'expired'], true)) {
            return redirect()->route('customer.history')
                ->with('error', 'This booking can no longer be paid. Please create a new booking.');
        }

        if ($book->payment_expires_at && now()->greaterThan($book->payment_expires_at)) {
            $this->expirePendingPayment($book);

            return redirect()->route('customer.history')
                ->with('error', 'Your payment session expired. The room has been released.');
        }

        $book->load('room.roomType');

        return view('customer.payment', ['book' => $book]);
    }

    public function processMockPayment(Request $request, Booking $book)
    {
        if ((int) $book->user_id !== (int) $request->user()->id) {
            abort(403, 'You are not allowed to process this payment.');
        }

        $validated = $request->validate([
            'outcome' => ['required', 'in:success,failed,cancelled'],
        ]);

        $outcome = $validated['outcome'];

        $updated = DB::transaction(function () use ($book, $outcome) {
            $lockedBook = Booking::with('room')->lockForUpdate()->findOrFail($book->id);

            if ($lockedBook->payment_status === 'paid') {
                return $lockedBook;
            }

            if ($lockedBook->payment_expires_at && now()->greaterThan($lockedBook->payment_expires_at)) {
                $this->expirePendingPayment($lockedBook);

                return $lockedBook->fresh(['room']);
            }

            if ($outcome === 'success') {
                $lockedBook->update([
                    'status' => 'confirmed',
                    'payment_status' => 'paid',
                    'payment_method' => 'mock_card',
                    'payment_paid_at' => now(),
                ]);

                return $lockedBook->fresh(['room']);
            }

            $lockedBook->update([
                'status' => 'cancelled',
                'payment_status' => 'failed',
            ]);

            $lockedBook->room->update(['status' => 'available']);

            return $lockedBook->fresh(['room']);
        });

        if ($updated->payment_status === 'paid') {
            return redirect()->route('customer.booking.confirmation', $updated)
                ->with('success', 'Mock payment successful. Your booking is now confirmed.');
        }

        if ($updated->payment_status === 'expired') {
            return redirect()->route('customer.history')
                ->with('error', 'Payment session expired. The room was released.');
        }

        return redirect()->route('customer.history')
            ->with('error', 'Payment was not completed. The room was released.');
    }

    public function bookingConfirmation(Booking $book, Request $request)
    {
        if ((int) $book->user_id !== (int) $request->user()->id) {
            abort(403, 'You are not allowed to view this booking.');
        }

        $book->load('room.roomType');
        $reference = $this->bookingReference($book);

        return view('customer.booking-confirmation', [
            'book' => $book,
            'reference' => $reference,
        ]);
    }

    public function history(Request $request)
    {
        $user = $request->user();
        $today = Carbon::today();

        $activeStay = Booking::with('room.roomType')
            ->where('user_id', $user->id)
            ->where('status', 'checked_in')
            ->whereDate('check_out', '>=', $today)
            ->orderBy('check_out')
            ->get();

        $upcoming = Booking::with('room.roomType')
            ->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereDate('check_out', '>=', $today)
            ->orderBy('check_in')
            ->get();

        $past = Booking::with('room.roomType')
            ->where('user_id', $user->id)
            ->where(function ($query) use ($today) {
                $query->where('status', 'checked_out')
                    ->orWhereDate('check_out', '<', $today);
            })
            ->latest('check_out')
            ->get();

        return view('customer.history', [
            'activeStay' => $activeStay,
            'upcoming' => $upcoming,
            'past' => $past,
        ]);
    }

    private function ensureRoomIsAvailable(Room $room, string $checkIn, string $checkOut): void
    {
        if ($room->status !== 'available') {
            throw ValidationException::withMessages([
                'room_id' => 'Selected room is not available right now.',
            ]);
        }

        $hasConflict = Booking::query()
            ->where('room_id', $room->id)
            ->where(function ($query) {
                $query->whereIn('status', ['confirmed', 'checked_in'])
                    ->orWhere(function ($pendingQuery) {
                        $pendingQuery->where('status', 'pending')
                            ->where(function ($expiryQuery) {
                                $expiryQuery->whereNull('payment_expires_at')
                                    ->orWhere('payment_expires_at', '>', now());
                            });
                    });
            })
            ->whereDate('check_in', '<', $checkOut)
            ->whereDate('check_out', '>', $checkIn)
            ->exists();

        if ($hasConflict) {
            throw ValidationException::withMessages([
                'check_in' => 'Selected room is already booked for your selected dates.',
            ]);
        }
    }

    private function calculateNights(?string $checkIn, ?string $checkOut): int
    {
        if (!$checkIn || !$checkOut) {
            return 0;
        }

        return max(1, Carbon::parse($checkIn)->diffInDays(Carbon::parse($checkOut)));
    }

    private function bookingReference(Booking $book): string
    {
        return 'AUR-' . $book->created_at->format('Ymd') . '-' . str_pad((string) $book->id, 6, '0', STR_PAD_LEFT);
    }

    private function mockPaymentReference(): string
    {
        return 'MOCK-' . strtoupper(bin2hex(random_bytes(4)));
    }

    private function expirePendingPayment(Booking $book): void
    {
        if ($book->payment_status === 'paid') {
            return;
        }

        $book->update([
            'status' => 'cancelled',
            'payment_status' => 'expired',
        ]);

        $book->room()->update(['status' => 'available']);
    }
}
