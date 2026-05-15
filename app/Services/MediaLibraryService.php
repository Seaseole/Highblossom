<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

final class MediaLibraryService
{
    public function create(array $data, Request $request): GalleryImage
    {
        $imagePath = $this->resolveImagePath($request);

        if (!$imagePath) {
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

    private function resolveImagePath(Request $request): ?string
    {
        $imagePath = $request->input('image_path');

        if (!empty($imagePath)) {
            return $imagePath;
        }

        if ($request->hasFile('upload')) {
            try {
                $file = $request->file('upload');
                if ($file && $file->isValid()) {
                    return $file->store('gallery', 'public');
                }
            } catch (\Exception $e) {
                Log::error('Failed to store media library image: ' . $e->getMessage());
                throw new \RuntimeException('Failed to upload image.');
            }
        }

        return null;
    }
}
