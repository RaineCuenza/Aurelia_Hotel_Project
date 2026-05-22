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
            border: none;
            cursor: pointer;
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

        .status-checked-in {
            color: #0c4a6e;
            background: #cffafe;
            border-color: rgba(6, 182, 212, 0.3);
        }

        .status-completed {
            color: #6b21a8;
            background: #e9d5ff;
            border-color: rgba(168, 85, 247, 0.3);
        }

        .reservations-table {
            min-width: 980px;
        }

        .btn-checkin {
            background: #dcfce7;
            color: #15803d;
        }

        .btn-checkout {
            background: #e9d5ff;
            color: #6b21a8;
        }
    </style>

    <div class="manage-shell p-5 sm:p-7">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div>
                <h1 class="section-title text-4xl font-bold text-gray-900">Staff Bookings</h1>
                <p class="mt-2 text-sm text-gray-600">Manage guest check-ins and check-outs.</p>
            </div>
        </div>

        <!-- Filters -->
        <form method="GET" action="{{ route('staff.reservations') }}" class="glass-panel mb-6 grid gap-3 p-4 md:grid-cols-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search guest / email / room"
                class="field-input">

            <select name="status" class="field-input">
                <option value="">All status</option>
                <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                <option value="confirmed" @selected(request('status') === 'confirmed')>Confirmed</option>
                <option value="checked_in" @selected(request('status') === 'checked_in')>Checked In</option>
                <option value="checked_out" @selected(request('status') === 'checked_out')>Checked Out</option>
            </select>

            <button class="btn-primary px-4 py-2.5 rounded-lg font-semibold text-white" style="background: linear-gradient(135deg, #b99a61, #9e814d);">
                Filter
            </button>
        </form>

        <!-- Bookings Table -->
        <div class="glass-panel overflow-x-auto p-4">
            <table class="reservations-table w-full text-left text-sm">
                <thead>
                    <tr class="table-head">
                        <th class="py-3 pr-3 font-semibold">Guest</th>
                        <th class="py-3 pr-3 font-semibold">Room</th>
                        <th class="py-3 pr-3 font-semibold">Dates</th>
                        <th class="py-3 pr-3 font-semibold">Status</th>
                        <th class="py-3 pr-3 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reservations as $reservation)
                        <tr class="table-row align-top">
                            <td class="py-3 pr-3">
                                <p class="font-medium text-gray-900">{{ $reservation->guest_name }}</p>
                                <p class="text-xs text-gray-500">{{ $reservation->guest_email }}</p>
                                <p class="text-xs text-gray-400">{{ $reservation->guest_phone ?? 'No phone' }}</p>
                            </td>
                            <td class="py-3 pr-3 text-gray-800">
                                Room {{ $reservation->room->room_number }}<br>
                                <span class="text-xs text-gray-600">{{ $reservation->room->roomType->name }}</span>
                            </td>
                            <td class="py-3 pr-3 text-gray-700">
                                <span class="text-xs">In:</span>
                                @if(!empty($reservation->check_in_at))
                                    {{ $reservation->check_in_at->format('M d, Y h:i A') }}<br>
                                @else
                                    {{ $reservation->check_in->format('M d, Y') }}<br>
                                @endif
                                <span class="text-xs">Out:</span> {{ $reservation->check_out->format('M d, Y') }}
                            </td>
                            <td class="py-3 pr-3">
                                <span class="status-badge status-{{ str_replace('_', '-', $reservation->status) }}">
                                    {{ str_replace('_', ' ', $reservation->status) }}
                                </span>
                            </td>
                            <td class="py-3 pr-3">
                                <div class="flex flex-wrap gap-2">
                                    @if ($reservation->status === 'confirmed' && $reservation->payment_status === 'paid')
                                        <form action="{{ route('staff.checkin', $reservation) }}" method="POST" class="inline" data-confirm-action="Confirm check-in for {{ $reservation->guest_name }} in Room {{ $reservation->room->room_number }}?" data-confirm-title="Confirm Check-in" data-confirm-button="Yes, Check-in" data-loader-message="Checking in guest...">
                                            @csrf
                                            <button class="action-link btn-checkin">Check-in</button>
                                        </form>
                                    @elseif ($reservation->status === 'confirmed' && $reservation->payment_status !== 'paid')
                                        <span class="action-link" style="background: #fee2e2; color: #991b1b;">Awaiting Payment</span>
                                    @endif

                                    @if ($reservation->status === 'checked_in')
                                        <form action="{{ route('staff.checkout', $reservation) }}" method="POST" class="inline" data-confirm-action="Confirm check-out for {{ $reservation->guest_name }} in Room {{ $reservation->room->room_number }}?" data-confirm-title="Confirm Check-out" data-confirm-button="Yes, Check-out" data-loader-message="Checking out guest...">
                                            @csrf
                                            <button class="action-link btn-checkout">Check-out</button>
                                        </form>
                                    @endif

                                    <a href="{{ route('staff.reservation-detail', $reservation) }}" class="action-link" style="background: #e5e7eb; color: #374151;">View</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-5 text-center text-gray-400">No bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-5 text-gray-600">
            {{ $reservations->links() }}
        </div>
    </div>
@endsection
