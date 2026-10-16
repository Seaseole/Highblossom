<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Support\SystemOverview\PackageCatalog;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * Cover the dependency catalogue: the lock file is large, so the whole point of
 * this class is that it is parsed once per fingerprint and never per view.
 */
class PackageCatalogTest extends TestCase
{
    public function test_it_counts_the_packages_declared_by_this_project(): void
    {
        $counts = (new PackageCatalog)->counts();

        $this->assertGreaterThan(100, $counts['composer']);
        $this->assertGreaterThan(10, $counts['composer_dev']);
        $this->assertGreaterThan(0, $counts['npm']);
        $this->assertNotNull($counts['locked_at']);
    }

    public function test_the_totals_are_cached_under_their_own_key(): void
    {
        $catalog = new PackageCatalog;

        $this->assertNull(Cache::get('system_overview.packages.counts'));

        $catalog->counts();

        $this->assertNotNull(Cache::get('system_overview.packages.counts'));

        $catalog->forget();

        $this->assertNull(Cache::get('system_overview.packages.counts'));
    }

    public function test_a_changed_cache_entry_is_not_replaced_while_the_fingerprint_matches(): void
    {
        $catalog = new PackageCatalog;
        $catalog->counts();

        // The stored payload carries the fingerprint, so a hand-edited cache entry
        // for the same lock files is still trusted: it is the same measurement.
        $payload = Cache::get('system_overview.packages.counts');
        $payload['counts']['composer'] = 999;
        Cache::put('system_overview.packages.counts', $payload, 60);

        $this->assertSame(999, $catalog->counts()['composer']);
    }

    public function test_composer_rows_carry_a_homepage_that_is_a_real_url(): void
    {
        $rows = (new PackageCatalog)->composerPackages();

        $this->assertNotEmpty($rows);

        foreach ($rows as $row) {
            $this->assertArrayHasKey('dev', $row);

            if ($row['homepage'] !== null) {
                $this->assertMatchesRegularExpression('#^https?://#', $row['homepage']);
            }
        }
    }

    public function test_production_and_development_packages_are_flagged_separately(): void
    {
        $rows = (new PackageCatalog)->composerPackages();

        $dev = array_filter($rows, static fn (array $row): bool => $row['dev']);

        $this->assertNotEmpty($dev);
        $this->assertNotEmpty(array_filter($rows, static fn (array $row): bool => ! $row['dev']));
    }

    public function test_npm_rows_report_the_declared_version_ranges(): void
    {
        $rows = (new PackageCatalog)->npmPackages();

        $this->assertNotEmpty($rows);

        foreach ($rows as $row) {
            $this->assertSame('npm', $row['type']);
            $this->assertNotSame('', $row['version']);
        }
    }

    public function test_the_rows_are_cached_under_their_own_key(): void
    {
        $catalog = new PackageCatalog;

        $this->assertNull(Cache::get('system_overview.packages.rows'));

        $catalog->rows();

        $this->assertNotNull(Cache::get('system_overview.packages.rows'));

        $catalog->forget();

        $this->assertNull(Cache::get('system_overview.packages.rows'));
    }

    public function test_a_search_is_answered_from_the_cached_rows_without_re_parsing(): void
    {
        $catalog = new PackageCatalog;
        $catalog->rows();

        // The stored payload carries the fingerprint, so rows kept for these lock files
        // are the measurement. Searching filters this array instead of decoding
        // composer.lock again, which is what keeps a keystroke cheap.
        $payload = Cache::get('system_overview.packages.rows');
        $payload['rows'] = [[
            'name' => 'acme/kept',
            'version' => '1.0.0',
            'type' => 'library',
            'description' => 'Hand written for this test.',
            'homepage' => null,
            'dev' => false,
            'source' => 'composer',
        ]];
        Cache::put('system_overview.packages.rows', $payload, 60);

        $rows = $catalog->paginate('kept', 'all', 1, 15)->items();

        $this->assertCount(1, $rows);
        $this->assertSame('acme/kept', $rows[0]['name']);
    }

    public function test_every_row_names_the_lock_file_it_came_from(): void
    {
        $rows = (new PackageCatalog)->rows();

        $this->assertNotEmpty(array_filter($rows, static fn (array $row): bool => $row['source'] === 'composer'));
        $this->assertNotEmpty(array_filter($rows, static fn (array $row): bool => $row['source'] === 'npm'));

        $names = array_column($rows, 'name');
        $sorted = $names;
        usort($sorted, 'strcasecmp');

        $this->assertSame($sorted, $names, 'Rows must arrive sorted so paging is stable.');
    }

    public function test_a_search_matches_names_and_descriptions_case_insensitively(): void
    {
        $catalog = new PackageCatalog;
        $needle = 'FRAMEWORK';

        $rows = $catalog->paginate($needle, 'all', 1, 1000)->items();

        $this->assertNotEmpty($rows);

        foreach ($rows as $row) {
            $matches = str_contains(strtolower($row['name']), strtolower($needle))
                || str_contains(strtolower($row['description']), strtolower($needle));

            $this->assertTrue($matches, "Row [{$row['name']}] should not have matched.");
        }
    }

    public function test_a_source_filter_returns_only_that_lock_file(): void
    {
        $catalog = new PackageCatalog;

        foreach (['composer', 'npm'] as $source) {
            $rows = $catalog->paginate('', $source, 1, 1000)->items();

            $this->assertNotEmpty($rows);

            foreach ($rows as $row) {
                $this->assertSame($source, $row['source']);
            }
        }
    }

    public function test_an_unknown_source_is_treated_as_no_filter_at_all(): void
    {
        $catalog = new PackageCatalog;

        $this->assertCount(count($catalog->rows()), $catalog->paginate('', 'environment', 1, 1000)->items());
    }

    public function test_pages_slice_the_list_without_losing_the_total(): void
    {
        $catalog = new PackageCatalog;
        $rows = $catalog->rows();

        $first = $catalog->paginate('', 'all', 1, 5);
        $second = $catalog->paginate('', 'all', 2, 5);

        $this->assertSame(array_column(array_slice($rows, 0, 5), 'name'), array_column($first->items(), 'name'));
        $this->assertSame(array_column(array_slice($rows, 5, 5), 'name'), array_column($second->items(), 'name'));
        $this->assertSame($first->total(), $second->total());
        $this->assertSame($second->total(), count($rows));
    }
}
