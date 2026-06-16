<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Gallery images with categorisation and featured flag.
 * Maps to the `gallery_images` database table.
 */
final class GalleryImage extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image_path',
        'gallery_category_id',
        'category',
        'is_featured',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope query to only include active images.
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
     * Scope query to only include featured images.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope query to order by sort_order then creation date.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    /**
     * Scope query to filter images by category slug.
     *
     * @param Builder $query
     * @param string  $category
     *
     * @return Builder
     */
    public function scopeByCategory($query, $category)
    {
        return $query->whereHas('category', fn ($q) => $q->where('slug', $category));
    }

    /**
     * Get the category this image belongs to.
     *
     * @return BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(GalleryCategory::class, 'gallery_category_id');
    }

    /**
     * Get the media registry entry for this image.
     *
     * @return HasOne
     */
    public function media()
    {
        return $this->hasOne(MediaRegistry::class, 'path', 'image_path');
    }

    /**
     * Get the full URL to the image.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        return asset('storage/'.$this->image_path);
    }
}
