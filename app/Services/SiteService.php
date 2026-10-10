<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AboutUsContent;
use App\Models\GalleryCategory;
use App\Models\GalleryImage;
use App\Models\GlassSubCategory;
use App\Models\GlassType;
use App\Models\Post;
use App\Models\Service;
use App\Models\ServiceType;
use App\Models\Staff;
use App\Models\Testimonial;
use App\Services\Settings\SettingsManager;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

/**
 * Service for assembling page-specific data from multiple models and settings.
 */
final readonly class SiteService
{
    public function __construct(
        private SettingsManager $settings,
        private ContactNumberService $contactNumberService
    ) {}

    /**
     * Get data for the home page.
     *
     * @return array<string, mixed>
     */
    public function getHomeData(): array
    {
        return [
            'featuredTestimonial' => Testimonial::where('is_featured', true)->active()->first(),
            'otherTestimonials' => Testimonial::active()->where('is_featured', false)->ordered()->get(),
            'featuredServices' => Service::active()->ordered()->take(3)->get(),
            'featuredGalleryImages' => GalleryImage::inGallery()->featured()->active()->with('category')->ordered()->take(3)->get(),
            'timeFormatDisplay' => $this->settings->time_format_display,
        ];
    }

    /**
     * Get data for the contact page.
     *
     * @return array<string, mixed>
     */
    public function getContactData(): array
    {
        $workingHours = $this->settings->working_hours;

        return [
            'primaryPhone' => $this->settings->primary_phone,
            'primaryEmail' => $this->settings->primary_email,
            'workingHours' => $workingHours,
            'timeFormatDisplay' => $this->settings->time_format_display,
            'hasWorkingHours' => is_array($workingHours) && ! empty($workingHours) && isset($workingHours['monday']),
            'dayOrder' => [
                'monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday',
                'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday', 'sunday' => 'Sunday',
            ],
            'companyName' => $this->settings->company_name,
            'companyAddress' => $this->settings->address,
            'googleMapsApiKey' => $this->settings->google_maps_api_key,
            'mapDirectionsLink' => $this->settings->map_directions_link,
            'contactNumbers' => $this->buildContactNumbers(),
        ];
    }

    /**
     * Get data for the About Us page.
     *
     * Returns null when no active About Us content exists so the caller can 404.
     *
     * @return array{content: AboutUsContent, staff: Collection<int, Staff>}|null
     */
    public function getAboutUsData(): ?array
    {
        $content = AboutUsContent::active();

        if (! $content) {
            return null;
        }

        return [
            'content' => $content,
            'staff' => Staff::where('is_active', true)->orderBy('order')->get(),
        ];
    }

    /**
     * Get paginated data for the Services listing page.
     *
     * @return array{services: LengthAwarePaginator}
     */
    public function getServicesData(int $page = 1, int $perPage = 6): array
    {
        return [
            'services' => Service::active()
                ->ordered()
                ->paginate($perPage, ['*'], 'page', $page),
        ];
    }

    /**
     * Get paginated data for the Gallery index page.
     *
     * @return array{images: LengthAwarePaginator, categories: Collection<int, GalleryCategory>, category: string|null, galleryMetrics: mixed}
     */
    public function getGalleryData(?string $category = null, int $page = 1, int $perPage = 9): array
    {
        $query = GalleryImage::inGallery()->active()->with('category')->ordered();

        if ($category) {
            $query->byCategory($category);
        }

        return [
            'images' => $query->paginate($perPage, ['*'], 'page', $page),
            'categories' => GalleryCategory::active()->ordered()->get(),
            'category' => $category,
            'galleryMetrics' => $this->settings->gallery_metrics,
        ];
    }

    /**
     * Get data for a single gallery image page.
     *
     * @return array{galleryImage: GalleryImage, relatedImages: Collection<int, GalleryImage>}
     */
    public function getGalleryShowData(GalleryImage $galleryImage): array
    {
        $galleryImage->load('category');

        $relatedImages = GalleryImage::inGallery()
            ->active()
            ->with('category')
            ->where('gallery_category_id', $galleryImage->gallery_category_id)
            ->where('id', '!=', $galleryImage->id)
            ->ordered()
            ->take(3)
            ->get();

        return [
            'galleryImage' => $galleryImage,
            'relatedImages' => $relatedImages,
        ];
    }

    /**
     * Get data for the Quote request page.
     *
     * @return array{contactNumbers: Collection<int, object>, glassTypes: Collection<int, GlassType>, serviceTypes: Collection<int, ServiceType>, glassSubCategories: Collection<int, GlassSubCategory>}
     */
    public function getQuotePageData(): array
    {
        return [
            'glassTypes' => GlassType::active()->ordered()->with('subCategories')->get(),
            'serviceTypes' => ServiceType::active()->ordered()->get(),
            'glassSubCategories' => GlassSubCategory::active()->ordered()->with('glassType')->get(),
            'contactNumbers' => $this->buildContactNumbers(),
        ];
    }

    /**
     * Get data for a single blog post page.
     *
     * @return array{post: Post, relatedPosts: Collection<int, Post>}
     *
     * @throws ModelNotFoundException
     */
    public function getBlogShowData(string $slug): array
    {
        $post = Post::published()->where('slug', $slug)->with('categories', 'tags')->firstOrFail();

        $relatedPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->whereHas('categories', fn ($q) => $q->whereIn('categories.id', $post->categories->pluck('id')))
            ->with(['categories', 'tags'])
            ->latest()
            ->take(3)
            ->get();

        return [
            'post' => $post,
            'relatedPosts' => $relatedPosts,
        ];
    }

    /**
     * Build the contact numbers collection from company settings.
     *
     * @return Collection<int, object>
     */
    private function buildContactNumbers(): Collection
    {
        return $this->contactNumberService->buildContactNumbers(
            $this->settings->whatsapp_number_default,
            $this->settings->whatsapp_additional_numbers,
            $this->settings->primary_phone,
            // A cleared optional field is stored as null, not an empty string.
            (string) $this->settings->secondary_phone
        );
    }
}
