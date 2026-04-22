<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Domains\Content\Models\GalleryCategory;
use App\Domains\Content\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class GalleryCategoryController
{
    public function index(): View
    {
        $categories = GalleryCategory::query()->ordered()->paginate(15);

        return view('admin.gallery-categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.gallery-categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        GalleryCategory::create($validated);

        return redirect()
            ->route('admin.gallery-categories.index')
            ->with('success', __('messages.gallery_category_created'));
    }

    public function edit(GalleryCategory $galleryCategory): View
    {
        return view('admin.gallery-categories.edit', compact('galleryCategory'));
    }

    public function update(Request $request, GalleryCategory $galleryCategory): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        $galleryCategory->update($validated);

        return redirect()
            ->route('admin.gallery-categories.index')
            ->with('success', __('messages.gallery_category_updated'));
    }

    public function destroy(GalleryCategory $galleryCategory): RedirectResponse
    {
        // Reassign images to default category (other)
        $defaultCategory = GalleryCategory::where('slug', 'other')->first();
        
        if ($defaultCategory) {
            GalleryImage::where('gallery_category_id', $galleryCategory->id)
                ->update(['gallery_category_id' => $defaultCategory->id]);
        }

        $galleryCategory->delete();

        return redirect()
            ->route('admin.gallery-categories.index')
            ->with('success', __('messages.gallery_category_deleted'));
    }
}
