@extends('layouts.app')

@section('content')
    <style>
        .reservation-shell {
            border-radius: 20px;
            border: 1px solid rgba(185, 154, 97, 0.12);
            background: linear-gradient(135deg, #faf8f4 0%, #f5f3ef 100%);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
        }

        .field-label {
            margin-bottom: 0.45rem;
            display: block;
            color: #8b7355;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .field-input {
            width: 100%;
            border-radius: 14px;
            border: 1px solid rgba(185, 154, 97, 0.15);
            background: #ffffff;
            padding: 0.75rem 0.85rem;
            color: #2c2c2c;
            transition: border-color 0.16s ease, box-shadow 0.16s ease;
        }

        .field-input::placeholder {
            color: #d1d5db;
        }

        .field-input:focus {
            outline: none;
            border-color: #b99a61;
            box-shadow: 0 0 0 3px rgba(185, 154, 97, 0.1);
        }

        .btn-gold-submit {
            border-radius: 12px;
            background: linear-gradient(135deg, #b99a61, #9e814d);
            color: #fff;
            font-weight: 700;
            padding: 0.75rem 1rem;
            transition: transform 0.16s ease, box-shadow 0.16s ease;
        }

        .btn-gold-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(185, 154, 97, 0.25);
        }

        .price-card {
            border-radius: 16px;
            border: 1px solid rgba(185, 154, 97, 0.1);
            background: #ffffff;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
        }
    </style>

    @php
        $currentRoomId = old('room_id', request('room_id'));
    @endphp

    <div class="mx-auto w-full max-w-4xl">
        <div class="reservation-shell p-6 sm:p-8">
            <h1 class="section-title text-4xl font-bold text-gray-900">New Reservation</h1>
            <p class="mt-2 text-sm text-gray-600">Create a premium guest booking for Aurelia Hotel.</p>

            @if (session('success'))
                <div class="mt-5 rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mt-5 rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mt-5 rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <p class="font-semibold">Please fix the following errors:</p>
                    <ul class="mt-1 list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('reservations.store') }}" method="POST" class="mt-6">
                @csrf

                <input type="hidden" name="status" value="confirmed">

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="field-label">Guest Name</label>
                        <input type="text" name="guest_name" value="{{ old('guest_name') }}" required class="field-input" placeholder="Enter guest full name">
                    </div>

                    <div>
                        <label class="field-label">Email Address</label>
                        <input type="email" name="guest_email" value="{{ old('guest_email') }}" required class="field-input" placeholder="guest@email.com">
                    </div>

                    <div>
                        <label class="field-label">Phone Number</label>
                        <input type="tel" name="guest_phone" value="{{ old('guest_phone') }}" required class="field-input" placeholder="+63 9XX XXX XXXX">
                    </div>

                    <div>
                        <label class="field-label">Room Selection</label>
                        <select id="room_id" name="room_id" required class="field-input">
                            <option value="">Select room</option>
                            @foreach ($rooms as $room)
                                <option value="{{ $room->id }}" data-price="{{ $room->roomType->base_price }}" @selected((string) $currentRoomId === (string) $room->id)>
                                    Room {{ $room->room_number }} - {{ $room->roomType->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="field-label">Check-in Date</label>
                        <input id="check_in" type="date" name="check_in" value="{{ old('check_in', now()->toDateString()) }}" required class="field-input">
                    </div>

                    <div>
                        <label class="field-label">Check-out Date</label>
                        <input id="check_out" type="date" name="check_out" value="{{ old('check_out', now()->addDay()->toDateString()) }}" required class="field-input">
                    </div>
                </div>

                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <div class="price-card p-4">
                        <p class="text-xs uppercase tracking-[0.14em] text-gray-600 font-semibold">Room Price</p>
                        <p id="roomPrice" class="mt-2 text-2xl font-bold text-gray-900">Select a room</p>
                        <p class="mt-1 text-sm text-gray-500">Price per night</p>
                    </div>

                    <div class="price-card p-4">
                        <p class="text-xs uppercase tracking-[0.14em] text-gray-600 font-semibold">Total Price</p>
                        <p id="totalPrice" class="mt-2 text-2xl font-bold text-gray-900">₱0.00</p>
                        <p id="nightsLabel" class="mt-1 text-sm text-gray-500">0 nights</p>
                    </div>
                </div>

                <div class="mt-6 rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    Booking status is automatically set to <span class="font-semibold">Confirmed</span>.
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    <button type="submit" class="btn-gold-submit">Create Reservation</button>
                    <a href="{{ route('reservations.index') }}" class="btn-secondary px-4 py-3">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const roomSelect = document.getElementById('room_id');
        const checkInInput = document.getElementById('check_in');
        const checkOutInput = document.getElementById('check_out');
        const roomPriceLabel = document.getElementById('roomPrice');
        const totalPriceLabel = document.getElementById('totalPrice');
        const nightsLabel = document.getElementById('nightsLabel');

        const formatPeso = (value) => new Intl.NumberFormat('en-PH', {
            style: 'currency',
            currency: 'PHP',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        }).format(value);

        const getSelectedPrice = () => {
            const selectedOption = roomSelect?.selectedOptions?.[0];
            return selectedOption ? Number(selectedOption.dataset.price || 0) : 0;
        };

        const getNightCount = () => {
            if (!checkInInput.value || !checkOutInput.value) {
                return 0;
            }

            const start = new Date(checkInInput.value);
            const end = new Date(checkOutInput.value);
            const diffMs = end - start;

            if (Number.isNaN(diffMs) || diffMs <= 0) {
                return 0;
            }

            return Math.ceil(diffMs / (1000 * 60 * 60 * 24));
        };

        const updatePricePreview = () => {
            const nightlyRate = getSelectedPrice();
            const nights = getNightCount();
            const total = nightlyRate * nights;

            roomPriceLabel.textContent = nightlyRate > 0 ? `${formatPeso(nightlyRate)} / night` : 'Select a room';
            totalPriceLabel.textContent = formatPeso(total);
            nightsLabel.textContent = `${nights} night${nights === 1 ? '' : 's'}`;
        };

        roomSelect?.addEventListener('change', updatePricePreview);
        checkInInput?.addEventListener('change', updatePricePreview);
        checkOutInput?.addEventListener('change', updatePricePreview);

        updatePricePreview();
    </script>
@endsection
