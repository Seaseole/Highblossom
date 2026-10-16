<?php

declare(strict_types=1);

namespace App\Support\SystemOverview;

/**
 * One independently measurable slice of the system overview.
 *
 * Implementations must stay side-effect free apart from their own cache usage
 * and must never return secrets: anything environment-derived has to go through
 * an allowlist, and values that must not leave the app are marked sensitive.
 */
interface SystemProbe
{
    /**
     * Allowlisted key used in cache entries and route parameters.
     */
    public function key(): string;

    /**
     * Card heading for the admin page.
     */
    public function label(): string;

    /**
     * One sentence describing what the probe inspects.
     */
    public function description(): string;

    /**
     * Heroicons name used by the card header.
     */
    public function icon(): string;

    /**
     * Whether the probe is safe to run inside a request.
     */
    public function cost(): ProbeCost;

    /**
     * Measure the section.
     *
     * @return list<ProbeItem>
     */
    public function run(): array;
}
