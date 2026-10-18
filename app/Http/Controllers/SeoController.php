<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Seo\BuildSitemap;
use App\Actions\Seo\GenerateRobotsTxt;
use App\Services\Settings\SettingsManager;
use Illuminate\Http\JsonResponse;
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

    /**
     * Serve the web app manifest with the correct MIME type.
     *
     * The icons live at the public root; when an admin-generated set is
     * active its version hash is used for cache-busting instead of the
     * static config('icons.version').
     */
    public function manifest(SettingsManager $settings): JsonResponse
    {
        $set = (array) $settings->get('favicon_set', []);
        $version = $set['v'] ?? config('icons.version', '1');

        $manifest = [
            'name' => 'High Blossom Glass',
            'short_name' => 'HB Glass',
            'icons' => [
                ['src' => '/icon-192.png?v='.$version, 'sizes' => '192x192', 'type' => 'image/png'],
                ['src' => '/icon-512.png?v='.$version, 'sizes' => '512x512', 'type' => 'image/png'],
                ['src' => '/icon-512-maskable.png?v='.$version, 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'maskable'],
            ],
            'theme_color' => '#b01828',
            'background_color' => '#ffffff',
            'display' => 'standalone',
            'start_url' => '/',
        ];

        return response()->json($manifest, 200)
            ->header('Content-Type', 'application/manifest+json')
            ->header('Cache-Control', 'public, max-age=3600');
    }
}
