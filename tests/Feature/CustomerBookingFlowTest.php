<?php

namespace Tests\Feature;

use App\Models\Booking;
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

        Booking::create([
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

        Booking::create([
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
        $this->assertEquals(1, Booking::count());
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

        Booking::create([
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
        $booking = Booking::where('guest_email', 'backtoback@example.com')->first();

        $this->assertNotNull($booking);
        $response->assertRedirect(route('customer.booking.payment', $booking));
        $this->assertSame('pending', $booking->status);
        $this->assertSame('pending', $booking->payment_status);
        $this->assertEquals(2, Booking::count());
        $this->assertSame('booked', $room->fresh()->status);
    }

    public function test_booked_room_does_not_appear_in_search_results(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-04-10'));

        $room = $this->createAvailableRoom('203');
        $room->update(['status' => 'booked']);

        $response = $this->get(route('customer.search', [
            'check_in' => '2026-04-11',
            'check_out' => '2026-04-12',
            'guests' => 2,
        ]));

        $response->assertOk();
        $response->assertDontSee('Room 203');
    }

    public function test_home_featured_room_types_show_remaining_available_rooms(): void
    {
        $roomType = RoomType::create([
            'name' => 'Standard',
            'slug' => 'standard-featured',
            'description' => 'Standard room',
            'base_price' => 1500,
        ]);

        Room::create([
            'room_type_id' => $roomType->id,
            'room_number' => '301',
            'max_guests' => 2,
            'status' => 'available',
        ]);

        Room::create([
            'room_type_id' => $roomType->id,
            'room_number' => '302',
            'max_guests' => 2,
            'status' => 'booked',
        ]);

        $response = $this->get(route('customer.home'));

        $response->assertOk();
        $response->assertSee('1 room left');
    }

    public function test_mock_payment_success_confirms_booking(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-04-10 10:00:00'));

        $customer = User::create([
            'first_name' => 'Paying',
            'last_name' => 'Customer',
            'email' => 'paying.customer@example.com',
            'phone' => '09170000015',
            'role' => 'customer',
            'email_verified_at' => now(),
            'password' => 'secret123',
        ]);

        $room = $this->createAvailableRoom('205');

        $bookingResponse = $this->actingAs($customer)->post(route('customer.booking.store', $room), [
            'guest_name' => 'Paying Customer',
            'guest_email' => 'paying.customer@example.com',
            'guest_phone' => '09170000015',
            'check_in' => '2026-04-12',
            'check_out' => '2026-04-13',
            'guests' => 2,
            'special_requests' => null,
        ]);

        $booking = Booking::where('guest_email', 'paying.customer@example.com')->first();

        $this->assertNotNull($booking);
        $bookingResponse->assertRedirect(route('customer.booking.payment', $booking));

        $paymentResponse = $this->actingAs($customer)->post(route('customer.booking.payment.process', $booking), [
            'outcome' => 'success',
        ]);

        $paymentResponse->assertRedirect(route('customer.booking.confirmation', $booking));
        $this->assertSame('confirmed', $booking->fresh()->status);
        $this->assertSame('paid', $booking->fresh()->payment_status);
        $this->assertSame('booked', $room->fresh()->status);
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
