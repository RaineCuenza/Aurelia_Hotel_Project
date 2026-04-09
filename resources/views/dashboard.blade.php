@extends('layouts.app')

@section('content')
    <h1 class="section-title text-4xl font-semibold text-slate-900">Dashboard</h1>
    <p class="mt-1 text-sm text-slate-500">Overview of current room and reservation activity.</p>

    <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="surface p-5">
            <p class="text-sm font-medium text-slate-500">Total Rooms</p>
            <p class="metric-value mt-3 text-3xl font-bold text-slate-900">{{ $metrics['total_rooms'] }}</p>
        </div>
        <div class="surface p-5">
            <p class="text-sm font-medium text-slate-500">Available Rooms</p>
            <p class="metric-value mt-3 text-3xl font-bold text-slate-900">{{ $metrics['available_rooms'] }}</p>
        </div>
        <div class="surface p-5">
            <p class="text-sm font-medium text-slate-500">Pending Reservations</p>
            <p class="metric-value mt-3 text-3xl font-bold text-slate-900">{{ $metrics['pending_reservations'] }}</p>
        </div>
        <div class="surface p-5">
            <p class="text-sm font-medium text-slate-500">Confirmed Reservations</p>
            <p class="metric-value mt-3 text-3xl font-bold text-slate-900">{{ $metrics['confirmed_reservations'] }}</p>
        </div>
    </div>

    <div class="surface mt-8 p-5">
        <h2 class="text-3xl font-semibold text-slate-900">Recent Reservations</h2>
        <div class="mt-4 overflow-x-auto">
            <table class="w-full min-w-[560px] text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500">
                        <th class="py-3 font-semibold">Guest</th>
                        <th class="py-3 font-semibold">Room</th>
                        <th class="py-3 font-semibold">Dates</th>
                        <th class="py-3 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentReservations as $reservation)
                        <tr class="border-b border-slate-100">
                            <td class="py-3 font-medium">{{ $reservation->guest_name }}</td>
                            <td class="py-3">{{ $reservation->room->room_number }} ({{ $reservation->room->roomType->name }})</td>
                            <td class="py-3">{{ $reservation->check_in->format('M d, Y') }} - {{ $reservation->check_out->format('M d, Y') }}</td>
                            <td class="py-3 capitalize">{{ str_replace('_', ' ', $reservation->status) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-4 text-slate-500">No reservations yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
