<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaffCleaningWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_moves_room_to_cleaning(): void
    {
        [$staff, $room, $reservation] = $this->makeCheckedInReservation();

        $response = $this->actingAs($staff)->post(route('staff.checkout', $reservation));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertSame('checked_out', $reservation->fresh()->status);
        $this->assertSame('cleaning', $room->fresh()->status);
    }

    public function test_staff_can_mark_cleaning_room_as_available(): void
    {
        $staff = User::factory()->create([
            'role' => 'staff',
        ]);

        $roomType = RoomType::create([
            'name' => 'Deluxe',
            'slug' => 'deluxe-clean',
            'description' => 'Deluxe room',
            'base_price' => 2200,
        ]);

        $room = Room::create([
            'room_type_id' => $roomType->id,
            'room_number' => '302',
            'max_guests' => 2,
            'status' => 'cleaning',
        ]);

        $response = $this->actingAs($staff)->post(route('staff.rooms.mark-clean', $room));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertSame('available', $room->fresh()->status);
    }

    public function test_customer_cannot_mark_room_clean(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        $roomType = RoomType::create([
            'name' => 'Standard',
            'slug' => 'standard-clean',
            'description' => 'Standard room',
            'base_price' => 1500,
        ]);

        $room = Room::create([
            'room_type_id' => $roomType->id,
            'room_number' => '303',
            'max_guests' => 2,
            'status' => 'cleaning',
        ]);

        $response = $this->actingAs($customer)->post(route('staff.rooms.mark-clean', $room));

        $response->assertStatus(302);
        $this->assertSame('cleaning', $room->fresh()->status);
    }

    /**
     * @return array{0: User, 1: Room, 2: Reservation}
     */
    private function makeCheckedInReservation(): array
    {
        $staff = User::factory()->create([
            'role' => 'staff',
        ]);

        $roomType = RoomType::create([
            'name' => 'Suite',
            'slug' => 'suite-checkout',
            'description' => 'Suite room',
            'base_price' => 3000,
        ]);

        $room = Room::create([
            'room_type_id' => $roomType->id,
            'room_number' => '301',
            'max_guests' => 2,
            'status' => 'occupied',
        ]);

        $reservation = Reservation::create([
            'room_id' => $room->id,
            'guest_name' => 'Checked In Guest',
            'guest_email' => 'checkedin@example.com',
            'guest_phone' => '09170000123',
            'check_in' => now()->subDay()->toDateString(),
            'check_out' => now()->toDateString(),
            'guests' => 2,
            'status' => 'checked_in',
            'total_amount' => 3000,
        ]);

        return [$staff, $room, $reservation];
    }
}
