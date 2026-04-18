<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domains\Seo\Actions\BuildSitemap;
use App\Domains\Seo\Actions\GenerateRobotsTxt;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

final class SeoController extends Controller
{
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
