<?php

namespace App\Domains\Content\Models;

use App\Domains\Seo\Contracts\HasSeoInterface;
use App\Domains\Seo\Traits\HasSeo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;

final class Page extends Model implements HasSeoInterface
{
    use SoftDeletes, HasSeo;

    protected $fillable = ['title', 'slug', 'seo_metadata', 'is_published', 'published_at'];

    protected $casts = [
        'seo_metadata' => 'json',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function contentBlocks(): MorphMany
    {
        return $this->morphMany(ContentBlock::class, 'blockable')->orderBy('sort_order');
    }

    public function seoDefaults(): array
    {
        return [
            'meta_title' => $this->title,
            'meta_description' => null,
            'og_type' => 'article',
            'priority' => 0.8,
            'changefreq' => 'weekly',
        ];
    }

    protected function getRouteName(): string
    {
        return 'pages.show';
    }

    protected function getRouteParameters(): array
    {
        return ['page' => $this->slug];
    }
}
