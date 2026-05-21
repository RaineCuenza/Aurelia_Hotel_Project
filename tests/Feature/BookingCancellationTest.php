<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingCancellationTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_cancel_booking_with_reason_and_room_status(): void
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'email_verified_at' => now(),
        ]);

        $customer = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        $room = $this->createRoom('401');

        $booking = Booking::create([
            'user_id' => $customer->id,
            'room_id' => $room->id,
            'guest_name' => 'Cancellation Guest',
            'guest_email' => $customer->email,
            'guest_phone' => '09170000401',
            'check_in' => now()->addDay()->toDateString(),
            'check_out' => now()->addDays(3)->toDateString(),
            'guests' => 2,
            'status' => 'confirmed',
            'total_amount' => 4500,
            'payment_status' => 'paid',
        ]);

        $response = $this->actingAs($staff)->patch(route('bookings.cancel', $booking), [
            'cancellation_reason' => 'Room 401 requires urgent maintenance because of a plumbing leak.',
            'room_status' => 'maintenance',
            'notify_customer' => 1,
        ]);

        $response->assertRedirect(route('bookings.index'));
        $response->assertSessionHas('success');

        $booking->refresh();

        $this->assertSame('cancelled', $booking->status);
        $this->assertSame('Room 401 requires urgent maintenance because of a plumbing leak.', $booking->cancellation_reason);
        $this->assertSame($staff->id, $booking->cancelled_by);
        $this->assertNotNull($booking->cancelled_at);
        $this->assertSame('maintenance', $room->fresh()->status);
    }

    public function test_staff_cancellation_requires_reason(): void
    {
        $staff = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $booking = $this->createConfirmedBooking();

        $response = $this->actingAs($staff)->patch(route('bookings.cancel', $booking), [
            'room_status' => 'available',
            'notify_customer' => 1,
        ]);

        $response->assertSessionHasErrors('cancellation_reason');
        $this->assertSame('confirmed', $booking->fresh()->status);
    }

    public function test_checked_out_booking_cannot_be_cancelled(): void
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'email_verified_at' => now(),
        ]);

        $booking = $this->createCheckedOutBooking();

        $response = $this->actingAs($staff)->patch(route('bookings.cancel', $booking), [
            'cancellation_reason' => 'Late system correction for a checked-out booking.',
            'room_status' => 'available',
            'notify_customer' => 1,
        ]);

        $response->assertSessionHas('error', 'Checked-out bookings cannot be cancelled.');
        $this->assertSame('checked_out', $booking->fresh()->status);
    }

    public function test_checked_in_booking_requires_explicit_override(): void
    {
        $staff = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $booking = $this->createCheckedInBooking();

        $response = $this->actingAs($staff)->patch(route('bookings.cancel', $booking), [
            'cancellation_reason' => 'Guest left and booking must be voided.',
            'room_status' => 'available',
            'notify_customer' => 1,
        ]);

        $response->assertSessionHasErrors('allow_checked_in_cancellation');
        $this->assertSame('checked_in', $booking->fresh()->status);
    }

    public function test_customer_history_shows_cancelled_booking_notice(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        $room = $this->createRoom('402');

        Booking::create([
            'user_id' => $customer->id,
            'room_id' => $room->id,
            'guest_name' => 'History Guest',
            'guest_email' => $customer->email,
            'guest_phone' => '09170000402',
            'check_in' => now()->addDay()->toDateString(),
            'check_out' => now()->addDays(2)->toDateString(),
            'guests' => 2,
            'status' => 'cancelled',
            'total_amount' => 3000,
            'cancellation_reason' => 'The room was moved to maintenance before arrival.',
            'cancelled_at' => now(),
        ]);

        $response = $this->actingAs($customer)->get(route('customer.history'));

        $response->assertOk();
        $response->assertSee('Cancelled Bookings');
        $response->assertSee('The room was moved to maintenance before arrival.');
    }

    private function createRoom(string $roomNumber): Room
    {
        $roomType = RoomType::create([
            'name' => 'Deluxe',
            'slug' => 'deluxe-'.$roomNumber,
            'description' => 'Deluxe room',
            'base_price' => 1500,
        ]);

        return Room::create([
            'room_type_id' => $roomType->id,
            'room_number' => $roomNumber,
            'max_guests' => 2,
            'status' => 'available',
        ]);
    }

    private function createConfirmedBooking(): Booking
    {
        $room = $this->createRoom('403');

        return Booking::create([
            'room_id' => $room->id,
            'guest_name' => 'Confirmed Guest',
            'guest_email' => 'confirmed@example.com',
            'guest_phone' => '09170000403',
            'check_in' => now()->addDay()->toDateString(),
            'check_out' => now()->addDays(2)->toDateString(),
            'guests' => 2,
            'status' => 'confirmed',
            'total_amount' => 3000,
            'payment_status' => 'paid',
        ]);
    }

    private function createCheckedOutBooking(): Booking
    {
        $room = $this->createRoom('404');

        return Booking::create([
            'room_id' => $room->id,
            'guest_name' => 'Checked Out Guest',
            'guest_email' => 'checkedout@example.com',
            'guest_phone' => '09170000404',
            'check_in' => now()->subDays(3)->toDateString(),
            'check_out' => now()->subDay()->toDateString(),
            'guests' => 2,
            'status' => 'checked_out',
            'total_amount' => 3000,
        ]);
    }

    private function createCheckedInBooking(): Booking
    {
        $room = $this->createRoom('405');
        $room->update(['status' => 'occupied']);

        return Booking::create([
            'room_id' => $room->id,
            'guest_name' => 'Checked In Guest',
            'guest_email' => 'checkedin@example.com',
            'guest_phone' => '09170000405',
            'check_in' => now()->subDay()->toDateString(),
            'check_out' => now()->addDay()->toDateString(),
            'guests' => 2,
            'status' => 'checked_in',
            'total_amount' => 3000,
        ]);
    }
}