<?php

declare(strict_types=1);

namespace App\Domains\Seo\Actions;

use App\Domains\Seo\Models\SeoStaticRoute;
use Illuminate\Support\Collection;

final readonly class BuildSitemap
{
    public function __construct(
        private string $baseUrl,
    ) {}

    public function execute(): string
    {
        $urls = $this->collectUrls();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls as $url) {
            $xml .= $this->buildUrlElement($url);
        }

        $xml .= '</urlset>';

        return $xml;
    }

    private function collectUrls(): Collection
    {
        $urls = collect();

        // Static routes
        SeoStaticRoute::indexable()->get()->each(function ($route) use ($urls) {
            $urls->push([
                'loc' => $this->resolveRouteUrl($route->route_name),
                'lastmod' => $route->updated_at?->format('Y-m-d'),
                'changefreq' => $route->changefreq ?? 'monthly',
                'priority' => number_format($route->priority ?? 0.5, 1),
            ]);
        });

        return $urls;
    }

    private function resolveRouteUrl(string $routeName): string
    {
        try {
            return route($routeName);
        } catch (\Exception) {
            return $this->baseUrl . '/' . str_replace('.', '/', $routeName);
        }
    }

    private function buildUrlElement(array $url): string
    {
        $element = '  <url>' . PHP_EOL;
        $element .= '    <loc>' . htmlspecialchars($url['loc'], ENT_XML1, 'UTF-8') . '</loc>' . PHP_EOL;

        if ($url['lastmod'] !== null) {
            $element .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . PHP_EOL;
        }

        $element .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . PHP_EOL;
        $element .= '    <priority>' . $url['priority'] . '</priority>' . PHP_EOL;
        $element .= '  </url>' . PHP_EOL;

        return $element;
    }
}
