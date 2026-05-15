<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Contracts\HasSeoInterface;
use App\Models\Concerns\HasSeo;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['title','slug','excerpt','content','featured_image_path', 'featured_image_url','status','published_at','user_id', 'seo_metadata'])]
final class Post extends Model implements HasSeoInterface
{
    use HasSeo;

    protected $casts = [
        'content' => 'array',
        'published_at' => 'datetime',
        'seo_metadata' => 'array',
    ];

    public function seoDefaults(): array
    {
        return [
            'meta_title' => $this->title,
            'meta_description' => $this->excerpt,
            'og_image' => $this->featured_image_url,
        ];
    }

    protected function getRouteName(): string
    {
        return 'blog.show';
    }

    protected function getRouteParameters(): array
    {
        return ['slug' => $this->slug];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'post_category');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        if ($this->attributes['featured_image_url'] ?? null) {
            return $this->attributes['featured_image_url'];
        }

        if ($this->featured_image_path) {
            return asset('storage/' . $this->featured_image_path);
        }

        return null;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = \Illuminate\Support\Str::slug($post->title);
            }
        });

        static::updating(function ($post) {
            if ($post->isDirty('title') && empty($post->slug)) {
                $post->slug = \Illuminate\Support\Str::slug($post->title);
            }
        });
    }
}
