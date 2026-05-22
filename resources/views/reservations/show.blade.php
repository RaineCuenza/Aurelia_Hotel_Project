@extends('layouts.app')

@section('content')
    <h1 class="section-title text-4xl font-semibold text-slate-900">Booking Details</h1>

    <div class="surface mt-6 p-5">
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <p class="text-sm text-slate-500">Guest Name</p>
                <p class="font-semibold">{{ $reservation->guest_name }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-500">Guest Email</p>
                <p class="font-semibold">{{ $reservation->guest_email }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-500">Room</p>
                <p class="font-semibold">{{ $reservation->room->room_number }} ({{ $reservation->room->roomType->name }})</p>
            </div>
            <div>
                <p class="text-sm text-slate-500">Guests</p>
                <p class="font-semibold">{{ $reservation->guests }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-500">Check-in</p>
                @if(!empty($reservation->check_in_at))
                    <p class="font-semibold">{{ $reservation->check_in_at->format('M d, Y h:i A') }}</p>
                @else
                    <p class="font-semibold">{{ $reservation->check_in->format('M d, Y') }}</p>
                @endif
            </div>
            <div>
                <p class="text-sm text-slate-500">Check-out</p>
                <p class="font-semibold">{{ $reservation->check_out->format('M d, Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-500">Nights</p>
                <p class="font-semibold">{{ $reservation->nights }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-500">Status</p>
                <p class="font-semibold capitalize">{{ str_replace('_', ' ', $reservation->status) }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm text-slate-500">Special Requests</p>
                <p class="font-semibold">{{ $reservation->special_requests ?: 'None' }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm text-slate-500">Total Amount</p>
                <p class="text-xl font-bold text-amber-700">PHP {{ number_format($reservation->total_amount, 2) }}</p>
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <a href="{{ route('reservations.edit', $reservation) }}" class="btn-primary px-4 py-2.5">Edit</a>
            <a href="{{ route('reservations.index') }}" class="btn-secondary px-4 py-2.5">Back</a>
        </div>
    </div>
@endsection
