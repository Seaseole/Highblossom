<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreApplicationVersionRequest;
use App\Models\ApplicationVersion;
use App\Services\ApplicationVersionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

/**
 * Append-only registry of application releases identified by semantic versions.
 */
final class ApplicationVersionController
{
    /**
     * Releases rendered per "load more" step in the history timeline.
     */
    private const HISTORY_PER_PAGE = 5;

    public function __construct(
        private readonly ApplicationVersionService $versions,
    ) {}

    /**
     * Display the release history with the current version and bump suggestions.
     */
    public function index(Request $request): View
    {
        $timeline = $this->timeline($request);

        return view('admin.versions.index', [
            'current' => $this->versions->current(),
            'nextVersions' => $this->versions->nextOptions(),
        ] + $timeline);
    }

    /**
     * Render the timeline alone so "load more" can append without a page reload.
     */
    public function history(Request $request): View
    {
        return view('admin.versions.timeline', $this->timeline($request));
    }

    /**
     * Resolve the accumulated timeline slice for the requested page count.
     *
     * @return array{releases: Collection<int, ApplicationVersion>, historyPages: int, historyTotal: int, historyHasMore: bool}
     */
    private function timeline(Request $request): array
    {
        $perPage = self::HISTORY_PER_PAGE;
        $total = ApplicationVersion::query()->count();
        $lastPage = max(1, (int) ceil($total / $perPage));
        $pages = min($lastPage, max(1, (int) $request->integer('pages')));

        $releases = ApplicationVersion::query()
            ->newestFirst()
            ->with('creator')
            ->limit($pages * $perPage)
            ->get();

        return [
            'releases' => $releases,
            'historyPages' => $pages,
            'historyTotal' => $total,
            'historyHasMore' => $releases->count() < $total,
        ];
    }

    /**
     * Record a new application release.
     */
    public function store(StoreApplicationVersionRequest $request): RedirectResponse
    {
        $version = $this->versions->record($request->validated(), $request->user());

        return redirect()
            ->route('admin.versions.index')
            ->with('success', "Version {$version->version} recorded.");
    }
}
