<?php

declare(strict_types=1);

namespace App\Services\Ai\DataTransferObjects;

/**
 * Everything a provider needs to produce one batch of suggestions.
 *
 * The prompt is built and redacted on the server before this object exists, so a
 * client cannot widen what is sent to a third party by adding fields to a POST.
 * `fallbackCandidates` is the deterministic local answer: the offline client
 * returns it, and domain services merge it into a live provider's answer so an
 * empty or unusable completion never leaves an editor with nothing.
 */
final class SuggestionRequest
{
    /**
     * @param string       $system             The instruction that fixes the output shape.
     * @param string       $prompt             The redacted, length-capped context.
     * @param string       $model              Provider model identifier.
     * @param int          $maxTokens          Hard ceiling on the completion.
     * @param list<string> $fallbackCandidates Deterministic local candidates.
     * @param float        $temperature        Sampling temperature.
     */
    public function __construct(
        public readonly string $system,
        public readonly string $prompt,
        public readonly string $model,
        public readonly int $maxTokens,
        public readonly array $fallbackCandidates = [],
        public readonly float $temperature = 0.7,
    ) {}

    /**
     * A stable cache key. The prompt is hashed, never stored in the cache key.
     */
    public function fingerprint(): string
    {
        return hash('sha256', implode("\0", [
            $this->system,
            $this->prompt,
            $this->model,
            (string) $this->maxTokens,
            (string) $this->temperature,
            implode("\0", $this->fallbackCandidates),
        ]));
    }
}
