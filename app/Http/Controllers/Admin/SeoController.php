<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SeoStaticRouteRequest;
use App\Models\SeoStaticRoute;
use App\Services\SeoService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Manage SEO static routes in the admin panel.
 */
final class SeoController
{
    public function __construct(
        private readonly SeoService $seoService,
    ) {}

    /**
     * Display a listing of static routes with their SEO metadata.
     */
    public function index(): View
    {
        $routes = $this->seoService->getRoutesWithSeo();

        return view('admin.seo.index', compact('routes'));
    }

    /**
     * Show the form for creating a new SEO entry for a static route.
     */
    public function create(Request $request): View
    {
        $routeName = $request->query('route_name');
        $routeLabel = $routeName ? $this->seoService->getRouteLabel($routeName) : '';

        return view('admin.seo.create', [
            'route_name' => $routeName,
            'route_label' => $routeLabel,
        ]);
    }

    /**
     * Store a newly created SEO entry in storage.
     *
     * @return RedirectResponse
     */
    public function store(SeoStaticRouteRequest $request)
    {
        $this->seoService->create($request->validated());

        return redirect()
            ->route('admin.seo.static-routes')
            ->with('success', __('messages.seo_created'));
    }

    /**
     * Show the form for editing the specified SEO route.
     *
     *
     * @throws ModelNotFoundException
     */
    public function edit(int $id): View
    {
        $route = SeoStaticRoute::findOrFail($id);

        return view('admin.seo.edit', [
            'route' => $route,
            'route_label' => $this->seoService->getRouteLabel($route->route_name),
        ]);
    }

    /**
     * Update the specified SEO entry in storage.
     *
     * @return RedirectResponse
     */
    public function update(SeoStaticRouteRequest $request, int $id)
    {
        $this->seoService->update($id, $request->validated());

        return redirect()
            ->route('admin.seo.static-routes')
            ->with('success', __('messages.seo_saved'));
    }
}
