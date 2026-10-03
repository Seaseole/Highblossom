<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\CompanySetting;
use App\Models\Inspection;
use App\Models\StaffAbsence;
use App\Models\User;
use App\Services\Contracts\AvailabilityServiceInterface;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class AvailabilityServiceTest extends TestCase
{
    use RefreshDatabase;

    private AvailabilityServiceInterface $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(AvailabilityServiceInterface::class);
    }

    public function test_it_allows_available_slots(): void
    {
        // Monday at 10 AM (assuming Monday isn't a weekend)
        $monday = Date::parse('next Monday 10:00:00');

        $this->assertTrue($this->service->isSlotAvailable($monday));
    }

    public function test_it_disallows_weekends(): void
    {
        $saturday = Date::parse('next Saturday 10:00:00');
        $sunday = Date::parse('next Sunday 10:00:00');

        $this->assertFalse($this->service->isSlotAvailable($saturday));
        $this->assertFalse($this->service->isSlotAvailable($sunday));
    }

    public function test_it_disallows_slots_with_staff_absences(): void
    {
        $monday = Date::parse('next Monday 10:00:00');
        $staff = User::factory()->create();

        StaffAbsence::create([
            'staff_id' => $staff->id,
            'starts_at' => $monday->copy()->subHour(),
            'ends_at' => $monday->copy()->addHour(),
            'reason' => 'Doctor appointment',
        ]);

        $this->assertFalse($this->service->isSlotAvailable($monday));
    }

    public function test_it_disallows_slots_with_existing_inspections(): void
    {
        $monday = Date::parse('next Monday 10:00:00');

        Inspection::factory()->create([
            'scheduled_at' => $monday,
        ]);

        $this->assertFalse($this->service->isSlotAvailable($monday));
    }

    public function test_it_disallows_slots_taken_by_a_live_booking(): void
    {
        $monday = Date::parse('next Monday 10:00:00');
        $this->bookingAt($monday, 'pending');

        $this->assertSame(AvailabilityServiceInterface::STATUS_TAKEN, $this->service->slotStatus($monday));
    }

    public function test_it_frees_a_slot_once_the_booking_is_cancelled(): void
    {
        $monday = Date::parse('next Monday 10:00:00');
        $this->bookingAt($monday, 'cancelled');

        $this->assertTrue($this->service->isSlotAvailable($monday));
    }

    public function test_it_rejects_same_day_slots_inside_the_lead_time(): void
    {
        // Default working hours are Mon-Fri 08:00-17:00 and the notice window is 2 hours.
        $this->travelTo(Carbon::parse('2026-10-05 09:00:00'));

        $this->assertSame(
            AvailabilityServiceInterface::STATUS_TOO_LATE,
            $this->service->slotStatus('2026-10-05T10:00:00'),
        );
    }

    public function test_it_allows_same_day_slots_outside_the_lead_time(): void
    {
        $this->travelTo(Carbon::parse('2026-10-05 09:00:00'));

        $this->assertSame(
            AvailabilityServiceInterface::STATUS_AVAILABLE,
            $this->service->slotStatus('2026-10-05T11:00:00'),
        );
    }

    public function test_it_never_offers_hours_that_have_already_elapsed(): void
    {
        $this->travelTo(Carbon::parse('2026-10-05 13:08:00'));

        $available = array_values(array_filter(
            $this->service->slotsForDate('2026-10-05'),
            fn (array $slot): bool => $slot['available'],
        ));

        $this->assertSame(['16:00'], array_column($available, 'time'));
    }

    public function test_it_reports_the_earliest_bookable_slot_for_today(): void
    {
        $this->travelTo(Carbon::parse('2026-10-05 13:08:00'));

        $this->assertSame('2026-10-05 16:00', $this->service->earliestBookableSlot()?->format('Y-m-d H:i'));
    }

    public function test_it_rolls_the_earliest_slot_over_to_the_next_open_day(): void
    {
        // Friday 16:30, two hours notice pushes past the 17:00 close; Sat and Sun are closed.
        $this->travelTo(Carbon::parse('2026-10-09 16:30:00'));

        $this->assertSame('2026-10-12 08:00', $this->service->earliestBookableSlot()?->format('Y-m-d H:i'));
    }

    public function test_it_skips_closed_days_in_the_upcoming_strip(): void
    {
        $this->travelTo(Carbon::parse('2026-10-05 09:00:00'));

        $this->assertSame(
            ['2026-10-05', '2026-10-06', '2026-10-07', '2026-10-08', '2026-10-09', '2026-10-12'],
            array_column($this->service->upcomingOpenDays(6), 'date'),
        );
    }

    public function test_it_drops_today_from_the_strip_once_the_day_is_over(): void
    {
        $this->travelTo(Carbon::parse('2026-10-05 16:30:00'));

        $this->assertSame('2026-10-06', array_column($this->service->upcomingOpenDays(3), 'date')[0]);
    }

    public function test_closed_days_come_from_the_working_hours_setting(): void
    {
        $this->travelTo(Carbon::parse('2026-10-05 09:00:00'));

        CompanySetting::set('working_hours', [
            'monday' => ['open' => '08:00', 'close' => '17:00', 'is_closed' => false],
            'tuesday' => ['open' => '08:00', 'close' => '17:00', 'is_closed' => false],
            'wednesday' => ['open' => '08:00', 'close' => '17:00', 'is_closed' => false],
            'thursday' => ['open' => '08:00', 'close' => '17:00', 'is_closed' => false],
            'friday' => ['open' => '08:00', 'close' => '17:00', 'is_closed' => false],
            'saturday' => ['open' => '08:00', 'close' => '12:00', 'is_closed' => false],
            'sunday' => ['open' => null, 'close' => null, 'is_closed' => true],
        ], 'json');

        $this->assertSame(
            AvailabilityServiceInterface::STATUS_AVAILABLE,
            $this->service->slotStatus('2026-10-10T09:00:00'),
        );
    }

    public function test_lead_time_of_zero_allows_the_next_hour_of_the_current_day(): void
    {
        $this->travelTo(Carbon::parse('2026-10-05 13:08:00'));
        CompanySetting::set('booking_lead_time_hours', '0', 'number');

        $available = array_values(array_filter(
            $this->service->slotsForDate('2026-10-05'),
            fn (array $slot): bool => $slot['available'],
        ));

        $this->assertSame(['14:00', '15:00', '16:00'], array_column($available, 'time'));
    }

    public function test_it_labels_slots_with_the_configured_time_format(): void
    {
        CompanySetting::set('time_format_display', '12', 'text');

        $slots = $this->service->slotsForDate('2026-10-05');

        $this->assertSame('08:00', $slots[0]['time']);
        $this->assertSame('08:00 AM', $slots[0]['label']);
    }

    /**
     * Create a booking in the given status at the supplied slot.
     */
    private function bookingAt(\DateTimeInterface $scheduledAt, string $status): Booking
    {
        return Booking::create([
            'client_name' => 'Existing',
            'client_email' => 'existing@example.com',
            'client_phone' => '26700000000',
            'vehicle_details' => 'Existing car',
            'scheduled_at' => $scheduledAt,
            'location' => 'workshop',
            'status' => $status,
            'total_price' => 0,
        ]);
    }
}
