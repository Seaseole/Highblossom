<?php

declare(strict_types=1);

namespace App\Services\Ai\DataTransferObjects;

/**
 * Raw candidate lines from a provider, before any domain filtering.
 *
 * Everything here is untrusted input: domain services must still normalise,
 * validate, cap and de-duplicate before a candidate reaches a form field.
 */
final class SuggestionResult
{
    /**
     * @param list<string> $candidates Raw, unfiltered candidate strings.
     */
    public function __construct(public readonly array $candidates) {}

    /**
     * Return the candidates as a plain list.
     *
     * @return list<string>
     */
    public function all(): array
    {
        return array_values($this->candidates);
    }
}
