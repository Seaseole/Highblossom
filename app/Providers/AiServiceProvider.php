<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Ai\Contracts\SuggestionClient;
use App\Services\Ai\NullSuggestionClient;
use App\Services\Ai\OpenAiCompatibleClient;
use App\Services\Ai\SuggestionBroker;
use Illuminate\Support\ServiceProvider;

/**
 * Choose the suggestion client from configuration, once per request.
 *
 * A missing key is not an error: it selects the offline client, so a fresh
 * checkout, a staging box and the test suite all behave identically and never
 * attempt a network call. Setting `AI_SUGGESTIONS_PROVIDER=null` forces that
 * behaviour even when the rest of the environment carries a real key.
 */
final class AiServiceProvider extends ServiceProvider
{
    /**
     * Register the client and the broker that wraps it.
     */
    public function register(): void
    {
        $this->app->singleton(SuggestionClient::class, function (): SuggestionClient {
            $provider = trim((string) config('ai.provider'));
            $key = trim((string) config('ai.key'));

            if ($provider === '' || $provider === 'null' || $key === '') {
                return new NullSuggestionClient;
            }

            return new OpenAiCompatibleClient;
        });

        $this->app->singleton(SuggestionBroker::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configuration-only service; nothing to publish or boot at runtime.
    }
}
