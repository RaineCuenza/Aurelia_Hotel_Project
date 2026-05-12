<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class BookingApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['nullable', 'in:pending,confirmed,checked_in,checked_out,cancelled'],
            'search' => ['nullable', 'string', 'max:120'],
            'sort' => ['nullable', 'in:created_at,check_in,check_out,total_amount,guest_name'],
            'direction' => ['nullable', 'in:asc,desc'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $sort = $validated['sort'] ?? 'created_at';
        $direction = $validated['direction'] ?? 'desc';
        $perPage = $validated['per_page'] ?? 10;

        $bookings = Booking::query()
            ->with(['room.roomType', 'user'])
            ->when(isset($validated['status']), function ($query) use ($validated) {
                $query->where('status', $validated['status']);
            })
            ->when(isset($validated['search']), function ($query) use ($validated) {
                $search = $validated['search'];

                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('guest_name', 'like', "%{$search}%")
                        ->orWhere('guest_email', 'like', "%{$search}%")
                        ->orWhereHas('room', function ($roomQuery) use ($search) {
                            $roomQuery->where('room_number', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->appends($request->query());

        return response()->json($bookings);
    }

    public function show(Booking $book): JsonResponse
    {
        $book->load(['room.roomType', 'user']);

        return response()->json([
            'data' => $book,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $payload = $this->validatePayload($request);
        $room = Room::with('roomType')->findOrFail($payload['room_id']);

        $this->ensureRoomIsAvailable($payload['room_id'], $payload['check_in'], $payload['check_out']);

        if ($payload['guests'] > $room->max_guests) {
            return response()->json([
                'message' => 'Guests exceed the room capacity.',
            ], 422);
        }

        $nights = max(1, Carbon::parse($payload['check_in'])->diffInDays(Carbon::parse($payload['check_out'])));
        $total = $nights * $room->roomType->base_price;

        $book = Booking::create([
            ...$payload,
            'total_amount' => $total,
            'status' => $payload['status'] ?? 'pending',
        ]);

        $room->update(['status' => 'booked']);

        $book->load(['room.roomType', 'user']);

        return response()->json([
            'message' => 'Booking created successfully.',
            'data' => $book,
        ], 201);
    }

    public function update(Request $request, Booking $book): JsonResponse
    {
        $payload = $this->validatePayload($request, true);
        $room = Room::with('roomType')->findOrFail($payload['room_id']);

        $this->ensureRoomIsAvailable($payload['room_id'], $payload['check_in'], $payload['check_out'], $book->id);

        if ($payload['guests'] > $room->max_guests) {
            return response()->json([
                'message' => 'Guests exceed the room capacity.',
            ], 422);
        }

        $nights = max(1, Carbon::parse($payload['check_in'])->diffInDays(Carbon::parse($payload['check_out'])));
        $total = $nights * $room->roomType->base_price;

        $book->update([
            ...$payload,
            'total_amount' => $total,
        ]);

        $room->update(['status' => 'booked']);

        $book->load(['room.roomType', 'user']);

        return response()->json([
            'message' => 'Booking updated successfully.',
            'data' => $book,
        ]);
    }

    public function destroy(Booking $book): JsonResponse
    {
        $book->delete();

        return response()->json([
            'message' => 'Booking deleted successfully.',
        ]);
    }

    private function validatePayload(Request $request, bool $isUpdate = false): array
    {
        $statusRule = $isUpdate
            ? ['required', 'in:pending,confirmed,checked_in,checked_out,cancelled']
            : ['nullable', 'in:pending,confirmed,checked_in,checked_out,cancelled'];

        return $request->validate([
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
            'guest_name' => ['required', 'string', 'max:120'],
            'guest_email' => ['required', 'email', 'max:120'],
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guests' => ['required', 'integer', 'min:1', 'max:12'],
            'special_requests' => ['nullable', 'string', 'max:1000'],
            'status' => $statusRule,
        ]);
    }

    private function ensureRoomIsAvailable(int $roomId, string $checkIn, string $checkOut, ?int $ignoreBookingId = null): void
    {
        $hasConflict = Booking::query()
            ->where('room_id', $roomId)
            ->when($ignoreBookingId, function ($query) use ($ignoreBookingId) {
                $query->where('id', '!=', $ignoreBookingId);
            })
            ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
            ->where('check_in', '<', $checkOut)
            ->where('check_out', '>', $checkIn)
            ->exists();

        if ($hasConflict) {
            throw ValidationException::withMessages([
                'room_id' => 'Selected room is already booked for the chosen dates.',
            ]);
        }
    }
}