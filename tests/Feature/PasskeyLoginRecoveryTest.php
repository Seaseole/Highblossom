<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\Auth\ResolvePasskeyLoginFailure;
use App\Livewire\PasskeyRecoveryBanner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Passkeys\Exceptions\InvalidPasskeyException;
use Livewire\Livewire;
use ParagonIE\ConstantTime\Base64UrlSafe;
use Tests\TestCase;

/**
 * Covers the recovery path for a device that offers a passkey the account no longer has.
 */
class PasskeyLoginRecoveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_renders_the_recovery_panels(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('data-passkey-recovery="unrecognized_passkey"', false)
            ->assertSee('data-passkey-signin', false);
    }

    public function test_unrecognized_passkey_is_reported_as_a_recoverable_reason(): void
    {
        $this->startPasskeyChallenge();

        $response = $this->postJson(route('passkey.login'), [
            'credential' => $this->assertionPayload($this->unknownCredentialId()),
            'remember' => false,
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('reason', ResolvePasskeyLoginFailure::REASON_UNRECOGNIZED)
            ->assertJsonPath('message', __('auth.login.passkey.recovery.unrecognized_passkey.message'))
            ->assertSessionHas(ResolvePasskeyLoginFailure::REENROLL_SESSION_KEY);

        $this->assertGuest();
    }

    public function test_expired_challenge_is_reported_instead_of_an_unknown_passkey(): void
    {
        $credentialId = $this->knownCredentialId();

        $response = $this->postJson(route('passkey.login'), [
            'credential' => $this->assertionPayload($credentialId),
            'remember' => false,
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('reason', ResolvePasskeyLoginFailure::REASON_EXPIRED_SESSION)
            ->assertJsonPath('message', __('auth.login.passkey.recovery.expired_passkey_session.message'))
            ->assertSessionMissing(ResolvePasskeyLoginFailure::REENROLL_SESSION_KEY);
    }

    public function test_known_credential_rejected_by_the_assertion_is_reported_as_a_failure(): void
    {
        $this->knownCredentialId();

        $request = Request::create('/passkeys/login', 'POST', [
            'credential' => ['rawId' => 'known-credential-id'],
        ]);
        $request->setLaravelSession(app('session.store'));

        $failure = (new ResolvePasskeyLoginFailure)(
            $request,
            InvalidPasskeyException::make('Signature verification failed')
        );

        $this->assertSame(ResolvePasskeyLoginFailure::REASON_FAILED, $failure['reason']);
    }

    public function test_reenrol_banner_shows_once_and_can_be_dismissed(): void
    {
        session([ResolvePasskeyLoginFailure::REENROLL_SESSION_KEY => true]);

        Livewire::test(PasskeyRecoveryBanner::class)
            ->assertSet('visible', true)
            ->assertSee(__('auth.passkey_reenroll.title'))
            ->call('dismiss')
            ->assertSet('visible', false)
            ->assertDontSee(__('auth.passkey_reenroll.title'));

        $this->assertNull(session()->get(ResolvePasskeyLoginFailure::REENROLL_SESSION_KEY));
    }

    /**
     * Store a WebAuthn challenge in the session, exactly as the login options route does.
     */
    private function startPasskeyChallenge(): void
    {
        $this->getJson(route('passkey.login-options'))->assertOk();
    }

    private function unknownCredentialId(): string
    {
        return Base64UrlSafe::encodeUnpadded(random_bytes(32));
    }

    /**
     * Persist a passkey row and return its credential identifier.
     */
    private function knownCredentialId(): string
    {
        $user = User::factory()->create();

        DB::table('passkeys')->insert([
            'user_id' => $user->id,
            'name' => 'Known passkey',
            'credential_id' => 'known-credential-id',
            'credential' => json_encode(['publicKey' => ['credentialId' => 'known-credential-id']]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return 'known-credential-id';
    }

    /**
     * A well-formed assertion payload for the given credential identifier.
     *
     * @return array<string, mixed>
     */
    private function assertionPayload(string $credentialId): array
    {
        return [
            'id' => $credentialId,
            'rawId' => $credentialId,
            'type' => 'public-key',
            'response' => [
                'clientDataJSON' => Base64UrlSafe::encodeUnpadded(
                    json_encode(['type' => 'webauthn.get', 'challenge' => 'challenge', 'origin' => 'http://localhost'])
                ),
                'authenticatorData' => Base64UrlSafe::encodeUnpadded(str_repeat("\x00", 37)),
                'signature' => Base64UrlSafe::encodeUnpadded('signature'),
                'userHandle' => null,
            ],
        ];
    }
}
