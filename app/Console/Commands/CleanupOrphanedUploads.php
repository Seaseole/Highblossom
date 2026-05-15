<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Post;
use App\Services\Content\ContentBlockCleanupService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

final class CleanupOrphanedUploads extends Command
{
    protected $signature = 'content-blocks:cleanup-orphaned-uploads';

    protected $description = 'Clean up orphaned temp upload files not referenced in any post content';

    public function __construct(
        protected ContentBlockCleanupService $cleanupService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        if (!config('content-blocks.temp_cleanup.enabled', true)) {
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
