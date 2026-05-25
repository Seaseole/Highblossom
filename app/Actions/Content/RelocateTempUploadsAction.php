<?php

declare(strict_types=1);

namespace App\Actions\Content;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

final class RelocateTempUploadsAction
{
    /**
     * Relocate uploaded files from temp storage to permanent storage.
     *
     * @param  array  $contentBlocks  The content blocks array containing file URLs
     * @return array Updated content blocks with relocated file URLs
     */
    public function execute(array $contentBlocks): array
    {
        $tempDisk = Storage::disk('temp');
        $publicDisk = Storage::disk('public');

        return $this->processBlocks($contentBlocks, $tempDisk, $publicDisk);
    }

    /**
     * Recursively process all blocks and relocate files.
     *
     * @param  Filesystem  $tempDisk
     * @param  Filesystem  $publicDisk
     */
    private function processBlocks(array $blocks, $tempDisk, $publicDisk): array
    {
        foreach ($blocks as &$block) {
            if (! is_array($block) || ! isset($block['type'])) {
                continue;
            }

            $attributes = $block['attributes'] ?? [];

            // Process based on block type
            $attributes = match ($block['type']) {
                'image' => $this->processImageBlock($attributes, $tempDisk, $publicDisk),
                'video' => $this->processVideoBlock($attributes, $tempDisk, $publicDisk),
                'gallery' => $this->processGalleryBlock($attributes, $tempDisk, $publicDisk),
                'columns' => $this->processColumnsBlock($attributes, $tempDisk, $publicDisk),
                default => $attributes,
            };

            $block['attributes'] = $attributes;
        }

        return $blocks;
    }

    /**
     * Process image block attributes.
     */
    private function processImageBlock(array $attributes, $tempDisk, $publicDisk): array
    {
        $attributes['src'] = $this->relocateFile($attributes['src'] ?? '', $tempDisk, $publicDisk, 'uploads/images');

        return $attributes;
    }

    /**
     * Process video block attributes.
     */
    private function processVideoBlock(array $attributes, $tempDisk, $publicDisk): array
    {
        $attributes['src'] = $this->relocateFile($attributes['src'] ?? '', $tempDisk, $publicDisk, 'uploads/videos');
        $attributes['poster'] = $this->relocateFile($attributes['poster'] ?? '', $tempDisk, $publicDisk, 'uploads/videos/thumbnails');

        return $attributes;
    }

    /**
     * Process gallery block attributes.
     */
    private function processGalleryBlock(array $attributes, $tempDisk, $publicDisk): array
    {
        $images = $attributes['images'] ?? [];

        foreach ($images as &$image) {
            $image['src'] = $this->relocateFile($image['src'] ?? '', $tempDisk, $publicDisk, 'uploads/gallery');
        }

        $attributes['images'] = $images;

        return $attributes;
    }

    /**
     * Relocate a single file from temp to permanent storage.
     */
    private function relocateFile(string $url, $tempDisk, $publicDisk, string $destinationDir): string
    {
        if (empty($url)) {
            return $url;
        }

        $tempPath = $this->extractStoragePath($url, 'temp');

        if ($tempPath && $tempDisk->exists($tempPath)) {
            $newPath = $this->moveToPermanent($tempPath, $tempDisk, $publicDisk, $destinationDir);

            if ($newPath) {
                Log::info("Relocated file from temp: {$tempPath} -> {$newPath}");

                return asset('storage/'.$newPath);
            }
        }

        return $url;
    }

    /**
     * Process columns block with nested blocks.
     *
     * @param  Filesystem  $tempDisk
     * @param  Filesystem  $publicDisk
     */
    private function processColumnsBlock(array $attributes, $tempDisk, $publicDisk): array
    {
        $columns = $attributes['columns'] ?? [];

        foreach ($columns as &$column) {
            $blocks = $column['blocks'] ?? [];
            $column['blocks'] = $this->processBlocks($blocks, $tempDisk, $publicDisk);
        }

        $attributes['columns'] = $columns;

        return $attributes;
    }

    /**
     * Extract storage path from URL if it matches the given disk.
     */
    private function extractStoragePath(string $url, string $diskName): ?string
    {
        // Handle temp disk URLs (not publicly accessible, just paths)
        if ($diskName === 'temp') {
            // Check for temp:// protocol
            if (str_starts_with($url, 'temp://')) {
                return str_replace('temp://', '', $url);
            }

            // Check if URL contains temp path patterns
            if (str_contains($url, '/temp/')) {
                $parts = explode('/temp/', $url);

                return $parts[1] ?? null;
            }

            // Check if it's a direct temp path
            $tempPrefix = storage_path('app/temp');
            if (str_starts_with($url, $tempPrefix)) {
                return str_replace($tempPrefix.'/', '', $url);
            }
        }

        return null;
    }

    /**
     * Move a file from temp to permanent storage.
     *
     * @param  Filesystem  $tempDisk
     * @param  Filesystem  $publicDisk
     * @return string|null The new relative path or null on failure
     */
    private function moveToPermanent(
        string $tempPath,
        $tempDisk,
        $publicDisk,
        string $destinationDir
    ): ?string {
        try {
            $filename = basename($tempPath);
            $newPath = $destinationDir.'/'.$filename;

            // Ensure destination directory exists
            $dirPath = dirname($publicDisk->path($newPath));
            if (! is_dir($dirPath)) {
                mkdir($dirPath, 0755, true);
            }

            // Read from temp and write to public
            $content = $tempDisk->get($tempPath);
            $publicDisk->put($newPath, $content);

            // Verify the file was written
            if ($publicDisk->exists($newPath)) {
                // Delete from temp
                $tempDisk->delete($tempPath);

                return $newPath;
            }

            Log::error("Failed to verify relocated file: {$newPath}");

            return null;
        } catch (\Exception $e) {
            Log::error("Failed to relocate file from temp: {$tempPath}", [
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
