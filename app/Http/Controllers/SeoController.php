<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Seo\BuildSitemap;
use App\Actions\Seo\GenerateRobotsTxt;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

/**
 * Serve SEO-related files (sitemap.xml, robots.txt).
 */
final class SeoController extends Controller
{
    /**
     * Generate and cache the sitemap XML.
     */
    public function sitemap(): Response
    {
        $xml = Cache::remember('seo.sitemap', 86400, function () {
            $action = new BuildSitemap(
                baseUrl: Config::get('app.url'),
            );

            return $action->execute();
        });

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    /**
     * Generate and cache the robots.txt content.
     */
    public function robots(): Response
    {
        $content = Cache::remember('seo.robots', 3600, function () {
            $action = new GenerateRobotsTxt(
                baseUrl: Config::get('app.url'),
                sitemapUrl: route('sitemap'),
            );

            return $action->execute();
        });

        return response($content, 200, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
