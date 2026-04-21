<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Domains\Seo\Models\SeoStaticRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

final class SeoController
{
    public function index(): View
    {
        $routeNames = config('seo.static_routes', []);
        $existing = SeoStaticRoute::all()->keyBy('route_name');

        $routes = collect($routeNames)->map(function ($routeName) use ($existing) {
            $seoRoute = $existing->get($routeName);

            return [
                'route_name' => $routeName,
                'route_label' => $this->getRouteLabel($routeName),
                'exists' => $seoRoute !== null,
                'id' => $seoRoute?->id,
                'meta_title' => $seoRoute?->meta_title ?? '',
                'meta_description' => $seoRoute?->meta_description ?? '',
                'no_index' => $seoRoute?->no_index ?? false,
                'priority' => $seoRoute?->priority ?? 0.5,
                'changefreq' => $seoRoute?->changefreq ?? 'monthly',
            ];
        })->toArray();

        return view('admin.seo.index', compact('routes'));
    }

    public function edit(int $id): View
    {
        $route = SeoStaticRoute::findOrFail($id);

        return view('admin.seo.edit', [
            'route' => $route,
            'route_label' => $this->getRouteLabel($route->route_name),
        ]);
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'meta_title' => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:300',
            'meta_keywords' => 'nullable|string|max:255',
            'og_title' => 'nullable|string|max:70',
            'og_description' => 'nullable|string|max:300',
            'og_image' => 'nullable|string|max:255',
            'twitter_title' => 'nullable|string|max:70',
            'twitter_description' => 'nullable|string|max:300',
            'twitter_image' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|string|max:255',
            'robots' => 'nullable|string|max:50',
            'no_index' => 'boolean',
            'priority' => 'numeric|between:0,1',
            'changefreq' => 'in:always,hourly,daily,weekly,monthly,yearly,never',
        ]);

        SeoStaticRoute::where('id', $id)->update([
            'meta_title' => $validated['meta_title'] ?: null,
            'meta_description' => $validated['meta_description'] ?: null,
            'meta_keywords' => $validated['meta_keywords'] ?: null,
            'og_title' => $validated['og_title'] ?: null,
            'og_description' => $validated['og_description'] ?: null,
            'og_image' => $validated['og_image'] ?: null,
            'twitter_title' => $validated['twitter_title'] ?: null,
            'twitter_description' => $validated['twitter_description'] ?: null,
            'twitter_image' => $validated['twitter_image'] ?: null,
            'canonical_url' => $validated['canonical_url'] ?: null,
            'robots' => $validated['robots'] ?: null,
            'no_index' => $validated['no_index'] ?? false,
            'priority' => $validated['priority'] ?? 0.5,
            'changefreq' => $validated['changefreq'] ?? 'monthly',
        ]);

        Cache::forget('seo.sitemap');
        Cache::forget('seo.robots');

        return redirect()
            ->route('admin.seo.static-routes')
            ->with('success', __('messages.seo_saved'));
    }

    private function getRouteLabel(string $routeName): string
    {
        return match ($routeName) {
            'home' => 'Homepage',
            'services' => 'Services List',
            'gallery' => 'Gallery',
            'quote' => 'Get a Quote',
            'contact' => 'Contact Us',
            default => ucfirst(str_replace(['.', '_'], ' ', $routeName)),
        };
    }
}
