<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Domains\Content\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\View\View;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;

final class GalleryController
{
    public function index(): View
    {
        $items = GalleryImage::query()->latest()->paginate(15);

        return view('admin.gallery.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'description' => 'nullable|string',
            'category' => 'required|in:automotive,heavy_machinery,fleet,other',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'location_address' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('gallery', 'public');
        } else {
            $validated['image_path'] = 'placeholder.gif';
        }

        GalleryImage::create($validated);

        return redirect()
            ->route('admin.gallery.index')
            ->with('success', 'Gallery item created successfully');
    }

    public function edit(GalleryImage $item): View
    {
        return view('admin.gallery.edit', compact('item'));
    }

    public function update(Request $request, GalleryImage $item): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'description' => 'nullable|string',
            'category' => 'required|in:automotive,heavy_machinery,fleet,other',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'location_address' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($item->image_path) {
                Storage::disk('public')->delete($item->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('gallery', 'public');
        }

        $item->update($validated);

        return redirect()
            ->route('admin.gallery.index')
            ->with('success', 'Gallery item updated successfully');
    }

    public function destroy(GalleryImage $item): RedirectResponse
    {
        if ($item->image_path) {
            Storage::disk('public')->delete($item->image_path);
        }
        
        $item->delete();

        return redirect()
            ->route('admin.gallery.index')
            ->with('success', 'Gallery item deleted successfully');
    }
}
