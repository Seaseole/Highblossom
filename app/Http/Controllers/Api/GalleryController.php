<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GalleryItemRequest;
use App\Http\Resources\Api\GalleryImageResource;
use App\Models\GalleryImage;
use App\Services\GalleryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Manage the curated public gallery for the staff app, including image upload.
 *
 * Reuses the web admin's GalleryItemRequest and GalleryService so uploads get the
 * same webp processing, media-registry tracking and gallery-source forcing (rows
 * are always `source=gallery`, never touching the library line). Lists are scoped
 * with `inGallery()` to honour the gallery / media-library split. The route param
 * is `item` because GalleryItemRequest resolves sort-order against it.
 */
final class GalleryController extends Controller
{
    public function __construct(private readonly GalleryService $galleryService) {}

    /**
     * List gallery images, optionally filtered by category slug.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $items = GalleryImage::query()
            ->inGallery()
            ->with('category')
            ->when($request->query('category'), fn ($q, $slug) => $q->byCategory($slug))
            ->when($request->query('featured'), fn ($q) => $q->featured())
            ->ordered()
            ->paginate(min(100, max(1, $request->integer('per_page', 15))));

        return GalleryImageResource::collection($items);
    }

    /**
     * Show one gallery image.
     */
    public function show(GalleryImage $item): GalleryImageResource
    {
        $item->load('category');

        return new GalleryImageResource($item);
    }

    /**
     * Create a gallery image (multipart upload of the `image` field).
     */
    public function store(GalleryItemRequest $request): JsonResponse
    {
        $item = $this->galleryService->create($request->validated(), $request);
        $item->load('category');

        return response()->json([
            'data' => new GalleryImageResource($item),
            'message' => __('messages.gallery_created'),
        ], 201);
    }

    /**
     * Update a gallery image, optionally replacing its file.
     */
    public function update(GalleryItemRequest $request, GalleryImage $item): JsonResponse
    {
        $item = $this->galleryService->update($item, $request->validated(), $request);
        $item->load('category');

        return response()->json([
            'data' => new GalleryImageResource($item),
            'message' => __('messages.gallery_updated'),
        ]);
    }

    /**
     * Delete a gallery image and unregister its media.
     */
    public function destroy(GalleryImage $item): JsonResponse
    {
        $this->galleryService->delete($item);

        return response()->json(['message' => __('messages.gallery_deleted')]);
    }
}
