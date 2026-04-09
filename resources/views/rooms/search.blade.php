@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold">Search Rooms</h1>

    <form method="GET" action="{{ route('rooms.search') }}" class="mt-5 grid gap-3 rounded-lg bg-white p-4 shadow md:grid-cols-4">
        <select name="room_type_id" class="rounded border border-slate-300 px-3 py-2">
            <option value="">All room types</option>
            @foreach ($roomTypes as $type)
                <option value="{{ $type->id }}" @selected(request('room_type_id') == $type->id)>{{ $type->name }}</option>
            @endforeach
        </select>

        <select name="status" class="rounded border border-slate-300 px-3 py-2">
            <option value="">Any status</option>
            <option value="available" @selected(request('status') === 'available')>Available</option>
            <option value="occupied" @selected(request('status') === 'occupied')>Occupied</option>
            <option value="maintenance" @selected(request('status') === 'maintenance')>Maintenance</option>
        </select>

        <input type="number" min="1" name="guests" value="{{ request('guests') }}" placeholder="Guests"
            class="rounded border border-slate-300 px-3 py-2">

        <button class="rounded bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700">Filter</button>
    </form>

    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($rooms as $room)
            <article class="rounded-lg bg-white p-4 shadow">
                <h2 class="text-xl font-semibold">Room {{ $room->room_number }}</h2>
                <p class="mt-2 text-sm text-slate-600">Type: {{ $room->roomType->name }}</p>
                <p class="text-sm text-slate-600">Max Guests: {{ $room->max_guests }}</p>
                <p class="text-sm capitalize text-slate-600">Status: {{ $room->status }}</p>
                <p class="mt-2 font-semibold text-blue-700">PHP {{ number_format($room->roomType->base_price, 2) }} / night</p>
                <a href="{{ route('reservations.create', ['room_id' => $room->id]) }}"
                    class="mt-3 inline-block rounded bg-slate-900 px-3 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                    Reserve This Room
                </a>
            </article>
        @empty
            <p class="text-slate-600">No rooms found for current filters.</p>
        @endforelse
    </div>

    <div class="mt-5">{{ $rooms->links() }}</div>
@endsection
