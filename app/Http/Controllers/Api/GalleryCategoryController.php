<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\GalleryCategoryResource;
use App\Models\GalleryCategory;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Read-only gallery categories, so the upload form can offer a picker.
 *
 * Category CRUD stays on the web admin. Gated by `view gallery`.
 */
final class GalleryCategoryController extends Controller
{
    /**
     * List active gallery categories in order.
     */
    public function index(): AnonymousResourceCollection
    {
        $categories = GalleryCategory::query()->active()->ordered()->get();

        return GalleryCategoryResource::collection($categories);
    }
}
