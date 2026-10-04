<?php

declare(strict_types=1);

namespace App\Actions\Seo;

use App\Models\GalleryImage;
use App\Models\Post;
use App\Models\SeoStaticRoute;
use Illuminate\Support\Collection;

/**
 * Build the XML sitemap for the application.
 *
 * Collects indexable URLs from static route SEO records, published blog
 * posts, and active gallery images, then generates a valid XML sitemap
 * with loc, lastmod, changefreq, and priority elements for each URL.
 */
final readonly class BuildSitemap
{
    public function __construct(
        private string $baseUrl,
    ) {}

    /**
     * Execute sitemap generation.
     */
    public function execute(): string
    {
        $urls = $this->collectUrls();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;

        foreach ($urls as $url) {
            $xml .= $this->buildUrlElement($url);
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Collect all indexable URLs from static routes and dynamic content.
     *
     * @return Collection<int, array{loc: string, lastmod: string|null, changefreq: string, priority: string}>
     */
    private function collectUrls(): Collection
    {
        return $this->collectStaticRoutes()
            ->merge($this->collectPosts())
            ->merge($this->collectGalleryImages());
    }

    /**
     * Collect indexable static route URLs from the database.
     *
     * @return Collection<int, array{loc: string, lastmod: string|null, changefreq: string, priority: string}>
     */
    private function collectStaticRoutes(): Collection
    {
        return SeoStaticRoute::indexable()->get()
            ->map(fn (SeoStaticRoute $route): array => [
                'loc' => $this->resolveRouteUrl($route->route_name),
                'lastmod' => $route->updated_at?->format('Y-m-d'),
                'changefreq' => $route->changefreq ?? 'monthly',
                'priority' => number_format((float) ($route->priority ?? 0.5), 1),
            ]);
    }

    /**
     * Collect URLs for published blog posts that allow indexing.
     *
     * @return Collection<int, array{loc: string, lastmod: string|null, changefreq: string, priority: string}>
     */
    private function collectPosts(): Collection
    {
        return Post::query()->published()->get()
            ->filter(fn (Post $post): bool => $post->shouldIndex())
            ->values()
            ->map(fn (Post $post): array => [
                'loc' => route('blog.show', ['slug' => $post->slug]),
                'lastmod' => ($post->updated_at ?? $post->published_at)?->format('Y-m-d'),
                'changefreq' => $post->getSitemapChangefreq(),
                'priority' => number_format($post->getSitemapPriority(), 1),
            ]);
    }

    /**
     * Collect URLs for active gallery images.
     *
     * @return Collection<int, array{loc: string, lastmod: string|null, changefreq: string, priority: string}>
     */
    private function collectGalleryImages(): Collection
    {
        return GalleryImage::query()->active()->get()
            ->map(fn (GalleryImage $image): array => [
                'loc' => route('gallery.show', ['galleryImage' => $image]),
                'lastmod' => $image->updated_at?->format('Y-m-d'),
                'changefreq' => 'monthly',
                'priority' => '0.5',
            ]);
    }

    /**
     * Resolve a route name to its full URL.
     */
    private function resolveRouteUrl(string $routeName): string
    {
        try {
            return route($routeName);
        } catch (\Exception) {
            return $this->baseUrl.'/'.str_replace('.', '/', $routeName);
        }
    }

    /**
     * Build a <url> XML element for the sitemap.
     *
     * @param array{loc: string, lastmod: string|null, changefreq: string, priority: string} $url
     */
    private function buildUrlElement(array $url): string
    {
        $element = '  <url>'.PHP_EOL;
        $element .= '    <loc>'.htmlspecialchars($url['loc'], ENT_XML1, 'UTF-8').'</loc>'.PHP_EOL;

        if ($url['lastmod'] !== null) {
            $element .= '    <lastmod>'.$url['lastmod'].'</lastmod>'.PHP_EOL;
        }

        $element .= '    <changefreq>'.$url['changefreq'].'</changefreq>'.PHP_EOL;
        $element .= '    <priority>'.$url['priority'].'</priority>'.PHP_EOL;
        $element .= '  </url>'.PHP_EOL;

        return $element;
    }
}
