<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Image\ImageException;
use Illuminate\Support\Facades\Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Service for handling image uploads via the admin AJAX uploader.
 *
 * Wraps Laravel 13.20's first-party `Image` component to normalise every
 * upload to WebP at a fixed quality before storage on the public disk.
 * Falls back to the raw upload path if image processing fails, so a
 * transient driver issue never takes the whole upload endpoint down.
 */
final class ImageUploadService
{
    /**
     * Maximum long-edge dimension for uploaded images.
     *
     * @var int<1, max>
     */
    private const MAX_DIMENSION = 1920;

    /**
     * WebP quality (1-100) applied to processed uploads.
     *
     * @var int<1, 100>
     */
    private const QUALITY = 82;

    /**
     * Process and store an uploaded image, normalising it to WebP.
     *
     * @return array{path: string, url: string}
     *
     * @throws \RuntimeException When the file is invalid or storage fails
     */
    public function upload(UploadedFile $file, string $folder = 'uploads'): array
    {
        if (! $file || ! $file->isValid()) {
            throw new \RuntimeException('Invalid file upload.');
        }

        try {
            $path = Image::fromUpload($file)
                ->scale(width: self::MAX_DIMENSION, height: self::MAX_DIMENSION)
                ->toWebp()
                ->quality(self::QUALITY)
                ->store($folder, 'public');
        } catch (ImageException $e) {
            Log::error('Failed to process uploaded image, storing raw file: '.$e->getMessage());
            $path = $file->store($folder, 'public');
        }

        if ($path === false) {
            throw new \RuntimeException('Failed to store uploaded file.');
        }

        return [
            'path' => $path,
            'url' => Storage::url($path),
        ];
    }
}
