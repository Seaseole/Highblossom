<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasSortOrder;
use Highblossom\ContentBlocks\Support\MediaUrl;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Individual services offered with descriptions, features, and images.
 * Maps to the `services` database table.
 */
final class Service extends Model
{
    use HasSortOrder;

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'full_description',
        'features',
        'is_active',
        'sort_order',
        'image_path',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Services are named by their title in a sort-order clash message.
     */
    public function sortOrderLabelColumn(): string
    {
        return 'title';
    }

    /**
     * Scope query to only include active services.
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
     * Get the full image URL from either image_url or stored image_path.
     */
    public function getFullImageUrlAttribute(): ?string
    {
        if ($this->image_url) {
            return MediaUrl::asset($this->image_url);
        }

        if ($this->image_path) {
            return MediaUrl::asset($this->image_path);
        }

        return null;
    }

    /**
     * Alias for full_image_url.
     */
    public function getImageAttribute(): ?string
    {
        return $this->full_image_url;
    }
}
