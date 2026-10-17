<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasSeo;
use App\Models\Contracts\HasSeoInterface;
use App\Observers\SitemapObserver;
use Highblossom\ContentBlocks\Support\MediaUrl;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

#[Fillable(['title', 'slug', 'excerpt', 'content', 'featured_image_path', 'featured_image_url', 'status', 'published_at', 'user_id', 'seo_metadata'])]
#[ObservedBy(SitemapObserver::class)]
/**
 * Blog posts with SEO, categories, tags, and publishing workflow.
 * Maps to the `posts` database table.
 */
final class Post extends Model implements HasSeoInterface
{
    use HasSeo;

    /**
     * Character limits enforced on the admin form and by validation, kept here so
     * the two can never drift apart.
     */
    public const TITLE_MAX = 120;

    public const EXCERPT_MAX = 300;

    /**
     * Workflow states. `archived` posts keep their content but leave the public site,
     * because the published scope only ever matches `published`.
     */
    public const STATUSES = ['draft', 'published', 'archived'];

    /**
     * Block attribute keys that hold author-written prose, used for the read-time
     * estimate. Media and structural attributes are deliberately excluded.
     */
    private const PROSE_KEYS = [
        'content', 'title', 'label', 'description', 'question', 'options',
        'items', 'headers', 'rows', 'text', 'submit_text', 'button_text', 'cite',
    ];

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
            return MediaUrl::asset($this->attributes['featured_image_url']);
        }

        if ($this->featured_image_path) {
            return MediaUrl::asset($this->featured_image_path);
        }

        return null;
    }

    /**
     * Whether the post is published but held back for a future publish date.
     */
    public function isScheduled(): bool
    {
        return $this->status === 'published' && $this->published_at !== null && $this->published_at->isFuture();
    }

    /**
     * Whether readers can actually reach this post right now.
     */
    public function isLive(): bool
    {
        return $this->status === 'published' && $this->published_at !== null && ! $this->isScheduled();
    }

    /**
     * The unfinished parts that block publishing, as short field labels.
     *
     * @return array<int, string>
     */
    public function missingPublishRequirements(): array
    {
        $missing = [];

        if (empty($this->content)) {
            $missing[] = 'content';
        }

        if (trim((string) $this->excerpt) === '') {
            $missing[] = 'excerpt';
        }

        if ($this->relationLoaded('categories') ? $this->categories->isEmpty() : ! $this->categories()->exists()) {
            $missing[] = 'category';
        }

        return $missing;
    }

    /**
     * Estimated minutes to read, based on the prose held by the content blocks.
     */
    public function readTimeMinutes(): int
    {
        $words = str_word_count($this->plainText());

        return $words > 0 ? max(1, (int) ceil($words / 200)) : 1;
    }

    /**
     * Flatten the prose out of the content blocks so it can be counted or previewed.
     */
    public function plainText(): string
    {
        return trim(strip_tags($this->collectProse($this->content ?? [])));
    }

    /**
     * Recursively gather string values held under the prose attribute keys.
     */
    private function collectProse(mixed $value): string
    {
        if (! is_array($value)) {
            return '';
        }

        $parts = [];

        foreach ($value as $key => $item) {
            if (is_array($item)) {
                $parts[] = $this->collectProse($item);

                continue;
            }

            if (is_string($item) && in_array($key, self::PROSE_KEYS, true)) {
                $parts[] = $item;
            }
        }

        return implode(' ', $parts);
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
