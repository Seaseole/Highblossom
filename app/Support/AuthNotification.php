<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

/**
 * Decide which authentication message the notification banner owns and which
 * stay with their input.
 *
 * Fortify reports credential and lockout failures on the email key
 * (AttemptToAuthenticate, LockoutResponse), so a bag key cannot tell a page-level
 * "these credentials do not match" apart from a plain "the email is required".
 * The split is made on the translated message instead, which keeps the banner and
 * the inline hints from showing the same sentence twice.
 */
final class AuthNotification
{
    /**
     * Translation keys whose messages describe the submission as a whole.
     *
     * Fortify emits the two-factor and password-confirmation failures as inline
     * JSON strings rather than under an `auth.*` key, and there is no
     * `lang/en.json` here, so translating the sentence returns the sentence.
     *
     * @var list<string>
     */
    private const PAGE_LEVEL_KEYS = [
        'auth.failed',
        'auth.throttle',
        'auth.password',
        'passwords.user',
        'passwords.token',
        'passwords.sent',
        'passwords.reset',
        'passwords.throttled',
        'The provided two factor authentication code was invalid.',
        'The provided two factor recovery code was invalid.',
        'The provided password was incorrect.',
    ];

    /**
     * Session flash keys mapped to the banner type they imply.
     *
     * @var array<string, string>
     */
    private const FLASH_KEYS = [
        'status' => 'success',
        'success' => 'success',
        'info' => 'info',
        'warning' => 'warning',
        'error' => 'error',
    ];

    /**
     * Banner content for the current request, or null when there is nothing to show.
     *
     * @return array{type: string, message: string}|null
     */
    public static function fromSession(): ?array
    {
        $session = request()->session();

        foreach (self::FLASH_KEYS as $key => $type) {
            $message = $session->get($key);

            if (is_string($message) && $message !== '') {
                return ['type' => $type, 'message' => $message];
            }
        }

        $messages = self::bag()->all();

        if ($messages === []) {
            return null;
        }

        $pageLevel = array_values(array_filter($messages, fn (string $message): bool => self::isPageLevel($message)));

        return ['type' => 'error', 'message' => $pageLevel[0] ?? $messages[0]];
    }

    /**
     * Messages for one input that the banner has not already taken.
     *
     * @return list<string>
     */
    public static function fieldMessages(string $field): array
    {
        $banner = self::fromSession()['message'] ?? null;

        return array_values(array_filter(
            self::bag()->get($field),
            fn (string $message): bool => $message !== $banner && ! self::isPageLevel($message)
        ));
    }

    /**
     * Determine whether a translated message belongs to the whole form.
     */
    public static function isPageLevel(string $message): bool
    {
        foreach (self::PAGE_LEVEL_KEYS as $key) {
            $template = (string) trans($key);

            if ($template === $message) {
                return true;
            }

            $prefix = self::placeholderPrefix($template);

            if ($prefix !== '' && str_starts_with($message, $prefix)) {
                return true;
            }
        }

        return false;
    }

    /**
     * The stable text ahead of the first placeholder in a translation template,
     * so interpolated messages such as the lockout timer still match.
     */
    private static function placeholderPrefix(string $template): string
    {
        $position = strpos($template, ':');

        return $position === false ? '' : rtrim(substr($template, 0, $position));
    }

    /**
     * The flashed validation messages for the current request.
     */
    private static function bag(): MessageBag
    {
        $errors = request()->session()->get('errors');

        return $errors instanceof ViewErrorBag ? $errors->getBag('default') : new MessageBag;
    }
}
