<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ApplicationVersion;
use App\Models\User;
use App\Services\ApplicationVersionService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

/**
 * Seeds the application version registry with its release history, starting at
 * the 1.2.0 baseline and ending at the version the application is on today.
 *
 * Idempotent: versions that already exist are left untouched, so this is safe
 * to re-run on a database that already holds recorded releases.
 */
class ApplicationVersionSeeder extends Seeder
{
    /**
     * The release history, oldest first. Each entry mirrors a real recorded
     * release: version, one-line summary, release date and change notes.
     *
     * @var list<array{version: string, summary: string, released_at: string, notes: list<array{type: string, text: string}>}>
     */
    private const HISTORY = [
        [
            'version' => '1.2.0',
            'summary' => 'Baseline release',
            'released_at' => '2026-10-01',
            'notes' => [
                ['type' => 'added', 'text' => 'Application version registry introduced.'],
            ],
        ],
        [
            'version' => '1.2.1',
            'summary' => 'Fix admin-wide crash when reading the cached current version',
            'released_at' => '2026-10-01',
            'notes' => [
                ['type' => 'fixed', 'text' => 'ApplicationVersionService::current() cached an Eloquent model object; stale cross-process cache entries deserialized as __PHP_Incomplete_Class and violated the return type, crashing every page using x-layouts::admin (including /admin/versions). The cache now stores only the release primary key and the model is re-queried by key; the poisoned cache entry was purged.'],
            ],
        ],
        [
            'version' => '1.3.0',
            'summary' => 'Per-announcement expiry for Company Settings announcements',
            'released_at' => '2026-10-01',
            'notes' => [
                ['type' => 'added', 'text' => 'Each announcement now has an optional "Active Until" date/time; it hides automatically from the marquee once that moment passes.'],
                ['type' => 'changed', 'text' => 'The announcement ticker bar hides entirely when every announcement has expired. Blank/legacy entries never expire (backward compatible).'],
            ],
        ],
        [
            'version' => '1.3.1',
            'summary' => 'Rename user-facing "Inspection" terminology to "Appointment"',
            'released_at' => '2026-10-01',
            'notes' => [
                ['type' => 'changed', 'text' => 'Admin appointments UI, customer booking flow, dashboard stats, sidebar, flash messages, emails and notification subjects now read "Appointment".'],
                ['type' => 'changed', 'text' => 'Terminology-only rename: route names, classes, DB tables/columns, permissions and generic service descriptions (e.g. "windshield inspection") are unchanged.'],
            ],
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $baseline = ApplicationVersionService::BASELINE;
        $author = User::query()->orderBy('id')->value('id');

        foreach (self::HISTORY as $release) {
            $parts = array_map('intval', explode('.', $release['version']));

            ApplicationVersion::query()->firstOrCreate(
                ['version' => $release['version']],
                [
                    'major' => $parts[0],
                    'minor' => $parts[1],
                    'patch' => $parts[2],
                    'summary' => $release['summary'],
                    'notes' => $release['notes'],
                    'released_at' => $release['released_at'],
                    // The baseline predates the registry's authorship tracking;
                    // later releases are attributed to the first admin user.
                    'created_by' => $release['version'] === $baseline ? null : $author,
                ]
            );
        }

        // The current release id is cached, so drop it to avoid a stale entry
        // pointing at a release that no longer reflects the registry.
        Cache::forget(ApplicationVersionService::CACHE_KEY);
    }
}
