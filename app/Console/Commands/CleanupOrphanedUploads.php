<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Content\ContentBlockCleanupService;
use Illuminate\Console\Command;

/**
 * Console command to clean up orphaned temporary upload files.
 *
 * Removes temp upload files that are no longer referenced in any post content.
 */
final class CleanupOrphanedUploads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content-blocks:cleanup-orphaned-uploads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up orphaned temp upload files not referenced in any post content';

    /**
     * Create a new command instance.
     */
    public function __construct(
        protected ContentBlockCleanupService $cleanupService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * Checks if temp cleanup is enabled in config, then scans for and removes orphaned files.
     *
     * @return int Command exit code
     */
    public function handle(): int
    {
        if (! config('content-blocks.temp_cleanup.enabled', true)) {
            $this->info('Temp cleanup is disabled in config.');

            return self::SUCCESS;
        }

        $retentionHours = config('content-blocks.temp_cleanup.retention_hours', 24);
        $this->info("Scanning for orphaned temp files older than {$retentionHours} hours...");

        $results = $this->cleanupService->cleanupOrphanedUploads($retentionHours);

        $this->info("Cleanup complete: {$results['deleted']} deleted, {$results['skipped']} skipped.");

        return self::SUCCESS;
    }
}
