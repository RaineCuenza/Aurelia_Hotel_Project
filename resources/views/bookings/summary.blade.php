@extends('layouts.app')

@section('content')
    <h1 class="section-title text-4xl font-semibold text-slate-900">Booking Summary</h1>

    @if ($book)
        <div class="surface mt-6 p-5">
            <p class="text-slate-600">Latest booking:</p>
            <h2 class="mt-1 text-2xl font-bold">{{ $book->guest_name }}</h2>
            <p class="mt-2">Room {{ $book->room->room_number }} ({{ $book->room->roomType->name }})</p>
            <p>Stay: {{ $book->check_in->format('M d, Y') }} - {{ $book->check_out->format('M d, Y') }} ({{ $book->nights }} nights)</p>
            <p class="mt-3 text-lg font-semibold text-amber-700">Total: PHP {{ number_format($book->total_amount, 2) }}</p>
            <a href="{{ route('bookings.show', $book) }}" class="btn-primary mt-4 inline-block px-4 py-2.5">View Full Details</a>
        </div>
    @else
        <div class="surface mt-6 p-5">
            <p class="text-slate-600">No booking found yet.</p>
            <a href="{{ route('bookings.create') }}" class="btn-primary mt-4 inline-block px-4 py-2.5">Create Booking</a>
        </div>
    @endif
@endsection
