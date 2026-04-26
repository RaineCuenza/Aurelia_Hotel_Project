@extends('layouts.app')

@section('content')
    <h1 class="section-title text-4xl font-semibold text-slate-900">Search Rooms</h1>
    <p class="mt-1 text-sm text-slate-500">Filter rooms by type, status, and guest capacity.</p>

    <form method="GET" action="{{ route('rooms.search') }}" class="surface mt-5 grid gap-3 p-4 md:grid-cols-4">
        <select name="room_type_id" class="rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-slate-700">
            <option value="">All room types</option>
            @foreach ($roomTypes as $type)
                <option value="{{ $type->id }}" @selected(request('room_type_id') == $type->id)>{{ $type->name }}</option>
            @endforeach
        </select>

        <select name="status" class="rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-slate-700">
            <option value="">Any status</option>
            <option value="available" @selected(request('status') === 'available')>Available</option>
            <option value="occupied" @selected(request('status') === 'occupied')>Occupied</option>
            <option value="maintenance" @selected(request('status') === 'maintenance')>Maintenance</option>
        </select>

        <input type="number" min="1" name="guests" value="{{ request('guests') }}" placeholder="Guests"
            class="rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-slate-700">

        <button class="btn-primary px-4 py-2.5">Filter</button>
    </form>

    <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @forelse ($rooms as $room)
            <article class="surface p-4">
                @php
                    $canBook = $room->status === 'available';
                @endphp
                <h2 class="text-xl font-bold text-slate-900">Room {{ $room->room_number }}</h2>
                <p class="mt-2 text-sm text-slate-600">Type: {{ $room->roomType->name }}</p>
                <p class="text-sm text-slate-600">Max Guests: {{ $room->max_guests }}</p>
                <p class="text-sm capitalize text-slate-600">Status: {{ $room->status }}</p>
                <p class="mt-3 text-lg font-bold text-amber-700">PHP {{ number_format($room->roomType->base_price, 2) }} / night</p>

                @if ($canBook)
                    <a href="{{ route('reservations.create', ['room_id' => $room->id]) }}"
                        class="btn-primary mt-4 inline-block px-3 py-2 text-sm">
                        Book Room
                    </a>
                @else
                    <span class="mt-4 inline-flex cursor-not-allowed items-center rounded-xl bg-slate-300 px-3 py-2 text-sm font-semibold text-slate-600 opacity-85"
                        aria-disabled="true">
                        Booking Unavailable ({{ ucfirst($room->status) }})
                    </span>
                @endif
            </article>
        @empty
            <p class="text-slate-600">No rooms found for current filters.</p>
        @endforelse
    </div>

    <div class="mt-5">{{ $rooms->links() }}</div>
@endsection
