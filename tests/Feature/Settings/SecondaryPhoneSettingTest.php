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
 * Cover the optional secondary phone field on the Company Settings screen:
 * rendering, persistence, clearing, and length validation.
 */
class SecondaryPhoneSettingTest extends TestCase
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

    public function test_the_settings_form_renders_the_secondary_phone_field(): void
    {
        $this->actingAsSettingsAdmin();
        CompanySetting::set('secondary_phone', '+26771234567');

        $this->get(route('admin.settings.index'))
            ->assertOk()
            ->assertSee('Secondary Phone')
            ->assertSee('name="secondary_phone"', false)
            ->assertSee('+26771234567');
    }

    public function test_saving_a_secondary_phone_persists_it(): void
    {
        $this->actingAsSettingsAdmin();

        $this->put(route('admin.settings.update'), $this->payload(['secondary_phone' => '+26771234567']))
            ->assertRedirect();

        $this->assertSame('+26771234567', CompanySetting::get('secondary_phone'));
    }

    public function test_clearing_the_secondary_phone_removes_the_value(): void
    {
        $this->actingAsSettingsAdmin();
        CompanySetting::set('secondary_phone', '+26771234567');

        $this->put(route('admin.settings.update'), $this->payload(['secondary_phone' => '']))
            ->assertRedirect();

        $this->assertNull(CompanySetting::get('secondary_phone'));
    }

    public function test_the_secondary_phone_is_rejected_when_too_long(): void
    {
        $this->actingAsSettingsAdmin();

        $tooLong = str_repeat('1', 21);

        $this->put(route('admin.settings.update'), $this->payload(['secondary_phone' => $tooLong]))
            ->assertSessionHasErrors('secondary_phone');

        $this->assertNotSame($tooLong, CompanySetting::get('secondary_phone'));
    }
}
