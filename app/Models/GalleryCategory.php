<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasSortOrder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Gallery image categories.
 * Maps to the `gallery_categories` database table.
 */
final class GalleryCategory extends Model
{
    use HasSortOrder;

    protected $fillable = [
        'name',
        'slug',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope query to only include active categories.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope query to order by sort order, with an id tie-break for rows outside the
     * same uniqueness scope.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderByDesc('id');
    }

    /**
     * Get the images in this category.
     *
     * @return HasMany
     */
    public function galleryImages()
    {
        return $this->hasMany(GalleryImage::class, 'gallery_category_id');
    }
}
