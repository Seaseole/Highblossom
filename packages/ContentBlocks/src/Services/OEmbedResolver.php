<?php

declare(strict_types=1);

namespace Highblossom\ContentBlocks\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class OEmbedResolver
{
    private array $providerEndpoints;

    public function __construct()
    {
        $this->providerEndpoints = config('content-blocks.oembed.providers', $this->getDefaultProviders());
    }

    public function resolve(string $url): ?array
    {
        if (!$this->isValidUrl($url)) {
            return null;
        }

        $provider = $this->findProviderForUrl($url);

        if (!$provider) {
            return null;
        }

        try {
            $endpoint = $this->buildEndpoint($provider, $url);

            $response = Http::timeout(5)->get($endpoint, [
                'url' => $url,
                'format' => 'json',
                'maxwidth' => config('content-blocks.oembed.max_width', 800),
                'maxheight' => config('content-blocks.oembed.max_height', 600),
            ]);

            if (!$response->successful()) {
                Log::warning('ContentBlocks: oEmbed request failed', [
                    'url' => $url,
                    'status' => $response->status(),
                ]);
                return null;
            }

            $data = $response->json();

            return [
                'html' => $data['html'] ?? null,
                'title' => $data['title'] ?? null,
                'thumbnail_url' => $data['thumbnail_url'] ?? null,
                'width' => $data['width'] ?? null,
                'height' => $data['height'] ?? null,
                'type' => $data['type'] ?? 'rich',
                'provider' => $provider['name'],
            ];
        } catch (\Throwable $e) {
            Log::error('ContentBlocks: oEmbed resolution failed', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    private function isValidUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    private function findProviderForUrl(string $url): ?array
    {
        foreach ($this->providerEndpoints as $provider) {
            foreach ($provider['patterns'] as $pattern) {
                if (preg_match($pattern, $url)) {
                    return $provider;
                }
            }
        }

        return null;
    }

    private function buildEndpoint(array $provider, string $url): string
    {
        $endpoint = $provider['endpoint'];

        if (str_contains($endpoint, '{url}')) {
            return str_replace('{url}', urlencode($url), $endpoint);
        }

        return $endpoint;
    }

    private function getDefaultProviders(): array
    {
        return [
            [
                'name' => 'YouTube',
                'patterns' => ['/(youtube\.com|youtu\.be)/i'],
                'endpoint' => 'https://www.youtube.com/oembed',
            ],
            [
                'name' => 'Vimeo',
                'patterns' => ['/vimeo\.com/i'],
                'endpoint' => 'https://vimeo.com/api/oembed.json',
            ],
            [
                'name' => 'Twitter/X',
                'patterns' => ['/(twitter\.com|x\.com)/i'],
                'endpoint' => 'https://publish.twitter.com/oembed',
            ],
            [
                'name' => 'Instagram',
                'patterns' => ['/instagram\.com/i'],
                'endpoint' => 'https://www.instagram.com/oembed',
            ],
        ];
    }
}
