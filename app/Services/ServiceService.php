<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

final class ServiceService
{
    public function create(array $data, Request $request): Service
    {
        $data['image_path'] = $this->resolveImagePath($request, null);

        return Service::create($data);
    }

    public function update(Service $service, array $data, Request $request): Service
    {
        // Handle image removal explicitly
        if ($request->boolean('remove_image')) {
            $this->removeImage($service);
        } else {
            // Normal image path resolution
            $resolvedPath = $this->resolveImagePath($request, $service->image_path);

            if ($resolvedPath !== $service->image_path) {
                $data['image_path'] = $resolvedPath;
            } else {
                unset($data['image_path']);
            }

            $service->update($data);
        }

        return $service->fresh();
    }

    public function delete(Service $service): void
    {
        if ($service->image_path) {
            Storage::disk('public')->delete($service->image_path);
        }

        $service->delete();
    }

    public function removeImage(Service $service): void
    {
        if ($service->image_path && $service->image_path !== 'placeholder.gif') {
            Storage::disk('public')->delete($service->image_path);
        }
        $service->update(['image_path' => 'placeholder.gif']);
    }

    private function resolveImagePath(Request $request, ?string $existingPath): ?string
    {
        $imagePath = $request->input('image_path');

        if (!empty($imagePath)) {
            return $imagePath;
        }

        if ($request->hasFile('image')) {
            return $request->file('image')->store('services', 'public');
        }

        // For create, use placeholder; for update, keep existing
        return $existingPath === null ? 'placeholder.gif' : $existingPath;
    }
}
