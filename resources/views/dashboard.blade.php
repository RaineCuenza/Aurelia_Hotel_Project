@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold">Dashboard</h1>

    <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-lg bg-white p-5 shadow">
            <p class="text-sm text-slate-500">Total Rooms</p>
            <p class="mt-2 text-3xl font-bold">{{ $metrics['total_rooms'] }}</p>
        </div>
        <div class="rounded-lg bg-white p-5 shadow">
            <p class="text-sm text-slate-500">Available Rooms</p>
            <p class="mt-2 text-3xl font-bold">{{ $metrics['available_rooms'] }}</p>
        </div>
        <div class="rounded-lg bg-white p-5 shadow">
            <p class="text-sm text-slate-500">Pending Reservations</p>
            <p class="mt-2 text-3xl font-bold">{{ $metrics['pending_reservations'] }}</p>
        </div>
        <div class="rounded-lg bg-white p-5 shadow">
            <p class="text-sm text-slate-500">Confirmed Reservations</p>
            <p class="mt-2 text-3xl font-bold">{{ $metrics['confirmed_reservations'] }}</p>
        </div>
    </div>

    <div class="mt-8 rounded-lg bg-white p-5 shadow">
        <h2 class="text-xl font-semibold">Recent Reservations</h2>
        <div class="mt-4 overflow-x-auto">
            <table class="w-full min-w-[560px] text-left text-sm">
                <thead>
                    <tr class="border-b text-slate-500">
                        <th class="py-2">Guest</th>
                        <th class="py-2">Room</th>
                        <th class="py-2">Dates</th>
                        <th class="py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentReservations as $reservation)
                        <tr class="border-b">
                            <td class="py-2">{{ $reservation->guest_name }}</td>
                            <td class="py-2">{{ $reservation->room->room_number }} ({{ $reservation->room->roomType->name }})</td>
                            <td class="py-2">{{ $reservation->check_in->format('M d, Y') }} - {{ $reservation->check_out->format('M d, Y') }}</td>
                            <td class="py-2 capitalize">{{ str_replace('_', ' ', $reservation->status) }}</td>
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
