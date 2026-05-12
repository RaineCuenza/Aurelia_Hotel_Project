@extends('layouts.app')

@section('content')
    <style>
        .payment-shell {
            max-width: 820px;
            margin: 0 auto;
            border-radius: 18px;
            border: 1px solid rgba(185, 154, 97, 0.16);
            background: linear-gradient(135deg, #faf8f3 0%, #f4f0e7 100%);
            box-shadow: 0 14px 32px rgba(31, 24, 16, 0.07);
            padding: 1.4rem;
        }

        .panel {
            border-radius: 14px;
            border: 1px solid rgba(185, 154, 97, 0.14);
            background: #fff;
            box-shadow: 0 8px 20px rgba(33, 26, 16, 0.05);
            padding: 1rem;
        }

        .mock-btn {
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-weight: 700;
            border: 1px solid transparent;
        }

        .mock-success {
            background: #16a34a;
            color: #fff;
        }

        .mock-failed {
            background: #ef4444;
            color: #fff;
        }

        .mock-cancel {
            background: #e5e7eb;
            color: #1f2937;
        }
    </style>

    <div class="payment-shell">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h1 class="section-title text-4xl font-bold text-stone-900">Mock Payment</h1>
                <p class="mt-1 text-sm text-stone-600">This is a sandbox payment step for your booking flow.</p>
            </div>
            <a href="{{ route('customer.history') }}" class="rounded-xl border border-stone-300 px-4 py-2 text-sm font-semibold text-stone-700 hover:bg-stone-50">Back to My Bookings</a>
        </div>

        <div class="panel mt-5 grid gap-3 md:grid-cols-2">
            <div>
                <p class="text-xs uppercase tracking-[0.16em] text-stone-500">Booking Reference</p>
                <p class="mt-1 font-semibold text-stone-900">{{ $book->payment_reference }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-[0.16em] text-stone-500">Room</p>
                <p class="mt-1 font-semibold text-stone-900">Room {{ $book->room->room_number }} • {{ $book->room->roomType->name }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-[0.16em] text-stone-500">Dates</p>
                <p class="mt-1 font-semibold text-stone-900">{{ $book->check_in->format('M d, Y') }} - {{ $book->check_out->format('M d, Y') }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-[0.16em] text-stone-500">Total</p>
                <p class="mt-1 text-xl font-bold text-amber-800">PHP {{ number_format((float) $book->total_amount, 2) }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-xs uppercase tracking-[0.16em] text-stone-500">Payment Expires</p>
                <p class="mt-1 font-semibold text-stone-900">{{ optional($book->payment_expires_at)->format('M d, Y h:i A') ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="panel mt-4">
            <p class="text-sm text-stone-700">Choose a mock outcome to simulate payment gateway response:</p>

            <div class="mt-4 flex flex-wrap gap-2">
                <form action="{{ route('customer.booking.payment.process', $book) }}" method="POST">
                    @csrf
                    <input type="hidden" name="outcome" value="success">
                    <button type="submit" class="mock-btn mock-success">Simulate Payment Success</button>
                </form>

                <form action="{{ route('customer.booking.payment.process', $book) }}" method="POST">
                    @csrf
                    <input type="hidden" name="outcome" value="failed">
                    <button type="submit" class="mock-btn mock-failed">Simulate Payment Failed</button>
                </form>

                <form action="{{ route('customer.booking.payment.process', $book) }}" method="POST">
                    @csrf
                    <input type="hidden" name="outcome" value="cancelled">
                    <button type="submit" class="mock-btn mock-cancel">Simulate Cancel</button>
                </form>
            </div>
        </div>
    </div>
@endsection
