<?php

declare(strict_types=1);

namespace App\Domains\Content\Models;

use App\Domains\Seo\DataTransferObjects\SeoMetadata;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class SeoStaticRoute extends Model
{
    use HasFactory;

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
        'schema_json_ld' => 'json',
    ];

    public function toMetadata(): SeoMetadata
    {
        return SeoMetadata::fromArray([
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
            'og_title' => $this->og_title,
            'og_description' => $this->og_description,
            'og_image' => $this->og_image,
            'og_type' => $this->og_type ?? 'website',
            'twitter_card' => $this->twitter_card ?? 'summary_large_image',
            'twitter_title' => $this->twitter_title,
            'twitter_description' => $this->twitter_description,
            'twitter_image' => $this->twitter_image,
            'canonical_url' => $this->canonical_url,
            'robots' => $this->robots,
            'no_index' => $this->no_index ?? false,
            'priority' => $this->priority ?? 0.5,
            'changefreq' => $this->changefreq ?? 'monthly',
            'schema_json_ld' => $this->schema_json_ld,
        ]);
    }

    public function scopeIndexable($query)
    {
        return $query->where('no_index', false);
    }

    public function shouldIndex(): bool
    {
        return ! $this->no_index;
    }
}
