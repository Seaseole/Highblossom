<?php

declare(strict_types=1);

namespace App\Http\View\Composers;

use App\Services\Contact\ContactDetails;
use App\Services\FaviconSetGenerator;
use App\Services\Settings\SettingsManager;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;

/**
 * View composer that injects global company settings into all views.
 *
 * Maps snake_case settings from the SettingsManager to camelCase view variables.
 */
final readonly class GlobalSettingsComposer
{
    /**
     * Create a new composer instance.
     */
    public function __construct(
        private SettingsManager $settings,
        private FaviconSetGenerator $favicons,
    ) {}

    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $settings = $this->settings->all();
        $view->with('settings', $settings);

        $faviconSet = (array) $settings->get('favicon_set', []);

        // Keeps the public-root icon files in sync with the stored set version
        // (self-heals after deploys rebuilt public/ from git). Cheap: one
        // marker read unless the version actually drifted.
        $this->favicons->ensure($faviconSet, $settings->get('favicon') ?: null);

        // Map snake_case settings to camelCase variables expected by views
        $view->with([
            'companyName' => $settings->get('company_name'),
            'logoText' => $settings->get('logo_text'),
            'primaryEmail' => $settings->get('primary_email'),
            'companyAddress' => $settings->get('address'),
            'primaryPhone' => $settings->get('primary_phone'),
            'secondaryPhone' => $settings->get('secondary_phone'),
            'whatsappDefault' => $settings->get('whatsapp_number_default'),
            'whatsappAdditional' => $settings->get('whatsapp_additional_numbers'),
            'workingHours' => $settings->get('working_hours'),
            'timeFormatDisplay' => $settings->get('time_format_display'),
            'businessLogo' => $settings->get('business_logo'),
            'favicon' => $settings->get('favicon'),
            'faviconSet' => $faviconSet,
            'googleMapsApiKey' => $settings->get('google_maps_api_key'),
            'mapDirectionsLink' => $settings->get('map_directions_link'),
            'timezone' => $settings->get('timezone'),
            'locale' => $settings->get('locale'),
            'dateFormat' => $settings->get('date_format'),
            'timeFormat' => $settings->get('time_format'),
            'currencySymbol' => $settings->get('currency_symbol'),
            'facebookUrl' => $settings->get('facebook_url'),
            'instagramUrl' => $settings->get('instagram_url'),
            'linkedinUrl' => $settings->get('linkedin_url'),
            'announcementActive' => (bool) $settings->get('announcement_active'),
            'announcements' => $this->liveAnnouncements($settings),
            'announcementKey' => $this->announcementKey($settings),
            'galleryMetrics' => $settings->get('gallery_metrics'),
            'googleSiteVerification' => $settings->get('google_site_verification'),
            'contact' => ContactDetails::fromSettings($this->settings),
            'homeTagline' => $settings->get('home_tagline'),
            'homeYearsExperience' => $settings->get('home_years_experience'),
            'homeWarrantyHeading' => $settings->get('home_warranty_heading'),
            'homeWarrantyText' => $settings->get('home_warranty_text'),
            'homeUrgencyEnabled' => filter_var($settings->get('home_urgency_enabled', true), FILTER_VALIDATE_BOOLEAN),
            'homeUrgencyText' => $settings->get('home_urgency_text'),
            'homeCtaHeading' => $settings->get('home_cta_heading'),
            'homeCtaText' => $settings->get('home_cta_text'),
            'homeFinalCtaHeading' => $settings->get('home_final_cta_heading'),
            'homeFinalCtaText' => $settings->get('home_final_cta_text'),
            'homeServiceArea' => $settings->get('home_service_area'),
            'homeMobileCoverage' => $settings->get('home_mobile_coverage'),
        ]);
    }

    /**
     * The announcements still inside their active window.
     *
     * Decided here rather than in the partial so the ticker and the dismissal key can
     * never disagree about which messages are live.
     *
     * @return list<array<string, mixed>>
     */
    private function liveAnnouncements(Collection $settings): array
    {
        return collect((array) $settings->get('announcements', []))
            ->filter(fn ($announcement): bool => empty($announcement['expires_at'] ?? null)
                || Carbon::parse($announcement['expires_at'])->isFuture())
            ->values()
            ->all();
    }

    /**
     * A fingerprint of the live messages, used as the dismissal key.
     *
     * Tied to the text and the link rather than to a row, because the announcements are one
     * JSON array with no ids: editing a message changes the fingerprint, so a visitor who
     * dismissed the old notice sees the new one, while the same notice stays hidden across
     * reloads and SPA navigations.
     */
    private function announcementKey(Collection $settings): ?string
    {
        $live = $this->liveAnnouncements($settings);

        if ($live === []) {
            return null;
        }

        return sha1((string) json_encode(array_map(
            static fn (array $announcement): array => [
                $announcement['text'] ?? '',
                $announcement['link'] ?? '',
            ],
            $live,
        )));
    }
}
