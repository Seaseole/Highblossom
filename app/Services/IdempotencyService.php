<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Cache;

/**
 * Service for preventing duplicate form submissions using idempotency tokens.
 */
class IdempotencyService
{
    /** @var int Cache TTL in seconds for idempotency tokens */
    private const CACHE_TTL_SECONDS = 300;

    /** @var string Cache key prefix for idempotency tokens */
    private const CACHE_PREFIX = 'idempotency:';

    /**
     * Check if a token has been processed (prevents duplicate submissions).
     */
    public function isProcessed(string $token): bool
    {
        if (empty($token)) {
            return false;
        }

        return Cache::has($this->getCacheKey($token));
    }

    /**
     * Mark a token as processed.
     *
     * @param string $token The idempotency token to mark as processed.
     * @param mixed  $value What the token produced, so a retry can be handed the
     *                      original result rather than only told it was a duplicate.
     */
    public function markProcessed(string $token, mixed $value = true): void
    {
        if (empty($token)) {
            return;
        }

        Cache::put($this->getCacheKey($token), $value, self::CACHE_TTL_SECONDS);
    }

    /**
     * What the first submission of this token produced, or null when it is unseen.
     */
    public function result(string $token): mixed
    {
        if (empty($token)) {
            return null;
        }

        return Cache::get($this->getCacheKey($token));
    }

    /**
     * Generate a unique idempotency key from request data.
     */
    public function generateKey(array $data, string $salt = ''): string
    {
        $hashInput = json_encode($data).$salt.microtime(true);

        return hash('sha256', $hashInput);
    }

    /**
     * Process an idempotent action - returns true if should proceed, false if duplicate.
     */
    public function attempt(string $token, callable $callback): mixed
    {
        if ($this->isProcessed($token)) {
            return [
                'success' => true,
                'duplicate' => true,
                'message' => 'Request already processed.',
            ];
        }

        $this->markProcessed($token);

        return $callback();
    }

    /**
     * Build the cache key for the given token.
     */
    private function getCacheKey(string $token): string
    {
        return self::CACHE_PREFIX.$token;
    }
}
