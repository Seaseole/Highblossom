<?php

declare(strict_types=1);

namespace App\Domains\Content\Models;

use App\Domains\Seo\Contracts\HasSeoInterface;
use App\Domains\Seo\Traits\HasSeo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Post extends Model implements HasSeoInterface
{
    use HasFactory, SoftDeletes, HasSeo;

    protected static ?string $factory = \Database\Factories\PostFactory::class;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'featured_image',
        'author_id',
        'category_id',
        'seo_metadata',
        'is_published',
        'published_at',
        'is_featured',
        'reading_time_minutes',
    ];

    protected $casts = [
        'seo_metadata' => 'json',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'reading_time_minutes' => 'integer',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function contentBlocks(): MorphMany
    {
        return $this->morphMany(ContentBlock::class, 'blockable')->orderBy('sort_order');
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable', 'taggables');
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(PostRevision::class)->latest();
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function seoDefaults(): array
    {
        return [
            'meta_title' => $this->title,
            'meta_description' => $this->excerpt,
            'og_type' => 'article',
            'priority' => 0.7,
            'changefreq' => 'weekly',
        ];
    }

    protected function getRouteName(): string
    {
        return 'blog.show';
    }

    protected function getRouteParameters(): array
    {
        return ['post' => $this->slug];
    }
}
