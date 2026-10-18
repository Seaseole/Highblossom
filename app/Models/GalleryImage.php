<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasSortOrder;
use App\Observers\SitemapObserver;
use Highblossom\ContentBlocks\Support\MediaUrl;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Gallery images with categorisation and featured flag.
 * Maps to the `gallery_images` database table.
 *
 * The table backs two surfaces, told apart by `source`: the curated public
 * gallery (see self::SOURCE_GALLERY) and the media library content blocks pick
 * assets from (see self::SOURCE_LIBRARY). Only gallery rows may render publicly.
 */
#[ObservedBy(SitemapObserver::class)]
final class GalleryImage extends Model
{
    use HasSortOrder;

    /**
     * Row curated on the public gallery by the admin Gallery form.
     */
    public const SOURCE_GALLERY = 'gallery';

    /**
     * Row stored for content blocks by the media library.
     */
    public const SOURCE_LIBRARY = 'library';

    protected $fillable = [
        'title',
        'description',
        'image_path',
        'source',
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
     * Mirrors the column default so an unsaved row knows which numbering line it
     * belongs to instead of reading its scope as null.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'source' => self::SOURCE_GALLERY,
    ];

    /**
     * Gallery and library rows keep their own numbering line, so a library upload
     * can never consume or clash with a public gallery rank.
     *
     * @return array<int, string>
     */
    public function sortOrderScopeColumns(): array
    {
        return ['source'];
    }

    /**
     * Gallery images are named by their title in a sort-order clash message.
     */
    public function sortOrderLabelColumn(): string
    {
        return 'title';
    }

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
     * Scope query to rows belonging to the curated public gallery.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeInGallery($query)
    {
        return $query->where('source', self::SOURCE_GALLERY);
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

        return MediaUrl::asset($this->image_path);
    }
}
