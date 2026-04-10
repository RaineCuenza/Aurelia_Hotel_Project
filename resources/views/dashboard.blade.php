@extends('layouts.app')

@section('content')
    <style>
        .admin-dash-shell {
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
            transition: all 0.3s ease;
        }

        .glass-panel:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .dash-kpi-label {
            color: #8b7355;
            font-size: 0.75rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            font-weight: 600;
        }

        .dash-kpi-value {
            color: #2c2c2c;
            font-family: 'JetBrains Mono', monospace;
            font-size: 2.2rem;
            line-height: 1;
            font-weight: 800;
            background: linear-gradient(135deg, #2c2c2c 0%, #b99a61 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .dash-kpi-sub {
            color: #9ca3af;
            font-size: 0.85rem;
            font-weight: 500;
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

        .quick-snapshot-item {
            background: #faf8f4;
            border: 1px solid rgba(185, 154, 97, 0.1);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .quick-snapshot-item:hover {
            background: #f5f3ef;
            border-color: rgba(185, 154, 97, 0.2);
        }
    </style>

    <div class="admin-dash-shell p-5 sm:p-7">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h1 class="section-title text-4xl font-bold text-gray-900">Admin Dashboard</h1>
                <p class="mt-2 text-sm text-gray-600">Live room capacity, bookings, and revenue insights for Aurelia Hotel.</p>
            </div>
            <p class="rounded-full border border-amber-200 bg-amber-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.16em] text-amber-900">Aurelia Hotel Console</p>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="glass-panel p-5">
                <p class="dash-kpi-label">Total Rooms</p>
                <p class="dash-kpi-value mt-3">{{ $metrics['total_rooms'] }}</p>
                <p class="dash-kpi-sub mt-2">Inventory across all room types</p>
            </div>

            <div class="glass-panel p-5">
                <p class="dash-kpi-label">Available Rooms</p>
                <p class="dash-kpi-value mt-3">{{ $metrics['available_rooms'] }}</p>
                <p class="dash-kpi-sub mt-2">Ready for new guest arrivals</p>
            </div>

            <div class="glass-panel p-5">
                <p class="dash-kpi-label">Active Bookings</p>
                <p class="dash-kpi-value mt-3">{{ $metrics['active_bookings'] }}</p>
                <p class="dash-kpi-sub mt-2">Pending, confirmed, and checked-in</p>
            </div>

            <div class="glass-panel p-5">
                <p class="dash-kpi-label">Total Revenue</p>
                <p class="dash-kpi-value mt-3">PHP {{ number_format((float) $metrics['total_revenue'], 2) }}</p>
                <p class="dash-kpi-sub mt-2">From confirmed and completed stays</p>
            </div>
        </div>

        <div class="mt-6 grid gap-5 xl:grid-cols-[1.35fr_1fr]">
            <div class="glass-panel p-5 sm:p-6">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="section-title text-3xl font-bold text-gray-900">Booking Statistics</h2>
                    <span class="text-xs uppercase tracking-[0.14em] text-gray-500 font-semibold">Last 7 Days</span>
                </div>
                <div class="mt-5 h-72">
                    <canvas id="bookingsChart" aria-label="Bookings per day chart"></canvas>
                </div>
            </div>

            <div class="glass-panel p-5 sm:p-6">
                <h2 class="section-title text-3xl font-bold text-gray-900">Quick Snapshot</h2>
                <p class="mt-2 text-sm text-gray-600">Operational status derived from your live reservation and room data.</p>

                <div class="mt-5 space-y-3 text-sm">
                    <div class="quick-snapshot-item flex items-center justify-between px-4 py-3">
                        <span class="text-gray-700 font-medium">Occupancy Pressure</span>
                        <span class="font-bold text-amber-900">{{ $metrics['total_rooms'] > 0 ? round(($metrics['active_bookings'] / $metrics['total_rooms']) * 100) : 0 }}%</span>
                    </div>
                    <div class="quick-snapshot-item flex items-center justify-between px-4 py-3">
                        <span class="text-gray-700 font-medium">Room Readiness</span>
                        <span class="font-bold text-amber-900">{{ $metrics['total_rooms'] > 0 ? round(($metrics['available_rooms'] / $metrics['total_rooms']) * 100) : 0 }}%</span>
                    </div>
                    <div class="quick-snapshot-item flex items-center justify-between px-4 py-3">
                        <span class="text-gray-700 font-medium">Revenue Stream</span>
                        <span class="font-bold text-amber-900">PHP {{ number_format((float) $metrics['total_revenue'], 0) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="glass-panel mt-6 p-5 sm:p-6">
            <h2 class="section-title text-3xl font-bold text-gray-900">Recent Reservations</h2>
            <p class="mt-1 text-sm text-gray-600">Latest guest bookings and status flow.</p>

            <div class="mt-4 overflow-x-auto">
                <table class="w-full min-w-full text-left text-sm">
                    <thead>
                        <tr class="table-head">
                            <th class="py-3 pr-3 font-semibold">Guest Name</th>
                            <th class="py-3 pr-3 font-semibold">Room #</th>
                            <th class="py-3 pr-3 font-semibold">Check-in</th>
                            <th class="py-3 pr-3 font-semibold">Check-out</th>
                            <th class="py-3 pr-3 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentReservations as $reservation)
                            <tr class="table-row">
                                <td class="py-3 pr-3 font-medium">{{ $reservation->guest_name }}</td>
                                <td class="py-3 pr-3">{{ $reservation->room?->room_number ?? 'N/A' }}</td>
                                <td class="py-3 pr-3">{{ $reservation->check_in->format('M d, Y') }}</td>
                                <td class="py-3 pr-3">{{ $reservation->check_out->format('M d, Y') }}</td>
                                <td class="py-3 pr-3">
                                    <span class="status-badge status-{{ $reservation->status }}">{{ str_replace('_', ' ', $reservation->status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-5 text-gray-400">No reservations yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartLabels = JSON.parse('{!! json_encode($bookingChartLabels, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}');
        const chartData = JSON.parse('{!! json_encode($bookingChartData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}');

        const chartCanvas = document.getElementById('bookingsChart');

        if (chartCanvas) {
            new Chart(chartCanvas, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Bookings',
                        data: chartData,
                        borderColor: '#b99a61',
                        backgroundColor: 'rgba(185, 154, 97, 0.08)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#b99a61',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#6b7280',
                                font: {
                                    size: 13,
                                    weight: '600'
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: '#9ca3af',
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                color: 'rgba(185, 154, 97, 0.08)'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                color: '#9ca3af',
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                color: 'rgba(185, 154, 97, 0.08)'
                            }
                        }
                    }
                }
            });
        }
    </script>
@endsection
