<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Domains\Content\Models\GalleryImage;
use App\Domains\Content\Models\GalleryCategory;
use App\Http\Requests\Admin\GalleryItemRequest;
use App\Services\GalleryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class GalleryController
{
    public function __construct(
        private readonly GalleryService $galleryService,
    ) {}

    public function index(): View
    {
        $items = GalleryImage::query()
            ->with('category')
            ->latest()
            ->paginate(15);

        return view('admin.gallery.index', compact('items'));
    }

    public function create(): View
    {
        $categories = GalleryCategory::active()->ordered()->get();

        return view('admin.gallery.create', compact('categories'));
    }

    public function store(GalleryItemRequest $request): RedirectResponse
    {
        $this->galleryService->create($request->validated(), $request);

        return redirect()
            ->route('admin.gallery.index')
            ->with('success', __('messages.gallery_created'));
    }

    public function edit(GalleryImage $item): View
    {
        $categories = GalleryCategory::active()->ordered()->get();

        return view('admin.gallery.edit', compact('item', 'categories'));
    }

    public function update(GalleryItemRequest $request, GalleryImage $item): RedirectResponse
    {
        $this->galleryService->update($item, $request->validated(), $request);

        return redirect()
            ->route('admin.gallery.index')
            ->with('success', __('messages.gallery_updated'));
    }

    public function destroy(GalleryImage $item): RedirectResponse
    {
        $this->galleryService->delete($item);

        return redirect()
            ->route('admin.gallery.index')
            ->with('success', __('messages.gallery_deleted'));
    }
}
