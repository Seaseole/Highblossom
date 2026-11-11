<?php

declare(strict_types=1);

namespace App\Services\Ai;

use App\Services\Ai\Contracts\SuggestionClient;
use App\Services\Ai\DataTransferObjects\SuggestionRequest;
use App\Services\Ai\DataTransferObjects\SuggestionResult;

/**
 * Deterministic, offline stand-in used when no provider key is configured.
 *
 * It returns the domain service's own local candidates, which means the entire
 * pipeline - normalising, availability checks, length caps, de-duplication -
 * stays exercisable in development and in `php artisan test` with no network
 * call, no API key and no cost. It is not a cheap imitation of the model: it is
 * the fallback the live path also merges in when a completion is thin.
 */
final class NullSuggestionClient implements SuggestionClient
{
    /**
     * Return the deterministic candidates carried by the request.
     *
     * @param SuggestionRequest $request The already-redacted, capped prompt.
     *
     * @return SuggestionResult Unfiltered candidates for the domain service.
     */
    public function suggest(SuggestionRequest $request): SuggestionResult
    {
        return new SuggestionResult($request->fallbackCandidates);
    }
}
