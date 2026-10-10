<?php

declare(strict_types=1);

namespace Tests\Feature\Settings;

use App\Models\CompanySetting;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Cover the `enable_registration` company setting: its General-tab toggle,
 * boolean persistence, and the removal of the environment-tab control.
 */
class EnableRegistrationSettingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Act as a user allowed to view and update company settings.
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
     * A valid company settings payload, overridable per test.
     *
     * @param array<string, mixed> $overrides
     *
     * @return array<string, mixed>
     */
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'company_name' => 'Highblossom PTY LTD',
            'primary_email' => 'sales@highblossom.net',
            'address' => 'Plot 22147 Gaborone West Industrial site, Gaborone',
            'primary_phone' => '+2671234567',
            'whatsapp_number_default' => '+26773732867',
            'timezone' => 'Africa/Gaborone',
            'locale' => 'en_GB',
            'date_format' => 'd/M/Y',
            'time_format' => 'H:i',
            'time_format_display' => '12',
            'booking_lead_time_hours' => 2,
            'currency_symbol' => 'P',
        ], $overrides);
    }

    public function test_the_settings_form_renders_the_registration_toggle_in_the_general_tab(): void
    {
        $this->actingAsSettingsAdmin();

        $this->get(route('admin.settings.index'))
            ->assertOk()
            ->assertSee('Account Security')
            ->assertSee('Enable registration')
            ->assertSee('name="enable_registration"', false);
    }

    public function test_the_registration_toggle_is_not_in_the_environment_tab(): void
    {
        $this->actingAsSettingsAdmin();

        $this->get(route('admin.settings.index'))
            ->assertOk()
            ->assertDontSee('env[FEATURES_REGISTRATION_ENABLED]', false);
    }

    public function test_the_registration_toggle_is_checked_when_the_setting_is_on(): void
    {
        $this->actingAsSettingsAdmin();
        CompanySetting::set('enable_registration', '1', 'boolean');

        $content = $this->get(route('admin.settings.index'))->content();

        $this->assertMatchesRegularExpression('/name="enable_registration"\s+value="1"\s+checked/s', $content);
    }

    public function test_enabling_the_toggle_persists_a_boolean(): void
    {
        $this->actingAsSettingsAdmin();

        $this->put(route('admin.settings.update'), $this->payload(['enable_registration' => '1']))
            ->assertRedirect();

        $this->assertTrue(CompanySetting::get('enable_registration'));
    }

    public function test_disabling_the_toggle_closes_registration(): void
    {
        $this->actingAsSettingsAdmin();
        CompanySetting::set('enable_registration', '1', 'boolean');

        $this->put(route('admin.settings.update'), $this->payload(['enable_registration' => '0']))
            ->assertRedirect();

        $this->assertFalse(CompanySetting::get('enable_registration'));
    }

    public function test_registration_is_closed_when_the_setting_row_is_absent(): void
    {
        $this->get(route('register'))->assertNotFound();
    }
}
