<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Domains\Content\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

final class MediaLibraryController
{
    public function index(Request $request)
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

    public function upload(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|in:automotive,heavy_machinery,fleet,other',
        ]);

        $imagePath = null;
        
        // Use AJAX uploaded path if provided, otherwise use traditional file upload
        if (!empty($request->input('image_path'))) {
            $imagePath = $request->input('image_path');
        } elseif ($request->hasFile('upload')) {
            try {
                $file = $request->file('upload');
                if ($file && $file->isValid()) {
                    $imagePath = $file->store('gallery', 'public');
                }
            } catch (\Exception $e) {
                Log::error('Failed to store media library image: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload image.',
                ], 500);
            }
        }

        if (!$imagePath) {
            return response()->json([
                'success' => false,
                'message' => 'No image provided.',
            ], 422);
        }

        $image = GalleryImage::create([
            'title' => $validated['title'],
            'image_path' => $imagePath,
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
