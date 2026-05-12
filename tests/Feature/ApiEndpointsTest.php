<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_rooms_index_returns_paginated_json(): void
    {
        $roomType = RoomType::create([
            'name' => 'Standard',
            'slug' => 'standard',
            'description' => 'Standard room',
            'base_price' => 1500,
        ]);

        Room::create([
            'room_type_id' => $roomType->id,
            'room_number' => '101',
            'max_guests' => 2,
            'status' => 'available',
        ]);

        $response = $this->getJson('/api/rooms?per_page=5');

        $response->assertOk()
            ->assertJsonStructure([
                'data',
                'current_page',
                'last_page',
                'per_page',
                'total',
            ])
            ->assertJsonPath('data.0.room_number', '101');
    }

    public function test_can_create_booking_via_api(): void
    {
        $room = $this->createRoom();

        $response = $this->postJson('/api/bookings', [
            'room_id' => $room->id,
            'guest_name' => 'API Guest',
            'guest_email' => 'api.guest@example.com',
            'check_in' => now()->addDay()->toDateString(),
            'check_out' => now()->addDays(3)->toDateString(),
            'guests' => 2,
            'special_requests' => 'Late check-in',
        ]);

        $response->assertCreated()
            ->assertJsonPath('message', 'Booking created successfully.')
            ->assertJsonPath('data.guest_name', 'API Guest')
            ->assertJsonPath('data.total_amount', '3000.00');

        $this->assertDatabaseHas('bookings', [
            'guest_name' => 'API Guest',
            'room_id' => $room->id,
            'status' => 'pending',
        ]);
    }

    public function test_can_update_and_delete_booking_via_api(): void
    {
        $room = $this->createRoom();

        $booking = Booking::create([
            'room_id' => $room->id,
            'guest_name' => 'Update Me',
            'guest_email' => 'update.me@example.com',
            'check_in' => now()->addDay()->toDateString(),
            'check_out' => now()->addDays(2)->toDateString(),
            'guests' => 1,
            'special_requests' => null,
            'status' => 'pending',
            'total_amount' => 1500,
        ]);

        $updateResponse = $this->putJson('/api/bookings/'.$booking->id, [
            'room_id' => $room->id,
            'guest_name' => 'Updated Guest',
            'guest_email' => 'updated@example.com',
            'check_in' => now()->addDays(2)->toDateString(),
            'check_out' => now()->addDays(4)->toDateString(),
            'guests' => 2,
            'special_requests' => 'Top floor',
            'status' => 'confirmed',
        ]);

        $updateResponse->assertOk()
            ->assertJsonPath('message', 'Booking updated successfully.')
            ->assertJsonPath('data.guest_name', 'Updated Guest')
            ->assertJsonPath('data.status', 'confirmed');

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'guest_name' => 'Updated Guest',
            'status' => 'confirmed',
        ]);

        $deleteResponse = $this->deleteJson('/api/bookings/'.$booking->id);

        $deleteResponse->assertOk()
            ->assertJsonPath('message', 'Booking deleted successfully.');

        $this->assertDatabaseMissing('bookings', [
            'id' => $booking->id,
        ]);
    }

    private function createRoom(): Room
    {
        $roomType = RoomType::create([
            'name' => 'Deluxe',
            'slug' => 'deluxe',
            'description' => 'Deluxe room',
            'base_price' => 1500,
        ]);

        return Room::create([
            'room_type_id' => $roomType->id,
            'room_number' => '202',
            'max_guests' => 3,
            'status' => 'available',
        ]);
    }
}
