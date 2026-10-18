<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Image\ImageException;
use Illuminate\Support\Facades\Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Create, update and delete services, including their image handling.
 */
final class ServiceService
{
    /**
     * Placeholder sentinel stored when a service has no real image.
     */
    private const PLACEHOLDER = 'placeholder.gif';

    /**
     * Create a service, resolving its image from an upload, a stored path or the placeholder.
     *
     * @param array<string, mixed> $data
     */
    public function create(array $data, Request $request): Service
    {
        $data['image_path'] = $this->resolveImagePath($request, null);

        return Service::create($data);
    }

    /**
     * Update a service, applying image removal or replacement alongside the other fields.
     *
     * @param array<string, mixed> $data
     */
    public function update(Service $service, array $data, Request $request): Service
    {
        if ($request->boolean('remove_image')) {
            $this->deleteStoredFile($service->image_path);
            $data['image_path'] = self::PLACEHOLDER;
        } else {
            $resolvedPath = $this->resolveImagePath($request, $service->image_path);

            if ($resolvedPath !== $service->image_path) {
                $this->deleteStoredFile($service->image_path);
                $data['image_path'] = $resolvedPath;
            } else {
                unset($data['image_path']);
            }
        }

        $service->update($data);

        return $service->fresh();
    }

    /**
     * Delete a service and the image file it owns.
     */
    public function delete(Service $service): void
    {
        $this->deleteStoredFile($service->image_path);

        $service->delete();
    }

    /**
     * Resolve the image path from an uploaded file, a stored-path input, or the existing value.
     */
    private function resolveImagePath(Request $request, ?string $existingPath): ?string
    {
        // An uploaded file always wins over the hidden image_path field: edit
        // forms keep the stored value (often the placeholder sentinel) in a
        // hidden input, which would otherwise shadow every new upload.
        if ($request->hasFile('image')) {
            try {
                $file = $request->file('image');
                if ($file && $file->isValid()) {
                    return Image::fromUpload($file)
                        ->contain(width: 800, height: 600)
                        ->toWebp()
                        ->quality(82)
                        ->store('services', 'public');
                }
            } catch (ImageException $e) {
                Log::error('Failed to process service image: '.$e->getMessage());
            }
        }

        $imagePath = $request->input('image_path');

        if (! empty($imagePath)) {
            return $imagePath;
        }

        // For create, use placeholder; for update, keep existing
        return $existingPath === null ? self::PLACEHOLDER : $existingPath;
    }

    /**
     * Delete a stored image file owned by a service.
     *
     * Only files under services/ are removed: paths adopted from the media
     * library or blog folders may still be referenced elsewhere, and the
     * placeholder sentinel is a shared asset.
     */
    private function deleteStoredFile(?string $path): void
    {
        if ($path !== null && $path !== self::PLACEHOLDER && str_starts_with($path, 'services/')) {
            Storage::disk('public')->delete($path);
        }
    }
}
