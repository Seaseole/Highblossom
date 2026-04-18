<?php

declare(strict_types=1);

namespace App\Domains\Content\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class PostRevision extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'post_id',
        'user_id',
        'title',
        'excerpt',
        'content_blocks',
        'seo_metadata',
        'revision_note',
        'created_at',
    ];

    protected $casts = [
        'content_blocks' => 'json',
        'seo_metadata' => 'json',
        'created_at' => 'datetime',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Restore this revision to the post
     */
    public function restore(): Post
    {
        $post = $this->post;

        // Update post content
        $post->update([
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'seo_metadata' => $this->seo_metadata,
        ]);

        // Clear existing blocks and restore from revision
        $post->contentBlocks()->delete();

        foreach ($this->content_blocks as $index => $blockData) {
            $post->contentBlocks()->create([
                'type' => $blockData['type'],
                'content' => $blockData['content'],
                'sort_order' => $index,
                'is_visible' => $blockData['is_visible'] ?? true,
            ]);
        }

        // Clear cache
        cache()->forget("post.{$post->slug}.blocks");

        return $post->fresh();
    }
}
