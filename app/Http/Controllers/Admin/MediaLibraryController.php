<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Domains\Content\Models\GalleryImage;
use App\Http\Requests\Admin\MediaLibraryRequest;
use App\Services\MediaLibraryService;
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
            ->when($request->search, fn ($q) => $q->where('title', 'like', '%' . $request->search . '%'))
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
}
