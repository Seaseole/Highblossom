<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\CompanySetting;
use App\Services\BookingCalendarService;
use App\Services\Contracts\AvailabilityServiceInterface;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Covers the same-day availability lookup that used to be refused outright.
 */
class BookingAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_slots_for_today(): void
    {
        $this->travelTo(Carbon::parse('2026-10-05 09:00:00'));

        $response = $this->getJson('/api/bookings/availability?date=2026-10-05');

        $response->assertOk()
            ->assertJsonPath('date', '2026-10-05')
            ->assertJsonPath('is_today', true)
            ->assertJsonPath('closed', false)
            ->assertJsonPath('lead_time_hours', 2)
            ->assertJsonCount(9, 'slots')
            ->assertJsonStructure([
                'date',
                'is_today',
                'closed',
                'lead_time_hours',
                'earliest' => ['date', 'day_label', 'time_label'],
                'slots' => [['time', 'label', 'available', 'status']],
            ]);
    }

    public function test_it_marks_hours_inside_the_notice_window_as_too_late(): void
    {
        $this->travelTo(Carbon::parse('2026-10-05 13:08:00'));

        $slots = $this->getJson('/api/bookings/availability?date=2026-10-05')
            ->assertOk()
            ->json('slots');

        $this->assertSame(['16:00'], array_column(
            array_filter($slots, fn (array $slot): bool => $slot['available']),
            'time',
        ));

        $this->assertSame('too_late', $slots[0]['status']);
    }

    public function test_it_reports_the_earliest_bookable_moment(): void
    {
        $this->travelTo(Carbon::parse('2026-10-09 16:30:00'));

        $response = $this->getJson('/api/bookings/availability?date=2026-10-09')
            ->assertOk()
            ->assertJsonPath('closed', false)
            ->assertJsonPath('earliest.date', '2026-10-12')
            ->assertJsonPath('earliest.day_label', 'Mon 12 Oct');

        $this->assertSame([], array_filter($response->json('slots'), fn (array $slot): bool => $slot['available']));
    }

    public function test_it_labels_slots_using_the_displayed_time_format(): void
    {
        $this->travelTo(Carbon::parse('2026-10-05 09:00:00'));

        $this->getJson('/api/bookings/availability?date=2026-10-05')
            ->assertOk()
            ->assertJsonPath('slots.0.time', '08:00')
            ->assertJsonPath('slots.0.label', '08:00 AM');
    }

    public function test_it_rejects_a_date_in_the_past(): void
    {
        $this->travelTo(Carbon::parse('2026-10-05 09:00:00'));

        $this->getJson('/api/bookings/availability?date=2026-10-04')->assertStatus(422);
    }

    public function test_it_rejects_a_date_beyond_the_booking_horizon(): void
    {
        $this->travelTo(Carbon::parse('2026-10-05 09:00:00'));

        $this->getJson('/api/bookings/availability?date=2027-04-01')->assertStatus(422);
    }

    public function test_it_reports_a_closed_day_as_empty_rather_than_an_error(): void
    {
        $this->travelTo(Carbon::parse('2026-10-05 09:00:00'));

        $this->getJson('/api/bookings/availability?date=2026-10-11')
            ->assertOk()
            ->assertJsonPath('closed', true)
            ->assertJsonCount(0, 'slots');
    }

    public function test_the_booking_page_offers_today_in_the_date_strip(): void
    {
        $this->travelTo(Carbon::parse('2026-10-05 09:00:00'));

        $this->get('/bookings/create')
            ->assertOk()
            ->assertSee('2026-10-05', false)
            ->assertSee('Today')
            ->assertSee('Pick another date')
            ->assertDontSee('October 2027');
    }

    /**
     * The wizard JSON-parses these attributes, so they must stay plain JSON rather
     * than the JS-ready `JSON.parse(...)` wrapper that Blade's @js directive emits.
     */
    public function test_the_booking_page_embeds_parseable_json_in_the_wizard_attributes(): void
    {
        $this->travelTo(Carbon::parse('2026-10-05 09:00:00'));

        $html = $this->get('/bookings/create')->assertOk()->getContent();

        foreach (['strip-dates', 'reason-labels', 'period-labels', 'months-labels'] as $attribute) {
            preg_match('/data-'.$attribute.'="([^"]*)"/', $html, $matches);

            $this->assertNotEmpty($matches[1] ?? null, "data-{$attribute} is missing");
            $this->assertStringNotContainsString('JSON.parse', html_entity_decode($matches[1]));
            $this->assertNotNull(json_decode(html_entity_decode($matches[1]), true), "data-{$attribute} is not valid JSON");
        }
    }

    public function test_the_booking_page_resumes_on_the_step_that_failed(): void
    {
        $this->travelTo(Carbon::parse('2026-10-05 09:00:00'));

        $this->from('/bookings/create')
            ->post('/bookings', $this->payload(['scheduled_at' => '2026-10-05T10:00:00']))
            ->assertRedirect();

        $this->get('/bookings/create')->assertOk()->assertSee('data-initial-step="1"', false);
    }

    public function test_the_booking_page_resumes_on_the_details_step_when_contact_fields_fail(): void
    {
        $this->travelTo(Carbon::parse('2026-10-05 09:00:00'));

        $this->from('/bookings/create')
            ->post('/bookings', $this->payload(['client_phone' => '07:30']))
            ->assertRedirect();

        $this->get('/bookings/create')->assertOk()->assertSee('data-initial-step="2"', false);
    }

    public function test_a_slot_taken_while_shopping_returns_to_step_one_with_the_reason(): void
    {
        $this->travelTo(Carbon::parse('2026-10-05 09:00:00'));

        Booking::create([
            'client_name' => 'Earlier customer',
            'client_email' => 'earlier@example.com',
            'client_phone' => '26700000000',
            'vehicle_details' => 'Earlier car',
            'scheduled_at' => Carbon::parse('2026-10-05 14:00:00'),
            'location' => 'workshop',
            'status' => 'pending',
            'total_price' => 0,
        ]);

        $this->from('/bookings/create')
            ->post('/bookings', $this->payload())
            ->assertRedirect();

        $this->get('/bookings/create')
            ->assertOk()
            ->assertSee('data-initial-step="1"', false)
            ->assertSee('That time slot is no longer available', false);
    }

    public function test_the_calendar_grid_refuses_a_day_the_strip_refuses(): void
    {
        $this->travelTo(Carbon::parse('2026-10-05 09:00:00'));

        // Default working hours close Saturday.
        $this->assertFalse($this->selectableDates()['2026-10-10']);
        $this->assertNotContains('2026-10-10', $this->stripDates());
    }

    public function test_the_calendar_grid_and_strip_both_open_when_config_opens_a_day(): void
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

        $this->assertTrue($this->selectableDates()['2026-10-10']);
        $this->assertContains('2026-10-10', $this->stripDates());
    }

    /**
     * Map of date => selectable for the first rendered calendar month.
     *
     * @return array<string, bool>
     */
    private function selectableDates(): array
    {
        $map = [];

        foreach (app(BookingCalendarService::class)->getMonths(1) as $month) {
            foreach ($month['weeks'] as $week) {
                foreach ($week as $day) {
                    if ($day !== null) {
                        $map[$day['date']] = $day['selectable'];
                    }
                }
            }
        }

        return $map;
    }

    /**
     * The dates offered by the date strip.
     *
     * @return list<string>
     */
    private function stripDates(): array
    {
        return array_column(app(AvailabilityServiceInterface::class)->upcomingOpenDays(14), 'date');
    }

    /**
     * A booking payload that would pass, with the given fields overridden.
     *
     * @param array<string, string> $overrides
     *
     * @return array<string, string>
     */
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'client_name' => 'Test User',
            'client_email' => 'test@example.com',
            'client_phone' => '26712345678',
            'vehicle_details' => 'Toyota Hilux 2020',
            'scheduled_at' => '2026-10-05T14:00:00',
            'location' => 'mobile',
            'client_address' => 'Plot 123, Gaborone North',
            '_idempotency_token' => md5(uniqid()),
        ], $overrides);
    }
}
