<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AboutUsContent;
use App\Models\GalleryCategory;
use App\Models\GalleryImage;
use App\Models\GlassSubCategory;
use App\Models\GlassType;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Service;
use App\Models\ServiceType;
use App\Models\Staff;
use App\Models\Testimonial;
use App\Services\Settings\SettingsManager;
use Highblossom\ContentBlocks\Support\MediaUrl;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

/**
 * Service for assembling page-specific data from multiple models and settings.
 */
final readonly class SiteService
{
    /** Published testimonials needed before a rating is presented as evidence. */
    private const MIN_RATED_TESTIMONIALS = 5;

    /** Share of rated testimonials at 4+ stars before "recommend" is claimed. */
    private const MIN_RECOMMEND_RATIO = 0.8;

    /** Photo tiles shown in the homepage services grid. */
    private const HOME_SERVICE_LIMIT = 4;

    /** Photo cards shown in the homepage portfolio strip. */
    private const HOME_PORTFOLIO_LIMIT = 6;

    public function __construct(
        private SettingsManager $settings,
        private ContactNumberService $contactNumberService
    ) {}

    /**
     * Get data for the home page.
     *
     * Every section reports whether it has real content so the view can hide
     * itself rather than render an empty state or an invented number.
     *
     * @return array<string, mixed>
     */
    public function getHomeData(): array
    {
        $featuredTestimonial = Testimonial::where('is_featured', true)->active()->first();
        $otherTestimonials = Testimonial::active()->where('is_featured', false)->ordered()->get();

        // collect() + concat() so $otherTestimonials is never mutated by the count.
        $testimonials = collect([$featuredTestimonial])
            ->concat($otherTestimonials)
            ->filter()
            ->values();

        return [
            'heroImage' => $this->heroImage(),
            'featuredTestimonial' => $featuredTestimonial,
            'otherTestimonials' => $otherTestimonials,
            'hasTestimonials' => $testimonials->isNotEmpty(),
            'featuredServices' => Service::active()->ordered()->take(self::HOME_SERVICE_LIMIT)->get(),
            'featuredGalleryImages' => GalleryImage::inGallery()
                ->featured()
                ->active()
                ->with('category')
                ->ordered()
                ->take(self::HOME_PORTFOLIO_LIMIT)
                ->get(),
            'partners' => $this->partnersWithLogos(),
            'homeStats' => $this->backedStats($testimonials),
            'factTiles' => $this->factTiles(),
            'warrantyHighlights' => $this->rows('home_warranty_highlights', ['title', 'text']),
            'processSteps' => $this->rows('home_process_steps', ['title', 'text']),
            'faqs' => $this->rows('home_faqs', ['question', 'answer']),
            'timeFormatDisplay' => $this->settings->time_format_display,
        ];
    }

    /**
     * Resolve the hero banner from the admin upload only.
     *
     * The homepage hero is its own asset, deliberately isolated from the public
     * gallery: with no upload configured the section keeps its gradient backdrop
     * rather than borrowing a gallery photograph the customer never chose for it.
     */
    private function heroImage(): ?string
    {
        $configured = (string) $this->settings->get('hero_image', '');

        return $configured !== '' ? MediaUrl::asset($configured) : null;
    }

    /**
     * Active partners that actually have a logo to render.
     *
     * @return Collection<int, Partner>
     */
    private function partnersWithLogos(): Collection
    {
        return Partner::where('is_active', true)
            ->orderBy('order')
            ->get()
            ->filter(fn (Partner $partner) => filled($partner->logo_path) && filled($partner->logo_url))
            ->values();
    }

    /**
     * Social-proof counters that are backed by stored records.
     *
     * A stat is only returned when enough published testimonials exist to make
     * the claim honest; below that the caller falls back to fact tiles.
     *
     * @param Collection<int, Testimonial> $testimonials
     *
     * @return Collection<int, array{value: string, label: string}>
     */
    private function backedStats(Collection $testimonials): Collection
    {
        $stats = collect();
        $rated = $testimonials->pluck('rating')->filter(fn ($rating) => is_numeric($rating) && $rating > 0);

        if ($testimonials->count() >= self::MIN_RATED_TESTIMONIALS) {
            $stats->push(['value' => (string) $testimonials->count(), 'label' => __('site.home.verified_reviews')]);
        }

        if ($rated->count() >= self::MIN_RATED_TESTIMONIALS) {
            $stats->push(['value' => number_format($rated->avg(), 1), 'label' => __('site.home.average_rating')]);

            $recommendRatio = $rated->filter(fn ($rating) => $rating >= 4)->count() / $rated->count();

            if ($recommendRatio >= self::MIN_RECOMMEND_RATIO) {
                $stats->push(['value' => round($recommendRatio * 100).'%', 'label' => __('site.home.recommend_us')]);
            }
        }

        return $stats;
    }

    /**
     * Read a JSON settings repeater, keyed to the fields the view expects.
     *
     * Rows missing any required field are dropped so a half-filled admin row
     * can never render a blank card, accordion or tile.
     *
     * @param list<string>                                $fields   Required fields; each must hold a filled value.
     * @param (callable(array<string, mixed>): bool)|null $filter   Extra row check, e.g. a non-empty display value.
     * @param list<string>                                $optional Fields kept when present, never required.
     *
     * @return Collection<int, array<string, mixed>>
     */
    private function rows(string $key, array $fields, ?callable $filter = null, array $optional = []): Collection
    {
        $rows = $this->settings->get($key, []);

        if (! is_array($rows)) {
            return collect();
        }

        $keep = array_merge($fields, $optional);

        return collect($rows)
            ->filter(fn ($row) => is_array($row)
                && collect($fields)->every(fn (string $field) => filled($row[$field] ?? null))
                && ($filter === null || $filter($row)))
            ->map(fn (array $row) => array_intersect_key($row, array_flip($keep)))
            ->values();
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
     * Admin-editable company facts shared by the homepage counter strip and the
     * services hero card, so both surfaces quote the same claims.
     *
     * A tile keeps its row when `suffix` is empty — several facts ("Lifetime",
     * "Same day") carry no suffix, and treating the field as required silently
     * deleted those tiles.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public function factTiles(): Collection
    {
        return $this->rows(
            'home_fact_tiles',
            ['label', 'value'],
            fn (array $row): bool => filled($row['value'] ?? null),
            ['suffix'],
        );
    }

    /**
     * Get paginated data for the Services listing page.
     *
     * @return array{services: LengthAwarePaginator, factTiles: Collection<int, array<string, mixed>>}
     */
    public function getServicesData(int $page = 1, int $perPage = 6): array
    {
        return [
            'services' => Service::active()
                ->ordered()
                ->paginate($perPage, ['*'], 'page', $page),
            'factTiles' => $this->factTiles(),
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
        $post = Post::published()->where('slug', $slug)->with(['categories', 'tags', 'author'])->firstOrFail();

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
