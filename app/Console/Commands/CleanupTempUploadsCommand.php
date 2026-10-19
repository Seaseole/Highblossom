<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Content\ContentBlockCleanupService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Description('Clean up temporary uploaded files older than the specified hours (default: 24)')]
#[Signature('content-blocks:cleanup-temp-uploads
                            {--hours=24 : The age in hours after which temp files should be deleted}')]
/**
 * Console command to clean up temporary uploaded files older than a specified age.
 *
 * Delegates the actual cleanup to ContentBlockCleanupService.
 */
final class CleanupTempUploadsCommand extends Command
{
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
     */
    public function handle(): int
    {
        $hours = (int) $this->option('hours');
        $this->info("Cleaning up all temp files older than {$hours} hours...");

        $results = $this->cleanupService->cleanupAllTempFiles($hours);

        if ($results['deleted'] > 0) {
            $this->info("Cleaned up {$results['deleted']} temp file(s).");
        } else {
            $this->info('No temp files found to clean up.');
        }

        if ($results['errors'] > 0) {
            $this->warn("Failed to delete {$results['errors']} file(s).");
        }

        return self::SUCCESS;
    }
}
