@extends('layouts.app')

@section('content')
    <h1 class="section-title text-4xl font-semibold text-slate-900">Booking Summary</h1>

    @if ($reservation)
        <div class="surface mt-6 p-5">
            <p class="text-slate-600">Latest booking:</p>
            <h2 class="mt-1 text-2xl font-bold">{{ $reservation->guest_name }}</h2>
            <p class="mt-2">Room {{ $reservation->room->room_number }} ({{ $reservation->room->roomType->name }})</p>
            <p>Stay: {{ $reservation->check_in->format('M d, Y') }} - {{ $reservation->check_out->format('M d, Y') }} ({{ $reservation->nights }} nights)</p>
            <p class="mt-3 text-lg font-semibold text-amber-700">Total: PHP {{ number_format($reservation->total_amount, 2) }}</p>
            <a href="{{ route('reservations.show', $reservation) }}" class="btn-primary mt-4 inline-block px-4 py-2.5">View Full Details</a>
        </div>
    @else
        <div class="surface mt-6 p-5">
            <p class="text-slate-600">No booking found yet.</p>
            <a href="{{ route('reservations.create') }}" class="btn-primary mt-4 inline-block px-4 py-2.5">Create Booking</a>
        </div>
    @endif
@endsection
