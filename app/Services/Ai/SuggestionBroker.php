<?php

declare(strict_types=1);

namespace App\Services\Ai;

use App\Services\Ai\Contracts\SuggestionClient;
use App\Services\Ai\DataTransferObjects\SuggestionRequest;
use App\Services\Ai\DataTransferObjects\SuggestionResult;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * The single gate every AI suggestion passes through.
 *
 * Owns three things the two features must not each reinvent: the site-wide
 * switch, a short cache so a second click on an unchanged draft is free, and a
 * structured log line that records the outcome without ever recording a prompt
 * or a completion. Controllers only ever see a result or one readable exception.
 */
final class SuggestionBroker
{
    private const CACHE_PREFIX = 'ai-suggestion.';

    /**
     * @param SuggestionClient $client The configured provider or its offline stand-in.
     */
    public function __construct(private readonly SuggestionClient $client) {}

    /**
     * Resolve a request, from cache when possible.
     *
     * @param SuggestionRequest $request The already-redacted, capped prompt.
     *
     * @return SuggestionResult Unfiltered candidates for the domain service.
     *
     * @throws AiSuggestionException When suggestions are off or the provider fails.
     */
    public function suggest(SuggestionRequest $request): SuggestionResult
    {
        if (! (bool) config('ai.enabled')) {
            throw AiSuggestionException::disabled();
        }

        $key = self::CACHE_PREFIX.$request->fingerprint();
        $cached = Cache::get($key);

        if (is_array($cached)) {
            return new SuggestionResult(array_values($cached));
        }

        try {
            $result = $this->client->suggest($request);
        } catch (AiSuggestionException $exception) {
            Log::warning('AI suggestion request failed', [
                'status' => $exception->httpStatus,
                'reason' => $exception->getMessage(),
                'cause' => $exception->cause ?? 'unknown',
            ]);

            throw $exception;
        }

        // An empty answer means the provider was reachable but produced nothing
        // usable (common with reasoning models). Do not cache it: the next click
        // should be allowed to succeed once max_tokens or the model is fixed.
        if ($result->all() !== []) {
            Cache::put(
                $key,
                $result->all(),
                now()->addSeconds(max(0, (int) config('ai.cache_ttl')))
            );
        }

        return $result;
    }
}
