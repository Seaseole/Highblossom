<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domains\Content\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

final class CleanupOrphanedUploads extends Command
{
    protected $signature = 'content-blocks:cleanup-orphaned-uploads';

    protected $description = 'Clean up orphaned temp upload files not referenced in any post content';

    public function handle(): int
    {
        if (!config('content-blocks.temp_cleanup.enabled', true)) {
            $this->info('Temp cleanup is disabled in config.');
            return self::SUCCESS;
        }

        $retentionHours = config('content-blocks.temp_cleanup.retention_hours', 24);
        $cutoffDate = now()->subHours($retentionHours);

        $this->info("Scanning for temp files older than {$retentionHours} hours (before {$cutoffDate->toDateTimeString()})...");

        $disk = Storage::disk('temp');
        $files = $disk->allFiles('uploads');

        if (empty($files)) {
            $this->info('No temp files found.');
            return self::SUCCESS;
        }

        $this->info("Found " . count($files) . " temp files.");

        // Get all referenced file paths from posts
        $referencedPaths = $this->getReferencedFilePaths();

        $deletedCount = 0;
        $skippedCount = 0;

        foreach ($files as $file) {
            $filePath = 'temp://' . $file;
            $fileTimestamp = $disk->lastModified($file);
            $fileDate = \DateTime::createFromFormat('U', (string) $fileTimestamp);

            if ($fileDate && $fileDate < $cutoffDate) {
                // Check if file is referenced in any post
                if (in_array($filePath, $referencedPaths, true)) {
                    $this->line("  Skipped (referenced): {$file}");
                    $skippedCount++;
                    continue;
                }

                // Delete orphaned file
                if ($disk->delete($file)) {
                    $this->line("  Deleted: {$file}");
                    Log::info('ContentBlocks: Deleted orphaned temp file', [
                        'file' => $file,
                        'age_hours' => $cutoffDate->diffInHours($fileDate),
                    ]);
                    $deletedCount++;
                } else {
                    $this->error("  Failed to delete: {$file}");
                    Log::warning('ContentBlocks: Failed to delete orphaned temp file', ['file' => $file]);
                }
            } else {
                $this->line("  Skipped (too recent): {$file}");
                $skippedCount++;
            }
        }

        $this->info("Cleanup complete: {$deletedCount} deleted, {$skippedCount} skipped.");

        return self::SUCCESS;
    }

    /**
     * Get all file paths referenced in post content.
     *
     * @return array<string>
     */
    protected function getReferencedFilePaths(): array
    {
        $paths = [];

        Post::whereNotNull('content')->chunk(100, function ($posts) use (&$paths) {
            foreach ($posts as $post) {
                if (!is_array($post->content)) {
                    continue;
                }

                foreach ($post->content as $block) {
                    $this->extractFilePathsFromBlock($block, $paths);
                }
            }
        });

        return $paths;
    }

    /**
     * Recursively extract file paths from block attributes.
     *
     * @param array<string, mixed> $block
     * @param array<string> $paths
     * @return void
     */
    protected function extractFilePathsFromBlock(array $block, array &$paths): void
    {
        if (!isset($block['attributes'])) {
            return;
        }

        foreach ($block['attributes'] as $value) {
            if (is_string($value) && str_starts_with($value, 'temp://')) {
                $paths[] = $value;
            } elseif (is_array($value)) {
                // Handle nested arrays (e.g., gallery images, table cells)
                foreach ($value as $nested) {
                    if (is_string($nested) && str_starts_with($nested, 'temp://')) {
                        $paths[] = $nested;
                    } elseif (is_array($nested)) {
                        // Handle deeply nested arrays
                        $this->extractFilePathsFromBlock(['attributes' => $nested], $paths);
                    }
                }
            }
        }
    }
}
