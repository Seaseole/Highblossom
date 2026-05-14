<?php

declare(strict_types=1);

namespace App\Services;

use App\Domains\Content\Models\GalleryImage;
use App\Services\MediaRegistryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

final class GalleryService
{
    public function __construct(
        private MediaRegistryService $mediaRegistryService
    ) {}

    public function create(array $data, Request $request): GalleryImage
    {
        $path = $this->resolveImagePath($request, null);
        $data['image_path'] = $path;

        $galleryImage = GalleryImage::create($data);

        if ($path && $path !== 'placeholder.gif') {
            $this->mediaRegistryService->register(
                $path,
                $request->file('image')?->getClientOriginalName() ?? 'uploaded',
                $request->file('image')?->getSize() ?? 0,
                $galleryImage,
                'image_path'
            );
        }

        return $galleryImage;
    }

    public function update(GalleryImage $item, array $data, Request $request): GalleryImage
    {
        $oldPath = $item->image_path;
        $newPath = $this->resolveImagePath($request, $oldPath);

        if (!isset($newPath)) {
            unset($data['image_path']);
        } else {
            $data['image_path'] = $newPath;
        }

        $item->update($data);

        // Handle registry updates...
        if ($newPath !== $oldPath) {
            if ($oldPath && $oldPath !== 'placeholder.gif') {
                $this->mediaRegistryService->unregister($item, 'image_path');
            }
            if ($newPath && $newPath !== 'placeholder.gif') {
                $this->mediaRegistryService->register(
                    $newPath,
                    $request->file('image')?->getClientOriginalName() ?? 'updated',
                    $request->file('image')?->getSize() ?? 0,
                    $item,
                    'image_path'
                );
            }
        }

        return $item->fresh();
    }

    public function delete(GalleryImage $item): void
    {
        $this->mediaRegistryService->unregister($item, 'image_path');
        
        // This is a naive cleanup. Real logic should use MediaRegistryService::forceDelete if usage count is 0
        if ($item->image_path) {
            Storage::disk('public')->delete($item->image_path);
        }

        $item->delete();
    }

    private function resolveImagePath(Request $request, ?string $existingPath): ?string
    {
        // Handle removal request first
        if ($request->boolean('remove_image')) {
            if ($existingPath && $existingPath !== 'placeholder.gif') {
                Storage::disk('public')->delete($existingPath);
            }
            return null;
        }

        $imagePath = $request->input('image_path');

        if (!empty($imagePath)) {
            return $imagePath;
        }

        if ($request->hasFile('image')) {
            return $request->file('image')->store('gallery', 'public');
        }

        // For create, use placeholder; for update, keep existing
        return $existingPath === null ? 'placeholder.gif' : null;
    }
}
