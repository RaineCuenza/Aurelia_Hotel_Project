<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StaffController extends Controller
{
    /**
     * Staff Dashboard: Display today's check-ins, check-outs, active guests, room status
     */
    public function dashboard()
    {
        $today = Carbon::today();

        // Today's check-ins (confirmed reservations with check-in date = today)
        $todayCheckIns = Reservation::where('check_in', $today)
            ->where('status', 'confirmed')
            ->with(['room.roomType', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // Today's check-outs (checked-in reservations with check-out date = today)
        $todayCheckOuts = Reservation::where('check_out', $today)
            ->where('status', 'checked_in')
            ->with(['room.roomType', 'user'])
            ->orderBy('check_out', 'asc')
            ->limit(8)
            ->get();

        // Active guests (currently checked-in)
        $activeGuests = Reservation::where('status', 'checked_in')
            ->with(['room.roomType', 'user'])
            ->count();

        // Room status breakdown
        $totalRooms = Room::count();
        $availableRooms = Room::where('status', 'available')->count();
        $reservedRooms = Room::where('status', 'reserved')->count();
        $occupiedRooms = Room::where('status', 'occupied')->count();
        $cleaningRooms = Room::where('status', 'cleaning')->count();

        // Room status for detailed view
        $roomStatus = Room::with('roomType')
            ->orderBy('room_number', 'asc')
            ->get();

        return view('staff.dashboard', [
            'todayCheckIns' => $todayCheckIns,
            'todayCheckOuts' => $todayCheckOuts,
            'activeGuests' => $activeGuests,
            'totalRooms' => $totalRooms,
            'availableRooms' => $availableRooms,
            'reservedRooms' => $reservedRooms,
            'occupiedRooms' => $occupiedRooms,
            'cleaningRooms' => $cleaningRooms,
            'roomStatus' => $roomStatus,
        ]);
    }

    /**
     * Display staff reservations with filtering and search
     */
    public function reservations(Request $request)
    {
        $query = Reservation::with(['room.roomType', 'user']);

        // Search by guest name, email, or room
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('guest_name', 'like', "%{$search}%")
                    ->orWhere('guest_email', 'like', "%{$search}%")
                    ->orWhereHas('room', function ($roomQ) use ($search) {
                        $roomQ->where('room_number', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $reservations = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('staff.reservations', [
            'reservations' => $reservations,
        ]);
    }

    /**
     * Check-in a guest: Update reservation to checked-in and room to occupied
     */
    public function checkIn(Request $request, Reservation $reservation)
    {
        // Can only check in confirmed reservations
        if ($reservation->status !== 'confirmed') {
            return redirect()->back()->with('error', 'Only confirmed reservations can be checked in.');
        }

        DB::transaction(function () use ($reservation) {
            // Update reservation status
            $reservation->update(['status' => 'checked_in']);

            // Mark room as occupied
            $reservation->room->update(['status' => 'occupied']);
        });

        return redirect()->back()->with('success', "{$reservation->guest_name} has been checked in successfully.");
    }

    /**
     * Check-out a guest: Update reservation to completed and room to cleaning
     */
    public function checkOut(Request $request, Reservation $reservation)
    {
        // Can only check out checked-in reservations
        if ($reservation->status !== 'checked_in') {
            return redirect()->back()->with('error', 'Only checked-in guests can be checked out.');
        }

        DB::transaction(function () use ($reservation) {
            // Update reservation status
            $reservation->update(['status' => 'checked_out']);

            // Room requires housekeeping before becoming available again
            $reservation->room->update(['status' => 'cleaning']);
        });

        return redirect()->back()->with('success', "{$reservation->guest_name} has been checked out. Room moved to cleaning.");
    }

    /**
     * Mark a cleaning room as available after housekeeping completes.
     */
    public function markRoomClean(Room $room)
    {
        if ($room->status !== 'cleaning') {
            return redirect()->back()->with('error', 'Only rooms under cleaning can be marked as clean.');
        }

        $room->update(['status' => 'available']);

        return redirect()->back()->with('success', "Room {$room->room_number} is now available for booking.");
    }

    /**
     * Display room status for staff
     */
    public function rooms()
    {
        $rooms = Room::with(['roomType', 'reservations' => function ($q) {
            $q->where('status', 'checked_in')->latest();
        }])->orderBy('room_number', 'asc')->get();

        $stats = [
            'total' => Room::count(),
            'available' => Room::where('status', 'available')->count(),
            'reserved' => Room::where('status', 'reserved')->count(),
            'occupied' => Room::where('status', 'occupied')->count(),
            'cleaning' => Room::where('status', 'cleaning')->count(),
        ];

        return view('staff.rooms', [
            'rooms' => $rooms,
            'stats' => $stats,
        ]);
    }

    /**
     * View reservation details
     */
    public function showReservation(Reservation $reservation)
    {
        $reservation->load(['room.roomType', 'user']);

        return view('staff.reservation-detail', [
            'reservation' => $reservation,
        ]);
    }
}
