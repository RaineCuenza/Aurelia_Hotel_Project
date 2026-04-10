@extends('layouts.app')

@section('content')
    <style>
        .booking-shell {
            border-radius: 18px;
            border: 1px solid rgba(185, 154, 97, 0.16);
            background: linear-gradient(135deg, #faf8f3 0%, #f4f0e7 100%);
            box-shadow: 0 14px 32px rgba(31, 24, 16, 0.07);
        }

        .panel {
            border-radius: 14px;
            border: 1px solid rgba(185, 154, 97, 0.14);
            background: #fff;
            box-shadow: 0 8px 20px rgba(33, 26, 16, 0.05);
            padding: 1rem;
        }

        .field-label {
            color: #8a7250;
            font-size: 0.74rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .field-input {
            width: 100%;
            border-radius: 12px;
            border: 1px solid #e4dbc9;
            background: #fff;
            padding: 0.72rem 0.8rem;
            color: #2e2a24;
            margin-top: 0.35rem;
        }

        .field-input:focus {
            outline: none;
            border-color: #b99a61;
            box-shadow: 0 0 0 3px rgba(185, 154, 97, 0.12);
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

    <div class="mx-auto max-w-5xl booking-shell p-5 sm:p-7">
        <div class="mb-5 flex items-end justify-between gap-4">
            <div>
                <h1 class="section-title text-4xl font-bold text-stone-900">Complete Your Booking</h1>
                <p class="mt-1 text-sm text-stone-600">Secure your stay in a few quick steps.</p>
            </div>
            <p class="text-sm font-semibold text-stone-600">Room {{ $room->room_number }} • {{ $room->roomType->name }}</p>
        </div>

        @if ($room->status !== 'available')
            <div class="mb-5 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                This room is currently {{ $room->status }} and can no longer be booked.
                <a href="{{ route('customer.search', ['check_in' => $checkIn, 'check_out' => $checkOut, 'guests' => $guests]) }}" class="font-semibold underline">Return to search</a>
                for other available rooms.
            </div>
        @endif

        <div class="grid gap-4 lg:grid-cols-[1.35fr_1fr]">
            <form action="{{ route('customer.booking.store', $room) }}" method="POST" class="panel grid gap-4">
                @csrf

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="field-label" for="guest_name">Guest Name</label>
                        <input id="guest_name" name="guest_name" type="text" required class="field-input" value="{{ old('guest_name', auth()->user()->name ?? '') }}">
                    </div>
                    <div>
                        <label class="field-label" for="guest_email">Email</label>
                        <input id="guest_email" name="guest_email" type="email" required class="field-input" value="{{ old('guest_email', auth()->user()->email ?? '') }}">
                    </div>
                    <div>
                        <label class="field-label" for="guest_phone">Phone</label>
                        <input id="guest_phone" name="guest_phone" type="text" required class="field-input" value="{{ old('guest_phone') }}">
                    </div>
                    <div>
                        <label class="field-label" for="guests">Guests</label>
                        <input id="guests" name="guests" type="number" min="1" max="12" required class="field-input" value="{{ old('guests', $guests) }}">
                    </div>
                    <div>
                        <label class="field-label" for="check_in">Check-In</label>
                        <input id="check_in" name="check_in" type="date" required class="field-input" min="{{ now()->toDateString() }}" value="{{ old('check_in', $checkIn ?? now()->toDateString()) }}">
                    </div>
                    <div>
                        <label class="field-label" for="check_out">Check-Out</label>
                        <input id="check_out" name="check_out" type="date" required class="field-input" min="{{ now()->toDateString() }}" value="{{ old('check_out', $checkOut ?? now()->addDay()->toDateString()) }}">
                    </div>
                </div>

                <div>
                    <label class="field-label" for="special_requests">Special Requests (Optional)</label>
                    <textarea id="special_requests" name="special_requests" rows="4" class="field-input">{{ old('special_requests') }}</textarea>
                </div>

                @if ($room->status === 'available')
                    <button type="submit" class="btn-gold">Confirm Booking</button>
                @endif
            </form>

            <aside class="panel">
                <div id="bookingMeta" data-nightly-rate="{{ (float) $room->roomType->base_price }}"></div>
                <p class="text-xs uppercase tracking-[0.16em] text-stone-500">Booking Summary</p>
                <h2 class="section-title mt-2 text-3xl font-semibold text-stone-900">{{ $room->roomType->name }}</h2>
                <p class="mt-1 text-sm text-stone-600">Room {{ $room->room_number }}</p>

                <div class="mt-4 space-y-2 text-sm text-stone-700">
                    <div class="flex items-center justify-between">
                        <span>Rate / night</span>
                        <span class="font-semibold">PHP {{ number_format((float) $room->roomType->base_price, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Nights</span>
                        <span id="nightsCount" class="font-semibold">{{ $nights }}</span>
                    </div>
                    <div class="flex items-center justify-between border-t border-stone-200 pt-2">
                        <span>Total</span>
                        <span id="totalPrice" class="text-lg font-bold text-amber-800">PHP {{ number_format((float) $total, 2) }}</span>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <script>
        const checkInInput = document.getElementById('check_in');
        const checkOutInput = document.getElementById('check_out');
        const totalPriceEl = document.getElementById('totalPrice');
        const nightsEl = document.getElementById('nightsCount');
        const bookingMeta = document.getElementById('bookingMeta');
        const nightlyRate = Number(bookingMeta?.dataset?.nightlyRate || 0);

        const formatPeso = (amount) => new Intl.NumberFormat('en-PH', {
            style: 'currency',
            currency: 'PHP',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        }).format(amount);

        const updateSummary = () => {
            if (!checkInInput.value || !checkOutInput.value) {
                nightsEl.textContent = '0';
                totalPriceEl.textContent = formatPeso(0);
                return;
            }

            const checkIn = new Date(checkInInput.value);
            const checkOut = new Date(checkOutInput.value);
            const diffMs = checkOut - checkIn;

            if (Number.isNaN(diffMs) || diffMs <= 0) {
                nightsEl.textContent = '0';
                totalPriceEl.textContent = formatPeso(0);
                return;
            }

            const nights = Math.ceil(diffMs / (1000 * 60 * 60 * 24));
            nightsEl.textContent = String(nights);
            totalPriceEl.textContent = formatPeso(nights * nightlyRate);
        };

        checkInInput?.addEventListener('change', updateSummary);
        checkOutInput?.addEventListener('change', updateSummary);
        updateSummary();
    </script>
@endsection
