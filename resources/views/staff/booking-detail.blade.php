@extends('layouts.app')

@section('content')
    <style>
        .detail-shell {
            border-radius: 20px;
            border: 1px solid rgba(185, 154, 97, 0.12);
            background: linear-gradient(135deg, #faf8f4 0%, #f5f3ef 100%);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
        }

        .glass-panel {
            border-radius: 16px;
            border: 1px solid rgba(185, 154, 97, 0.15);
            background: #ffffff;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-top: 1rem;
        }

        .detail-item {
            padding: 1rem;
            border-radius: 12px;
            background: #faf8f4;
            border: 1px solid rgba(185, 154, 97, 0.1);
        }

        .detail-label {
            color: #8b7355;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .detail-value {
            color: #2c2c2c;
            font-size: 1.1rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }

        .status-badge {
            display: inline-block;
            padding: 0.3rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-confirmed {
            background: #dcfce7;
            color: #15803d;
        }

        .status-checked-in {
            background: #cffafe;
            color: #0c4a6e;
        }

        .status-completed {
            background: #e9d5ff;
            color: #6b21a8;
        }

        .action-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: transform 0.16s ease, opacity 0.16s ease;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            opacity: 0.9;
        }

        .btn-checkin {
            background: #dcfce7;
            color: #15803d;
        }

        .btn-checkout {
            background: #e9d5ff;
            color: #6b21a8;
        }

        .btn-back {
            background: #e5e7eb;
            color: #374151;
        }
    </style>

    <div class="detail-shell p-5 sm:p-7">
        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div>
                <a href="{{ route('staff.bookings') }}" class="text-sm text-blue-600 hover:text-blue-800">← Back to Bookings</a>
                <h1 class="section-title text-4xl font-bold text-gray-900 mt-2">Booking #{{ $book->id }}</h1>
            </div>
            <span class="status-badge status-{{ str_replace('_', '-', $book->status) }}">
                {{ str_replace('_', ' ', $book->status) }}
            </span>
        </div>

        <!-- Guest Information -->
        <div class="glass-panel p-5 sm:p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Guest Information</h2>
            <div class="detail-grid">
                <div class="detail-item">
                    <p class="detail-label">Name</p>
                    <p class="detail-value">{{ $book->guest_name }}</p>
                </div>
                <div class="detail-item">
                    <p class="detail-label">Email</p>
                    <p class="detail-value text-sm">{{ $book->guest_email }}</p>
                </div>
                <div class="detail-item">
                    <p class="detail-label">Phone</p>
                    <p class="detail-value">{{ $book->guest_phone ?? 'Not provided' }}</p>
                </div>
            </div>
        </div>

        <!-- Room Information -->
        <div class="glass-panel p-5 sm:p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Room Information</h2>
            <div class="detail-grid">
                <div class="detail-item">
                    <p class="detail-label">Room Number</p>
                    <p class="detail-value">{{ $book->room->room_number }}</p>
                </div>
                <div class="detail-item">
                    <p class="detail-label">Room Type</p>
                    <p class="detail-value">{{ $book->room->roomType->name }}</p>
                </div>
                <div class="detail-item">
                    <p class="detail-label">Room Status</p>
                    <p class="detail-value">{{ ucfirst($book->room->status) }}</p>
                </div>
            </div>
        </div>

        <!-- Stay Details -->
        <div class="glass-panel p-5 sm:p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Stay Details</h2>
            <div class="detail-grid">
                <div class="detail-item">
                    <p class="detail-label">Check-in Date</p>
                    <p class="detail-value">{{ $book->check_in->format('M d, Y') }}</p>
                </div>
                <div class="detail-item">
                    <p class="detail-label">Check-out Date</p>
                    <p class="detail-value">{{ $book->check_out->format('M d, Y') }}</p>
                </div>
                <div class="detail-item">
                    <p class="detail-label">Number of Nights</p>
                    <p class="detail-value">{{ $book->check_in->diffInDays($book->check_out) }}</p>
                </div>
                <div class="detail-item">
                    <p class="detail-label">Total Amount</p>
                    <p class="detail-value text-amber-900">PHP {{ number_format($book->total_amount, 2) }}</p>
                </div>
                <div class="detail-item">
                    <p class="detail-label">Payment Status</p>
                    <p class="detail-value">{{ ucfirst(str_replace('_', ' ', $book->payment_status ?? 'unpaid')) }}</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        @if ($book->status === 'confirmed' && $book->payment_status === 'paid')
            <div class="glass-panel p-5 sm:p-6">
                <p class="text-gray-600 mb-4">This guest is ready to check in.</p>
                <form action="{{ route('staff.checkin', $book) }}" method="POST" class="inline" data-confirm-action="Confirm check-in for {{ $book->guest_name }} in Room {{ $book->room->room_number }}?" data-confirm-title="Confirm Check-in" data-confirm-button="Yes, Check-in" data-loader-message="Checking in guest...">
                    @csrf
                    <button type="submit" class="action-btn btn-checkin">Check-in Guest</button>
                </form>
            </div>
        @elseif ($book->status === 'confirmed' && $book->payment_status !== 'paid')
            <div class="glass-panel p-5 sm:p-6">
                <p class="text-gray-600">This booking is confirmed but not paid yet. Check-in is disabled until payment is completed.</p>
            </div>
        @elseif ($book->status === 'checked_in')
            <div class="glass-panel p-5 sm:p-6">
                <p class="text-gray-600 mb-4">The guest is currently checked in. Process check-out below.</p>
                <form action="{{ route('staff.checkout', $book) }}" method="POST" class="inline" data-confirm-action="Confirm check-out for {{ $book->guest_name }} in Room {{ $book->room->room_number }}?" data-confirm-title="Confirm Check-out" data-confirm-button="Yes, Check-out" data-loader-message="Checking out guest...">
                    @csrf
                    <button type="submit" class="action-btn btn-checkout">Check-out Guest</button>
                </form>
            </div>
        @else
            <div class="glass-panel p-5 sm:p-6">
                <p class="text-gray-600">This booking has been completed.</p>
            </div>
        @endif
    </div>
@endsection
