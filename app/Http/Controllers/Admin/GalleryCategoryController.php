<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\GalleryCategory;
use App\Http\Requests\Admin\GalleryCategoryRequest;
use App\Services\GalleryCategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class GalleryCategoryController
{
    public function __construct(
        private readonly GalleryCategoryService $categoryService,
    ) {}

    public function index(): View
    {
        $categories = GalleryCategory::query()->ordered()->paginate(15);

        return view('admin.gallery-categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.gallery-categories.create');
    }

    public function store(GalleryCategoryRequest $request): RedirectResponse
    {
        $this->categoryService->create($request->validated());

        return redirect()
            ->route('admin.gallery-categories.index')
            ->with('success', __('messages.gallery_category_created'));
    }

    public function edit(GalleryCategory $galleryCategory): View
    {
        return view('admin.gallery-categories.edit', compact('galleryCategory'));
    }

    public function update(GalleryCategoryRequest $request, GalleryCategory $galleryCategory): RedirectResponse
    {
        $this->categoryService->update($galleryCategory, $request->validated());

        return redirect()
            ->route('admin.gallery-categories.index')
            ->with('success', __('messages.gallery_category_updated'));
    }

    public function destroy(GalleryCategory $galleryCategory): RedirectResponse
    {
        $this->categoryService->delete($galleryCategory);

        return redirect()
            ->route('admin.gallery-categories.index')
            ->with('success', __('messages.gallery_category_deleted'));
    }
}
