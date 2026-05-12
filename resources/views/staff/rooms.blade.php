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

        .status-booked {
            background: #fef3c7;
            color: #92400e;
        }

        .status-cleaning {
            background: #fed7aa;
            color: #92400e;
        }

        .stat-card {
            padding: 1rem;
        }

        .stat-label {
            color: #8b7355;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .stat-value {
            margin-top: 0.35rem;
            font-size: 1.9rem;
            font-weight: 800;
            font-family: 'JetBrains Mono', monospace;
        }

        .value-available {
            color: #15803d;
        }

        .value-occupied {
            color: #0c4a6e;
        }

        .value-booked {
            color: #92400e;
        }

        .value-cleaning {
            color: #9a3412;
        }

        .value-total {
            color: #2c2c2c;
        }

        .btn-clean {
            margin-top: 0.75rem;
            width: 100%;
            border: 1px solid rgba(21, 128, 61, 0.35);
            background: #dcfce7;
            color: #166534;
            border-radius: 8px;
            padding: 0.45rem 0.7rem;
            font-size: 0.78rem;
            font-weight: 700;
            transition: transform 0.16s ease, box-shadow 0.16s ease;
        }

        .btn-clean:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(22, 101, 52, 0.16);
        }
    </style>

    <div class="staff-shell p-5 sm:p-7">
        <!-- Header -->
        <div>
            <h1 class="section-title text-4xl font-bold text-gray-900">Room Status</h1>
            <p class="mt-2 text-sm text-gray-600">View all rooms and their current status.</p>
        </div>

        <!-- Stats -->
        <div class="grid gap-4 md:grid-cols-5 mt-6 mb-6">
            <div class="glass-panel stat-card">
                <p class="stat-label">Available</p>
                <p class="stat-value value-available">{{ $stats['available'] }}</p>
            </div>

            <div class="glass-panel stat-card">
                <p class="stat-label">Occupied</p>
                <p class="stat-value value-occupied">{{ $stats['occupied'] }}</p>
            </div>

            <div class="glass-panel stat-card">
                <p class="stat-label">Booked</p>
                <p class="stat-value value-booked">{{ $stats['booked'] }}</p>
            </div>

            <div class="glass-panel stat-card">
                <p class="stat-label">Cleaning</p>
                <p class="stat-value value-cleaning">{{ $stats['cleaning'] }}</p>
            </div>

            <div class="glass-panel stat-card">
                <p class="stat-label">Total</p>
                <p class="stat-value value-total">{{ $stats['total'] }}</p>
            </div>
        </div>

        <!-- Rooms Grid -->
        <div class="glass-panel p-5 sm:p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">All Rooms</h2>

            <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @forelse ($rooms as $room)
                    <div class="room-card {{ $room->status === 'available' ? 'bg-green-50 border-green-200' : ($room->status === 'booked' ? 'bg-amber-50 border-amber-200' : ($room->status === 'occupied' ? 'bg-blue-50 border-blue-200' : 'bg-orange-50 border-orange-200')) }}">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="room-number">{{ $room->room_number }}</p>
                                <p class="room-type">{{ $room->roomType->name }}</p>
                            </div>
                            <span class="room-status {{ $room->status === 'available' ? 'status-available' : ($room->status === 'booked' ? 'status-booked' : ($room->status === 'occupied' ? 'status-occupied' : 'status-cleaning')) }}">
                                {{ ucfirst($room->status) }}
                            </span>
                        </div>

                        @if (in_array($room->status, ['booked', 'occupied'], true) && $room->bookings->count() > 0)
                            <div class="mt-3 pt-3 border-t border-current border-opacity-20">
                                <p class="text-xs text-gray-600 font-semibold">Current Booking:</p>
                                <p class="text-sm font-medium text-gray-900">{{ $room->bookings->first()->guest_name }}</p>
                            </div>
                        @endif

                        @if ($room->status === 'cleaning')
                            <form action="{{ route('staff.rooms.mark-clean', $room) }}" method="POST" class="mt-2" data-confirm-action="Mark Room {{ $room->room_number }} as clean and available?" data-confirm-title="Complete Cleaning" data-confirm-button="Yes, Mark Clean" data-loader-message="Updating room status...">
                                @csrf
                                <button type="submit" class="btn-clean">Mark as Clean</button>
                            </form>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-400 col-span-full text-center py-8">No rooms available.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
