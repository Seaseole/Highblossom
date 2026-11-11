<?php

declare(strict_types=1);

namespace App\Services\Ai\Contracts;

use App\Services\Ai\AiSuggestionException;
use App\Services\Ai\DataTransferObjects\SuggestionRequest;
use App\Services\Ai\DataTransferObjects\SuggestionResult;

/**
 * The boundary between the CMS and whatever produces suggestion ideas.
 *
 * Keeping this narrow means the provider can be swapped, faked in tests, or
 * replaced by a local model without touching either feature that consumes it.
 * Implementations must never log or return the prompt or the raw response.
 */
interface SuggestionClient
{
    /**
     * Produce a batch of raw candidate lines for the given request.
     *
     * @param SuggestionRequest $request The already-redacted, capped prompt.
     *
     * @return SuggestionResult Unfiltered candidates for the domain service.
     *
     * @throws AiSuggestionException When the provider cannot produce a usable answer.
     */
    public function suggest(SuggestionRequest $request): SuggestionResult;
}
