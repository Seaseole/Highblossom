<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasSeo;
use App\Models\Contracts\HasSeoInterface;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

#[Fillable(['title', 'slug', 'excerpt', 'content', 'featured_image_path', 'featured_image_url', 'status', 'published_at', 'user_id', 'seo_metadata'])]
/**
 * Blog posts with SEO, categories, tags, and publishing workflow.
 * Maps to the `posts` database table.
 */
final class Post extends Model implements HasSeoInterface
{
    use HasSeo;

    protected $casts = [
        'content' => 'array',
        'published_at' => 'datetime',
        'seo_metadata' => 'array',
    ];

    /**
     * Get the default SEO values derived from the post content.
     */
    public function seoDefaults(): array
    {
        return [
            'meta_title' => $this->title,
            'meta_description' => $this->excerpt,
            'og_image' => $this->featured_image_url,
        ];
    }

    /**
     * Get the route name used for canonical URL generation.
     */
    protected function getRouteName(): string
    {
        return 'blog.show';
    }

    /**
     * Get the route parameters used for canonical URL generation.
     */
    protected function getRouteParameters(): array
    {
        return ['slug' => $this->slug];
    }

    /**
     * Get the route key name for implicit model binding.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the categories this post belongs to.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'post_category');
    }

    /**
     * Get the tags assigned to this post.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }

    /**
     * Get the user who authored this post.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope query to only include published posts.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope query to only include draft posts.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope query to order by published date descending.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    /**
     * Get the featured image URL from stored URL or local path.
     */
    public function getFeaturedImageUrlAttribute(): ?string
    {
        if ($this->attributes['featured_image_url'] ?? null) {
            return $this->attributes['featured_image_url'];
        }

        if ($this->featured_image_path) {
            return asset('storage/'.$this->featured_image_path);
        }

        return null;
    }

    /**
     * Boot the model and auto-generate slug on creating/updating.
     */
    protected static function boot()
    {
        parent::boot();

        self::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });

        self::updating(function ($post) {
            if ($post->isDirty('title') && empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }
}
