@extends('layouts.app')

@section('content')
    @php
        $image = 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=1400&q=60';
        if ($room->roomType->slug === 'suite') {
            $image = 'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=1400&q=60';
        }
        if ($room->roomType->slug === 'deluxe') {
            $image = 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=1400&q=60';
        }
    @endphp

    <style>
        .detail-shell {
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid rgba(185, 154, 97, 0.16);
            background: #fff;
            box-shadow: 0 14px 32px rgba(31, 24, 16, 0.08);
        }

        .hero-image {
            min-height: 320px;
            background-size: cover;
            background-position: center;
        }

        .amenity-chip {
            border-radius: 9999px;
            border: 1px solid rgba(185, 154, 97, 0.25);
            background: #faf6ed;
            padding: 0.4rem 0.7rem;
            font-size: 0.78rem;
            font-weight: 600;
            color: #7b6644;
        }

        .btn-gold {
            border-radius: 12px;
            background: linear-gradient(135deg, #b99a61, #9e814d);
            color: #fff;
            font-weight: 700;
            padding: 0.78rem 1rem;
            transition: transform 0.16s ease;
        }

        .btn-gold:hover {
            transform: translateY(-1px);
        }
    </style>

    <article class="detail-shell">
        <div class="hero-image" style="background-image: linear-gradient(145deg, rgba(12, 16, 23, 0.12), rgba(12, 16, 23, 0.42)), url('{{ $image }}')"></div>

        <div class="p-5 sm:p-7">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <p class="text-xs uppercase tracking-[0.16em] text-stone-500">Room {{ $room->room_number }}</p>
                    <h1 class="section-title mt-1 text-4xl font-bold text-stone-900">{{ $room->roomType->name }}</h1>
                    <p class="mt-2 text-sm text-stone-600">{{ $room->roomType->description ?: 'Designed for premium comfort with warm, timeless interiors.' }}</p>
                </div>
                <p class="text-2xl font-bold text-amber-800">PHP {{ number_format((float) $room->roomType->base_price, 2) }} / night</p>
            </div>

            @if ($room->status !== 'available')
                <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    This room is currently {{ $room->status }} and cannot be booked right now.
                </div>
            @endif

            <div class="mt-5 flex flex-wrap gap-2">
                <span class="amenity-chip">Luxury Bedding</span>
                <span class="amenity-chip">Smart TV</span>
                <span class="amenity-chip">Fast Wi-Fi</span>
                <span class="amenity-chip">Rain Shower</span>
                <span class="amenity-chip">In-room Coffee</span>
                <span class="amenity-chip">Max {{ $room->max_guests }} Guests</span>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                @if ($room->status === 'available')
                    <a href="{{ route('customer.booking.form', ['room' => $room->id, 'check_in' => $checkIn, 'check_out' => $checkOut, 'guests' => $guests]) }}" class="btn-gold">Book Now</a>
                @endif
                <a href="{{ route('customer.search', ['check_in' => $checkIn, 'check_out' => $checkOut, 'guests' => $guests]) }}" class="rounded-xl border border-stone-300 px-4 py-3 text-sm font-semibold text-stone-700 hover:bg-stone-50">Back to Search</a>
            </div>
        </div>
    </article>
@endsection
