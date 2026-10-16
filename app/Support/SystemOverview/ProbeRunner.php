<?php

declare(strict_types=1);

namespace App\Support\SystemOverview;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Runs probes, caches each section on its own, and keeps one broken probe from
 * taking the whole page down.
 *
 * Cost decides placement: light sections are measured inline and cached briefly,
 * heavy sections are only ever read back here. Results are memoised per request so
 * a page render and an export in the same request pay for a section once.
 *
 * What is cached is the full measurement; what a caller gets is filtered by ability.
 * That keeps one entry serving every reader, which matters because the cache key has
 * no user dimension, and because a warm-up job runs with no reader at all.
 */
final class ProbeRunner
{
    /**
     * @var array<string, ProbeResult>
     */
    private array $memo = [];

    public function __construct(
        private readonly ProbeRegistry $registry,
    ) {}

    /**
     * The cached section when it is still fresh, otherwise a fresh measurement, with the
     * lines this reader may not see removed.
     *
     * @param string $key Allowlisted probe key.
     */
    public function run(string $key): ProbeResult
    {
        if (isset($this->memo[$key])) {
            return $this->memo[$key];
        }

        $probe = $this->registry->resolve($key);

        $cached = $this->cached($probe);

        if ($cached !== null) {
            return $this->memo[$key] = $this->visibleTo($cached);
        }

        $result = $this->measure($probe);

        if (! $result->isFailed()) {
            Cache::put($this->cacheKey($key), $result->toArray(), $probe->cost()->ttl());
        }

        return $this->memo[$key] = $this->visibleTo($result);
    }

    /**
     * Every section, in page order, optionally filtered by cost.
     *
     * @param ProbeCost|null $cost Cost class to filter by, or null for all.
     *
     * @return list<ProbeResult>
     */
    public function runAll(?ProbeCost $cost = null): array
    {
        $probes = $cost === null ? $this->registry->all() : $this->registry->byCost($cost);

        return array_map(
            fn (SystemProbe $probe): ProbeResult => $this->run($probe->key()),
            $probes,
        );
    }

    /**
     * Measure a section immediately, whatever its cost class, and store it.
     *
     * This is the only path that runs a heavy probe: it is what the warm-up job
     * calls, and what the card's explicit "Measure now" control calls when no
     * worker is running. A page view never reaches it.
     *
     * @param string $key Allowlisted probe key.
     */
    public function warm(string $key): ProbeResult
    {
        $probe = $this->registry->resolve($key);
        $result = $this->measure($probe);

        if (! $result->isFailed()) {
            Cache::put($this->cacheKey($key), $result->toArray(), $probe->cost()->ttl());
        }

        return $this->memo[$key] = $this->visibleTo($result);
    }

    /**
     * Drop the lines whose ability the current reader does not hold. The cache keeps
     * them: this is a read-time decision, not a stored one.
     */
    private function visibleTo(ProbeResult $result): ProbeResult
    {
        return $result->filter(
            fn (ProbeItem $item): bool => $item->requires === null || Gate::allows($item->requires),
        );
    }

    /**
     * Discard every cached section so the next read re-measures.
     */
    public function forgetAll(): void
    {
        foreach ($this->registry->keys() as $key) {
            Cache::forget($this->cacheKey($key));
        }

        $this->memo = [];
    }

    /**
     * Read a cached section, or build the placeholder a heavy section needs
     * before its first measurement.
     *
     * @param SystemProbe $probe Probe to look up.
     */
    private function cached(SystemProbe $probe): ?ProbeResult
    {
        $payload = Cache::get($this->cacheKey($probe->key()));

        if (is_array($payload)) {
            try {
                return ProbeResult::fromArray($payload);
            } catch (Throwable $e) {
                // An entry written by an older build of a probe is a miss, not a fatal.
                Log::warning('Discarding unreadable system overview cache entry', [
                    'probe' => $probe->key(),
                    'message' => $e->getMessage(),
                ]);
            }
        }

        if (! $probe->cost()->runsInline()) {
            return ProbeResult::pending($probe, __('messages.system_overview_not_warmed'));
        }

        return null;
    }

    /**
     * Time one probe and turn any throw into a failed section.
     *
     * @param SystemProbe $probe Probe to execute.
     */
    private function measure(SystemProbe $probe): ProbeResult
    {
        $startedAt = microtime(true);

        try {
            $items = $probe->run();
        } catch (Throwable $e) {
            Log::warning('System overview probe failed', [
                'probe' => $probe->key(),
                'user_id' => auth()->id(),
                'exception' => $e::class,
                'message' => $e->getMessage(),
            ]);

            return ProbeResult::failure($probe, __('messages.system_overview_probe_failed'), $this->elapsedMs($startedAt));
        }

        return ProbeResult::measured($probe, $items, $this->elapsedMs($startedAt));
    }

    /**
     * @param string $key Probe key.
     */
    private function cacheKey(string $key): string
    {
        return 'system_overview.'.$key;
    }

    /**
     * @param float $startedAt Result of microtime(true) at the start.
     */
    private function elapsedMs(float $startedAt): int
    {
        return (int) round((microtime(true) - $startedAt) * 1000);
    }
}
