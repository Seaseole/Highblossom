<?php

declare(strict_types=1);

namespace App\Services;

use InvalidArgumentException;

/**
 * Service for reading and writing .env configuration files.
 *
 * Writing is intentionally restricted to a fixed allowlist of non-secret keys
 * via {@see setEditable()} and {@see displayable()}. The raw {@see set()} is
 * kept public because trusted services (e.g. the SMTP editor) legitimately
 * write MAIL_* keys, but it never renders values back to the browser and
 * validates the key name so untrusted input cannot inject arbitrary keys.
 */
final class EnvEditor
{
    /**
     * Environment keys that admins may view and edit from the settings UI.
     *
     * Secrets (APP_KEY, DB_*, REDIS_*, MAIL_*, session/queue drivers, etc.)
     * are deliberately excluded so they are never exposed to the browser.
     *
     * @var list<string>
     */
    public const SAFE_EDITABLE_KEYS = [
        'APP_NAME',
        'APP_URL',
        'APP_TIMEZONE',
        'APP_LOCALE',
        'APP_FAKER_LOCALE',
        'APP_FALLBACK_LOCALE',
        'FEATURES_REGISTRATION_ENABLED',
    ];

    /** @var string Path to the .env file */
    private string $path;

    public function __construct(?string $path = null)
    {
        $this->path = $path ?? base_path('.env');
    }

    /**
     * Determine whether an environment key is safe to expose to the settings UI.
     */
    public static function isSafeEditableKey(string $key): bool
    {
        return in_array($key, self::SAFE_EDITABLE_KEYS, true)
            || str_starts_with($key, 'FEATURES_');
    }

    /**
     * Get a value from the .env file.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->all()[$key] ?? $default;
    }

    /**
     * Get only the safe, non-secret editable key-value pairs for display.
     *
     * @return array<string, string>
     */
    public function displayable(): array
    {
        return array_filter(
            $this->all(),
            static fn (string $key): bool => self::isSafeEditableKey($key),
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * Set a value only when its key is in the admin allowlist.
     *
     * Untrusted request input must go through this method, never set() directly.
     *
     * @return bool Whether the key was accepted and written.
     */
    public function setEditable(string $key, string $value): bool
    {
        if (! self::isSafeEditableKey($key)) {
            return false;
        }

        $this->set($key, $value);

        return true;
    }

    /**
     * Set a value in the .env file, creating or replacing the key.
     *
     * @throws InvalidArgumentException when the key name is not a valid env identifier.
     */
    public function set(string $key, mixed $value): void
    {
        if (! preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $key)) {
            throw new InvalidArgumentException("Invalid environment key [{$key}].");
        }

        if (! file_exists($this->path)) {
            return;
        }

        $lines = file($this->path, FILE_IGNORE_NEW_LINES);
        $replaced = false;
        $newValue = $this->quote((string) $value);

        foreach ($lines as $index => $line) {
            if (str_starts_with(trim($line), '#')) {
                continue;
            }

            [$envKey] = $this->parseLine($line);

            if ($envKey === $key) {
                $lines[$index] = "{$key}={$newValue}";
                $replaced = true;
                break;
            }
        }

        if (! $replaced) {
            $lines[] = "{$key}={$newValue}";
        }

        $this->writeAtomically(implode(PHP_EOL, $lines).PHP_EOL);
    }

    /**
     * Get all key-value pairs from the .env file.
     *
     * @return array<string, mixed>
     */
    public function all(): array
    {
        if (! file_exists($this->path)) {
            return [];
        }

        $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $result = [];

        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) {
                continue;
            }

            [$key, $value] = $this->parseLine($line);

            if ($key !== '') {
                $result[$key] = $this->unquote($value);
            }
        }

        return $result;
    }

    /**
     * Parse a single .env line into key and value.
     *
     * @return array{0: string, 1: string}
     */
    private function parseLine(string $line): array
    {
        $trimmed = trim($line);

        // If the line has no '=' but contains text, treat it as a key with an empty value.
        if (! str_contains($trimmed, '=')) {
            return [$trimmed, ''];
        }

        $parts = explode('=', $trimmed, 2);

        return [trim($parts[0]), trim($parts[1])];
    }

    /**
     * Persist contents atomically: write a temp file then rename over .env.
     */
    private function writeAtomically(string $contents): void
    {
        $tmp = $this->path.'.tmp.'.bin2hex(random_bytes(6));

        file_put_contents($tmp, $contents, LOCK_EX);
        rename($tmp, $this->path);
    }

    /**
     * Quote a value only when it contains characters that are unsafe in an
     * unquoted dotenv line. Simple tokens are left unquoted so dotenv keeps
     * casting keywords like true/false/null to their scalar values.
     */
    private function quote(string $value): string
    {
        if (! preg_match('/[\s"#$\'\\\\`]/', $value)) {
            return $value;
        }

        $escaped = str_replace(['\\', '"', '$', "\n", "\r", "\t"], ['\\\\', '\"', '\$', '\n', '', '\t'], $value);

        return '"'.$escaped.'"';
    }

    /**
     * Remove surrounding double quotes and reverse the escaping applied by quote().
     */
    private function unquote(string $value): string
    {
        if (! (str_starts_with($value, '"') && str_ends_with($value, '"') && strlen($value) >= 2)) {
            return $value;
        }

        $inner = substr($value, 1, -1);

        return preg_replace_callback(
            '/\\\\(.)/',
            static fn (array $m): string => match ($m[1]) {
                'n' => "\n",
                'r' => '',
                't' => "\t",
                default => $m[1],
            },
            $inner
        );
    }
}
