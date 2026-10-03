<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\CompanySetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

/**
 * Coverage for the require_email_verification company setting toggle.
 */
class EmailVerificationEnforcementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Authenticate an admin-panel user with the given verification state.
     */
    private function actingAsPanelUser(bool $verified): User
    {
        Permission::findOrCreate('access admin panel', 'web');

        $user = $verified
            ? User::factory()->create()
            : User::factory()->unverified()->create();
        $user->givePermissionTo('access admin panel');
        $this->actingAs($user);

        return $user;
    }

    public function test_unverified_user_is_blocked_when_enforcement_is_on(): void
    {
        $this->actingAsPanelUser(verified: false);
        CompanySetting::set('require_email_verification', '1');

        $this->get(route('admin.profile.index'))
            ->assertRedirect(route('verification.notice'));
    }

    public function test_verified_user_passes_when_enforcement_is_on(): void
    {
        $this->actingAsPanelUser(verified: true);
        CompanySetting::set('require_email_verification', '1');

        $this->get(route('admin.profile.index'))->assertOk();
    }

    public function test_unverified_user_passes_when_enforcement_is_off(): void
    {
        $this->actingAsPanelUser(verified: false);
        CompanySetting::set('require_email_verification', '0');

        $this->get(route('admin.profile.index'))->assertOk();
    }

    public function test_unverified_user_passes_when_setting_is_missing(): void
    {
        $this->actingAsPanelUser(verified: false);

        $this->get(route('admin.profile.index'))->assertOk();
    }

    public function test_json_requests_get_forbidden_when_enforcement_is_on(): void
    {
        $this->actingAsPanelUser(verified: false);
        CompanySetting::set('require_email_verification', '1');

        $this->getJson(route('admin.profile.index'))->assertForbidden();
    }
}
