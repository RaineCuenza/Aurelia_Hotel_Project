<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomSearchController extends Controller
{
    public function index(Request $request)
    {
        $roomTypes = RoomType::orderBy('name')->get();

        $rooms = Room::with('roomType')
            ->when($request->filled('room_type_id'), function ($query) use ($request) {
                $query->where('room_type_id', $request->integer('room_type_id'));
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->string('status'));
            })
            ->when($request->filled('guests'), function ($query) use ($request) {
                $query->where('max_guests', '>=', $request->integer('guests'));
            })
            ->orderBy('room_number')
            ->paginate(9)
            ->withQueryString();

        return view('rooms.search', compact('roomTypes', 'rooms'));
    }
}
