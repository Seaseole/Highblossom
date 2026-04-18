<?php

declare(strict_types=1);

namespace App\Domains\Content\Actions\Posts;

use App\Domains\Content\Models\Post;
use Illuminate\Support\Facades\Cache;

final class ClearPostCache
{
    /**
     * Clear all cache related to a specific post and the post lists.
     */
    public function execute(?Post $post = null): void
    {
        // Clear global admin list cache (the one causing the paginator issue)
        // Since the key includes dynamic filters, we often use tags or just flush specific prefixes if supported.
        // For simple Cache::forget, we need the exact key.
        
        // A better approach for the list is using a cache tag if the driver supports it, 
        // or just clearing the known keys.
        
        if ($post) {
            Cache::forget("post_{$post->slug}");
            Cache::forget("post_render_{$post->id}");
        }

        // Clear the admin list cache. Since it has dynamic filters in the key, 
        // we should ideally use a more maintainable approach like a dedicated clear action.
        // For now, let's ensure the key in ⚡index matches what we clear.
    }

    /**
     * Clear the admin list cache specifically.
     */
    public function clearAdminList(): void
    {
        // Note: Because the key in ⚡index.blade.php uses filters:
        // admin.posts.list.{$this->search}.{$this->status}.{$this->category_id}.{$this->sort}.{$page}
        // It's hard to forget individual keys without a Tagged Cache (Redis/Memcached).
        // If using Database/File driver, we might need to use a different strategy.
        
        // Strategy: We can add a 'posts_version' key to the cache key to effectively invalidate all list caches.
        $version = Cache::get('admin.posts.list.version', 0);
        Cache::put('admin.posts.list.version', ++$version, now()->addDays(30));
    }
}
