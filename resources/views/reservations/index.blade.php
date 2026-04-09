@extends('layouts.app')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="section-title text-4xl font-semibold text-slate-900">Reservations</h1>
        <a href="{{ route('reservations.create') }}" class="btn-primary px-4 py-2.5">
            New Reservation
        </a>
    </div>

    <form method="GET" action="{{ route('reservations.index') }}" class="surface mt-5 grid gap-3 p-4 md:grid-cols-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search guest/email/room"
            class="rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-slate-700">

        <select name="status" class="rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-slate-700">
            <option value="">Any status</option>
            <option value="pending" @selected(request('status') === 'pending')>Pending</option>
            <option value="confirmed" @selected(request('status') === 'confirmed')>Confirmed</option>
            <option value="checked_in" @selected(request('status') === 'checked_in')>Checked in</option>
            <option value="checked_out" @selected(request('status') === 'checked_out')>Checked out</option>
            <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
        </select>

        <button class="btn-primary px-4 py-2.5">Apply</button>
    </form>

    <div class="surface mt-6 overflow-x-auto p-4">
        <table class="w-full min-w-[860px] text-left text-sm">
            <thead>
                <tr class="border-b border-slate-200 text-slate-500">
                    <th class="py-3 font-semibold">Guest</th>
                    <th class="py-3 font-semibold">Room</th>
                    <th class="py-3 font-semibold">Dates</th>
                    <th class="py-3 font-semibold">Total</th>
                    <th class="py-3 font-semibold">Status</th>
                    <th class="py-3 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reservations as $reservation)
                    <tr class="border-b border-slate-100 align-top">
                        <td class="py-3">
                            <p class="font-medium">{{ $reservation->guest_name }}</p>
                            <p class="text-xs text-slate-500">{{ $reservation->guest_email }}</p>
                        </td>
                        <td class="py-3">{{ $reservation->room->room_number }} ({{ $reservation->room->roomType->name }})</td>
                        <td class="py-3">{{ $reservation->check_in->format('M d, Y') }} - {{ $reservation->check_out->format('M d, Y') }}</td>
                        <td class="py-3 font-semibold text-amber-700">PHP {{ number_format($reservation->total_amount, 2) }}</td>
                        <td class="py-3 capitalize">{{ str_replace('_', ' ', $reservation->status) }}</td>
                        <td class="py-3">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('reservations.show', $reservation) }}" class="rounded-lg bg-slate-100 px-2.5 py-1.5 text-slate-700">View</a>
                                <a href="{{ route('reservations.edit', $reservation) }}" class="rounded-lg bg-amber-100 px-2.5 py-1.5 text-amber-800">Edit</a>
                                <form action="{{ route('reservations.destroy', $reservation) }}" method="POST"
                                    onsubmit="return confirm('Delete this reservation?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="rounded-lg bg-red-100 px-2.5 py-1.5 text-red-700">Delete</button>
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
