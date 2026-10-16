<?php

declare(strict_types=1);

namespace App\Support\SystemOverview\Probes;

use App\Support\SystemOverview\PackageCatalog;
use App\Support\SystemOverview\ProbeCost;
use App\Support\SystemOverview\ProbeItem;
use App\Support\SystemOverview\ProbeStatus;
use App\Support\SystemOverview\SystemProbe;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;

/**
 * What the application is built from, as recorded by its lock files.
 *
 * Heavy because composer.lock is about a megabyte of JSON: it is parsed in the
 * warm-up job, and the totals and the row list are then served from a
 * fingerprint-keyed cache.
 */
final class PackagesProbe implements SystemProbe
{
    public function __construct(
        private readonly PackageCatalog $catalog,
    ) {}

    public function key(): string
    {
        return 'packages';
    }

    public function label(): string
    {
        return 'Dependencies';
    }

    public function description(): string
    {
        return 'Composer and npm packages declared by the lock files.';
    }

    public function icon(): string
    {
        return 'document-text';
    }

    public function cost(): ProbeCost
    {
        return ProbeCost::Heavy;
    }

    /**
     * @return list<ProbeItem>
     */
    public function run(): array
    {
        // The table under this card reads the row list. Parsing it here, in the warm-up
        // path, means that request finds the rows cached instead of paying for them.
        $this->catalog->rows();

        $counts = $this->catalog->counts();

        return [
            ProbeItem::flagged(
                'Composer packages',
                Number::forHumans($counts['composer']).' required',
                $counts['composer'] === 0 ? ProbeStatus::Warn : ProbeStatus::Ok,
                Number::forHumans($counts['composer_dev']).' development-only.',
            ),
            ProbeItem::text(
                'npm packages',
                Number::forHumans($counts['npm']).' required',
                Number::forHumans($counts['npm_dev']).' development-only.',
            ),
            ProbeItem::text(
                'Lock file written',
                $counts['locked_at'] === null ? 'No composer.lock' : Carbon::parse($counts['locked_at'])->diffForHumans(),
                $counts['locked_at'] === null ? null : $counts['locked_at'].' UTC',
            ),
        ];
    }
}
