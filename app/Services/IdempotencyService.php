<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class IdempotencyService
{
    private const CACHE_TTL_SECONDS = 300; // 5 minutes

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
     * @return void
     */
    public function markProcessed(string $token): void
    {
        if (empty($token)) {
            return;
        }

        Cache::put($this->getCacheKey($token), true, self::CACHE_TTL_SECONDS);
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

    private function getCacheKey(string $token): string
    {
        return self::CACHE_PREFIX.$token;
    }
}
