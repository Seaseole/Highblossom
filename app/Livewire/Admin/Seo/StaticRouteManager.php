<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Seo;

use App\Domains\Content\Models\SeoStaticRoute;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Static Route SEO Management')]
class StaticRouteManager extends Component
{
    public array $routes = [];
    public ?int $editingId = null;
    public array $form = [];

    public function mount(): void
    {
        $this->loadRoutes();
    }

    public function loadRoutes(): void
    {
        $routeNames = config('seo.static_routes', []);
        $existing = SeoStaticRoute::all()->keyBy('route_name');

        $this->routes = collect($routeNames)->map(function ($routeName) use ($existing) {
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
    }

    public function edit(int $index): void
    {
        $route = $this->routes[$index];
        $this->editingId = $route['id'] ?? null;

        $this->form = [
            'route_name' => $route['route_name'],
            'meta_title' => $route['meta_title'] ?? '',
            'meta_description' => $route['meta_description'] ?? '',
            'meta_keywords' => $route['meta_keywords'] ?? '',
            'og_title' => $route['og_title'] ?? '',
            'og_description' => $route['og_description'] ?? '',
            'og_image' => $route['og_image'] ?? '',
            'twitter_title' => $route['twitter_title'] ?? '',
            'twitter_description' => $route['twitter_description'] ?? '',
            'twitter_image' => $route['twitter_image'] ?? '',
            'canonical_url' => $route['canonical_url'] ?? '',
            'robots' => $route['robots'] ?? '',
            'no_index' => $route['no_index'] ?? false,
            'priority' => $route['priority'] ?? 0.5,
            'changefreq' => $route['changefreq'] ?? 'monthly',
        ];
    }

    public function save(): void
    {
        $validated = $this->validate([
            'form.meta_title' => 'nullable|string|max:70',
            'form.meta_description' => 'nullable|string|max:300',
            'form.meta_keywords' => 'nullable|string|max:255',
            'form.og_title' => 'nullable|string|max:70',
            'form.og_description' => 'nullable|string|max:300',
            'form.og_image' => 'nullable|string|max:255',
            'form.twitter_title' => 'nullable|string|max:70',
            'form.twitter_description' => 'nullable|string|max:300',
            'form.twitter_image' => 'nullable|string|max:255',
            'form.canonical_url' => 'nullable|string|max:255',
            'form.robots' => 'nullable|string|max:50',
            'form.no_index' => 'boolean',
            'form.priority' => 'numeric|between:0,1',
            'form.changefreq' => 'in:always,hourly,daily,weekly,monthly,yearly,never',
        ]);

        SeoStaticRoute::updateOrCreate(
            ['route_name' => $this->form['route_name']],
            [
                'meta_title' => $this->form['meta_title'] ?: null,
                'meta_description' => $this->form['meta_description'] ?: null,
                'meta_keywords' => $this->form['meta_keywords'] ?: null,
                'og_title' => $this->form['og_title'] ?: null,
                'og_description' => $this->form['og_description'] ?: null,
                'og_image' => $this->form['og_image'] ?: null,
                'twitter_title' => $this->form['twitter_title'] ?: null,
                'twitter_description' => $this->form['twitter_description'] ?: null,
                'twitter_image' => $this->form['twitter_image'] ?: null,
                'canonical_url' => $this->form['canonical_url'] ?: null,
                'robots' => $this->form['robots'] ?: null,
                'no_index' => $this->form['no_index'] ?? false,
                'priority' => $this->form['priority'] ?? 0.5,
                'changefreq' => $this->form['changefreq'] ?? 'monthly',
            ]
        );

        // Clear caches
        Cache::forget('seo.sitemap');
        Cache::forget('seo.robots');

        $this->editingId = null;
        $this->form = [];
        $this->loadRoutes();

        $this->dispatch('notify', ['message' => 'SEO settings saved successfully', 'type' => 'success']);
    }

    public function cancel(): void
    {
        $this->editingId = null;
        $this->form = [];
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

    public function render()
    {
        return view('livewire.admin.seo.static-route-manager');
    }
}
