<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $startDate = $today->copy()->subDays(6);

        $activeStatuses = ['pending', 'confirmed', 'checked_in'];
        $revenueStatuses = ['confirmed', 'checked_in', 'checked_out'];

        $metrics = [
            'total_rooms' => Room::count(),
            'available_rooms' => Room::where('status', 'available')->count(),
            'active_bookings' => Reservation::whereIn('status', $activeStatuses)->count(),
            'total_revenue' => Reservation::whereIn('status', $revenueStatuses)->sum('total_amount'),
        ];

        $dailyBookingData = Reservation::query()
            ->selectRaw('DATE(created_at) as booking_date, COUNT(*) as total')
            ->whereDate('created_at', '>=', $startDate->toDateString())
            ->groupBy('booking_date')
            ->orderBy('booking_date')
            ->get()
            ->keyBy('booking_date');

        $bookingChartLabels = [];
        $bookingChartData = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            $key = $date->toDateString();

            $bookingChartLabels[] = $date->format('M d');
            $bookingChartData[] = (int) ($dailyBookingData[$key]->total ?? 0);
        }

        $recentReservations = Reservation::with(['room.roomType'])
            ->latest()
            ->limit(8)
            ->get();

        return view('dashboard', compact('metrics', 'recentReservations', 'bookingChartLabels', 'bookingChartData'));
    }
}
