@extends('layouts.app')

@section('content')
    <style>
        .search-shell {
            border-radius: 18px;
            border: 1px solid rgba(185, 154, 97, 0.16);
            background: linear-gradient(135deg, #faf8f3 0%, #f4f0e7 100%);
            box-shadow: 0 12px 28px rgba(33, 26, 16, 0.06);
            padding: 1.1rem;
        }

        .filter-input {
            width: 100%;
            border-radius: 12px;
            border: 1px solid #e4dbc9;
            background: #fff;
            padding: 0.72rem 0.8rem;
            color: #2e2a24;
        }

        .filter-input:focus {
            outline: none;
            border-color: #b99a61;
            box-shadow: 0 0 0 3px rgba(185, 154, 97, 0.12);
        }

        .room-card {
            border-radius: 16px;
            border: 1px solid rgba(185, 154, 97, 0.16);
            background: #fff;
            box-shadow: 0 10px 24px rgba(36, 28, 19, 0.06);
            overflow: hidden;
        }

        .room-image {
            height: 170px;
            background-size: cover;
            background-position: center;
        }

        .btn-gold {
            border-radius: 12px;
            background: linear-gradient(135deg, #b99a61, #9e814d);
            color: #fff;
            font-weight: 700;
            padding: 0.65rem 0.95rem;
            transition: transform 0.16s ease;
        }

        .btn-gold:hover {
            transform: translateY(-1px);
        }
    </style>

    <h1 class="section-title text-4xl font-bold text-stone-900">Available Rooms</h1>
    <p class="mt-1 text-sm text-stone-600">Find your ideal stay based on your selected dates.</p>

    <form method="GET" action="{{ route('customer.search') }}" class="search-shell mt-5 grid gap-3 md:grid-cols-5">
        <input type="date" name="check_in" value="{{ request('check_in', now()->toDateString()) }}" class="filter-input" min="{{ now()->toDateString() }}" required>
        <input type="date" name="check_out" value="{{ request('check_out', now()->addDay()->toDateString()) }}" class="filter-input" min="{{ now()->toDateString() }}" required>
        <input type="number" name="guests" value="{{ request('guests') }}" min="1" max="12" placeholder="Guests" class="filter-input" required>

        <select name="room_type_id" class="filter-input">
            <option value="">All room types</option>
            @foreach ($roomTypes as $type)
                <option value="{{ $type->id }}" @selected((string) request('room_type_id') === (string) $type->id)>{{ $type->name }}</option>
            @endforeach
        </select>

        <button class="btn-gold">Search</button>
    </form>

    <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @forelse ($rooms as $room)
            @php
                $image = "https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=1200&q=60";
                if ($room->roomType->slug === 'suite') {
                    $image = "https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=1200&q=60";
                }
                if ($room->roomType->slug === 'deluxe') {
                    $image = "https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=1200&q=60";
                }
            @endphp

            <article class="room-card">
                <div class="room-image" style="background-image: linear-gradient(145deg, rgba(12, 16, 23, 0.05), rgba(12, 16, 23, 0.3)), url('{{ $image }}');"></div>
                <div class="p-4">
                    <p class="text-xs uppercase tracking-[0.15em] text-stone-500">Room {{ $room->room_number }}</p>
                    <h2 class="section-title mt-1 text-3xl font-semibold text-stone-900">{{ $room->roomType->name }}</h2>
                    <p class="mt-1 text-sm text-stone-600">Fits up to {{ $room->max_guests }} guest{{ $room->max_guests > 1 ? 's' : '' }}</p>
                    <p class="mt-3 text-xl font-bold text-amber-800">PHP {{ number_format((float) $room->roomType->base_price, 2) }} / night</p>

                    <div class="mt-4 flex gap-2">
                        <a href="{{ route('customer.room.details', ['room' => $room->id, 'check_in' => request('check_in'), 'check_out' => request('check_out'), 'guests' => request('guests')]) }}" class="rounded-xl border border-stone-300 px-3 py-2 text-sm font-semibold text-stone-700 hover:bg-stone-50">Details</a>
                        <a href="{{ route('customer.booking.form', ['room' => $room->id, 'check_in' => request('check_in'), 'check_out' => request('check_out'), 'guests' => request('guests')]) }}" class="btn-gold text-sm">Reserve</a>
                    </div>
                </div>
            </article>
        @empty
            <p class="text-stone-600">No available rooms found for your selected dates.</p>
        @endforelse
    </div>

    <div class="mt-6">{{ $rooms->links() }}</div>
@endsection
