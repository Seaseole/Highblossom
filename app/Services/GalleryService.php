<?php

declare(strict_types=1);

namespace App\Services;

use App\Domains\Content\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

final class GalleryService
{
    public function create(array $data, Request $request): GalleryImage
    {
        $data['image_path'] = $this->resolveImagePath($request, null);

        return GalleryImage::create($data);
    }

    public function update(GalleryImage $item, array $data, Request $request): GalleryImage
    {
        $data['image_path'] = $this->resolveImagePath($request, $item->image_path);

        // Remove image_path if no new image and keeping existing
        if (!isset($data['image_path'])) {
            unset($data['image_path']);
        }

        $item->update($data);

        return $item->fresh();
    }

    public function delete(GalleryImage $item): void
    {
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
