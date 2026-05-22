<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification as BaseNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $bookings = Booking::with(['room.roomType', 'user'])
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->string('status'));
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');

                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('guest_name', 'like', "%{$search}%")
                        ->orWhere('guest_email', 'like', "%{$search}%")
                        ->orWhereHas('room', function ($roomQuery) use ($search) {
                            $roomQuery->where('room_number', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rooms = Room::with('roomType')
            ->where('status', 'available')
            ->orderBy('room_number')
            ->get();

        return view('bookings.create', compact('rooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateBooking($request);
        DB::transaction(function () use ($request, $validated) {
            $room = Room::with('roomType')->lockForUpdate()->findOrFail($validated['room_id']);
            $this->ensureRoomIsAvailable($room, $validated['check_in'], $validated['check_out']);

            $nights = max(1, Carbon::parse($validated['check_in'])->diffInDays(Carbon::parse($validated['check_out'])));
            $total = $nights * $room->roomType->base_price;

            Booking::create([
                ...$validated,
                'user_id' => $request->user()?->id,
                'guests' => $validated['guests'] ?? 1,
                'guest_phone' => $validated['guest_phone'] ?? null,
                'total_amount' => $total,
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'payment_method' => 'manual_frontdesk',
                'payment_paid_at' => now(),
            ]);

            $room->update(['status' => 'booked']);
        });

        return redirect()->route('bookings.index')->with('success', 'Booking created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $book)
    {
        $book->load(['room.roomType', 'cancelledBy']);

        return view('bookings.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $book)
    {
        if ($book->status === 'cancelled') {
            return redirect()->route('bookings.index')
                ->with('error', 'Cancelled bookings cannot be edited.');
        }

        $rooms = Room::with('roomType')
            ->where('status', 'available')
            ->orWhere('id', $book->room_id)
            ->orderBy('room_number')
            ->get();

        return view('bookings.edit', compact('book', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $book)
    {
        if ($book->status === 'cancelled') {
            return redirect()->route('bookings.index')
                ->with('error', 'Cancelled bookings cannot be edited.');
        }

        $validated = $this->validateBooking($request, true);
        $room = Room::with('roomType')->findOrFail($validated['room_id']);
        $this->ensureRoomIsAvailable($room, $validated['check_in'], $validated['check_out'], $book->id, $book->room_id);

        $nights = max(1, Carbon::parse($validated['check_in'])->diffInDays(Carbon::parse($validated['check_out'])));
        $total = $nights * $room->roomType->base_price;

        $book->update([
            ...$validated,
            'guests' => $validated['guests'] ?? $book->guests,
            'guest_phone' => $validated['guest_phone'] ?? $book->guest_phone,
            'status' => $validated['status'] ?? $book->status,
            'total_amount' => $total,
        ]);

        return redirect()->route('bookings.index')->with('success', 'Booking updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $book)
    {
        $book->delete();

        return redirect()->route('bookings.index')->with('success', 'Booking deleted successfully.');
    }

    public function updateStatus(Request $request, Booking $book)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:confirmed'],
        ]);

        if ($book->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending bookings can be approved.');
        }

        $book->update([
            'status' => $validated['status'],
        ]);

        return redirect()->route('bookings.index')->with('success', 'Booking status updated successfully.');
    }

    public function cancel(Request $request, Booking $book)
    {
        $validated = $request->validate([
            'cancellation_reason' => ['required', 'string', 'min:10', 'max:1000'],
            'room_status' => ['required', 'in:available,maintenance'],
            'notify_customer' => ['nullable', 'boolean'],
            'allow_checked_in_cancellation' => ['nullable', 'boolean'],
        ]);

        if ($book->status === 'checked_out') {
            return redirect()->back()->with('error', 'Checked-out bookings cannot be cancelled.');
        }

        if ($book->status === 'checked_in' && ! $request->boolean('allow_checked_in_cancellation')) {
            return back()
                ->withErrors([
                    'allow_checked_in_cancellation' => 'Checked-in bookings require explicit approval before cancellation.',
                ])
                ->withInput();
        }

        $cancelledBook = DB::transaction(function () use ($book, $validated, $request) {
            $lockedBook = Booking::with('room.roomType', 'cancelledBy')->lockForUpdate()->findOrFail($book->id);

            if ($lockedBook->status === 'checked_out') {
                throw ValidationException::withMessages([
                    'cancellation_reason' => 'Checked-out bookings cannot be cancelled.',
                ]);
            }

            if ($lockedBook->status === 'checked_in' && ! $request->boolean('allow_checked_in_cancellation')) {
                throw ValidationException::withMessages([
                    'allow_checked_in_cancellation' => 'Checked-in bookings require explicit approval before cancellation.',
                ]);
            }

            $lockedBook->update([
                'status' => 'cancelled',
                'cancellation_reason' => $validated['cancellation_reason'],
                'cancelled_by' => $request->user()->id,
                'cancelled_at' => now(),
            ]);

            $lockedBook->room->update([
                'status' => $validated['room_status'],
            ]);

            return $lockedBook->fresh(['room.roomType', 'cancelledBy']);
        });

        // Always notify the guest by email when an admin/staff cancels their booking.
        Notification::route('mail', $cancelledBook->guest_email)
            ->notify(new class($cancelledBook) extends BaseNotification
            {
                use Queueable;

                public function __construct(private readonly Booking $booking)
                {
                }

                /**
                 * @return array<int, string>
                 */
                public function via(object $notifiable): array
                {
                    return ['mail'];
                }

                public function toMail(object $notifiable): MailMessage
                {
                    $booking = $this->booking->loadMissing('room.roomType', 'cancelledBy');

                    return (new MailMessage)
                        ->subject('Your booking was cancelled')
                        ->greeting('Hello '.$booking->guest_name.',')
                        ->line('Your booking for Room '.$booking->room->room_number.' has been cancelled.')
                        ->line('Check-in: '.Carbon::parse($booking->check_in)->format('M d, Y'))
                        ->line('Check-out: '.Carbon::parse($booking->check_out)->format('M d, Y'))
                        ->line('Cancellation reason: '.$booking->cancellation_reason)
                        ->line('Cancelled on: '.optional($booking->cancelled_at)->format('M d, Y h:i A'))
                        ->lineIf($booking->cancelledBy !== null, 'Cancelled by: '.$booking->cancelledBy->name)
                        ->line('If you need help, please contact the front desk or create a new booking for a future stay.');
                }
            });

        return redirect()
            ->route('bookings.index')
            ->with('success', 'Booking cancelled successfully and the customer has been notified.');
    }

    public function summary(Request $request)
    {
        $book = Booking::with('room.roomType')
            ->when($request->user(), function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            })
            ->latest()
            ->first();

        return view('bookings.summary', compact('book'));
    }

    private function validateBooking(Request $request, bool $isUpdate = false): array
    {
        $statusRule = $isUpdate
            ? ['nullable', 'in:pending,confirmed,checked_in,checked_out,cancelled']
            : ['sometimes', 'in:pending,confirmed,checked_in,checked_out,cancelled'];

        return $request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'guest_name' => ['required', 'string', 'max:120'],
            'guest_email' => ['required', 'email', 'max:120'],
            'guest_phone' => ['nullable', 'string', 'max:30'],
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guests' => ['nullable', 'integer', 'min:1', 'max:12'],
            'special_requests' => ['nullable', 'string', 'max:1000'],
            'status' => $statusRule,
        ]);
    }

    private function ensureRoomIsAvailable(Room $room, string $checkIn, string $checkOut, ?int $ignoreBookingId = null, ?int $allowRoomId = null): void
    {
        if ($room->status !== 'available' && $room->id !== $allowRoomId) {
            throw ValidationException::withMessages([
                'room_id' => 'Selected room is not available right now.',
            ]);
        }

        $hasConflict = Booking::query()
            ->where('room_id', $room->id)
            ->when($ignoreBookingId, function ($query) use ($ignoreBookingId) {
                $query->where('id', '!=', $ignoreBookingId);
            })
            ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
            ->whereDate('check_in', '<', $checkOut)
            ->whereDate('check_out', '>', $checkIn)
            ->exists();

        if ($hasConflict) {
            throw ValidationException::withMessages([
                'room_id' => 'Selected room is already booked for the chosen dates.',
            ]);
        }
    }
}
