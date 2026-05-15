<?php

declare(strict_types=1);

namespace App\Services\Content;

use App\Models\Post;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Finder\Finder;

final class ContentBlockCleanupService
{
    /**
     * Clean up orphaned temp upload files not referenced in any post content.
     *
     * @param int $retentionHours
     * @return array{deleted: int, skipped: int}
     */
    public function cleanupOrphanedUploads(int $retentionHours = 24): array
    {
        $cutoffDate = now()->subHours($retentionHours);
        $disk = Storage::disk('temp');
        $files = $disk->allFiles('uploads');

        if (empty($files)) {
            return ['deleted' => 0, 'skipped' => 0];
        }

        $referencedPaths = $this->getReferencedFilePaths();
        $deletedCount = 0;
        $skippedCount = 0;

        foreach ($files as $file) {
            $result = $this->shouldDeleteOrphanedFile($file, $cutoffDate, $disk, $referencedPaths);
            
            if ($result === 'deleted') {
                $deletedCount++;
            } else {
                $skippedCount++;
            }
        }

        return ['deleted' => $deletedCount, 'skipped' => $skippedCount];
    }

    /**
     * Determine if an orphaned file should be deleted and delete it if so.
     */
    private function shouldDeleteOrphanedFile(string $file, \Illuminate\Support\Carbon $cutoffDate, $disk, array $referencedPaths): string
    {
        $filePath = 'temp://' . $file;
        $fileTimestamp = $disk->lastModified($file);
        $fileDate = \DateTime::createFromFormat('U', (string) $fileTimestamp);

        if (!$fileDate || $fileDate >= $cutoffDate) {
            return 'skipped';
        }

        if (in_array($filePath, $referencedPaths, true)) {
            return 'skipped';
        }

        if ($disk->delete($file)) {
            Log::info('ContentBlocks: Deleted orphaned temp file', [
                'file' => $file,
                'age_hours' => $cutoffDate->diffInHours($fileDate),
            ]);
            return 'deleted';
        }

        Log::warning('ContentBlocks: Failed to delete orphaned temp file', ['file' => $file]);
        return 'error';
    }

    /**
     * Clean up all temporary uploaded files older than the specified hours.
     */
    public function cleanupAllTempFiles(int $hours = 24): array
    {
        $cutoffTime = now()->subHours($hours);
        $tempDisk = Storage::disk('temp');
        $tempPath = $tempDisk->path('');

        if (!is_dir($tempPath)) {
            return ['deleted' => 0, 'errors' => 0];
        }

        $finder = new Finder();
        $finder->files()
            ->in($tempPath)
            ->date("< {$cutoffTime->format('Y-m-d H:i:s')}");

        $count = 0;
        $errors = 0;

        foreach ($finder as $file) {
            if ($this->deleteTempFile($file->getRelativePathname(), $tempDisk)) {
                $count++;
            } else {
                $errors++;
            }
        }

        return ['deleted' => $count, 'errors' => $errors];
    }

    /**
     * Delete a single temporary file.
     */
    private function deleteTempFile(string $relativePath, $tempDisk): bool
    {
        try {
            if ($tempDisk->delete($relativePath)) {
                Log::info("Cleaned up old temp file: {$relativePath}");
                return true;
            }
            Log::warning("Failed to delete temp file: {$relativePath}");
        } catch (\Exception $e) {
            Log::error("Error deleting temp file: {$relativePath}", [
                'error' => $e->getMessage(),
            ]);
        }

        return false;
    }

    /**
     * Get all file paths referenced in post content.
     *
     * @return array<string>
     */
    private function getReferencedFilePaths(): array
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
     */
    private function extractFilePathsFromBlock(array $block, array &$paths): void
    {
        if (!isset($block['attributes']) || !is_array($block['attributes'])) {
            return;
        }

        foreach ($block['attributes'] as $value) {
            $this->processAttributeValue($value, $paths);
        }
    }

    /**
     * Process an attribute value to extract temp paths.
     */
    private function processAttributeValue(mixed $value, array &$paths): void
    {
        if (is_string($value)) {
            $this->collectTempPath($value, $paths);
            return;
        }

        if (is_array($value)) {
            $this->processNestedAttributes($value, $paths);
        }
    }

    /**
     * Process nested attribute arrays.
     */
    private function processNestedAttributes(array $values, array &$paths): void
    {
        foreach ($values as $item) {
            if (is_string($item)) {
                $this->collectTempPath($item, $paths);
            } elseif (is_array($item)) {
                $this->extractFilePathsFromBlock(['attributes' => $item], $paths);
            }
        }
    }

    /**
     * Collect string if it's a temp path.
     */
    private function collectTempPath(string $value, array &$paths): void
    {
        if (str_starts_with($value, 'temp://')) {
            $paths[] = $value;
        }
    }
}
