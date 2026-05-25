<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\MediaLibraryRequest;
use App\Models\GalleryImage;
use App\Services\MediaLibraryService;
use App\Services\MediaRegistryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class MediaLibraryController
{
    public function __construct(
        private readonly MediaLibraryService $mediaLibraryService,
    ) {}

    public function index(Request $request): View
    {
        $images = GalleryImage::query()
            ->when($request->search, fn ($q) => $q->where('title', 'like', '%'.$request->search.'%'))
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        if ($request->header('HX-Request')) {
            return view('admin.media-library.partials.image-grid', compact('images'));
        }

        return view('admin.media-library.index', compact('images'));
    }

    public function upload(MediaLibraryRequest $request): JsonResponse
    {
        try {
            $image = $this->mediaLibraryService->create($request->validated(), $request);

            return response()->json([
                'url' => $image->image_url,
                'id' => $image->id,
            ]);
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getMessage() === 'No image provided.' ? 422 : 500);
        }
    }

    public function show(GalleryImage $image): JsonResponse
    {
        $image->load(['media.usages.model']);

        return response()->json([
            'id' => $image->id,
            'title' => $image->title,
            'url' => $image->image_url,
            'path' => $image->image_path,
            'metadata' => $image->media ? [
                'original_name' => $image->media->original_name,
                'file_size' => $this->formatFileSize($image->media->file_size),
                'created_at' => $image->media->created_at->format('Y-m-d H:i:s'),
                'usage_count' => $image->media->usages->count(),
                'usages' => $image->media->usages->map(fn ($usage) => [
                    'model' => class_basename($usage->model_type),
                    'id' => $usage->model_id,
                ]),
            ] : null,
        ]);
    }

    public function destroy(GalleryImage $image): JsonResponse
    {
        try {
            if ($image->media) {
                // If it's registered in the media registry, try to force delete it from there
                // But GalleryImage::delete() also handles its own cleanup if not using service.
                // Let's use the RegistryService for superpowers.

                $registryId = $image->media->id;

                // First unregister this specific usage
                MediaRegistryService::class; // Ensure loaded
                $registryService = app(MediaRegistryService::class);
                $registryService->unregister($image, 'image_path');

                // Then delete the gallery image record
                $image->delete();

                // Finally try to force delete the file if no more usages exist
                $deletedFile = $registryService->forceDelete($registryId);

                return response()->json([
                    'success' => true,
                    'file_deleted' => $deletedFile,
                ]);
            }

            $image->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function formatFileSize(?int $bytes): string
    {
        if (! $bytes) {
            return '0 B';
        }
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, 2).' '.$units[$pow];
    }
}
