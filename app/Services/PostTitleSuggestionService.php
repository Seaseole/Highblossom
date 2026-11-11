<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Post;
use App\Services\Ai\AiSuggestionException;
use App\Services\Ai\DataTransferObjects\SuggestionRequest;
use App\Services\Ai\DataTransferObjects\TitleSuggestionContext;
use App\Services\Ai\SuggestionBroker;
use Illuminate\Support\Str;

/**
 * Produce a short list of usable post headlines from an editor's draft.
 *
 * Model output is untrusted input, so the last word always belongs to this
 * class: strip markup, collapse whitespace, cap at Post::TITLE_MAX, remove
 * duplicates and working/existing titles, and never return more than the
 * configured count. Deterministic local candidates are merged into the live
 * answer as well, so a thin completion never leaves the button empty.
 */
final class PostTitleSuggestionService
{
    /**
     * The shape of the answer is fixed here rather than left to the model.
     */
    private const SYSTEM_PROMPT = <<<'PROMPT'
You write headline options for a company blog. Return only a plain list, one headline per line, with no numbering, bullets, quotes, prefixes or explanation. Each headline must be a single line of at most 120 characters, written in the language named in the brief, factual in tone, and free of HTML, emoji and clickbait. Do not repeat a headline already listed in the brief.
PROMPT;

    /**
     * @param SuggestionBroker $broker The shared flag, cache and failure gate.
     */
    public function __construct(private readonly SuggestionBroker $broker) {}

    /**
     * Suggest headlines for the given brief.
     *
     * @param TitleSuggestionContext $context The bounded, redacted brief.
     *
     * @return list<string> Between zero and the configured count of titles.
     *
     * @throws AiSuggestionException When suggestions are off or the provider fails.
     */
    public function suggest(TitleSuggestionContext $context): array
    {
        $count = max(1, (int) config('ai.titles.count', 5));
        $fallback = $this->localCandidates($context, $count);

        $request = new SuggestionRequest(
            system: self::SYSTEM_PROMPT,
            prompt: $context->prompt(),
            model: (string) config('ai.model'),
            maxTokens: max(1, (int) config('ai.max_tokens', 300)),
            fallbackCandidates: $fallback,
            temperature: (float) config('ai.temperature', 0.7),
        );

        $candidates = array_merge($this->broker->suggest($request)->all(), $fallback);

        return $this->postProcess($candidates, $context, $count);
    }

    /**
     * Deterministic ideas used by the offline client and as a live fallback.
     *
     * @param TitleSuggestionContext $context The bounded, redacted brief.
     * @param int                    $count   How many to generate.
     *
     * @return list<string> Unfiltered candidate strings.
     */
    private function localCandidates(TitleSuggestionContext $context, int $count): array
    {
        $seed = $context->workingTitle !== ''
            ? $context->workingTitle
            : ($context->excerpt !== '' ? $context->excerpt : 'A new post');

        $seed = trim(Str::substr($seed, 0, 60));
        $lower = Str::lower($seed);

        $ideas = [
            $seed,
            $seed.' — a practical guide',
            'What to know about '.$lower,
            $seed.': key takeaways',
            'Understanding '.$lower,
            'A closer look at '.$lower,
        ];

        return array_slice($ideas, 0, max(1, min($count + 1, count($ideas))));
    }

    /**
     * Turn raw lines into a clean, de-duplicated list of valid titles.
     *
     * @param list<string>           $candidates Raw provider and local candidates.
     * @param TitleSuggestionContext $context    The bounded, redacted brief.
     * @param int                    $count      Maximum number of titles to return.
     *
     * @return list<string> Validated titles, never longer than Post::TITLE_MAX.
     */
    private function postProcess(array $candidates, TitleSuggestionContext $context, int $count): array
    {
        $normalised = [];

        foreach ($candidates as $candidate) {
            if (! is_string($candidate)) {
                continue;
            }

            $title = $this->normalise($candidate);

            if ($title !== '') {
                $normalised[] = $title;
            }
        }

        if ($normalised === []) {
            return [];
        }

        $taken = Post::query()
            ->whereIn('title', array_slice(array_values(array_unique($normalised)), 0, 60))
            ->pluck('title')
            ->all();

        $existing = [];

        foreach ($taken as $title) {
            $existing[mb_strtolower(trim((string) $title))] = true;
        }

        $working = mb_strtolower(trim($context->workingTitle));
        $seen = [];
        $titles = [];

        foreach ($normalised as $title) {
            $key = mb_strtolower($title);

            if ($key === '' || $key === $working || isset($existing[$key]) || isset($seen[$key])) {
                continue;
            }

            $seen[$key] = true;
            $titles[] = $title;

            if (count($titles) >= $count) {
                break;
            }
        }

        return $titles;
    }

    /**
     * Clean one candidate line and enforce the model's column limit.
     *
     * @param string $value Raw candidate text.
     *
     * @return string A safe title of at most Post::TITLE_MAX characters.
     */
    private function normalise(string $value): string
    {
        $value = strip_tags($value);
        $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $value) ?? '';
        $value = preg_replace('/\s+/u', ' ', $value) ?? $value;
        $value = trim($value, " \t\n\r\0\x0B\"'`");
        $value = preg_replace('/^(?:\d+[.)]|[-*\x{2022}])\s*/u', '', $value) ?? $value;

        return trim(Str::substr(trim($value), 0, Post::TITLE_MAX));
    }
}
