<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\CompanySetting;
use App\Models\SeoStaticRoute;
use App\Services\Settings\SettingsManager;
use Illuminate\Http\Request;
use Illuminate\Image\ImageException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

final class SeoService
{
    /** Meta image containment dimensions (max width/height). Recommended social share size. */
    private const META_IMAGE_MAX_DIMENSIONS = [1200, 630];

    /** Output quality for processed images (1-100). */
    private const IMAGE_QUALITY = 82;

    public function __construct(
        private readonly SettingsManager $settings,
    ) {}

    /**
     * Get the site-wide SEO settings for the admin form.
     *
     * @return array{meta_title: string, meta_description: string, meta_keywords: string, meta_image: string, google_site_verification: string}
     */
    public function getSettings(): array
    {
        return [
            'meta_title' => (string) $this->settings->get('seo_meta_title', ''),
            'meta_description' => (string) $this->settings->get('seo_meta_description', ''),
            'meta_keywords' => (string) $this->settings->get('seo_meta_keywords', ''),
            'meta_image' => (string) $this->settings->get('seo_meta_image', ''),
            'google_site_verification' => (string) $this->settings->get('google_site_verification', ''),
        ];
    }

    /**
     * Persist site-wide SEO settings, including meta image handling.
     */
    public function updateSettings(array $data, Request $request): void
    {
        CompanySetting::set('seo_meta_title', $data['meta_title'] ?? '');
        CompanySetting::set('seo_meta_description', $data['meta_description'] ?? '');
        CompanySetting::set('seo_meta_keywords', $data['meta_keywords'] ?? '');
        CompanySetting::set('google_site_verification', $data['google_site_verification'] ?? '');

        $this->handleMetaImageUpload($request);

        $this->clearCache();
    }

    /**
     * Handle meta image removal, pre-uploaded path adoption, or direct upload.
     */
    private function handleMetaImageUpload(Request $request): void
    {
        $oldImage = (string) CompanySetting::get('seo_meta_image', '');

        if ($request->boolean('remove_meta_image')) {
            $this->deleteStoredImage($oldImage);
            CompanySetting::set('seo_meta_image', '');

            return;
        }

        $imagePath = $request->input('meta_image_path');

        if (filled($imagePath)) {
            if ($oldImage !== '' && $oldImage !== $imagePath) {
                $this->deleteStoredImage($oldImage);
            }
            CompanySetting::set('seo_meta_image', $imagePath);

            return;
        }

        if ($request->hasFile('meta_image') && $request->file('meta_image')->isValid()) {
            try {
                $this->deleteStoredImage($oldImage);
                $file = $request->file('meta_image');
                $path = Image::fromUpload($file)
                    ->contain(self::META_IMAGE_MAX_DIMENSIONS[0], self::META_IMAGE_MAX_DIMENSIONS[1])
                    ->toWebp()
                    ->quality(self::IMAGE_QUALITY)
                    ->store('settings', 'public');

                if ($path !== false) {
                    CompanySetting::set('seo_meta_image', $path);
                }
            } catch (ImageException $e) {
                Log::error('Failed to process SEO meta image upload: '.$e->getMessage());
            }
        }
    }

    /**
     * Delete a stored image from the public disk if it exists.
     */
    private function deleteStoredImage(string $path): void
    {
        if (blank($path)) {
            return;
        }

        try {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        } catch (\Exception $e) {
            Log::warning('Could not delete stored image', [
                'path' => $path,
                'reason' => $e->getMessage(),
            ]);
        }
    }

    public function getRoutesWithSeo(): array
    {
        $routeNames = config('seo.static_routes', []);
        $existing = SeoStaticRoute::all()->keyBy('route_name');

        return collect($routeNames)->map(function ($routeName) use ($existing) {
            $seoRoute = $existing->get($routeName);

            return [
                'route_name' => $routeName,
                'route_label' => $this->getRouteLabel($routeName),
                'exists' => $seoRoute !== null,
                'id' => $seoRoute?->id,
                'meta_title' => $seoRoute?->meta_title ?? '',
                'meta_description' => $seoRoute?->meta_description ?? '',
                'no_index' => $seoRoute?->no_index ?? false,
                'priority' => $seoRoute?->priority ?? 0.5,
                'changefreq' => $seoRoute?->changefreq ?? 'monthly',
            ];
        })->toArray();
    }

    public function create(array $data): SeoStaticRoute
    {
        $seoRoute = SeoStaticRoute::create($this->prepareData($data));

        $this->clearCache();

        return $seoRoute;
    }

    public function update(int $id, array $data): bool
    {
        $updated = SeoStaticRoute::where('id', $id)->update($this->prepareData($data));

        $this->clearCache();

        return $updated > 0;
    }

    public function getRouteLabel(string $routeName): string
    {
        return match ($routeName) {
            'home' => 'Homepage',
            'services' => 'Services List',
            'gallery' => 'Gallery',
            'quote' => 'Get a Quote',
            'contact' => 'Contact Us',
            default => ucfirst(str_replace(['.', '_'], ' ', $routeName)),
        };
    }

    private function prepareData(array $data): array
    {
        $fields = [
            'meta_title', 'meta_description', 'meta_keywords',
            'og_title', 'og_description', 'og_image',
            'twitter_title', 'twitter_description', 'twitter_image',
            'canonical_url', 'robots',
        ];

        $result = [
            'no_index' => $data['no_index'] ?? false,
            'priority' => $data['priority'] ?? 0.5,
            'changefreq' => $data['changefreq'] ?? 'monthly',
        ];

        // route_name is only supplied on create; it is immutable and absent
        // from update payloads, so omit it rather than nulling the column.
        if (array_key_exists('route_name', $data)) {
            $result['route_name'] = $data['route_name'];
        }

        foreach ($fields as $field) {
            $result[$field] = ! empty($data[$field]) ? $data[$field] : null;
        }

        return $result;
    }

    /**
     * Invalidate cached sitemap and robots output so the next request rebuilds them.
     */
    public function invalidate(): void
    {
        $this->clearCache();
    }

    private function clearCache(): void
    {
        Cache::forget('seo.sitemap');
        Cache::forget('seo.robots');
    }
}
