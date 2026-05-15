<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\SeoStaticRoute;
use App\Http\Requests\Admin\SeoStaticRouteRequest;
use App\Services\SeoService;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class SeoController
{
    public function __construct(
        private readonly SeoService $seoService,
    ) {}

    public function index(): View
    {
        $routes = $this->seoService->getRoutesWithSeo();

        return view('admin.seo.index', compact('routes'));
    }

    public function create(Request $request): View
    {
        $routeName = $request->query('route_name');
        $routeLabel = $routeName ? $this->seoService->getRouteLabel($routeName) : '';

        return view('admin.seo.create', [
            'route_name' => $routeName,
            'route_label' => $routeLabel,
        ]);
    }

    public function store(SeoStaticRouteRequest $request)
    {
        $this->seoService->create($request->validated());

        return redirect()
            ->route('admin.seo.static-routes')
            ->with('success', __('messages.seo_created'));
    }

    public function edit(int $id): View
    {
        $route = SeoStaticRoute::findOrFail($id);

        return view('admin.seo.edit', [
            'route' => $route,
            'route_label' => $this->seoService->getRouteLabel($route->route_name),
        ]);
    }

    public function update(SeoStaticRouteRequest $request, int $id)
    {
        $this->seoService->update($id, $request->validated());

        return redirect()
            ->route('admin.seo.static-routes')
            ->with('success', __('messages.seo_saved'));
    }
}
