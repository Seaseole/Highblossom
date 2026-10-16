<?php

declare(strict_types=1);

namespace App\Support\SystemOverview;

/**
 * How expensive a probe is to run, which decides where it may execute.
 *
 * Light probes are safe inside a page request. Heavy probes do bounded file
 * system or lock-file work and are therefore warmed by a queued job instead.
 */
enum ProbeCost: string
{
    case Light = 'light';
    case Heavy = 'heavy';

    /**
     * Seconds a result of this cost stays valid in cache.
     */
    public function ttl(): int
    {
        return match ($this) {
            self::Light => 30,
            self::Heavy => 3600,
        };
    }

    /**
     * Whether the probe may be executed while serving a request.
     */
    public function runsInline(): bool
    {
        return $this === self::Light;
    }
}
