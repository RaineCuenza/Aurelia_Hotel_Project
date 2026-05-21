@extends('layouts.app')

@section('content')
    <style>
        .manage-shell {
            border-radius: 20px;
            border: 1px solid rgba(185, 154, 97, 0.12);
            background: linear-gradient(135deg, #faf8f4 0%, #f5f3ef 100%);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
        }

        .glass-panel {
            border-radius: 16px;
            border: 1px solid rgba(185, 154, 97, 0.12);
            background: #ffffff;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
        }

        .table-head {
            color: #6b7280;
            font-weight: 700;
            background: #faf8f4;
        }

        .table-row {
            color: #2c2c2c;
            border-top: 1px solid rgba(185, 154, 97, 0.1);
            transition: background-color 0.2s ease;
        }

        .table-row:hover {
            background-color: rgba(245, 243, 239, 0.5);
        }

        .field-input {
            width: 100%;
            border-radius: 12px;
            border: 1px solid rgba(185, 154, 97, 0.15);
            background: #ffffff;
            padding: 0.75rem 0.85rem;
            color: #2c2c2c;
        }

        .field-input:focus {
            outline: none;
            border-color: #b99a61;
            box-shadow: 0 0 0 3px rgba(185, 154, 97, 0.1);
        }

        .action-link {
            border-radius: 8px;
            padding: 0.48rem 0.7rem;
            font-size: 0.75rem;
            font-weight: 700;
            transition: transform 0.16s ease, opacity 0.16s ease;
        }

        .action-link:hover {
            transform: translateY(-1px);
            opacity: 0.88;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            border-radius: 9999px;
            border: 1px solid transparent;
            padding: 0.3rem 0.75rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .status-pending {
            color: #92400e;
            background: #fef3c7;
            border-color: rgba(185, 154, 97, 0.3);
        }

        .status-confirmed {
            color: #15803d;
            background: #dcfce7;
            border-color: rgba(34, 197, 94, 0.3);
        }

        .status-checked_in {
            color: #0c4a6e;
            background: #cffafe;
            border-color: rgba(6, 182, 212, 0.3);
        }

        .status-checked_out {
            color: #6b21a8;
            background: #e9d5ff;
            border-color: rgba(168, 85, 247, 0.3);
        }

        .status-cancelled {
            color: #7f1d1d;
            background: #fee2e2;
            border-color: rgba(239, 68, 68, 0.3);
        }

        .books-table {
            min-width: 980px;
        }
    </style>

    <div class="manage-shell p-5 sm:p-7">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="section-title text-4xl font-bold text-gray-900">Manage Bookings</h1>
                <p class="mt-2 text-sm text-gray-600">Review, approve, cancel, and edit live bookings.</p>
            </div>
            <a href="{{ route('bookings.create') }}" class="btn-primary px-4 py-2.5">New Booking</a>
        </div>

        <form method="GET" action="{{ route('bookings.index') }}" class="glass-panel mt-6 grid gap-3 p-4 md:grid-cols-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search guest / email / room"
                class="field-input">

            <select name="status" class="field-input">
                <option value="">Any status</option>
                <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                <option value="confirmed" @selected(request('status') === 'confirmed')>Confirmed</option>
                <option value="checked_in" @selected(request('status') === 'checked_in')>Checked in</option>
                <option value="checked_out" @selected(request('status') === 'checked_out')>Checked out</option>
                <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
            </select>

            <button class="btn-primary px-4 py-2.5">Apply Filters</button>
        </form>

        <div class="glass-panel mt-6 overflow-x-auto p-4">
            <table class="books-table w-full text-left text-sm">
                <thead>
                    <tr class="table-head">
                        <th class="py-3 pr-3 font-semibold">Guest</th>
                        <th class="py-3 pr-3 font-semibold">Room</th>
                        <th class="py-3 pr-3 font-semibold">Dates</th>
                        <th class="py-3 pr-3 font-semibold">Total</th>
                        <th class="py-3 pr-3 font-semibold">Status</th>
                        <th class="py-3 pr-3 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $book)
                        <tr class="table-row align-top">
                            <td class="py-3 pr-3">
                                <p class="font-medium text-gray-900">{{ $book->guest_name }}</p>
                                <p class="text-xs text-gray-500">{{ $book->guest_email }}</p>
                                <p class="text-xs text-gray-400">{{ $book->guest_phone ?? 'No phone provided' }}</p>
                            </td>
                            <td class="py-3 pr-3 text-gray-800">{{ $book->room->room_number }} ({{ $book->room->roomType->name }})</td>
                            <td class="py-3 pr-3 text-gray-700">
                                {{ $book->check_in->format('M d, Y') }}<br>
                                {{ $book->check_out->format('M d, Y') }}
                            </td>
                            <td class="py-3 pr-3 font-semibold text-amber-900">PHP {{ number_format($book->total_amount, 2) }}</td>
                            <td class="py-3 pr-3">
                                <span class="status-badge status-{{ $book->status }}">{{ str_replace('_', ' ', $book->status) }}</span>
                            </td>
                            <td class="py-3 pr-3">
                                <div class="flex flex-wrap gap-2">
                                    @if ($book->status === 'pending')
                                        <form action="{{ route('bookings.status', $book) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="confirmed">
                                            <button class="action-link bg-emerald-500 text-white">Approve</button>
                                        </form>
                                    @endif

                                    @if ($book->status !== 'cancelled' && $book->status !== 'checked_out')
                                        <button type="button" class="action-link bg-red-500 text-white" data-open-booking-cancel-modal="booking-cancel-modal-{{ $book->id }}">Cancel</button>
                                    @endif

                                    @if ($book->status !== 'cancelled')
                                        <a href="{{ route('bookings.edit', $book) }}" class="action-link bg-amber-400 text-slate-950">Edit</a>
                                    @endif
                                    <a href="{{ route('bookings.show', $book) }}" class="action-link bg-slate-200 text-slate-900">View</a>
                                </div>
                            </td>
                        </tr>
                        @include('bookings.partials.cancel-modal', ['book' => $book])
                    @empty
                        <tr>
                            <td colspan="6" class="py-5 text-gray-400">No bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-5 text-gray-600">{{ $bookings->links() }}</div>
    </div>
@endsection
