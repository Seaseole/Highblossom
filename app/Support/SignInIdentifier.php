<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

/**
 * One field that takes either a username or an email address.
 *
 * The detection is deliberately dumb — an address is anything with an @ that parses as an
 * email, and everything else is a username — because it runs before any lookup and must
 * not be able to tell the caller which branch was taken. The two branches cost the same:
 * a name that matches nothing still pays for a bcrypt comparison against a fixed dummy
 * hash, so "no such user" and "wrong password" arrive after the same delay and carry the
 * same sentence. Without that, the response time alone confirms whether an account exists
 * before a single credential is checked.
 *
 * Fortify still calls the login field `email` (config/fortify.php `username`), so that
 * stays the wire format; only what it accepts changes.
 */
final class SignInIdentifier
{
    /**
     * A throwaway hash burned only to spend the same time a real verification would.
     *
     * Not anyone's password. It exists so an unknown identifier cannot be answered
     * instantly; cost 12 matches the configured bcrypt rounds, so the delay is the delay a
     * real account would have caused.
     */
    private const TIMING_HASH = '$2y$12$f6L5vGWevv1GIGeAVtOKue5WoPylSVRIcQ9Owx6icfLbaR9dZIy4S';

    /**
     * Whether the typed value should be read as an email address.
     */
    public static function isEmail(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Lowercase and trim the typed value, the same way the stored columns are held.
     */
    public static function normalize(string $value): string
    {
        return Str::lower(trim($value));
    }

    /**
     * The account this identifier points at, if any.
     *
     * Case-insensitive on both halves: email is compared by the utf8mb4_unicode_ci
     * collation the column already carries, and usernames are only ever stored lowercase.
     */
    public static function find(string $value): ?User
    {
        $value = self::normalize($value);

        if ($value === '') {
            return null;
        }

        $query = User::query();

        if (self::isEmail($value)) {
            $query->where('email', $value);
        } else {
            $query->where('username', $value);
        }

        return $query->first();
    }

    /**
     * Verify a password against the account, burning the same time when there is none.
     *
     * @return array{0: bool, 1: User|null}
     */
    public static function verify(string $value, string $password): array
    {
        $user = self::find($value);

        if ($user === null) {
            // The result is discarded on purpose. This exists for its cost, not its answer.
            Hash::check($password, self::TIMING_HASH);

            return [false, null];
        }

        return [Hash::check($password, $user->password), $user];
    }

    /**
     * The throttle key for a sign-in attempt.
     *
     * Keyed on the account rather than the string typed, so switching an email and its own
     * username between attempts lands in the same bucket — with the raw value as the key,
     * two identifiers for one account would double the allowance, and that is true today
     * for anyone who typos an address and retypes it differently. An identifier that
     * resolves to nothing falls back to its lowercased form, which keeps an attacker
     * sweeping names from collapsing every bucket into one and locking out the whole site.
     *
     * `|` separates the two halves because no identifier can contain it: it is neither an
     * email character nor an allowed username character, so no pair can forge another's key.
     */
    public static function throttleKey(string $value, string $ip): string
    {
        $value = self::normalize($value);
        $canonical = self::find($value)?->email ?? $value;

        return Str::transliterate($canonical.'|'.$ip);
    }

    /**
     * Rules for the login field: accept either identifier shape, reject anything else.
     *
     * The closure runs after format checks, so a malformed address is still named as an
     * address rather than answered as a credential failure — the distinction
     * LoginRequest was written to keep.
     *
     * @return array<string, list<mixed>>
     */
    public static function rules(): array
    {
        return [
            Fortify::username() => ['bail', 'required', 'string', 'max:255', function (string $attribute, mixed $value, \Closure $fail): void {
                $value = self::normalize((string) $value);

                if ($value === '') {
                    $fail('Enter your username or email address.');

                    return;
                }

                if (self::isEmail($value)) {
                    return;
                }

                if (! Username::isValid($value)) {
                    $fail('Enter a complete email address, or a username of '.Username::MIN_LENGTH.' to '.Username::MAX_LENGTH.' letters, numbers, dots, underscores or hyphens.');
                }
            }],
            'password' => ['bail', 'required', 'string'],
        ];
    }
}
