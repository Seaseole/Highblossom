<?php

declare(strict_types=1);

namespace App\Domains\Content\Actions\Posts;

use App\Domains\Content\Models\Post;
use Illuminate\Support\Facades\Cache;

final class PublishPost
{
    public function execute(Post $post): Post
    {
        if ($post->is_published) {
            return $post;
        }

        $post->update([
            'is_published' => true,
            'published_at' => now(),
        ]);

        // Clear related caches
        Cache::forget("post_{$post->slug}");
        Cache::forget('blog.posts.latest');
        Cache::forget('blog.posts.featured');

        return $post->fresh();
    }
}
