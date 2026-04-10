@extends('layouts.app')

@section('content')
    <style>
        .confirm-shell {
            max-width: 850px;
            margin: 0 auto;
            border-radius: 18px;
            border: 1px solid rgba(185, 154, 97, 0.18);
            background: linear-gradient(135deg, #fcfaf5 0%, #f4efe6 100%);
            box-shadow: 0 16px 34px rgba(31, 24, 16, 0.07);
            padding: 1.5rem;
        }

        .card {
            border-radius: 14px;
            border: 1px solid rgba(185, 154, 97, 0.14);
            background: #fff;
            box-shadow: 0 8px 20px rgba(33, 26, 16, 0.05);
            padding: 1rem;
        }
    </style>

    <div class="confirm-shell">
        <div class="rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-800">
            Booking confirmed. Your reservation has been saved successfully.
        </div>

        <div class="mt-4 flex flex-wrap items-end justify-between gap-3">
            <div>
                <h1 class="section-title text-4xl font-bold text-stone-900">Booking Confirmation</h1>
                <p class="mt-1 text-sm text-stone-600">Reference Number: <span class="font-bold text-amber-800">{{ $reference }}</span></p>
            </div>
            <a href="{{ route('customer.history') }}" class="rounded-xl border border-stone-300 px-4 py-2 text-sm font-semibold text-stone-700 hover:bg-stone-50">View My Bookings</a>
        </div>

        <div class="card mt-5 grid gap-4 md:grid-cols-2">
            <div>
                <p class="text-xs uppercase tracking-[0.16em] text-stone-500">Guest Name</p>
                <p class="mt-1 font-semibold text-stone-900">{{ $reservation->guest_name }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-[0.16em] text-stone-500">Room</p>
                <p class="mt-1 font-semibold text-stone-900">Room {{ $reservation->room->room_number }} • {{ $reservation->room->roomType->name }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-[0.16em] text-stone-500">Check-In</p>
                <p class="mt-1 font-semibold text-stone-900">{{ $reservation->check_in->format('M d, Y') }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-[0.16em] text-stone-500">Check-Out</p>
                <p class="mt-1 font-semibold text-stone-900">{{ $reservation->check_out->format('M d, Y') }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-[0.16em] text-stone-500">Status</p>
                <p class="mt-1 font-semibold capitalize text-stone-900">{{ str_replace('_', ' ', $reservation->status) }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-[0.16em] text-stone-500">Total Price</p>
                <p class="mt-1 text-xl font-bold text-amber-800">PHP {{ number_format((float) $reservation->total_amount, 2) }}</p>
            </div>
        </div>
    </div>
@endsection
