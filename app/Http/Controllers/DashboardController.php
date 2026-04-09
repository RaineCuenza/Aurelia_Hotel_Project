<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $metrics = [
            'total_rooms' => Room::count(),
            'available_rooms' => Room::where('status', 'available')->count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
            'confirmed_reservations' => Reservation::where('status', 'confirmed')->count(),
        ];

        $recentReservations = Reservation::with(['room.roomType'])
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact('metrics', 'recentReservations'));
    }
}
