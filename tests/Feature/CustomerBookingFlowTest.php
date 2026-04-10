<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CustomerBookingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_login_redirects_to_home(): void
    {
        $user = User::create([
            'first_name' => 'Customer',
            'last_name' => 'User',
            'email' => 'customer.flow@example.com',
            'phone' => '09170000010',
            'role' => 'customer',
            'email_verified_at' => now(),
            'password' => 'secret123',
        ]);

        $response = $this->post(route('login.attempt'), [
            'email' => $user->email,
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('customer.home'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_search_allows_back_to_back_booking_windows(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-04-10'));

        $room = $this->createAvailableRoom('101');

        Reservation::create([
            'room_id' => $room->id,
            'guest_name' => 'Existing Guest',
            'guest_email' => 'existing@example.com',
            'guest_phone' => '09170000000',
            'check_in' => '2026-04-10',
            'check_out' => '2026-04-11',
            'guests' => 2,
            'status' => 'confirmed',
            'total_amount' => 1500,
        ]);

        $response = $this->get(route('customer.search', [
            'check_in' => '2026-04-11',
            'check_out' => '2026-04-12',
            'guests' => 2,
        ]));

        $response->assertOk();
        $response->assertViewHas('rooms', function ($rooms) {
            return $rooms->contains(function ($room) {
                return (string) $room->room_number === '101';
            });
        });
    }

    public function test_customer_booking_rejects_overlapping_dates(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-04-10'));

        $customer = User::create([
            'first_name' => 'Booking',
            'last_name' => 'Customer',
            'email' => 'booking.customer@example.com',
            'phone' => '09170000011',
            'role' => 'customer',
            'email_verified_at' => now(),
            'password' => 'secret123',
        ]);

        $room = $this->createAvailableRoom('201');

        Reservation::create([
            'room_id' => $room->id,
            'guest_name' => 'Current Guest',
            'guest_email' => 'current@example.com',
            'guest_phone' => '09170000001',
            'check_in' => '2026-04-10',
            'check_out' => '2026-04-11',
            'guests' => 2,
            'status' => 'confirmed',
            'total_amount' => 1500,
        ]);

        $response = $this->actingAs($customer)->post(route('customer.booking.store', $room), [
            'guest_name' => 'New Guest',
            'guest_email' => 'new@example.com',
            'guest_phone' => '09170000002',
            'check_in' => '2026-04-10',
            'check_out' => '2026-04-11',
            'guests' => 2,
            'special_requests' => null,
        ]);

        $response->assertSessionHasErrors('check_in');
        $this->assertEquals(1, Reservation::count());
    }

    public function test_customer_booking_allows_back_to_back_dates(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-04-10'));

        $customer = User::create([
            'first_name' => 'Booking',
            'last_name' => 'Customer 2',
            'email' => 'booking.customer2@example.com',
            'phone' => '09170000012',
            'role' => 'customer',
            'email_verified_at' => now(),
            'password' => 'secret123',
        ]);

        $room = $this->createAvailableRoom('202');

        Reservation::create([
            'room_id' => $room->id,
            'guest_name' => 'Current Guest',
            'guest_email' => 'current2@example.com',
            'guest_phone' => '09170000003',
            'check_in' => '2026-04-10',
            'check_out' => '2026-04-11',
            'guests' => 2,
            'status' => 'confirmed',
            'total_amount' => 1500,
        ]);

        // Explicitly keep the room open for a back-to-back booking window in this test.
        $room->update(['status' => 'available']);

        $response = $this->actingAs($customer)->post(route('customer.booking.store', $room), [
            'guest_name' => 'Back To Back Guest',
            'guest_email' => 'backtoback@example.com',
            'guest_phone' => '09170000004',
            'check_in' => '2026-04-11',
            'check_out' => '2026-04-12',
            'guests' => 2,
            'special_requests' => null,
        ]);

        $response->assertSessionHasNoErrors();
        $reservation = Reservation::where('guest_email', 'backtoback@example.com')->first();

        $this->assertNotNull($reservation);
        $response->assertRedirect(route('customer.booking.confirmation', $reservation));
        $this->assertEquals(2, Reservation::count());
        $this->assertSame('reserved', $room->fresh()->status);
    }

    public function test_reserved_room_does_not_appear_in_search_results(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-04-10'));

        $room = $this->createAvailableRoom('203');
        $room->update(['status' => 'reserved']);

        $response = $this->get(route('customer.search', [
            'check_in' => '2026-04-11',
            'check_out' => '2026-04-12',
            'guests' => 2,
        ]));

        $response->assertOk();
        $response->assertDontSee('Room 203');
    }

    private function createAvailableRoom(string $roomNumber): Room
    {
        $roomType = RoomType::create([
            'name' => 'Standard',
            'slug' => 'standard-'.$roomNumber,
            'description' => 'Standard room',
            'base_price' => 1500,
        ]);

        return Room::create([
            'room_type_id' => $roomType->id,
            'room_number' => $roomNumber,
            'max_guests' => 2,
            'status' => 'available',
        ]);
    }
}
