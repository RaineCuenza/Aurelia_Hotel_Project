@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold">Booking Summary</h1>

    @if ($reservation)
        <div class="mt-6 rounded-lg bg-white p-5 shadow">
            <p class="text-slate-600">Latest reservation:</p>
            <h2 class="mt-1 text-2xl font-bold">{{ $reservation->guest_name }}</h2>
            <p class="mt-2">Room {{ $reservation->room->room_number }} ({{ $reservation->room->roomType->name }})</p>
            <p>Stay: {{ $reservation->check_in->format('M d, Y') }} - {{ $reservation->check_out->format('M d, Y') }} ({{ $reservation->nights }} nights)</p>
            <p class="mt-3 text-lg font-semibold text-blue-700">Total: PHP {{ number_format($reservation->total_amount, 2) }}</p>
            <a href="{{ route('reservations.show', $reservation) }}" class="mt-4 inline-block rounded bg-slate-900 px-4 py-2 font-semibold text-white">View Full Details</a>
        </div>
    @else
        <div class="mt-6 rounded-lg bg-white p-5 shadow">
            <p class="text-slate-600">No reservation found yet.</p>
            <a href="{{ route('reservations.create') }}" class="mt-4 inline-block rounded bg-blue-600 px-4 py-2 font-semibold text-white">Create Reservation</a>
        </div>
    @endif
@endsection
