<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CheckoutTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or get a test customer user
        $testCustomer = User::firstOrCreate(
            ['email' => 'customer@stayease.local'],
            [
                'first_name' => 'Customer',
                'last_name' => 'User',
                'role' => 'customer',
                'password' => Hash::make('123'),
            ]
        );

        // Get an available room from the database
        $room = Room::with('roomType')
            ->where('status', 'available')
            ->first();

        // Fallback if no available room exists
        if (!$room) {
            $room = Room::with('roomType')->first();
        }

        // Only create reservation if we have a room
        if ($room && $room->roomType) {
            // Define check-in as yesterday and check-out as today
            $checkIn = Carbon::yesterday();
            $checkOut = Carbon::today();
            $nights = max(1, $checkIn->diffInDays($checkOut));

            // Calculate total amount based on room type base price
            $totalAmount = $room->roomType->base_price * $nights;

            // Create the reservation with checked_in status so it shows in checkout queue
            Reservation::firstOrCreate(
                [
                    'user_id' => $testCustomer->id,
                    'room_id' => $room->id,
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                ],
                [
                    'guest_name' => $testCustomer->first_name . ' ' . $testCustomer->last_name,
                    'guest_email' => $testCustomer->email,
                    'guest_phone' => '+63 912 345 6789',
                    'guests' => 1,
                    'special_requests' => 'Test reservation for checkout testing.',
                    'status' => 'checked_in',
                    'total_amount' => $totalAmount,
                ]
            );

            // Update room status to occupied since guest is checked in
            $room->update(['status' => 'occupied']);

            $this->command->info("✓ Test checkout reservation created for {$testCustomer->first_name} in Room {$room->room_number} ({$room->roomType->name})");
            $this->command->info("  Check-in: {$checkIn->format('M d, Y')}, Check-out: {$checkOut->format('M d, Y')}");
            $this->command->info("  Status: checked_in (ready for checkout testing)");
            $this->command->info("  Total: PHP {$totalAmount}");

        } else {
            $this->command->warn('⚠ No rooms found in database. Please seed rooms first.');
        }
    }
}
