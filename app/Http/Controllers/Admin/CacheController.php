<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Services\CacheManagerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Inspect and manage application caches from the admin panel.
 */
final class CacheController
{
    public function __construct(
        private readonly CacheManagerService $cacheManager,
    ) {}

    /**
     * Display the cache management dashboard.
     */
    public function index(): View
    {
        return view('admin.cache.index', [
            'groups' => $this->cacheManager->stats(),
        ]);
    }

    /**
     * Clear the cache entries of a single group.
     */
    public function clear(string $group): RedirectResponse
    {
        abort_unless(in_array($group, $this->cacheManager->groupKeys(), true), 404);

        $this->cacheManager->clear($group);

        return back()->with('success', __(
            'messages.cache_group_cleared',
            ['group' => $this->cacheManager->groups()[$group]['label']]
        ));
    }

    /**
     * Warm or rebuild the cache of a single group.
     */
    public function optimize(string $group): RedirectResponse
    {
        abort_unless(in_array($group, $this->cacheManager->optimizableGroupKeys(), true), 404);

        $this->cacheManager->optimize($group);

        return back()->with('success', __(
            'messages.cache_group_optimized',
            ['group' => $this->cacheManager->groups()[$group]['label']]
        ));
    }

    /**
     * Clear all cache groups.
     */
    public function clearAll(): RedirectResponse
    {
        $this->cacheManager->clearAll();

        return back()->with('success', __('messages.cache_all_cleared'));
    }

    /**
     * Optimize all optimizable cache groups.
     */
    public function optimizeAll(): RedirectResponse
    {
        $this->cacheManager->optimizeAll();

        return back()->with('success', __('messages.cache_all_optimized'));
    }
}
