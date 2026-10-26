<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\TwoFactorAuthenticationProvider;

/**
 * Stateless two-factor challenges for the staff mobile API.
 *
 * The web panel keeps the "waiting for a second factor" state in the session
 * (Fortify stores `login.id`). Sanctum requests are stateless, so the same
 * state is carried by an opaque, single-use, short-lived challenge id held in
 * the cache. Only the user id is stored — never a TOTP secret, code, or the
 * submitted code itself.
 *
 * Without this, `POST /api/staff/login` would mint a token on the password
 * alone, making the mobile app a way around the second factor the web admin
 * requires.
 */
final readonly class StaffTwoFactorChallengeService
{
    /**
     * How long an issued challenge stays valid, in seconds.
     */
    private const int TTL_SECONDS = 600;

    /**
     * Wrong codes tolerated before the challenge is burned.
     */
    private const int MAX_ATTEMPTS = 5;

    /**
     * Cache key for a challenge id.
     *
     * @param string $challenge Opaque challenge identifier
     */
    private function key(string $challenge): string
    {
        return 'staff:2fa:challenge:'.$challenge;
    }

    /**
     * Cache key counting failed attempts against a challenge.
     *
     * @param string $challenge Opaque challenge identifier
     */
    private function attemptsKey(string $challenge): string
    {
        return $this->key($challenge).':attempts';
    }

    /**
     * Begin a challenge for a user who has two-factor authentication enabled.
     *
     * @param User $user The account whose second factor is pending
     *
     * @return string The challenge id to hand back to the client
     */
    public function issue(User $user): string
    {
        $challenge = (string) Str::uuid();

        Cache::put($this->key($challenge), $user->id, self::TTL_SECONDS);

        return $challenge;
    }

    /**
     * Resolve the account a challenge belongs to.
     *
     * @param string $challenge Opaque challenge identifier
     *
     * @return User|null Null when the challenge is unknown or has expired
     */
    public function userFor(string $challenge): ?User
    {
        $userId = Cache::get($this->key($challenge));

        return $userId === null ? null : User::find($userId);
    }

    /**
     * Verify a submitted code or recovery code, consuming the challenge.
     *
     * A used recovery code is rotated, matching the web panel. A wrong code is
     * counted, and the challenge is burned once MAX_ATTEMPTS is reached, so a
     * stolen challenge id cannot be ground through the TOTP space.
     *
     * @param string      $challenge    Opaque challenge identifier
     * @param string|null $code         Six-digit authenticator code, if supplied
     * @param string|null $recoveryCode One-time recovery code, if supplied
     *
     * @return bool True when the second factor checks out
     */
    public function verify(string $challenge, ?string $code, ?string $recoveryCode): bool
    {
        $user = $this->userFor($challenge);

        if ($user === null) {
            return false;
        }

        if ($recoveryCode !== null && $this->matchesRecoveryCode($user, $recoveryCode)) {
            $user->replaceRecoveryCode($recoveryCode);
            $this->revoke($challenge);

            return true;
        }

        $verified = false;

        if ($code !== null && $user->two_factor_secret !== null) {
            $verified = app(TwoFactorAuthenticationProvider::class)->verify(
                Fortify::currentEncrypter()->decrypt($user->two_factor_secret),
                $code,
            );
        }

        if ($verified) {
            $this->revoke($challenge);
        } else {
            $this->registerFailure($challenge);
        }

        return $verified;
    }

    /**
     * Discard a challenge so it cannot be replayed.
     *
     * @param string $challenge Opaque challenge identifier
     */
    public function revoke(string $challenge): void
    {
        Cache::forget($this->key($challenge));
        Cache::forget($this->attemptsKey($challenge));
    }

    /**
     * Failed attempts recorded against a challenge so far.
     *
     * @param string $challenge Opaque challenge identifier
     */
    public function attempts(string $challenge): int
    {
        return (int) Cache::get($this->attemptsKey($challenge), 0);
    }

    /**
     * Record one failed attempt, burning the challenge once the cap is reached.
     *
     * @param string $challenge Opaque challenge identifier
     */
    private function registerFailure(string $challenge): void
    {
        $key = $this->attemptsKey($challenge);
        $attempts = $this->attempts($challenge);

        if (Cache::has($key)) {
            $attempts = (int) Cache::increment($key);
        } else {
            Cache::put($key, 1, self::TTL_SECONDS);
            $attempts = 1;
        }

        if ($attempts >= self::MAX_ATTEMPTS) {
            $this->revoke($challenge);
        }
    }

    /**
     * Compare a recovery code against the stored set in constant time.
     *
     * @param User   $user         The challenged account
     * @param string $recoveryCode Candidate code
     */
    private function matchesRecoveryCode(User $user, string $recoveryCode): bool
    {
        if ($user->two_factor_recovery_codes === null) {
            return false;
        }

        foreach ($user->recoveryCodes() as $stored) {
            if (is_string($stored) && hash_equals($stored, $recoveryCode)) {
                return true;
            }
        }

        return false;
    }
}
