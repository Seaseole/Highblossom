<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\DataTransferObjects\SeoMetadata;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * SEO metadata for static routes with schema.org support.
 * Maps to the `seo_static_routes` database table.
 */
final class SeoStaticRoute extends Model
{
    protected $fillable = [
        'route_name',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'og_image',
        'og_type',
        'twitter_card',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'canonical_url',
        'robots',
        'no_index',
        'priority',
        'changefreq',
        'schema_json_ld',
    ];

    protected $casts = [
        'no_index' => 'boolean',
        'priority' => 'decimal:2',
        'schema_json_ld' => 'array',
    ];

    /**
     * Scope query to only include routes that should be indexed.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeIndexable($query)
    {
        return $query->where('no_index', false);
    }

    /**
     * Convert this route's SEO data into a SeoMetadata DTO.
     */
    public function toMetadata(): SeoMetadata
    {
        return new SeoMetadata(
            metaTitle: $this->meta_title,
            metaDescription: $this->meta_description,
            metaKeywords: $this->meta_keywords,
            ogTitle: $this->og_title,
            ogDescription: $this->og_description,
            ogImage: $this->og_image,
            ogType: $this->og_type,
            twitterCard: $this->twitter_card,
            twitterTitle: $this->twitter_title,
            twitterDescription: $this->twitter_description,
            twitterImage: $this->twitter_image,
            canonicalUrl: $this->canonical_url,
            robots: $this->robots,
        );
    }
}
