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

        .confirmed-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            border-radius: 14px;
            background: linear-gradient(135deg, #10b981, #059669);
            padding: 0.9rem 1.2rem;
            margin: -1.5rem -1.5rem 1.5rem;
            width: calc(100% + 3rem);
        }

        .confirmed-badge svg {
            width: 20px;
            height: 20px;
            color: #fff;
            flex-shrink: 0;
        }

        .confirmed-badge-text {
            font-weight: 600;
            color: #fff;
            font-size: 0.95rem;
            letter-spacing: 0.3px;
        }

        .reminder-section {
            border-radius: 14px;
            border: 1px solid rgba(185, 154, 97, 0.12);
            background: rgba(245, 243, 239, 0.6);
            padding: 1.2rem;
            margin-top: 1.5rem;
        }

        .reminder-title {
            font-size: 1rem;
            font-weight: 700;
            color: #2c2c2c;
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .reminder-title svg {
            width: 20px;
            height: 20px;
            color: #b99a61;
        }

        .reminder-list {
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
        }

        .reminder-item {
            display: flex;
            gap: 0.65rem;
            font-size: 0.9rem;
            color: #4b5563;
            line-height: 1.5;
        }

        .reminder-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #b99a61;
            flex: 0 0 6px;
            margin-top: 0.45rem;
        }
    </style>

    <div class="confirm-shell">
        <div class="confirmed-badge">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            <span class="confirmed-badge-text">Booking Confirmed — Your Room is Reserved</span>
        </div>

        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h1 class="section-title text-4xl font-bold text-stone-900">Thank You for Booking with Us</h1>
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

        <div class="reminder-section">
            <p class="reminder-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                Important Reminders
            </p>
            <div class="reminder-list">
                <div class="reminder-item">
                    <div class="reminder-dot"></div>
                    <span><strong>Check-In Time:</strong> Standard check-in is at 2:00 PM. Early check-in may be available upon request—contact us directly.</span>
                </div>
                <div class="reminder-item">
                    <div class="reminder-dot"></div>
                    <span><strong>Valid ID Required:</strong> Please bring a valid government-issued ID and a credit card for registration during check-in.</span>
                </div>
                <div class="reminder-item">
                    <div class="reminder-dot"></div>
                    <span><strong>Check-Out Time:</strong> Standard check-out is at 11:00 AM. Late check-out may incur additional charges.</span>
                </div>
                <div class="reminder-item">
                    <div class="reminder-dot"></div>
                    <span><strong>Confirmation Email:</strong> A detailed confirmation has been sent to your registered email. Keep it handy for your arrival.</span>
                </div>
                <div class="reminder-item">
                    <div class="reminder-dot"></div>
                    <span><strong>Cancellation Policy:</strong> Review our cancellation terms. Changes can be made up to 48 hours before check-in.</span>
                </div>
                <div class="reminder-item">
                    <div class="reminder-dot"></div>
                    <span><strong>Need Help?</strong> Contact our 24/7 support team at support@stayease.local or call +63 9XX XXX XXXX if you have any questions.</span>
                </div>
            </div>
        </div>
    </div>
@endsection
