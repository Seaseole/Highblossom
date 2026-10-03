<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Features;
use PragmaRX\Google2FA\Google2FA;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

/**
 * Regression tests for the two-factor authentication flow in Profile Settings.
 */
class TwoFactorSettingsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Authenticate a user that can reach the admin profile routes.
     */
    private function actingAsAdminUser(bool $passwordConfirmed = true): User
    {
        Permission::findOrCreate('access admin panel', 'web');

        $user = User::factory()->create();
        $user->givePermissionTo('access admin panel');

        $test = $this->actingAs($user);

        if ($passwordConfirmed) {
            $test->withSession(['auth.password_confirmed_at' => time()]);
        }

        return $user;
    }

    /**
     * Build a Google2FA instance sharing the configured verification window.
     */
    private function engine(): Google2FA
    {
        $engine = new Google2FA;
        $engine->setWindow((int) config('fortify-options.two-factor-authentication.window', 0));

        return $engine;
    }

    public function test_two_factor_setup_can_be_completed_with_a_valid_code(): void
    {
        $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

        $user = $this->actingAsAdminUser();

        $this->post(route('admin.profile.two-factor.enable'))->assertRedirect();

        $user->refresh();
        $this->assertNotNull($user->two_factor_secret);
        $this->assertNull($user->two_factor_confirmed_at);

        $this->get(route('admin.profile.index'))
            ->assertOk()
            ->assertSee('Scan this QR code', false);

        $code = $this->engine()->getCurrentOtp(decrypt($user->two_factor_secret));

        $this->from(route('admin.profile.index'))
            ->post(route('admin.profile.two-factor.confirm'), ['code' => $code])
            ->assertRedirect(route('admin.profile.index'))
            ->assertSessionHas('recovery_codes');

        $user->refresh();
        $this->assertNotNull($user->two_factor_confirmed_at);

        $this->get(route('admin.profile.index'))
            ->assertOk()
            ->assertSee('Two-factor authentication is enabled.', false);
    }

    public function test_configured_totp_window_allows_previous_slot_codes(): void
    {
        $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

        $this->assertSame(1, config('fortify-options.two-factor-authentication.window'));

        $user = $this->actingAsAdminUser();
        $this->post(route('admin.profile.two-factor.enable'));

        $user->refresh();
        $secret = decrypt($user->two_factor_secret);
        $engine = $this->engine();

        $previousSlot = $engine->oathTotp($secret, $engine->getTimestamp() - 1);

        $this->from(route('admin.profile.index'))
            ->post(route('admin.profile.two-factor.confirm'), ['code' => $previousSlot])
            ->assertSessionHasNoErrors();

        $this->assertNotNull($user->refresh()->two_factor_confirmed_at);
    }

    public function test_codes_outside_the_totp_window_are_rejected(): void
    {
        $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

        $user = $this->actingAsAdminUser();
        $this->post(route('admin.profile.two-factor.enable'));

        $user->refresh();
        $secret = decrypt($user->two_factor_secret);
        $engine = $this->engine();

        $tooOld = $engine->oathTotp($secret, $engine->getTimestamp() - 5);

        $this->from(route('admin.profile.index'))
            ->post(route('admin.profile.two-factor.confirm'), ['code' => $tooOld])
            ->assertSessionHasErrors('code');

        $this->assertNull($user->refresh()->two_factor_confirmed_at);
    }

    public function test_code_separators_are_normalized_before_verification(): void
    {
        $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

        $user = $this->actingAsAdminUser();
        $this->post(route('admin.profile.two-factor.enable'));

        $user->refresh();
        $code = $this->engine()->getCurrentOtp(decrypt($user->two_factor_secret));

        $this->from(route('admin.profile.index'))
            ->post(route('admin.profile.two-factor.confirm'), ['code' => substr($code, 0, 3).' '.substr($code, 3)])
            ->assertSessionHasNoErrors();

        $this->assertNotNull($user->refresh()->two_factor_confirmed_at);
    }

    public function test_enabling_twice_does_not_rotate_an_existing_secret(): void
    {
        $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

        $user = $this->actingAsAdminUser();

        $this->post(route('admin.profile.two-factor.enable'));
        $firstSecret = $user->refresh()->two_factor_secret;

        $this->post(route('admin.profile.two-factor.enable'));

        $this->assertSame($firstSecret, $user->refresh()->two_factor_secret);
    }

    public function test_pending_setup_can_be_cancelled(): void
    {
        $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

        $user = $this->actingAsAdminUser();
        $this->post(route('admin.profile.two-factor.enable'));

        $this->from(route('admin.profile.index'))
            ->post(route('admin.profile.two-factor.cancel'))
            ->assertRedirect(route('admin.profile.index'));

        $user->refresh();
        $this->assertNull($user->two_factor_secret);
        $this->assertNull($user->two_factor_recovery_codes);
    }

    public function test_cancelling_setup_refuses_to_touch_a_confirmed_configuration(): void
    {
        $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

        $user = $this->actingAsAdminUser();
        $this->post(route('admin.profile.two-factor.enable'));

        $user->refresh();
        $this->post(route('admin.profile.two-factor.confirm'), [
            'code' => $this->engine()->getCurrentOtp(decrypt($user->two_factor_secret)),
        ]);

        $user->refresh();
        $secret = $user->two_factor_secret;

        $this->post(route('admin.profile.two-factor.cancel'))->assertSessionHasErrors('error');

        $this->assertSame($secret, $user->refresh()->two_factor_secret);
        $this->assertNotNull($user->two_factor_confirmed_at);
    }

    public function test_enabling_two_factor_requires_a_recently_confirmed_password(): void
    {
        $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

        $this->actingAsAdminUser(passwordConfirmed: false);

        $this->post(route('admin.profile.two-factor.enable'))
            ->assertRedirect(route('password.confirm'));
    }

    public function test_disabling_two_factor_requires_a_recently_confirmed_password(): void
    {
        $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

        $user = $this->actingAsAdminUser();
        $this->post(route('admin.profile.two-factor.enable'));
        $user->refresh();
        $this->post(route('admin.profile.two-factor.confirm'), [
            'code' => $this->engine()->getCurrentOtp(decrypt($user->two_factor_secret)),
        ]);

        session()->forget('auth.password_confirmed_at');

        $this->post(route('admin.profile.two-factor.disable'))
            ->assertRedirect(route('password.confirm'));
    }

    public function test_confirm_password_endpoint_marks_the_session_as_password_confirmed(): void
    {
        $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

        $user = $this->actingAsAdminUser(passwordConfirmed: false);

        $this->postJson(route('admin.profile.two-factor.confirm-password'), [
            'password' => 'password',
        ])->assertOk()->assertJson(['status' => 'confirmed'])
            ->assertSessionHas('auth.password_confirmed_at');

        $this->post(route('admin.profile.two-factor.enable'))->assertRedirect();

        $this->assertNotNull($user->refresh()->two_factor_secret);
    }

    public function test_confirm_password_endpoint_rejects_a_wrong_password(): void
    {
        $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

        $user = $this->actingAsAdminUser(passwordConfirmed: false);

        $this->postJson(route('admin.profile.two-factor.confirm-password'), [
            'password' => 'not-the-users-password',
        ])->assertStatus(422)->assertJsonValidationErrors('password')
            ->assertSessionHas('auth.password_confirmed_at', function ($value) {
                return $value === null;
            });

        $this->post(route('admin.profile.two-factor.enable'))
            ->assertRedirect(route('password.confirm'));

        $this->assertNull($user->refresh()->two_factor_secret);
    }

    public function test_profile_page_renders_the_enable_two_factor_password_modal(): void
    {
        $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

        $this->actingAsAdminUser();

        $this->get(route('admin.profile.index'))
            ->assertOk()
            ->assertSee('x-ref="enable2faForm"', false)
            ->assertSee('Confirm Password', false)
            ->assertSee('For your security', false);
    }

    public function test_recovery_codes_flash_does_not_break_the_alpine_attribute_after_confirmation(): void
    {
        $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

        $user = $this->actingAsAdminUser();
        $this->post(route('admin.profile.two-factor.enable'));

        $user->refresh();
        $this->post(route('admin.profile.two-factor.confirm'), [
            'code' => $this->engine()->getCurrentOtp(decrypt($user->two_factor_secret)),
        ]);

        $html = $this->get(route('admin.profile.index'))->getContent();

        // Raw double quotes would terminate the x-data="..." attribute and leak
        // the Alpine JS onto the page as visible text.
        $this->assertStringNotContainsString('recoveryCodes: ["', $html);
        $this->assertStringContainsString('recoveryCodes: [&quot;', $html);
        $this->assertStringContainsString('Two-factor authentication is enabled.', $html);
    }

    public function test_login_challenge_accepts_a_recovery_code_and_replaces_it(): void
    {
        $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

        Permission::findOrCreate('access admin panel', 'web');
        $user = User::factory()->create();
        $user->givePermissionTo('access admin panel');

        $codes = ['AAAAA-BBBBBBBB', 'CCCCC-DDDDDDDD'];

        $user->forceFill([
            'two_factor_secret' => encrypt('JBSWY3DPEHPK3PXP'),
            'two_factor_recovery_codes' => encrypt(json_encode($codes)),
            'two_factor_confirmed_at' => now(),
        ])->save();

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('two-factor.login'));

        $this->post(route('two-factor.login'), ['recovery_code' => $codes[0]])
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();

        $remaining = $user->refresh()->recoveryCodes();
        $this->assertNotContains($codes[0], $remaining);
        $this->assertContains($codes[1], $remaining);
    }

    public function test_login_challenge_view_offers_a_recovery_code_input(): void
    {
        $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

        Permission::findOrCreate('access admin panel', 'web');
        $user = User::factory()->create();
        $user->givePermissionTo('access admin panel');

        $user->forceFill([
            'two_factor_secret' => encrypt('JBSWY3DPEHPK3PXP'),
            'two_factor_recovery_codes' => encrypt(json_encode(['AAAAA-BBBBBBBB'])),
            'two_factor_confirmed_at' => now(),
        ])->save();

        $this->post('/login', ['email' => $user->email, 'password' => 'password']);

        $this->get(route('two-factor.login'))
            ->assertOk()
            ->assertSee('name="recovery_code"', false)
            ->assertSee('Use a recovery code', false);
    }
}
