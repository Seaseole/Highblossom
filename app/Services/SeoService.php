<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\SeoStaticRoute;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

final class SeoService
{
    public function getRoutesWithSeo(): array
    {
        $routeNames = config('seo.static_routes', []);
        $existing = SeoStaticRoute::all()->keyBy('route_name');

        return collect($routeNames)->map(function ($routeName) use ($existing) {
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

    public function create(array $data): SeoStaticRoute
    {
        $seoRoute = SeoStaticRoute::create($this->prepareData($data));

        $this->clearCache();

        return $seoRoute;
    }

    public function update(int $id, array $data): bool
    {
        $updated = SeoStaticRoute::where('id', $id)->update($this->prepareData($data));

        $this->clearCache();

        return $updated;
    }

    public function getRouteLabel(string $routeName): string
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

    private function prepareData(array $data): array
    {
        $fields = [
            'meta_title', 'meta_description', 'meta_keywords',
            'og_title', 'og_description', 'og_image',
            'twitter_title', 'twitter_description', 'twitter_image',
            'canonical_url', 'robots',
        ];

        $result = [
            'route_name' => $data['route_name'] ?? null,
            'no_index' => $data['no_index'] ?? false,
            'priority' => $data['priority'] ?? 0.5,
            'changefreq' => $data['changefreq'] ?? 'monthly',
        ];

        foreach ($fields as $field) {
            $result[$field] = !empty($data[$field]) ? $data[$field] : null;
        }

        return $result;
    }

    private function clearCache(): void
    {
        Cache::forget('seo.sitemap');
        Cache::forget('seo.robots');
    }
}
