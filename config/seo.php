<?php

declare(strict_types=1);

return [
    'site_name' => env('SEO_SITE_NAME', env('APP_NAME', 'Highblossom')),
    'separator' => env('SEO_SEPARATOR', '|'),
    'default_og_image' => env('SEO_DEFAULT_OG_IMAGE', null),
    'twitter_site' => env('SEO_TWITTER_SITE', null),

    'cache' => [
        'sitemap_duration' => 86400, // 24 hours
        'robots_duration' => 3600, // 1 hour
    ],

    'ai' => [
        'model' => env('SEO_AI_MODEL', 'gpt-4o-mini'),
        'temperature' => 0.7,
        'max_tokens' => 500,
        'rate_limit' => 10, // requests per minute
    ],

    'defaults' => [
        'changefreq' => 'monthly',
        'priority' => 0.5,
        'og_type' => 'website',
        'twitter_card' => 'summary_large_image',
    ],

    'static_routes' => [
        'home',
        'services',
        'gallery',
        'quote',
        'contact',
    ],
];
