<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Support\SystemOverview\BoundedSizeScanner;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

/**
 * Cover the file-system measurement budgets: the caps are the only reason a
 * diagnostics walk cannot turn into an unbounded sweep of the host.
 */
class BoundedSizeScannerTest extends TestCase
{
    private string $root;

    protected function setUp(): void
    {
        parent::setUp();

        $this->root = storage_path('framework/testing/system-overview-scan');

        File::deleteDirectory($this->root);
        File::ensureDirectoryExists($this->root);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->root);

        parent::tearDown();
    }

    public function test_it_counts_files_and_bytes_in_a_tree(): void
    {
        File::put($this->root.'/one.txt', str_repeat('a', 120));
        File::ensureDirectoryExists($this->root.'/nested');
        File::put($this->root.'/nested/two.txt', str_repeat('b', 80));

        $scan = (new BoundedSizeScanner)->scan($this->root);

        $this->assertSame(2, $scan['files']);
        $this->assertSame(200, $scan['bytes']);
        $this->assertFalse($scan['truncated']);
    }

    public function test_it_never_descends_into_pruned_directories(): void
    {
        File::put($this->root.'/app.txt', 'x');
        File::ensureDirectoryExists($this->root.'/vendor/heavy-package');
        File::put($this->root.'/vendor/heavy-package/lib.php', str_repeat('y', 5000));
        File::ensureDirectoryExists($this->root.'/node_modules');
        File::put($this->root.'/node_modules/thing.js', str_repeat('y', 5000));

        $scan = (new BoundedSizeScanner)->scan($this->root);

        $this->assertSame(1, $scan['files']);
        $this->assertSame(1, $scan['bytes']);
    }

    public function test_it_stops_at_the_file_cap_and_reports_the_result_as_partial(): void
    {
        for ($i = 0; $i < 6; $i++) {
            File::put($this->root."/file-{$i}.txt", 'data');
        }

        $scan = (new BoundedSizeScanner(maxFiles: 3))->scan($this->root);

        $this->assertSame(3, $scan['files']);
        $this->assertTrue($scan['truncated']);
    }

    public function test_a_missing_directory_is_an_empty_result_rather_than_an_error(): void
    {
        $scan = (new BoundedSizeScanner)->scan($this->root.'/does-not-exist');

        $this->assertSame(['files' => 0, 'bytes' => 0, 'truncated' => false], $scan);
    }
}
