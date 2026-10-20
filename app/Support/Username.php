<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * The username as a sign-in identifier: its shape, its reserved names, and how one is
 * minted for an account that predates the column.
 *
 * Stored lowercase and validated case-insensitively, because the users table compares
 * with utf8mb4_unicode_ci and MySQL's LIKE is case-insensitive too. A username saved as
 * "Eugene" would then be reachable as "eugene" while no rule anywhere had promised that,
 * and a second person could not claim it. Normalising on the way in is the only version
 * of this where the stored value is the value that gets looked up.
 *
 * The format is deliberately narrow: a username is handed to a login form and to other
 * people, so characters that survive a copy through email or a chat window are the ones
 * worth allowing.
 */
final class Username
{
    public const MIN_LENGTH = 3;

    public const MAX_LENGTH = 30;

    /**
     * Letters, numbers, and the three separators that survive being typed on a phone.
     *
     * First character must be a letter or a number, so no username begins as punctuation.
     */
    public const PATTERN = '/^[a-z0-9][a-z0-9._-]{'.(self::MIN_LENGTH - 1).','.(self::MAX_LENGTH - 1).'}$/';

    /**
     * Names a person must not hold, because they read as the site speaking rather than a
     * user. This is a namespace guard, not a security control: none of these values grant
     * anything, and authorisation lives in the roles table.
     *
     * @var list<string>
     */
    public const RESERVED = [
        'admin', 'administrator', 'api', 'root', 'owner', 'moderator', 'staff', 'system',
        'support', 'help', 'info', 'contact', 'sales', 'service', 'office', 'abuse',
        'webmaster', 'postmaster', 'hostmaster', 'noreply', 'no-reply', 'reply',
        'security', 'privacy', 'terms', 'legal', 'billing', 'payment', 'payments',
        'login', 'logout', 'signin', 'sign-in', 'signup', 'register', 'password',
        'forgot', 'reset', 'settings', 'profile', 'account', 'dashboard', 'console',
        'www', 'www1', 'app', 'apps', 'cdn', 'static', 'assets', 'public', 'mail',
        'email', 'highblossom', 'high', 'blossom', 'high-blossom', 'highblossompty',
        'null', 'false', 'true', 'undefined', 'test', 'demo', 'example', 'root-1',
    ];

    /**
     * Lowercase and trim, the only form the column is allowed to hold.
     */
    public static function normalize(?string $value): string
    {
        return Str::lower(trim((string) $value));
    }

    /**
     * Whether the value is shaped like a username.
     */
    public static function isValid(string $value): bool
    {
        return preg_match(self::PATTERN, self::normalize($value)) === 1;
    }

    /**
     * Whether the name is held back.
     */
    public static function isReserved(string $value): bool
    {
        return in_array(self::normalize($value), self::RESERVED, true);
    }

    /**
     * Validation rules for a username field.
     *
     * `unique` runs against the stored (already lowercased) value, so the caller must
     * normalise the input before validating — see the prepareForValidation() on each form
     * that carries the field.
     *
     * `$required` is false on the two edit surfaces on purpose. A profile save posts the
     * whole record — name, email, phone, avatar — so a username that became mandatory there
     * would block a person whose account has not been backfilled yet from changing their
     * phone number, for a reason the form gives them no way to guess. Creating an account is
     * the opposite case: a brand-new one should not be born without an identifier.
     *
     * @return list<mixed>
     */
    public static function rules(?int $ignoreUserId = null, bool $required = true): array
    {
        $unique = $ignoreUserId === null
            ? Rule::unique(User::class, 'username')
            : Rule::unique(User::class, 'username')->ignore($ignoreUserId);

        return [
            $required ? 'required' : 'nullable',
            'string',
            'min:'.self::MIN_LENGTH,
            'max:'.self::MAX_LENGTH,
            'regex:'.self::PATTERN,
            Rule::notIn(self::RESERVED),
            $unique,
        ];
    }

    /**
     * The sentence that describes the format, used by the field hint and by the rejection.
     */
    public static function formatHint(): string
    {
        return self::MIN_LENGTH.' to '.self::MAX_LENGTH.' characters, using letters, numbers, dots, underscores or hyphens, starting with a letter or a number';
    }

    /**
     * A deterministic username for an account that predates the column.
     *
     * Built from the local part of the email because that is the only handle the account
     * already owns, then walked forward through numeric suffixes so two people sharing an
     * email local part land on different names. Processing in id order is what makes it
     * repeatable: the same database always yields the same set of names, so a preview can
     * be trusted and a half-finished run can be re-run without drifting.
     *
     * @param callable(string): bool $isTaken
     */
    public static function generateFromEmail(string $email, int $userId, callable $isTaken): ?string
    {
        $base = preg_replace('/[^a-z0-9._-]+/', '-', Str::lower(Str::before($email, '@'))) ?? '';
        $base = trim($base, '._-');

        // A local part made only of punctuation ("a.b@example.test" trimmed to "a.b" is
        // fine, but "+tag@" is not) has nothing to offer, so fall back to the id.
        if (Str::length($base) < self::MIN_LENGTH) {
            $base = 'user'.$userId;
        }

        // Reserve room for the worst suffix the loop can reach.
        $base = Str::substr($base, 0, self::MAX_LENGTH - 4);

        for ($suffix = 0; $suffix < 100; $suffix++) {
            $candidate = $suffix === 0 ? $base : $base.'-'.$suffix;

            if (self::isReserved($candidate)) {
                $candidate = 'u-'.$candidate;
            }

            if (Str::length($candidate) > self::MAX_LENGTH) {
                $candidate = Str::substr($candidate, 0, self::MAX_LENGTH);
            }

            if (self::isValid($candidate) && ! $isTaken($candidate)) {
                return $candidate;
            }
        }

        return null;
    }
}
