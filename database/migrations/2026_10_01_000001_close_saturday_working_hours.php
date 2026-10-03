<?php

declare(strict_types=1);

use App\Models\CompanySetting;
use Illuminate\Database\Migrations\Migration;

/**
 * Mark Saturday as closed in the stored working hours.
 *
 * Availability used to reject weekends in code while the stored setting declared
 * Saturday open 08:00-12:00, so that config did nothing. Closed days are now derived
 * from the setting, which would silently make Saturdays bookable. This aligns the
 * stored data with the behaviour customers actually experienced; opening Saturday is
 * now a one-toggle change in Settings rather than a code change.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->setSaturdayClosed(true);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->setSaturdayClosed(false);
    }

    /**
     * Flip the Saturday closed flag, leaving its open/close times intact.
     */
    private function setSaturdayClosed(bool $closed): void
    {
        $setting = CompanySetting::where('key', 'working_hours')->first();

        if ($setting === null) {
            return;
        }

        $workingHours = json_decode($setting->value, true);

        if (! is_array($workingHours) || ! isset($workingHours['saturday'])) {
            return;
        }

        $workingHours['saturday']['is_closed'] = $closed;

        CompanySetting::set('working_hours', $workingHours, 'json');
    }
};
