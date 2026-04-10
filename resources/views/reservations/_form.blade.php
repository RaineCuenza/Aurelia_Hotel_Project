@php
    $currentRoomId = old('room_id', $reservation->room_id ?? request('room_id'));
@endphp

<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label class="mb-1 block text-sm font-semibold text-slate-700">Guest Name</label>
        <input type="text" name="guest_name" value="{{ old('guest_name', $reservation->guest_name ?? '') }}" required
            class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-slate-700">
    </div>

    <div>
        <label class="mb-1 block text-sm font-semibold text-slate-700">Guest Email</label>
        <input type="email" name="guest_email" value="{{ old('guest_email', $reservation->guest_email ?? '') }}" required
            class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-slate-700">
    </div>

    <div>
        <label class="mb-1 block text-sm font-semibold text-slate-700">Check-in Date</label>
        <input type="date" name="check_in"
            value="{{ old('check_in', isset($reservation) ? optional($reservation->check_in)->format('Y-m-d') : now()->toDateString()) }}" required
            class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-slate-700">
    </div>

    <div>
        <label class="mb-1 block text-sm font-semibold text-slate-700">Check-out Date</label>
        <input type="date" name="check_out"
            value="{{ old('check_out', isset($reservation) ? optional($reservation->check_out)->format('Y-m-d') : now()->addDay()->toDateString()) }}" required
            class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-slate-700">
    </div>

    <div>
        <label class="mb-1 block text-sm font-semibold text-slate-700">Room</label>
        <select name="room_id" required class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-slate-700">
            <option value="">Select room</option>
            @foreach ($rooms as $room)
                <option value="{{ $room->id }}" @selected((string) $currentRoomId === (string) $room->id)>
                    {{ $room->room_number }} - {{ $room->roomType->name }} (max {{ $room->max_guests }})
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="mb-1 block text-sm font-semibold text-slate-700">Guests</label>
        <input type="number" min="1" max="12" name="guests" value="{{ old('guests', $reservation->guests ?? 1) }}" required
            class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-slate-700">
    </div>

    @isset($reservation)
        <div>
            <label class="mb-1 block text-sm font-semibold text-slate-700">Status</label>
            <select name="status" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-slate-700">
                @foreach (['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'] as $status)
                    <option value="{{ $status }}" @selected(old('status', $reservation->status) === $status)>
                        {{ str_replace('_', ' ', ucfirst($status)) }}
                    </option>
                @endforeach
            </select>
        </div>
    @endisset

    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-semibold text-slate-700">Special Requests</label>
        <textarea name="special_requests" rows="3" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-slate-700">{{ old('special_requests', $reservation->special_requests ?? '') }}</textarea>
    </div>
</div>
