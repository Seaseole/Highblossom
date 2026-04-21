<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Domains\Content\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

final class MediaLibraryController
{
    public function index(Request $request): View
    {
        $images = GalleryImage::query()
            ->when($request->search, fn ($q) => $q->where('title', 'like', '%' . $request->search . '%'))
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('admin.media-library.index', compact('images'));
    }

    public function upload(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'upload' => 'required|image|max:5120',
            'title' => 'required|string|max:255',
            'category' => 'required|string|in:automotive,heavy_machinery,fleet,other',
        ]);

        $path = $request->file('upload')->store('gallery', 'public');

        $image = GalleryImage::create([
            'title' => $validated['title'],
            'image_path' => $path,
            'category' => $validated['category'],
            'is_active' => true,
            'sort_order' => GalleryImage::max('sort_order') + 1,
        ]);

        return response()->json([
            'url' => $image->image_url,
            'id' => $image->id,
        ]);
    }
}
