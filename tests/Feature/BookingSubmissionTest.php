<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class BookingSubmissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
        Notification::fake();
    }

    public function test_successful_booking_redirects_to_signed_confirmation(): void
    {
        $monday = now()->addDays(2)->next(1)->setHour(10)->setMinute(0)->setSecond(0)->format('Y-m-d\TH:i:s');

        $response = $this->post('/bookings', [
            'client_name' => 'Test User',
            'client_email' => 'test@example.com',
            'client_phone' => '26712345678',
            'vehicle_details' => 'Toyota Hilux 2020',
            'scheduled_at' => $monday,
            'location' => 'mobile',
            'client_address' => 'Plot 123, Gaborone North',
            '_idempotency_token' => md5(uniqid()),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('bookings', [
            'client_name' => 'Test User',
            'client_email' => 'test@example.com',
            'location' => 'mobile',
            'client_address' => 'Plot 123, Gaborone North',
        ]);
    }

    public function test_weekend_scheduled_at_fails_validation(): void
    {
        $saturday = now()->next(6)->setHour(10)->setMinute(0)->setSecond(0)->format('Y-m-d\TH:i:s');

        $response = $this->post('/bookings', [
            'client_name' => 'Test User',
            'client_email' => 'test@example.com',
            'client_phone' => '26712345678',
            'vehicle_details' => 'Toyota Hilux 2020',
            'scheduled_at' => $saturday,
            'location' => 'mobile',
            '_idempotency_token' => md5(uniqid()),
        ]);

        $response->assertSessionHasErrors('scheduled_at');
        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_slot_already_booked_returns_failure(): void
    {
        $monday = now()->addDays(2)->next(1)->setHour(10)->setMinute(0)->setSecond(0);
        $formatted = $monday->format('Y-m-d\TH:i:s');

        Booking::create([
            'client_name' => 'Existing',
            'client_email' => 'existing@example.com',
            'client_phone' => '26700000000',
            'vehicle_details' => 'Existing car',
            'scheduled_at' => $monday,
            'location' => 'workshop',
            'status' => 'pending',
            'total_price' => 0,
        ]);

        $response = $this->post('/bookings', [
            'client_name' => 'Test User',
            'client_email' => 'test@example.com',
            'client_phone' => '26712345678',
            'vehicle_details' => 'Toyota Hilux 2020',
            'scheduled_at' => $formatted,
            'location' => 'mobile',
            'client_address' => 'Plot 123, Gaborone North',
            '_idempotency_token' => md5(uniqid()),
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseCount('bookings', 1);
    }

    public function test_missing_required_fields_returns_errors(): void
    {
        $response = $this->post('/bookings', [
            '_idempotency_token' => md5(uniqid()),
        ]);

        $response->assertSessionHasErrors([
            'client_name',
            'client_email',
            'client_phone',
            'vehicle_details',
            'scheduled_at',
            'location',
        ]);
    }

    public function test_unsigned_confirmation_url_returns_403(): void
    {
        $booking = Booking::factory()->create();

        $response = $this->get("/bookings/{$booking->id}/confirmation");

        $response->assertStatus(403);
    }

    public function test_phone_format_invalid_fails_validation(): void
    {
        $monday = now()->addDays(2)->next(1)->setHour(10)->setMinute(0)->setSecond(0)->format('Y-m-d\TH:i:s');

        $response = $this->post('/bookings', [
            'client_name' => 'Test User',
            'client_email' => 'test@example.com',
            'client_phone' => 'abc+()',
            'vehicle_details' => 'Toyota Hilux 2020',
            'scheduled_at' => $monday,
            'location' => 'mobile',
            '_idempotency_token' => md5(uniqid()),
        ]);

        $response->assertSessionHasErrors('client_phone');
        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_phone_too_short_fails_validation(): void
    {
        $monday = now()->addDays(2)->next(1)->setHour(10)->setMinute(0)->setSecond(0)->format('Y-m-d\TH:i:s');

        $response = $this->post('/bookings', [
            'client_name' => 'Test User',
            'client_email' => 'test@example.com',
            'client_phone' => '123',
            'vehicle_details' => 'Toyota Hilux 2020',
            'scheduled_at' => $monday,
            'location' => 'mobile',
            '_idempotency_token' => md5(uniqid()),
        ]);

        $response->assertSessionHasErrors('client_phone');
        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_vehicle_details_exceeds_max_length_fails(): void
    {
        $monday = now()->addDays(2)->next(1)->setHour(10)->setMinute(0)->setSecond(0)->format('Y-m-d\TH:i:s');

        $response = $this->post('/bookings', [
            'client_name' => 'Test User',
            'client_email' => 'test@example.com',
            'client_phone' => '26712345678',
            'vehicle_details' => str_repeat('A', 256),
            'scheduled_at' => $monday,
            'location' => 'mobile',
            '_idempotency_token' => md5(uniqid()),
        ]);

        $response->assertSessionHasErrors('vehicle_details');
        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_scheduled_at_wrong_format_fails(): void
    {
        $monday = now()->addDays(2)->next(1)->setHour(10)->setMinute(0)->format('Y-m-d H:i:s');

        $response = $this->post('/bookings', [
            'client_name' => 'Test User',
            'client_email' => 'test@example.com',
            'client_phone' => '26712345678',
            'vehicle_details' => 'Toyota Hilux 2020',
            'scheduled_at' => $monday,
            'location' => 'mobile',
            '_idempotency_token' => md5(uniqid()),
        ]);

        $response->assertSessionHasErrors('scheduled_at');
        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_idempotency_token_missing_fails(): void
    {
        $monday = now()->addDays(2)->next(1)->setHour(10)->setMinute(0)->setSecond(0)->format('Y-m-d\TH:i:s');

        $response = $this->post('/bookings', [
            'client_name' => 'Test User',
            'client_email' => 'test@example.com',
            'client_phone' => '26712345678',
            'vehicle_details' => 'Toyota Hilux 2020',
            'scheduled_at' => $monday,
            'location' => 'mobile',
        ]);

        $response->assertSessionHasErrors('_idempotency_token');
    }

    public function test_location_invalid_value_fails(): void
    {
        $monday = now()->addDays(2)->next(1)->setHour(10)->setMinute(0)->setSecond(0)->format('Y-m-d\TH:i:s');

        $response = $this->post('/bookings', [
            'client_name' => 'Test User',
            'client_email' => 'test@example.com',
            'client_phone' => '26712345678',
            'vehicle_details' => 'Toyota Hilux 2020',
            'scheduled_at' => $monday,
            'location' => 'invalid_location',
            '_idempotency_token' => md5(uniqid()),
        ]);

        $response->assertSessionHasErrors('location');
    }
}
