<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Image\ImageException;
use Illuminate\Support\Facades\Image;
use Illuminate\Support\Facades\Log;

/**
 * Service for managing the media library (gallery image uploads).
 */
final class MediaLibraryService
{
    /**
     * Create a new media library entry from request data.
     *
     * @throws \RuntimeException When no image is provided or upload fails
     */
    public function create(array $data, Request $request): GalleryImage
    {
        $imagePath = $this->resolveImagePath($request);

        if (! $imagePath) {
            throw new \RuntimeException('No image provided.');
        }

        return GalleryImage::create([
            'title' => $data['title'],
            'image_path' => $imagePath,
            'category' => $data['category'],
            'is_active' => true,
            'sort_order' => GalleryImage::max('sort_order') + 1,
        ]);
    }

    /**
     * Resolve image path from request input or file upload.
     *
     * @throws \RuntimeException When file upload fails
     */
    private function resolveImagePath(Request $request): ?string
    {
        $imagePath = $request->input('image_path');

        if (! empty($imagePath)) {
            return $imagePath;
        }

        if ($request->hasFile('upload')) {
            try {
                $file = $request->file('upload');
                if ($file && $file->isValid()) {
                    return Image::fromUpload($file)
                        ->scale(width: 1920, height: 1920)
                        ->toWebp()
                        ->quality(82)
                        ->store('gallery', 'public');
                }
            } catch (ImageException $e) {
                Log::error('Failed to process media library image: '.$e->getMessage());
                throw new \RuntimeException('Failed to upload image.');
            }
        }

        return null;
    }
}
