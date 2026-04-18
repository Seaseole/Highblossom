<?php

declare(strict_types=1);

namespace App\Domains\Seo\Actions;

use App\Domains\Content\Models\Page;
use App\Domains\Content\Models\Service;
use App\Domains\Content\Models\SeoStaticRoute;
use App\Domains\Seo\Contracts\HasSeoInterface;
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

        // Dynamic pages
        Page::where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->get()
            ->each(function (Page $page) use ($urls) {
                if ($page instanceof HasSeoInterface && $page->shouldIndex()) {
                    $urls->push([
                        'loc' => route('pages.show', $page->slug),
                        'lastmod' => $page->getLastModified()?->format('Y-m-d'),
                        'changefreq' => $page->getSitemapChangefreq(),
                        'priority' => number_format($page->getSitemapPriority(), 1),
                    ]);
                }
            });

        // Services
        Service::where('is_active', true)->get()->each(function (Service $service) use ($urls) {
            if ($service instanceof HasSeoInterface && $service->shouldIndex()) {
                $urls->push([
                    'loc' => $this->baseUrl . '/services/' . $service->slug,
                    'lastmod' => $service->getLastModified()?->format('Y-m-d'),
                    'changefreq' => $service->getSitemapChangefreq(),
                    'priority' => number_format($service->getSitemapPriority(), 1),
                ]);
            }
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
