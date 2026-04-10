<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $reservations = Reservation::with(['room.roomType', 'user'])
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

        return view('reservations.index', compact('reservations'));
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

        return view('reservations.create', compact('rooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateReservation($request);
        DB::transaction(function () use ($request, $validated) {
            $room = Room::with('roomType')->lockForUpdate()->findOrFail($validated['room_id']);
            $this->ensureRoomIsAvailable($room, $validated['check_in'], $validated['check_out']);

            $nights = max(1, Carbon::parse($validated['check_in'])->diffInDays(Carbon::parse($validated['check_out'])));
            $total = $nights * $room->roomType->base_price;

            Reservation::create([
                ...$validated,
                'user_id' => $request->user()?->id,
                'guests' => $validated['guests'] ?? 1,
                'guest_phone' => $validated['guest_phone'] ?? null,
                'total_amount' => $total,
                'status' => 'confirmed',
            ]);

            $room->update(['status' => 'reserved']);
        });

        return redirect()->route('reservations.index')->with('success', 'Reservation created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        $reservation->load('room.roomType');

        return view('reservations.show', compact('reservation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        $rooms = Room::with('roomType')
            ->where('status', 'available')
            ->orWhere('id', $reservation->room_id)
            ->orderBy('room_number')
            ->get();

        return view('reservations.edit', compact('reservation', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservation $reservation)
    {
        $validated = $this->validateReservation($request, true);
        $room = Room::with('roomType')->findOrFail($validated['room_id']);
        $this->ensureRoomIsAvailable($room, $validated['check_in'], $validated['check_out'], $reservation->id, $reservation->room_id);

        $nights = max(1, Carbon::parse($validated['check_in'])->diffInDays(Carbon::parse($validated['check_out'])));
        $total = $nights * $room->roomType->base_price;

        $reservation->update([
            ...$validated,
            'guests' => $validated['guests'] ?? $reservation->guests,
            'guest_phone' => $validated['guest_phone'] ?? $reservation->guest_phone,
            'status' => $validated['status'] ?? $reservation->status,
            'total_amount' => $total,
        ]);

        return redirect()->route('reservations.index')->with('success', 'Reservation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return redirect()->route('reservations.index')->with('success', 'Reservation deleted successfully.');
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:confirmed,cancelled'],
        ]);

        $reservation->update([
            'status' => $validated['status'],
        ]);

        if ($validated['status'] === 'cancelled') {
            $reservation->room()->update(['status' => 'available']);
        }

        return redirect()->route('reservations.index')->with('success', 'Reservation status updated successfully.');
    }

    public function summary(Request $request)
    {
        $reservation = Reservation::with('room.roomType')
            ->when($request->user(), function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            })
            ->latest()
            ->first();

        return view('reservations.summary', compact('reservation'));
    }

    private function validateReservation(Request $request, bool $isUpdate = false): array
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

    private function ensureRoomIsAvailable(Room $room, string $checkIn, string $checkOut, ?int $ignoreReservationId = null, ?int $allowRoomId = null): void
    {
        if ($room->status !== 'available' && $room->id !== $allowRoomId) {
            throw ValidationException::withMessages([
                'room_id' => 'Selected room is not available right now.',
            ]);
        }

        $hasConflict = Reservation::query()
            ->where('room_id', $room->id)
            ->when($ignoreReservationId, function ($query) use ($ignoreReservationId) {
                $query->where('id', '!=', $ignoreReservationId);
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
