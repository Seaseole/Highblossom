<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\CompanySetting;
use App\Models\User;
use App\Services\SessionManager;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Cover the administrator-editable session idle timeout: that the field renders on
 * the Account Security tab, saves through the company settings store, and rejects a
 * value outside the bounds the sweep could sensibly apply.
 */
class SessionIdleTimeoutSettingTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_settings_page_offers_the_idle_timeout_field(): void
    {
        $this->actingAsSettingsAdmin();

        $this->get(route('admin.settings.index'))
            ->assertOk()
            ->assertSee('Session idle timeout (minutes)')
            ->assertSee('name="session_idle_timeout_minutes"', false);
    }

    public function test_the_idle_timeout_is_saved_and_read_back_by_the_sweep(): void
    {
        $this->actingAsSettingsAdmin();

        $this->put(route('admin.settings.update'), $this->payload(45))
            ->assertSessionHasNoErrors();

        $this->assertSame('45', (string) CompanySetting::get('session_idle_timeout_minutes'));
        $this->assertSame(45, $this->app->make(SessionManager::class)->idleTimeoutMinutes());
    }

    public function test_leaving_the_idle_timeout_blank_disables_it(): void
    {
        $this->actingAsSettingsAdmin();

        CompanySetting::set('session_idle_timeout_minutes', 45);

        $this->put(route('admin.settings.update'), $this->payload(''))
            ->assertSessionHasNoErrors();

        $this->assertSame(0, $this->app->make(SessionManager::class)->idleTimeoutMinutes());
    }

    public function test_an_idle_timeout_outside_the_bounds_is_rejected(): void
    {
        $this->actingAsSettingsAdmin();

        foreach ([99999, -5, 'soon'] as $invalid) {
            $this->put(route('admin.settings.update'), $this->payload($invalid))
                ->assertSessionHasErrors('session_idle_timeout_minutes');
        }
    }

    /**
     * Seed roles and act as an administrator who may edit company settings.
     */
    private function actingAsSettingsAdmin(): User
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create();
        $user->assignRole(Role::findOrCreate('Super Admin', 'web'));

        $this->actingAs($user);

        return $user;
    }

    /**
     * The smallest complete settings payload the form accepts, with one idle timeout.
     *
     * @return array<string, mixed>
     */
    private function payload(mixed $idleTimeout): array
    {
        return [
            'company_name' => 'Highblossom PTY LTD',
            'primary_email' => 'sales@highblossom.net',
            'address' => 'Plot 22147 Gaborone West Industrial site, Gaborone',
            'primary_phone' => '+267 311 7480',
            'whatsapp_number_default' => '+26773732867',
            'timezone' => 'Africa/Gaborone',
            'locale' => 'en_GB',
            'date_format' => 'd/m/Y',
            'time_format' => 'H:i',
            'time_format_display' => '24',
            'booking_lead_time_hours' => 2,
            'currency_symbol' => 'P',
            'session_idle_timeout_minutes' => $idleTimeout,
        ];
    }
}
