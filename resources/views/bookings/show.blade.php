@extends('layouts.app')

@section('content')
    <h1 class="section-title text-4xl font-semibold text-slate-900">Booking Details</h1>

    <div class="surface mt-6 p-5">
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <p class="text-sm text-slate-500">Guest Name</p>
                <p class="font-semibold">{{ $book->guest_name }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-500">Guest Email</p>
                <p class="font-semibold">{{ $book->guest_email }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-500">Room</p>
                <p class="font-semibold">{{ $book->room->room_number }} ({{ $book->room->roomType->name }})</p>
            </div>
            <div>
                <p class="text-sm text-slate-500">Guests</p>
                <p class="font-semibold">{{ $book->guests }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-500">Check-in</p>
                <p class="font-semibold">{{ $book->check_in->format('M d, Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-500">Check-out</p>
                <p class="font-semibold">{{ $book->check_out->format('M d, Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-500">Nights</p>
                <p class="font-semibold">{{ $book->nights }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-500">Status</p>
                <p class="font-semibold capitalize">{{ str_replace('_', ' ', $book->status) }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm text-slate-500">Special Requests</p>
                <p class="font-semibold">{{ $book->special_requests ?: 'None' }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm text-slate-500">Total Amount</p>
                <p class="text-xl font-bold text-amber-700">PHP {{ number_format($book->total_amount, 2) }}</p>
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <a href="{{ route('bookings.edit', $book) }}" class="btn-primary px-4 py-2.5">Edit</a>
            <a href="{{ route('bookings.index') }}" class="btn-secondary px-4 py-2.5">Back</a>
        </div>
    </div>
@endsection
