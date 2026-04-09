@extends('layouts.app')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-3xl font-bold">Reservations</h1>
        <a href="{{ route('reservations.create') }}" class="rounded bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700">
            New Reservation
        </a>
    </div>

    <form method="GET" action="{{ route('reservations.index') }}" class="mt-5 grid gap-3 rounded-lg bg-white p-4 shadow md:grid-cols-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search guest/email/room"
            class="rounded border border-slate-300 px-3 py-2">

        <select name="status" class="rounded border border-slate-300 px-3 py-2">
            <option value="">Any status</option>
            <option value="pending" @selected(request('status') === 'pending')>Pending</option>
            <option value="confirmed" @selected(request('status') === 'confirmed')>Confirmed</option>
            <option value="checked_in" @selected(request('status') === 'checked_in')>Checked in</option>
            <option value="checked_out" @selected(request('status') === 'checked_out')>Checked out</option>
            <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
        </select>

        <button class="rounded bg-slate-900 px-4 py-2 font-semibold text-white hover:bg-slate-800">Apply</button>
    </form>

    <div class="mt-6 overflow-x-auto rounded-lg bg-white p-4 shadow">
        <table class="w-full min-w-[860px] text-left text-sm">
            <thead>
                <tr class="border-b text-slate-500">
                    <th class="py-2">Guest</th>
                    <th class="py-2">Room</th>
                    <th class="py-2">Dates</th>
                    <th class="py-2">Total</th>
                    <th class="py-2">Status</th>
                    <th class="py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reservations as $reservation)
                    <tr class="border-b align-top">
                        <td class="py-2">
                            <p class="font-medium">{{ $reservation->guest_name }}</p>
                            <p class="text-xs text-slate-500">{{ $reservation->guest_email }}</p>
                        </td>
                        <td class="py-2">{{ $reservation->room->room_number }} ({{ $reservation->room->roomType->name }})</td>
                        <td class="py-2">{{ $reservation->check_in->format('M d, Y') }} - {{ $reservation->check_out->format('M d, Y') }}</td>
                        <td class="py-2">PHP {{ number_format($reservation->total_amount, 2) }}</td>
                        <td class="py-2 capitalize">{{ str_replace('_', ' ', $reservation->status) }}</td>
                        <td class="py-2">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('reservations.show', $reservation) }}" class="rounded bg-slate-200 px-2 py-1">View</a>
                                <a href="{{ route('reservations.edit', $reservation) }}" class="rounded bg-blue-200 px-2 py-1">Edit</a>
                                <form action="{{ route('reservations.destroy', $reservation) }}" method="POST"
                                    onsubmit="return confirm('Delete this reservation?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="rounded bg-red-200 px-2 py-1">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-4 text-slate-500">No reservations found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $reservations->links() }}</div>
@endsection
