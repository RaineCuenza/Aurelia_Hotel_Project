<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_for_protected_pages(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_create_reservation(): void
    {
        $user = User::factory()->create();

        $roomType = RoomType::create([
            'name' => 'Standard',
            'slug' => 'standard',
            'description' => 'Standard room',
            'base_price' => 1500,
        ]);

        $room = Room::create([
            'room_type_id' => $roomType->id,
            'room_number' => '101',
            'max_guests' => 2,
            'status' => 'available',
        ]);

        $response = $this->actingAs($user)->post(route('reservations.store'), [
            'room_id' => $room->id,
            'guest_name' => 'Lorraine Cuenza',
            'guest_email' => 'lorraine@example.com',
            'check_in' => now()->addDay()->toDateString(),
            'check_out' => now()->addDays(3)->toDateString(),
            'guests' => 2,
            'special_requests' => 'Near window',
        ]);

        $response->assertRedirect(route('reservations.index'));

        $this->assertDatabaseHas('reservations', [
            'guest_name' => 'Lorraine Cuenza',
            'guest_email' => 'lorraine@example.com',
            'room_id' => $room->id,
            'status' => 'confirmed',
        ]);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);
        $this->assertEquals('3000.00', $reservation->total_amount);
    }
}
