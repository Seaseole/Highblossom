<?php

declare(strict_types=1);

namespace App\Domains\Content\Actions\Posts;

use App\Domains\Content\Models\Post;
use Illuminate\Support\Facades\Cache;

final class DeletePost
{
    public function execute(Post $post): bool
    {
        // Clear cache before deletion
        Cache::forget("post_{$post->slug}");
        Cache::forget("post_render_{$post->id}");

        return $post->delete();
    }
}
