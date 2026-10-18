<?php

declare(strict_types=1);

namespace App\Services\Seo;

use App\Services\Contact\ContactDetails;
use App\Services\Settings\SettingsManager;
use Highblossom\ContentBlocks\Support\MediaUrl;

/**
 * Builds the LocalBusiness JSON-LD graph node from the central company settings.
 *
 * Reads the same {@see ContactDetails} source the visible page uses, so the
 * structured data can never advertise a phone number or address the site does
 * not show.
 */
final class LocalBusinessSchema
{
    /** @var list<string> Day keys in the order schema.org expects them */
    private const DAY_NAMES = [
        'monday' => 'Monday',
        'tuesday' => 'Tuesday',
        'wednesday' => 'Wednesday',
        'thursday' => 'Thursday',
        'friday' => 'Friday',
        'saturday' => 'Saturday',
        'sunday' => 'Sunday',
    ];

    /**
     * @return array<string, mixed>|null Null when there is not enough stored data to describe the business
     */
    public static function build(SettingsManager $settings, ContactDetails $contact): ?array
    {
        if (blank($contact->companyName)) {
            return null;
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => ['LocalBusiness', 'AutoRepair'],
            '@id' => url('/').'#business',
            'name' => $contact->companyName,
            'url' => url('/'),
            'description' => (string) $settings->get('home_warranty_text', '') ?: (string) $settings->get('seo_meta_description', ''),
            'priceRange' => (string) $settings->get('currency_symbol', 'P'),
        ];

        if (filled($contact->address)) {
            $schema['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => $contact->address,
                'addressLocality' => 'Gaborone',
                'addressCountry' => 'BW',
            ];
        }

        if ($contact->hasPhone()) {
            $schema['telephone'] = $contact->primaryPhone;
        }

        if ($contact->emailHref()) {
            $schema['email'] = $contact->email;
        }

        $image = (string) $settings->get('hero_image', '');

        if (filled($image)) {
            $schema['image'] = MediaUrl::asset($image);
        }

        if (filled($contact->quoteMessage)) {
            $schema['potentialAction'] = [
                '@type' => 'ReserveAction',
                'target' => url('/bookings/create'),
                'description' => $contact->quoteMessage,
            ];
        }

        $sameAs = collect([
            $settings->get('facebook_url'),
            $settings->get('instagram_url'),
            $settings->get('linkedin_url'),
        ])->filter(fn ($url) => filled($url))->values()->all();

        if ($sameAs !== []) {
            $schema['sameAs'] = $sameAs;
        }

        $hours = self::openingHours($contact);

        if ($hours !== []) {
            $schema['openingHoursSpecification'] = $hours;
        }

        $areaServed = (string) $settings->get('home_service_area', '');

        if (filled($areaServed)) {
            $schema['areaServed'] = $areaServed;
        }

        return $schema;
    }

    /**
     * Convert the stored working-hours array into schema.org specifications.
     *
     * @return list<array<string, mixed>>
     */
    private static function openingHours(ContactDetails $contact): array
    {
        $specs = [];

        foreach (self::DAY_NAMES as $key => $day) {
            $entry = $contact->workingHours[$key] ?? null;

            if ($entry === null || ($entry['is_closed'] ?? false)) {
                continue;
            }

            if (blank($entry['open'] ?? null) || blank($entry['close'] ?? null)) {
                continue;
            }

            $specs[] = [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => $day,
                'opens' => $entry['open'],
                'closes' => $entry['close'],
            ];
        }

        return $specs;
    }
}
