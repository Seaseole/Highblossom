<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Domains\Content\Models\GalleryImage;
use App\Domains\Content\Models\GalleryCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;

final class GalleryController
{
    public function index(): View
    {
        $items = GalleryImage::query()->with('category')->latest()->paginate(15);

        return view('admin.gallery.index', compact('items'));
    }

    public function create(): View
    {
        $categories = GalleryCategory::active()->ordered()->get();
        return view('admin.gallery.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'image_path' => 'nullable|string',
            'description' => 'nullable|string',
            'gallery_category_id' => 'required|exists:gallery_categories,id',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'location_address' => 'nullable|string|max:255',
        ]);

        // Use AJAX uploaded path if provided, otherwise use traditional file upload
        if (!empty($validated['image_path'])) {
            // image_path is already set in validated
        } elseif ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('gallery', 'public');
        } else {
            $validated['image_path'] = 'placeholder.gif';
        }

        GalleryImage::create($validated);

        return redirect()
            ->route('admin.gallery.index')
            ->with('success', __('messages.gallery_created'));
    }

    public function edit(GalleryImage $item): View
    {
        $categories = GalleryCategory::active()->ordered()->get();
        return view('admin.gallery.edit', compact('item', 'categories'));
    }

    public function update(Request $request, GalleryImage $item): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'image_path' => 'nullable|string',
            'description' => 'nullable|string',
            'gallery_category_id' => 'required|exists:gallery_categories,id',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'location_address' => 'nullable|string|max:255',
        ]);

        // Use AJAX uploaded path if provided, otherwise use traditional file upload
        if (!empty($validated['image_path'])) {
            if ($item->image_path && $item->image_path !== $validated['image_path']) {
                Storage::disk('public')->delete($item->image_path);
            }
            // image_path is already set in validated
        } elseif ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($item->image_path) {
                Storage::disk('public')->delete($item->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('gallery', 'public');
        } else {
            // Keep existing image if no new image provided
            unset($validated['image_path']);
        }

        $item->update($validated);

        return redirect()
            ->route('admin.gallery.index')
            ->with('success', __('messages.gallery_updated'));
    }

    public function destroy(GalleryImage $item): RedirectResponse
    {
        if ($item->image_path) {
            Storage::disk('public')->delete($item->image_path);
        }
        
        $item->delete();

        return redirect()
            ->route('admin.gallery.index')
            ->with('success', __('messages.gallery_deleted'));
    }
}
