<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\GalleryCategoryRequest;
use App\Models\GalleryCategory;
use App\Services\GalleryCategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Manage CRUD operations for gallery categories.
 */
final class GalleryCategoryController
{
    public function __construct(
        private readonly GalleryCategoryService $categoryService,
    ) {}

    /**
     * Display a paginated list of gallery categories.
     */
    public function index(): View
    {
        $categories = GalleryCategory::query()->ordered()->paginate(15);

        return view('admin.gallery-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new gallery category.
     */
    public function create(): View
    {
        return view('admin.gallery-categories.create');
    }

    /**
     * Store a newly created gallery category in storage.
     */
    public function store(GalleryCategoryRequest $request): RedirectResponse
    {
        $this->categoryService->create($request->validated());

        return redirect()
            ->route('admin.gallery-categories.index')
            ->with('success', __('messages.gallery_category_created'));
    }

    /**
     * Show the form for editing the specified gallery category.
     */
    public function edit(GalleryCategory $galleryCategory): View
    {
        return view('admin.gallery-categories.edit', compact('galleryCategory'));
    }

    /**
     * Update the specified gallery category in storage.
     */
    public function update(GalleryCategoryRequest $request, GalleryCategory $galleryCategory): RedirectResponse
    {
        $this->categoryService->update($galleryCategory, $request->validated());

        return redirect()
            ->route('admin.gallery-categories.index')
            ->with('success', __('messages.gallery_category_updated'));
    }

    /**
     * Remove the specified gallery category from storage.
     */
    public function destroy(GalleryCategory $galleryCategory): RedirectResponse
    {
        $this->categoryService->delete($galleryCategory);

        return redirect()
            ->route('admin.gallery-categories.index')
            ->with('success', __('messages.gallery_category_deleted'));
    }
}
