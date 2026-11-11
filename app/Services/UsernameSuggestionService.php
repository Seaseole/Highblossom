<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Services\Ai\AiSuggestionException;
use App\Services\Ai\DataTransferObjects\SuggestionRequest;
use App\Services\Ai\SuggestionBroker;
use App\Support\Username;
use Illuminate\Support\Str;

/**
 * Suggest usernames that are genuinely available at the moment of the request.
 *
 * A provider only supplies *ideas*. The last step is always local and
 * authoritative: normalise, check the shared Username rules, reject reserved
 * names, then subtract every matching row in one query. Suggestions are hints,
 * not reservations - Username::rules() still re-checks uniqueness on submit,
 * which is what closes the race between clicking a name and saving the form.
 */
final class UsernameSuggestionService
{
    /**
     * The shape of the answer is fixed here rather than left to the model.
     */
    private const SYSTEM_PROMPT = <<<'PROMPT'
You suggest account usernames. Return only a plain list, one username per line, with no numbering, bullets, quotes or explanation. Each must be 3 to 30 characters, start with a letter or digit, and use only lowercase letters, digits, dots, underscores and hyphens. Avoid words that read as the site speaking (admin, support, billing, security and similar). Do not include email addresses, phone numbers or offensive terms.
PROMPT;

    /**
     * @param SuggestionBroker $broker The shared flag, cache and failure gate.
     */
    public function __construct(private readonly SuggestionBroker $broker) {}

    /**
     * Suggest available usernames for a person being created or edited.
     *
     * @param string   $name         The name currently typed in the form.
     * @param string   $email        The email currently typed in the form.
     * @param int      $count        Maximum number of names to return.
     * @param int|null $ignoreUserId The record being edited, whose own name is free.
     *
     * @return list<string> Verified-available names, possibly fewer than $count.
     *
     * @throws AiSuggestionException When suggestions are off or the provider fails.
     */
    public function suggest(string $name, string $email, int $count, ?int $ignoreUserId = null): array
    {
        $count = max(1, min($count, 10));
        $seed = $this->seed($name, $email);
        $pool = max($count * 3, (int) config('ai.usernames.pool', 18));
        $fallback = $this->localCandidates($seed, $pool);

        $request = new SuggestionRequest(
            system: self::SYSTEM_PROMPT,
            prompt: implode("\n", [
                'Suggest '.$pool.' distinct username ideas inspired by these values.',
                'Name: '.$this->clean($name),
                'Email local part: '.$this->clean(Str::lower(Str::before($email, '@'))),
                'Seed: '.$seed,
            ]),
            model: (string) config('ai.model'),
            maxTokens: max(1, (int) config('ai.max_tokens', 300)),
            fallbackCandidates: $fallback,
            temperature: (float) config('ai.temperature', 0.7),
        );

        $candidates = array_merge($this->broker->suggest($request)->all(), $fallback);

        return $this->available($candidates, $count, $ignoreUserId);
    }

    /**
     * Build one safe seed from whichever identifying field the form carries.
     *
     * @param string $name  The name currently typed in the form.
     * @param string $email The email currently typed in the form.
     *
     * @return string A lowercase seed that fits inside a username with room for a suffix.
     */
    private function seed(string $name, string $email): string
    {
        $value = trim($this->clean($name));

        if ($value === '') {
            $value = Str::lower(Str::before($email, '@'));
        }

        $value = preg_replace('/[^a-z0-9._-]+/', '-', Str::lower($value)) ?? '';
        $value = trim($value, '._-');

        if (Str::length($value) < Username::MIN_LENGTH) {
            $value = 'user';
        }

        if (Username::isReserved($value)) {
            $value = 'u-'.$value;
        }

        return Str::substr($value, 0, Username::MAX_LENGTH - 4);
    }

    /**
     * Deterministic ideas used by the offline client and as a live fallback.
     *
     * @param string $seed The cleaned, capped seed.
     * @param int    $pool How many ideas to produce.
     *
     * @return list<string> Unfiltered candidate strings.
     */
    private function localCandidates(string $seed, int $pool): array
    {
        $candidates = [$seed];

        for ($suffix = 2; count($candidates) < $pool; $suffix++) {
            foreach (['-', '.', '_'] as $separator) {
                $candidates[] = $seed.$separator.$suffix;

                if (count($candidates) >= $pool) {
                    break 2;
                }
            }
        }

        return $candidates;
    }

    /**
     * Keep only candidates that pass every local rule and are not in the database.
     *
     * @param list<string> $candidates   Raw provider and local candidates.
     * @param int          $count        Maximum number of names to return.
     * @param int|null     $ignoreUserId The record being edited, whose own name is free.
     *
     * @return list<string> Verified-available names.
     */
    private function available(array $candidates, int $count, ?int $ignoreUserId): array
    {
        $unique = [];

        foreach ($candidates as $candidate) {
            if (! is_string($candidate)) {
                continue;
            }

            $value = Username::normalize($this->stripListMarker($candidate));

            if (! Username::isValid($value) || Username::isReserved($value)) {
                continue;
            }

            $unique[$value] = true;
        }

        if ($unique === []) {
            return [];
        }

        $taken = User::query()
            ->whereIn('username', array_keys($unique))
            ->when($ignoreUserId !== null, fn ($query) => $query->whereKeyNot($ignoreUserId))
            ->pluck('username')
            ->all();

        foreach ($taken as $username) {
            unset($unique[Username::normalize((string) $username)]);
        }

        return array_slice(array_keys($unique), 0, $count);
    }

    /**
     * Remove the numbering or quoting a model often adds to a plain list.
     *
     * @param string $value One raw candidate line.
     *
     * @return string The candidate without list decoration.
     */
    private function stripListMarker(string $value): string
    {
        $value = trim($value, " \t\n\r\0\x0B\"'`");

        return preg_replace('/^(?:\d+[.)]|[-*\x{2022}])\s*/u', '', $value) ?? $value;
    }

    /**
     * Strip markup and redact contact details before a value reaches a prompt.
     *
     * @param string $value Raw form text.
     *
     * @return string Safe, single-line text.
     */
    private function clean(string $value): string
    {
        $value = strip_tags($value);
        $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $value) ?? '';
        $value = preg_replace('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}/iu', '[email redacted]', $value) ?? $value;
        $value = preg_replace('/(?:\+?\d[\d\s().-]{7,}\d)/u', '[phone redacted]', $value) ?? $value;
        $value = preg_replace('/\s+/u', ' ', $value) ?? $value;

        return trim(Str::substr(trim($value), 0, 100));
    }
}
