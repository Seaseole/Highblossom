<?php

namespace Tests\Feature;

use App\Domains\Bookings\Models\Inspection;
use App\Domains\Bookings\Models\StaffAbsence;
use App\Infrastructure\Services\AvailabilityService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class AvailabilityServiceTest extends TestCase
{
    use RefreshDatabase;

    private AvailabilityService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AvailabilityService();
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
}
