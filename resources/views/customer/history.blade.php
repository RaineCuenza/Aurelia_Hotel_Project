    @extends('layouts.app')

@section('content')
    <style>
        .history-shell {
            border-radius: 18px;
            border: 1px solid rgba(185, 154, 97, 0.16);
            background: linear-gradient(135deg, #faf8f3 0%, #f4f0e7 100%);
            box-shadow: 0 14px 32px rgba(31, 24, 16, 0.07);
            padding: 1.2rem;
        }

        .table-card {
            border-radius: 14px;
            border: 1px solid rgba(185, 154, 97, 0.14);
            background: #fff;
            box-shadow: 0 8px 20px rgba(33, 26, 16, 0.05);
            padding: 0.8rem;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            border-radius: 9999px;
            padding: 0.28rem 0.72rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-confirmed { background: #dcfce7; color: #166534; }
        .status-checked_in { background: #cffafe; color: #155e75; }
        .status-checked_out { background: #e9d5ff; color: #6b21a8; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        .status-pending { background: #fef3c7; color: #92400e; }

        .cancel-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }

        .cancel-card {
            border-radius: 14px;
            border: 1px solid rgba(153, 27, 27, 0.14);
            background: #fff5f5;
            box-shadow: 0 8px 20px rgba(33, 26, 16, 0.05);
            padding: 1rem;
        }
    </style>

    <div class="history-shell">
        <h1 class="section-title text-4xl font-bold text-stone-900">My Bookings</h1>

        <section class="mt-6">
            <h2 class="section-title text-3xl font-semibold text-stone-900">Active Stay</h2>
            <div class="table-card mt-3 overflow-x-auto">
                <table class="w-full min-w-[720px] text-left text-sm">
                    <thead>
                        <tr class="text-stone-500">
                            <th class="py-3 pr-3">Reference</th>
                            <th class="py-3 pr-3">Room</th>
                            <th class="py-3 pr-3">Dates</th>
                            <th class="py-3 pr-3">Total</th>
                            <th class="py-3 pr-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($activeStay as $reservation)
                            <tr class="border-t border-stone-200 text-stone-800">
                                <td class="py-3 pr-3 font-medium">AUR-{{ $reservation->created_at->format('Ymd') }}-{{ str_pad((string) $reservation->id, 6, '0', STR_PAD_LEFT) }}</td>
                                <td class="py-3 pr-3">{{ $reservation->room->roomType->name }} ({{ $reservation->room->room_number }})</td>
                                <td class="py-3 pr-3">{{ $reservation->check_in->format('M d, Y') }} - {{ $reservation->check_out->format('M d, Y') }}</td>
                                <td class="py-3 pr-3 font-semibold">PHP {{ number_format((float) $reservation->total_amount, 2) }}</td>
                                <td class="py-3 pr-3"><span class="status-badge status-{{ $reservation->status }}">{{ str_replace('_', ' ', $reservation->status) }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-stone-500">You have no active stay right now.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="mt-7">
            <h2 class="section-title text-3xl font-semibold text-stone-900">Upcoming Bookings</h2>
            <div class="table-card mt-3 overflow-x-auto">
                <table class="w-full min-w-[720px] text-left text-sm">
                    <thead>
                        <tr class="text-stone-500">
                            <th class="py-3 pr-3">Reference</th>
                            <th class="py-3 pr-3">Room</th>
                            <th class="py-3 pr-3">Dates</th>
                            <th class="py-3 pr-3">Total</th>
                            <th class="py-3 pr-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($upcoming as $reservation)
                            <tr class="border-t border-stone-200 text-stone-800">
                                <td class="py-3 pr-3 font-medium">AUR-{{ $reservation->created_at->format('Ymd') }}-{{ str_pad((string) $reservation->id, 6, '0', STR_PAD_LEFT) }}</td>
                                <td class="py-3 pr-3">{{ $reservation->room->roomType->name }} ({{ $reservation->room->room_number }})</td>
                                <td class="py-3 pr-3">{{ $reservation->check_in->format('M d, Y') }} - {{ $reservation->check_out->format('M d, Y') }}</td>
                                <td class="py-3 pr-3 font-semibold">PHP {{ number_format((float) $reservation->total_amount, 2) }}</td>
                                <td class="py-3 pr-3"><span class="status-badge status-{{ $reservation->status }}">{{ str_replace('_', ' ', $reservation->status) }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-stone-500">No upcoming bookings yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="mt-7">
            <h2 class="section-title text-3xl font-semibold text-stone-900">Past Stays</h2>
            <div class="table-card mt-3 overflow-x-auto">
                <table class="w-full min-w-[720px] text-left text-sm">
                    <thead>
                        <tr class="text-stone-500">
                            <th class="py-3 pr-3">Reference</th>
                            <th class="py-3 pr-3">Room</th>
                            <th class="py-3 pr-3">Dates</th>
                            <th class="py-3 pr-3">Total</th>
                            <th class="py-3 pr-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($past as $reservation)
                            <tr class="border-t border-stone-200 text-stone-800">
                                <td class="py-3 pr-3 font-medium">AUR-{{ $reservation->created_at->format('Ymd') }}-{{ str_pad((string) $reservation->id, 6, '0', STR_PAD_LEFT) }}</td>
                                <td class="py-3 pr-3">{{ $reservation->room->roomType->name }} ({{ $reservation->room->room_number }})</td>
                                <td class="py-3 pr-3">{{ $reservation->check_in->format('M d, Y') }} - {{ $reservation->check_out->format('M d, Y') }}</td>
                                <td class="py-3 pr-3 font-semibold">PHP {{ number_format((float) $reservation->total_amount, 2) }}</td>
                                <td class="py-3 pr-3"><span class="status-badge status-{{ $reservation->status }}">{{ str_replace('_', ' ', $reservation->status) }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-stone-500">No past stays yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="mt-7">
            <h2 class="section-title text-3xl font-semibold text-stone-900">Cancelled Bookings</h2>
            <div class="mt-3 space-y-3">
                @forelse ($cancelled as $reservation)
                    <div class="cancel-card">
                        <div class="cancel-grid">
                            <div>
                                <p class="text-xs uppercase tracking-[0.16em] text-stone-500">Reference</p>
                                <p class="mt-1 font-semibold text-stone-900">AUR-{{ $reservation->created_at->format('Ymd') }}-{{ str_pad((string) $reservation->id, 6, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.16em] text-stone-500">Room</p>
                                <p class="mt-1 font-semibold text-stone-900">{{ $reservation->room->roomType->name }} ({{ $reservation->room->room_number }})</p>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.16em] text-stone-500">Dates</p>
                                <p class="mt-1 font-semibold text-stone-900">{{ $reservation->check_in->format('M d, Y') }} - {{ $reservation->check_out->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.16em] text-stone-500">Cancelled On</p>
                                <p class="mt-1 font-semibold text-stone-900">{{ optional($reservation->cancelled_at)->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        <div class="mt-4 rounded-xl border border-rose-200 bg-white p-4 text-sm text-rose-900">
                            <p class="font-semibold">Cancellation Reason</p>
                            <p class="mt-1">{{ $reservation->cancellation_reason }}</p>
                            @if ($reservation->cancelledBy)
                                <p class="mt-2 text-xs text-rose-700">Cancelled by {{ $reservation->cancelledBy->name }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="table-card text-stone-500">You have no cancelled bookings.</div>
                @endforelse
            </div>
        </section>
    </div>
@endsection
