<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoomApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'room_type_id' => ['nullable', 'integer', 'exists:room_types,id'],
            'status' => ['nullable', 'in:available,reserved,occupied,cleaning,maintenance'],
            'guests' => ['nullable', 'integer', 'min:1', 'max:12'],
            'sort' => ['nullable', 'in:room_number,max_guests,status,created_at'],
            'direction' => ['nullable', 'in:asc,desc'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $sort = $validated['sort'] ?? 'room_number';
        $direction = $validated['direction'] ?? 'asc';
        $perPage = $validated['per_page'] ?? 10;

        $rooms = Room::query()
            ->with('roomType')
            ->when(isset($validated['room_type_id']), function ($query) use ($validated) {
                $query->where('room_type_id', $validated['room_type_id']);
            })
            ->when(isset($validated['status']), function ($query) use ($validated) {
                $query->where('status', $validated['status']);
            })
            ->when(isset($validated['guests']), function ($query) use ($validated) {
                $query->where('max_guests', '>=', $validated['guests']);
            })
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->appends($request->query());

        return response()->json($rooms);
    }

    public function show(Room $room): JsonResponse
    {
        $room->load('roomType');

        return response()->json([
            'data' => $room,
        ]);
    }
}
