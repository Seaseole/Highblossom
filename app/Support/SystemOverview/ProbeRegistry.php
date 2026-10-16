<?php

declare(strict_types=1);

namespace App\Support\SystemOverview;

use App\Support\SystemOverview\Probes\ApplicationProbe;
use App\Support\SystemOverview\Probes\DatabaseProbe;
use App\Support\SystemOverview\Probes\FootprintProbe;
use App\Support\SystemOverview\Probes\PackagesProbe;
use App\Support\SystemOverview\Probes\PhpRuntimeProbe;
use App\Support\SystemOverview\Probes\PostureProbe;
use App\Support\SystemOverview\Probes\QueueProbe;
use App\Support\SystemOverview\Probes\SessionProbe;
use App\Support\SystemOverview\Probes\StoresProbe;
use InvalidArgumentException;

/**
 * The allowlisted catalogue of system overview probes.
 *
 * Keys are the only probe identifiers that ever reach a route parameter, so an
 * unknown key can never resolve a class, a path, or a cache key.
 */
final class ProbeRegistry
{
    /**
     * Probe keys in page order, mapped to their implementation.
     *
     * @var array<string, class-string<SystemProbe>>
     */
    private const PROBES = [
        'application' => ApplicationProbe::class,
        'posture' => PostureProbe::class,
        'runtime' => PhpRuntimeProbe::class,
        'database' => DatabaseProbe::class,
        'stores' => StoresProbe::class,
        'queue' => QueueProbe::class,
        'sessions' => SessionProbe::class,
        'footprint' => FootprintProbe::class,
        'packages' => PackagesProbe::class,
    ];

    /**
     * The allowlisted probe keys, in page order.
     *
     * @return list<string>
     */
    public function keys(): array
    {
        return array_keys(self::PROBES);
    }

    /**
     * Whether a key names a registered probe.
     *
     * @param string $key Probe key.
     */
    public function has(string $key): bool
    {
        return isset(self::PROBES[$key]);
    }

    /**
     * Resolve one probe implementation.
     *
     * @param string $key Allowlisted probe key.
     *
     * @throws InvalidArgumentException When the key is not registered.
     */
    public function resolve(string $key): SystemProbe
    {
        if (! $this->has($key)) {
            throw new InvalidArgumentException("Unknown system overview probe [{$key}].");
        }

        return app(self::PROBES[$key]);
    }

    /**
     * Every registered probe, in page order.
     *
     * @return list<SystemProbe>
     */
    public function all(): array
    {
        return array_map([$this, 'resolve'], $this->keys());
    }

    /**
     * The allowlisted keys of one cost class, in page order.
     *
     * @param ProbeCost $cost Cost class to filter by.
     *
     * @return list<string>
     */
    public function keysByCost(ProbeCost $cost): array
    {
        return array_values(array_filter(
            $this->keys(),
            fn (string $key): bool => $this->resolve($key)->cost() === $cost,
        ));
    }

    /**
     * Every registered probe of one cost class.
     *
     * @param ProbeCost $cost Cost class to filter by.
     *
     * @return list<SystemProbe>
     */
    public function byCost(ProbeCost $cost): array
    {
        return array_values(array_filter(
            $this->all(),
            fn (SystemProbe $probe): bool => $probe->cost() === $cost,
        ));
    }
}
