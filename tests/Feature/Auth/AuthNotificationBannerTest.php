<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

/**
 * The auth notification banner must surface credential and password-reset failures,
 * and must never repeat a message it already owns inline.
 *
 * assertSessionHasErrors() consumes the flashed bag before the next request renders,
 * so the render tests post without it and one test covers the bag on its own.
 */
class AuthNotificationBannerTest extends TestCase
{
    use RefreshDatabase;

    private const VALID_PASSWORD = 'Seaseole!Window92';

    private const CREDENTIALS_MESSAGE = 'These credentials do not match our records.';

    private function user(): User
    {
        return User::factory()->create([
            'email' => 'customer@example.test',
            'password' => self::VALID_PASSWORD,
        ]);
    }

    /**
     * Count how often a fragment renders, to prove the banner/inline split.
     */
    private function occurrences(string $html, string $fragment): int
    {
        return substr_count($html, $fragment);
    }

    public function test_a_failed_login_is_reported_by_the_banner_and_not_repeated_inline(): void
    {
        $user = $this->user();

        $this->post(route('login'), ['email' => $user->email, 'password' => 'not-the-password']);

        $html = $this->get(route('login'))->getContent();

        $this->assertStringContainsString('data-auth-alert', $html);
        $this->assertSame(1, $this->occurrences($html, self::CREDENTIALS_MESSAGE));
        $this->assertStringNotContainsString('id="email-error"', $html);
    }

    public function test_a_failed_login_flashes_the_credential_error_on_the_email_key(): void
    {
        $user = $this->user();

        $this->post(route('login'), ['email' => $user->email, 'password' => 'not-the-password'])
            ->assertSessionHasErrors('email');
    }

    public function test_a_lockout_is_answered_by_the_throttle_response_not_the_banner(): void
    {
        $user = $this->user();

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post(route('login'), ['email' => $user->email, 'password' => 'not-the-password'])
                ->assertStatus(302);
        }

        // Fortify's login route carries throttle:login, so the sixth attempt is a 429
        // rendered by errors/429.blade.php rather than a flashed banner message.
        $this->post(route('login'), ['email' => $user->email, 'password' => 'not-the-password'])
            ->assertStatus(429);
    }

    public function test_an_unknown_reset_email_is_reported_by_the_banner(): void
    {
        $this->post(route('password.email'), ['email' => 'nobody@example.test']);

        $html = $this->get(route('password.request'))->getContent();

        $this->assertStringContainsString('find a user with that email address.', $html);
        $this->assertSame(1, $this->occurrences($html, 'find a user with that email address.'));
    }

    public function test_a_sent_reset_link_renders_a_success_banner(): void
    {
        $user = $this->user();

        $this->post(route('password.email'), ['email' => $user->email]);

        $html = $this->get(route('password.request'))->getContent();

        $this->assertStringContainsString('emailed your password reset link.', $html);
        $this->assertStringContainsString('border-emerald-500/25', $html);
    }

    public function test_an_invalid_reset_token_is_reported_by_the_banner(): void
    {
        $user = $this->user();

        $this->post(route('password.update'), [
            'token' => 'expired-or-unknown-token',
            'email' => $user->email,
            'password' => self::VALID_PASSWORD,
            'password_confirmation' => self::VALID_PASSWORD,
        ]);

        $html = $this->get(route('password.reset', 'expired-or-unknown-token'))->getContent();

        $this->assertStringContainsString('password reset token is invalid.', $html);
        $this->assertSame(1, $this->occurrences($html, 'password reset token is invalid.'));
    }

    public function test_weak_password_failures_split_between_the_banner_and_the_field(): void
    {
        $user = $this->user();
        $token = Password::broker()->createToken($user);

        $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $html = $this->get(route('password.reset', $token))->getContent();

        $this->assertStringContainsString('data-auth-alert', $html);
        $this->assertStringContainsString('id="password-error"', $html);
        $this->assertSame(1, $this->occurrences($html, 'The password must be at least 12 characters'));
    }

    public function test_a_completed_reset_announces_itself_on_the_login_page(): void
    {
        $user = $this->user();
        $token = Password::broker()->createToken($user);

        $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'Rotation!Panels77',
            'password_confirmation' => 'Rotation!Panels77',
        ]);

        $html = $this->get(route('login'))->getContent();

        $this->assertStringContainsString('password has been reset.', $html);
        $this->assertStringContainsString('border-emerald-500/25', $html);
    }

    public function test_the_login_inputs_turn_red_when_the_credentials_are_rejected(): void
    {
        $user = $this->user();

        $this->post(route('login'), ['email' => $user->email, 'password' => 'not-the-password']);

        $html = $this->get(route('login'))->getContent();

        $this->assertStringContainsString('border-[#DC2626]', $html);
    }

    public function test_a_clean_login_page_carries_no_banner(): void
    {
        $html = $this->get(route('login'))->getContent();

        $this->assertStringNotContainsString('data-auth-alert', $html);
        $this->assertStringNotContainsString('id="email-error"', $html);
    }
}
