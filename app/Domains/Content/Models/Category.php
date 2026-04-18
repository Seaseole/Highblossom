<?php

declare(strict_types=1);

namespace App\Domains\Content\Models;

use App\Domains\Seo\Contracts\HasSeoInterface;
use App\Domains\Seo\Traits\HasSeo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Category extends Model implements HasSeoInterface
{
    use HasFactory, HasSeo;

    protected static ?string $factory = \Database\Factories\CategoryFactory::class;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'sort_order',
        'seo_metadata',
    ];

    protected $casts = [
        'seo_metadata' => 'json',
        'sort_order' => 'integer',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }

    public function scopeForPosts($query)
    {
        return $query->where('type', 'post');
    }

    public function scopeForPages($query)
    {
        return $query->where('type', 'page');
    }

    public function seoDefaults(): array
    {
        return [
            'meta_title' => $this->name,
            'meta_description' => $this->description,
            'og_type' => 'website',
            'priority' => 0.6,
            'changefreq' => 'weekly',
        ];
    }

    protected function getRouteName(): string
    {
        return $this->type === 'post' ? 'blog.category' : 'pages.category';
    }

    protected function getRouteParameters(): array
    {
        return ['category' => $this->slug];
    }
}
