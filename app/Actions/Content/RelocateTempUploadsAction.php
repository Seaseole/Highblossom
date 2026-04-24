<?php

declare(strict_types=1);

namespace App\Actions\Content;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

final class RelocateTempUploadsAction
{
    /**
     * Relocate uploaded files from temp storage to permanent storage.
     *
     * @param array $contentBlocks The content blocks array containing file URLs
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
     * @param array $blocks
     * @param \Illuminate\Contracts\Filesystem\Filesystem $tempDisk
     * @param \Illuminate\Contracts\Filesystem\Filesystem $publicDisk
     * @return array
     */
    private function processBlocks(array $blocks, $tempDisk, $publicDisk): array
    {
        foreach ($blocks as &$block) {
            if (!is_array($block) || !isset($block['type'])) {
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
     *
     * @param array $attributes
     * @param \Illuminate\Contracts\Filesystem\Filesystem $tempDisk
     * @param \Illuminate\Contracts\Filesystem\Filesystem $publicDisk
     * @return array
     */
    private function processImageBlock(array $attributes, $tempDisk, $publicDisk): array
    {
        $src = $attributes['src'] ?? '';

        if (empty($src)) {
            return $attributes;
        }

        // Check if the file is in temp storage
        $tempPath = $this->extractStoragePath($src, 'temp');

        if ($tempPath && $tempDisk->exists($tempPath)) {
            $newPath = $this->moveToPermanent($tempPath, $tempDisk, $publicDisk, 'uploads/images');

            if ($newPath) {
                $attributes['src'] = asset('storage/' . $newPath);
                Log::info("Relocated image from temp: {$tempPath} -> {$newPath}");
            }
        }

        return $attributes;
    }

    /**
     * Process video block attributes.
     *
     * @param array $attributes
     * @param \Illuminate\Contracts\Filesystem\Filesystem $tempDisk
     * @param \Illuminate\Contracts\Filesystem\Filesystem $publicDisk
     * @return array
     */
    private function processVideoBlock(array $attributes, $tempDisk, $publicDisk): array
    {
        $src = $attributes['src'] ?? '';
        $poster = $attributes['poster'] ?? '';

        // Process video file
        if (!empty($src)) {
            $tempPath = $this->extractStoragePath($src, 'temp');

            if ($tempPath && $tempDisk->exists($tempPath)) {
                $newPath = $this->moveToPermanent($tempPath, $tempDisk, $publicDisk, 'uploads/videos');

                if ($newPath) {
                    $attributes['src'] = asset('storage/' . $newPath);
                    Log::info("Relocated video from temp: {$tempPath} -> {$newPath}");
                }
            }
        }

        // Process poster image
        if (!empty($poster)) {
            $tempPath = $this->extractStoragePath($poster, 'temp');

            if ($tempPath && $tempDisk->exists($tempPath)) {
                $newPath = $this->moveToPermanent($tempPath, $tempDisk, $publicDisk, 'uploads/videos/thumbnails');

                if ($newPath) {
                    $attributes['poster'] = asset('storage/' . $newPath);
                    Log::info("Relocated video poster from temp: {$tempPath} -> {$newPath}");
                }
            }
        }

        return $attributes;
    }

    /**
     * Process gallery block attributes.
     *
     * @param array $attributes
     * @param \Illuminate\Contracts\Filesystem\Filesystem $tempDisk
     * @param \Illuminate\Contracts\Filesystem\Filesystem $publicDisk
     * @return array
     */
    private function processGalleryBlock(array $attributes, $tempDisk, $publicDisk): array
    {
        $images = $attributes['images'] ?? [];

        foreach ($images as &$image) {
            $src = $image['src'] ?? '';

            if (empty($src)) {
                continue;
            }

            $tempPath = $this->extractStoragePath($src, 'temp');

            if ($tempPath && $tempDisk->exists($tempPath)) {
                $newPath = $this->moveToPermanent($tempPath, $tempDisk, $publicDisk, 'uploads/gallery');

                if ($newPath) {
                    $image['src'] = asset('storage/' . $newPath);
                    Log::info("Relocated gallery image from temp: {$tempPath} -> {$newPath}");
                }
            }
        }

        $attributes['images'] = $images;

        return $attributes;
    }

    /**
     * Process columns block with nested blocks.
     *
     * @param array $attributes
     * @param \Illuminate\Contracts\Filesystem\Filesystem $tempDisk
     * @param \Illuminate\Contracts\Filesystem\Filesystem $publicDisk
     * @return array
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
     *
     * @param string $url
     * @param string $diskName
     * @return string|null
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
                return str_replace($tempPrefix . '/', '', $url);
            }
        }

        return null;
    }

    /**
     * Move a file from temp to permanent storage.
     *
     * @param string $tempPath
     * @param \Illuminate\Contracts\Filesystem\Filesystem $tempDisk
     * @param \Illuminate\Contracts\Filesystem\Filesystem $publicDisk
     * @param string $destinationDir
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
            $newPath = $destinationDir . '/' . $filename;

            // Ensure destination directory exists
            $dirPath = dirname($publicDisk->path($newPath));
            if (!is_dir($dirPath)) {
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
