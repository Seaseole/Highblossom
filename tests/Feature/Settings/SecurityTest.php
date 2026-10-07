<?php

declare(strict_types=1);

namespace Tests\Feature\Settings;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Features;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

/**
 * Coverage for the password and two-factor endpoints behind the admin profile page.
 */
class SecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());
    }

    /**
     * Authenticate a user that can reach the admin profile routes.
     */
    private function actingAsProfileUser(): User
    {
        Permission::findOrCreate('access admin panel', 'web');

        $user = User::factory()->create();
        $user->givePermissionTo('access admin panel');
        $this->actingAs($user);

        return $user;
    }

    public function test_security_settings_page_can_be_rendered(): void
    {
        $this->actingAsProfileUser();

        $this->get(route('admin.profile.index'))
            ->assertOk()
            ->assertSee('Two-Factor Authentication')
            ->assertSee('Enable Two-Factor');
    }

    public function test_two_factor_enable_requires_password_confirmation(): void
    {
        $user = $this->actingAsProfileUser();

        $this->post(route('admin.profile.two-factor.enable'))
            ->assertRedirect(route('password.confirm'));

        $this->assertNull($user->fresh()->two_factor_secret);
    }

    public function test_two_factor_cannot_be_enabled_when_the_feature_is_disabled(): void
    {
        config(['fortify.features' => []]);

        $user = $this->actingAsProfileUser();

        $this->from(route('admin.profile.index'))
            ->withSession(['auth.password_confirmed_at' => time()])
            ->post(route('admin.profile.two-factor.enable'))
            ->assertSessionHasErrors('error');

        $this->assertNull($user->fresh()->two_factor_secret);
    }

    public function test_pending_two_factor_setup_is_discarded_when_cancelled(): void
    {
        $user = $this->actingAsProfileUser();

        $user->forceFill([
            'two_factor_secret' => encrypt('test-secret'),
            'two_factor_recovery_codes' => encrypt(json_encode(['code1', 'code2'])),
            'two_factor_confirmed_at' => null,
        ])->save();

        $this->from(route('admin.profile.index'))
            ->withSession(['auth.password_confirmed_at' => time()])
            ->post(route('admin.profile.two-factor.cancel'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
        ]);
    }

    public function test_password_can_be_updated(): void
    {
        $user = $this->actingAsProfileUser();

        $this->from(route('admin.profile.index'))
            ->put(route('admin.profile.password.update'), [
                'current_password' => 'password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ])
            ->assertSessionHasNoErrors();

        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }

    public function test_correct_password_must_be_provided_to_update_password(): void
    {
        $user = $this->actingAsProfileUser();

        $this->from(route('admin.profile.index'))
            ->put(route('admin.profile.password.update'), [
                'current_password' => 'wrong-password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ])
            ->assertSessionHasErrors('current_password');

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }
}
