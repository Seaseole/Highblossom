<?php

declare(strict_types=1);

namespace App\Actions\Seo;

use App\Models\SeoStaticRoute;

final readonly class GenerateRobotsTxt
{
    public function __construct(
        private string $baseUrl,
        private string $sitemapUrl,
    ) {}

    public function execute(): string
    {
        $lines = [
            'User-agent: *',
            'Disallow: /admin/',
            'Disallow: /dashboard',
            'Disallow: /login',
            'Disallow: /register',
            'Disallow: /password-reset',
        ];

        // Dynamic no-index routes
        $lines = array_merge($lines, $this->getDynamicDisallowedPaths());

        $lines[] = '';
        $lines[] = 'Sitemap: ' . $this->sitemapUrl;

        return implode(PHP_EOL, $lines);
    }

    /**
     * Get paths for routes marked as no-index.
     *
     * @return array<string>
     */
    private function getDynamicDisallowedPaths(): array
    {
        return SeoStaticRoute::where('no_index', true)
            ->get()
            ->map(function ($route) {
                try {
                    $path = parse_url(route($route->route_name), PHP_URL_PATH);
                    return ($path !== null && $path !== '/') ? 'Disallow: ' . $path : null;
                } catch (\Exception) {
                    return null;
                }
            })
            ->filter()
            ->values()
            ->toArray();
    }
}
