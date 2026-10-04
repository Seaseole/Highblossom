<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\GalleryImage;
use App\Models\Post;
use App\Services\SeoService;
use Illuminate\Database\Eloquent\Model;

/**
 * Invalidate the cached sitemap whenever indexed content changes.
 *
 * Registered for models whose public pages appear in the sitemap
 * (posts and gallery images) so the 24-hour sitemap cache never
 * serves stale URL sets after content edits.
 */
final class SitemapObserver
{
    public function __construct(
        private readonly SeoService $seoService,
    ) {}

    /**
     * Handle post-save events for supported models.
     *
     * @param Post|GalleryImage $model
     */
    public function saved(Model $model): void
    {
        $this->seoService->invalidate();
    }

    /**
     * Handle delete events for supported models.
     *
     * @param Post|GalleryImage $model
     */
    public function deleted(Model $model): void
    {
        $this->seoService->invalidate();
    }
}
