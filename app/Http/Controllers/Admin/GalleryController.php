<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\GalleryItemRequest;
use App\Models\GalleryCategory;
use App\Models\GalleryImage;
use App\Services\GalleryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Manage CRUD operations for gallery images.
 */
final class GalleryController
{
    public function __construct(
        private readonly GalleryService $galleryService,
    ) {}

    /**
     * Display a paginated list of gallery images.
     */
    public function index(): View
    {
        $items = GalleryImage::query()
            ->with('category')
            ->latest()
            ->paginate(15);

        return view('admin.gallery.index', compact('items'));
    }

    /**
     * Show the form for creating a new gallery image.
     */
    public function create(): View
    {
        $categories = GalleryCategory::active()->ordered()->get();

        return view('admin.gallery.create', compact('categories'));
    }

    /**
     * Store a newly created gallery image in storage.
     */
    public function store(GalleryItemRequest $request): RedirectResponse
    {
        $this->galleryService->create($request->validated(), $request);

        return redirect()
            ->route('admin.gallery.index')
            ->with('success', __('messages.gallery_created'));
    }

    /**
     * Show the form for editing the specified gallery image.
     */
    public function edit(GalleryImage $item): View
    {
        $categories = GalleryCategory::active()->ordered()->get();

        return view('admin.gallery.edit', compact('item', 'categories'));
    }

    /**
     * Update the specified gallery image in storage.
     */
    public function update(GalleryItemRequest $request, GalleryImage $item): RedirectResponse
    {
        $this->galleryService->update($item, $request->validated(), $request);

        return redirect()
            ->route('admin.gallery.index')
            ->with('success', __('messages.gallery_updated'));
    }

    /**
     * Remove the specified gallery image from storage.
     */
    public function destroy(GalleryImage $item): RedirectResponse
    {
        $this->galleryService->delete($item);

        return redirect()
            ->route('admin.gallery.index')
            ->with('success', __('messages.gallery_deleted'));
    }
}
