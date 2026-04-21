<?php

declare(strict_types=1);

namespace App\Domains\Seo\Actions;

use App\Domains\Seo\Models\SeoStaticRoute;

final readonly class GenerateRobotsTxt
{
    public function __construct(
        private string $baseUrl,
        private string $sitemapUrl,
    ) {}

    public function execute(): string
    {
        $lines = [];

        // User-agent rules
        $lines[] = 'User-agent: *';

        // Disallow admin routes
        $lines[] = 'Disallow: /admin/';
        $lines[] = 'Disallow: /dashboard';
        $lines[] = 'Disallow: /login';
        $lines[] = 'Disallow: /register';
        $lines[] = 'Disallow: /password-reset';

        // Dynamic no-index routes
        $noIndexRoutes = SeoStaticRoute::where('no_index', true)->get();
        foreach ($noIndexRoutes as $route) {
            try {
                $path = parse_url(route($route->route_name), PHP_URL_PATH);
                if ($path !== null && $path !== '/') {
                    $lines[] = 'Disallow: ' . $path;
                }
            } catch (\Exception) {
                // Skip routes that can't be resolved
            }
        }

        $lines[] = '';
        $lines[] = 'Sitemap: ' . $this->sitemapUrl;

        return implode(PHP_EOL, $lines);
    }
}
