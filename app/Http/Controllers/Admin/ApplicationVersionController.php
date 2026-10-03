<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreApplicationVersionRequest;
use App\Models\ApplicationVersion;
use App\Services\ApplicationVersionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Append-only registry of application releases identified by semantic versions.
 */
final class ApplicationVersionController
{
    public function __construct(
        private readonly ApplicationVersionService $versions,
    ) {}

    /**
     * Display the release history with the current version and bump suggestions.
     */
    public function index(): View
    {
        return view('admin.versions.index', [
            'current' => $this->versions->current(),
            'nextVersions' => $this->versions->nextOptions(),
            'releases' => ApplicationVersion::query()->newestFirst()->with('creator')->get(),
        ]);
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
