<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CategoryResource;
use App\Http\Resources\Api\TagResource;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Read-only blog taxonomy for the staff app, so posts can show their categories
 * and tags. Creating/editing taxonomy stays on the web admin. Gated by `view blog`.
 */
final class TaxonomyController extends Controller
{
    /**
     * List all categories, alphabetically.
     */
    public function categories(): AnonymousResourceCollection
    {
        return CategoryResource::collection(Category::orderBy('name')->get());
    }

    /**
     * List all tags, alphabetically.
     */
    public function tags(): AnonymousResourceCollection
    {
        return TagResource::collection(Tag::orderBy('name')->get());
    }
}
