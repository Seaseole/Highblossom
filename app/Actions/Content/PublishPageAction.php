<?php

namespace App\Actions\Content;

use App\Domains\Content\Models\Page;
use Illuminate\Support\Facades\Cache;

final class PublishPageAction
{
    public function __invoke(Page $page): Page
    {
        $page->update([
            'is_published' => true,
            'published_at' => now(),
        ]);

        Cache::forget("page_{$page->slug}");
        
        return $page->refresh();
    }
}
