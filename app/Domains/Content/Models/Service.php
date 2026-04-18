<?php

namespace App\Domains\Content\Models;

use App\Domains\Seo\Contracts\HasSeoInterface;
use App\Domains\Seo\Traits\HasSeo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model implements HasSeoInterface
{
    use HasFactory, HasSeo;

    protected $fillable = [
        'title',
        'slug',
        'icon',
        'short_description',
        'full_description',
        'features',
        'is_active',
        'sort_order',
        'seo_metadata',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'seo_metadata' => 'json',
    ];

    /**
     * Scope for active services.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered services.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    public function seoDefaults(): array
    {
        return [
            'meta_title' => $this->title,
            'meta_description' => $this->short_description,
            'og_type' => 'product',
            'priority' => 0.7,
            'changefreq' => 'monthly',
        ];
    }

    protected function getRouteName(): string
    {
        return 'services.show';
    }

    protected function getRouteParameters(): array
    {
        return ['service' => $this->slug];
    }
}
