<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AboutUsContent;
use Illuminate\Container\Attributes\Singleton;
use Illuminate\Http\Request;
use Illuminate\Image\ImageException;
use Illuminate\Support\Facades\Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Service for managing the About Us page content.
 */
#[Singleton(name: 'about_us')]
final class AboutUsService
{
    /**
     * Cover dimensions for the About Us hero image.
     *
     * @var array{0: int<1, max>, 1: int<1, max>}
     */
    private const HERO_DIMENSIONS = [1920, 600];

    /**
     * WebP quality (1-100) applied to processed hero images.
     *
     * @var int<1, 100>
     */
    private const HERO_QUALITY = 82;

    /**
     * Get existing content or create with defaults if none exists.
     */
    public function getOrCreateContent(): AboutUsContent
    {
        $content = AboutUsContent::first();

        if (! $content) {
            $content = AboutUsContent::create([
                'title' => 'About Highblossom',
                'subtitle' => '',
                'body' => '',
                'mission' => '',
                'vision' => '',
                'is_active' => true,
            ]);
        }

        return $content;
    }

    /**
     * Update About Us content with hero image handling.
     */
    public function update(array $data, Request $request): AboutUsContent
    {
        $content = $this->getOrCreateContent();

        $content->title = $data['title'];
        $content->subtitle = $data['subtitle'] ?? '';
        $content->body = $data['body'];
        $content->mission = $data['mission'] ?? '';
        $content->vision = $data['vision'] ?? '';
        $content->is_active = $data['is_active'] ?? false;

        $this->handleHeroImage($content, $request);

        $content->save();

        return $content;
    }

    /**
     * Handle hero image upload, replacement, or removal.
     *
     * Three branches are evaluated in order:
     *  1. Explicit removal request -> delete the stored image and null the field.
     *  2. Pre-uploaded path provided (e.g. from the AJAX media uploader) -> adopt it,
     *     deleting the previous file if it has changed.
     *  3. Direct file upload -> process the uploaded image through the Laravel 13.20
     *     `Image` facade (cover to 1920x600, normalised to WebP at fixed quality) and
     *     store on the public disk, deleting the previous file if present.
     */
    private function handleHeroImage(AboutUsContent $content, Request $request): void
    {
        // Branch 1: Explicit removal request.
        if ($request->boolean('remove_hero_image')) {
            $this->deleteStoredImage($content->hero_image);
            $content->hero_image = null;

            return;
        }

        // Branch 2: Pre-uploaded path provided by the AJAX media uploader.
        $imagePath = $request->input('hero_image_path');

        if (! empty($imagePath) && is_string($imagePath)) {
            if ($content->hero_image && $content->hero_image !== $imagePath) {
                $this->deleteStoredImage($content->hero_image);
            }
            $content->hero_image = $imagePath;

            return;
        }

        // Branch 3: Direct file upload - process and normalise before storage.
        if ($request->hasFile('hero_image')) {
            $file = $request->file('hero_image');

            if ($file && $file->isValid()) {
                try {
                    $this->deleteStoredImage($content->hero_image);

                    $content->hero_image = Image::fromUpload($file)
                        ->cover(self::HERO_DIMENSIONS[0], self::HERO_DIMENSIONS[1])
                        ->toWebp()
                        ->quality(self::HERO_QUALITY)
                        ->store('about-us', 'public');
                } catch (ImageException $e) {
                    Log::error('Failed to process hero image: '.$e->getMessage());
                }
            }
        }
    }

    /**
     * Delete a stored image from the public disk if it exists.
     */
    private function deleteStoredImage(?string $path): void
    {
        if (blank($path)) {
            return;
        }

        try {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        } catch (\Exception $e) {
            Log::warning('Could not delete stored hero image', [
                'path' => $path,
                'reason' => $e->getMessage(),
            ]);
        }
    }
}
