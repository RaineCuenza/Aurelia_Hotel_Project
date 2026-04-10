@extends('layouts.app')

@section('content')
    <style>
        .staff-shell {
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

        .stat-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem;
        }

        .stat-label {
            color: #8b7355;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .stat-value {
            color: #2c2c2c;
            font-size: 2rem;
            font-weight: 800;
            font-family: 'JetBrains Mono', monospace;
        }

        .stat-color-available {
            color: #15803d;
        }

        .stat-color-occupied {
            color: #0c4a6e;
        }

        .stat-color-reserved {
            color: #92400e;
        }

        .stat-color-cleaning {
            color: #7c2d12;
        }

        .reservation-item {
            border-bottom: 1px solid rgba(185, 154, 97, 0.1);
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .reservation-item:last-child {
            border-bottom: none;
        }

        .reservation-item:hover {
            background-color: rgba(245, 243, 239, 0.5);
        }

        .guest-info {
            flex: 1;
        }

        .guest-name {
            color: #2c2c2c;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .guest-room {
            color: #6b7280;
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }

        .action-btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.8rem;
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

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .section-title {
            color: #2c2c2c;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .status-badge-small {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-confirmed {
            background: #dcfce7;
            color: #15803d;
        }

        .status-checked-in {
            background: #cffafe;
            color: #0c4a6e;
        }
    </style>

    <div class="staff-shell p-5 sm:p-7">
        <!-- Header -->
        <div class="flex flex-wrap items-end justify-between gap-3 mb-6">
            <div>
                <h1 class="section-title text-4xl font-bold text-gray-900">Staff Dashboard</h1>
                <p class="mt-2 text-sm text-gray-600">Today's operations and room status at a glance.</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">{{ Carbon\Carbon::now()->format('l, F j, Y') }}</p>
            </div>
        </div>

        <!-- Room Status Stats -->
        <div class="grid gap-3 md:grid-cols-4 mb-6">
            <div class="glass-panel stat-card">
                <div>
                    <p class="stat-label">Total Rooms</p>
                    <p class="stat-value mt-2">{{ $totalRooms }}</p>
                </div>
            </div>

            <div class="glass-panel stat-card">
                <div>
                    <p class="stat-label">Available</p>
                    <p class="stat-value mt-2 stat-color-available">{{ $availableRooms }}</p>
                </div>
            </div>

            <div class="glass-panel stat-card">
                <div>
                    <p class="stat-label">Reserved</p>
                    <p class="stat-value mt-2 stat-color-reserved">{{ $reservedRooms }}</p>
                </div>
            </div>

            <div class="glass-panel stat-card">
                <div>
                    <p class="stat-label">Occupied</p>
                    <p class="stat-value mt-2 stat-color-occupied">{{ $occupiedRooms }}</p>
                </div>
            </div>

            <div class="glass-panel stat-card">
                <div>
                    <p class="stat-label">Cleaning</p>
                    <p class="stat-value mt-2 stat-color-cleaning">{{ $cleaningRooms }}</p>
                </div>
            </div>
        </div>

        <!-- Today's Operations -->
        <div class="grid gap-6 xl:grid-cols-2 mb-6">
            <!-- Today's Check-ins -->
            <div class="glass-panel p-5 sm:p-6">
                <div class="section-header">
                    <h2 class="section-title">Today's Check-Ins</h2>
                    <span class="text-sm text-gray-500 font-semibold">{{ $todayCheckIns->count() }}</span>
                </div>

                <div class="space-y-1">
                    @forelse ($todayCheckIns as $reservation)
                        <div class="reservation-item">
                            <div class="guest-info">
                                <p class="guest-name">{{ $reservation->guest_name }}</p>
                                <p class="guest-room">Room {{ $reservation->room->room_number }} • {{ $reservation->room->roomType->name }}</p>
                            </div>
                            @if ($reservation->status === 'confirmed')
                                <form action="{{ route('staff.checkin', $reservation) }}" method="POST" class="ml-2">
                                    @csrf
                                    <button type="submit" class="action-btn btn-checkin">Check-in</button>
                                </form>
                            @else
                                <span class="status-badge-small status-checked-in">Checked In</span>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-400 text-center py-4">No check-ins today</p>
                    @endforelse
                </div>
            </div>

            <!-- Today's Check-outs -->
            <div class="glass-panel p-5 sm:p-6">
                <div class="section-header">
                    <h2 class="section-title">Today's Check-Outs</h2>
                    <span class="text-sm text-gray-500 font-semibold">{{ $todayCheckOuts->count() }}</span>
                </div>

                <div class="space-y-1">
                    @forelse ($todayCheckOuts as $reservation)
                        <div class="reservation-item">
                            <div class="guest-info">
                                <p class="guest-name">{{ $reservation->guest_name }}</p>
                                <p class="guest-room">Room {{ $reservation->room->room_number }} • {{ $reservation->room->roomType->name }}</p>
                            </div>
                            <form action="{{ route('staff.checkout', $reservation) }}" method="POST" class="ml-2">
                                @csrf
                                <button type="submit" class="action-btn btn-checkout">Check-out</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-gray-400 text-center py-4">No check-outs scheduled</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Room Status -->
        <div class="glass-panel p-5 sm:p-6">
            <h2 class="section-title mb-4">Room Status Overview</h2>

            <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($roomStatus as $room)
                    <div class="border rounded-lg p-3 {{ $room->status === 'available' ? 'bg-green-50 border-green-300' : ($room->status === 'occupied' ? 'bg-blue-50 border-blue-300' : 'bg-orange-50 border-orange-300') }}">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-gray-900">Room {{ $room->room_number }}</p>
                                <p class="text-xs text-gray-600">{{ $room->roomType->name }}</p>
                            </div>
                            <span class="font-semibold {{ $room->status === 'available' ? 'text-green-700' : ($room->status === 'occupied' ? 'text-blue-700' : 'text-orange-700') }}">
                                {{ ucfirst($room->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400">No rooms available</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
