<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Support\SystemOverview\ProbeCost;
use App\Support\SystemOverview\ProbeRegistry;
use App\Support\SystemOverview\ProbeRunner;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

/**
 * Measures the heavy system overview sections away from the request path.
 *
 * One job warms every heavy section so a page refresh cannot enqueue a pile of
 * near-identical walks. Uniqueness is what stops overlapping runs from stacking
 * up when an admin keeps pressing Re-measure.
 */
class WarmSystemOverviewProbe implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    /**
     * @var int Seconds the unique lock is held, so repeat dispatches coalesce.
     */
    public int $uniqueFor = 60;

    /**
     * @var int A measurement is idempotent, so one attempt is enough; a failure
     *          leaves the section pending and the admin can retry by hand.
     */
    public int $tries = 1;

    /**
     * @var int Wall-clock limit for the whole warm-up, in seconds.
     */
    public int $timeout = 90;

    /**
     * @param string|null $probe One allowlisted section, or null for every heavy section.
     */
    public function __construct(
        public readonly ?string $probe = null,
    ) {}

    /**
     * Coalesce concurrent warm-ups of the same scope.
     */
    public function uniqueId(): string
    {
        return 'system_overview.warm.'.($this->probe ?? 'all');
    }

    /**
     * Measure each requested section and store it.
     *
     * @param ProbeRegistry $registry Probe catalogue.
     * @param ProbeRunner   $runner   Section runner.
     */
    public function handle(ProbeRegistry $registry, ProbeRunner $runner): void
    {
        $keys = $this->probe === null
            ? $registry->keysByCost(ProbeCost::Heavy)
            : (array) $this->probe;

        foreach ($keys as $key) {
            if (! $registry->has($key)) {
                continue;
            }

            $runner->warm($key);
        }
    }
}
