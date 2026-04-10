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

        .room-card {
            padding: 1rem;
            border-radius: 12px;
            border: 1px solid rgba(185, 154, 97, 0.1);
            transition: all 0.2s ease;
        }

        .room-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .room-number {
            font-size: 1.25rem;
            font-weight: 700;
            color: #2c2c2c;
        }

        .room-type {
            font-size: 0.85rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        .room-status {
            font-size: 0.9rem;
            font-weight: 600;
            margin-top: 0.5rem;
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
        }

        .status-available {
            background: #dcfce7;
            color: #15803d;
        }

        .status-occupied {
            background: #cffafe;
            color: #0c4a6e;
        }

        .status-cleaning {
            background: #fed7aa;
            color: #92400e;
        }

        .stat-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-content p:first-child {
            color: #6b7280;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-content p:last-child {
            color: #2c2c2c;
            font-size: 1.75rem;
            font-weight: 800;
            font-family: 'JetBrains Mono', monospace;
            margin-top: 0.25rem;
        }

        .icon-available {
            background: #dcfce7;
            color: #15803d;
        }

        .icon-occupied {
            background: #cffafe;
            color: #0c4a6e;
        }

        .icon-cleaning {
            background: #fed7aa;
            color: #92400e;
        }
    </style>

    <div class="staff-shell p-5 sm:p-7">
        <!-- Header -->
        <div>
            <h1 class="section-title text-4xl font-bold text-gray-900">Room Status</h1>
            <p class="mt-2 text-sm text-gray-600">View all rooms and their current status.</p>
        </div>

        <!-- Stats -->
        <div class="grid gap-4 md:grid-cols-4 mt-6 mb-6">
            <div class="glass-panel stat-card">
                <div class="stat-icon icon-available">✓</div>
                <div class="stat-content">
                    <p>Available</p>
                    <p>{{ $stats['available'] }}</p>
                </div>
            </div>

            <div class="glass-panel stat-card">
                <div class="stat-icon icon-occupied">🔒</div>
                <div class="stat-content">
                    <p>Occupied</p>
                    <p>{{ $stats['occupied'] }}</p>
                </div>
            </div>

            <div class="glass-panel stat-card">
                <div class="stat-icon icon-cleaning">🧹</div>
                <div class="stat-content">
                    <p>Cleaning</p>
                    <p>{{ $stats['cleaning'] }}</p>
                </div>
            </div>

            <div class="glass-panel stat-card">
                <div style="width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: rgba(185, 154, 97, 0.1); color: #b99a61; font-size: 1.5rem;">🔑</div>
                <div class="stat-content">
                    <p>Total</p>
                    <p>{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <!-- Rooms Grid -->
        <div class="glass-panel p-5 sm:p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">All Rooms</h2>

            <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @forelse ($rooms as $room)
                    <div class="room-card {{ $room->status === 'available' ? 'bg-green-50 border-green-200' : ($room->status === 'occupied' ? 'bg-blue-50 border-blue-200' : 'bg-orange-50 border-orange-200') }}">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="room-number">{{ $room->room_number }}</p>
                                <p class="room-type">{{ $room->roomType->name }}</p>
                            </div>
                            <span class="room-status {{ $room->status === 'available' ? 'status-available' : ($room->status === 'occupied' ? 'status-occupied' : 'status-cleaning') }}">
                                {{ ucfirst($room->status) }}
                            </span>
                        </div>

                        @if ($room->status === 'occupied' && $room->reservations->count() > 0)
                            <div class="mt-3 pt-3 border-t border-current border-opacity-20">
                                <p class="text-xs text-gray-600 font-semibold">Current Guest:</p>
                                <p class="text-sm font-medium text-gray-900">{{ $room->reservations->first()->guest_name }}</p>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-400 col-span-full text-center py-8">No rooms available.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
