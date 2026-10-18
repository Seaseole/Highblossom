<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use App\Services\Settings\SettingsManager;

/**
 * Renders a booking as an iCalendar event the customer can drop into a diary.
 *
 * @see https://datatracker.ietf.org/doc/html/rfc5545
 */
final class BookingIcsService
{
    public function __construct(private readonly SettingsManager $settings) {}

    /**
     * Build the .ics body for one booking.
     *
     * The event times are written as floating local wall-clock, because that is
     * exactly what `scheduled_at` holds. Attaching a TZID here would imply the column
     * knows which zone it was written in, and it does not.
     */
    public function build(Booking $booking): string
    {
        $start = $booking->scheduled_at;

        if ($start === null) {
            return '';
        }

        $end = $booking->ends_at ?? $start->copy()->addMinutes($booking->durationMinutes());

        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//Highblossom//Bookings//EN',
            'CALSCALE:GREGORIAN',
            'METHOD:PUBLISH',
            'BEGIN:VEVENT',
            'UID:'.$booking->displayReference().'@bookings.highblossom.net',
            'DTSTAMP:'.now()->utc()->format('Ymd\THis\Z'),
            'DTSTART:'.$start->format('Ymd\THis'),
            'DTEND:'.$end->format('Ymd\THis'),
            'SUMMARY:'.$this->escape($this->summary($booking)),
            'DESCRIPTION:'.$this->escape($this->description($booking)),
            'LOCATION:'.$this->escape($this->location($booking)),
            'STATUS:CONFIRMED',
            'END:VEVENT',
            'END:VCALENDAR',
        ];

        return implode("\r\n", array_map(
            fn (string $line): string => $this->fold($line),
            $lines
        ))."\r\n";
    }

    /**
     * The event title.
     */
    private function summary(Booking $booking): string
    {
        return 'Highblossom: '.($booking->service_type?->label() ?? 'Glass fitting').' — '.$booking->vehicleSummary();
    }

    /**
     * The event body, with the reference the shop will ask for on the phone.
     */
    private function description(Booking $booking): string
    {
        $lines = [
            'Booking reference: '.$booking->displayReference(),
            'Damage: '.($booking->damage_type?->label() ?? 'Not specified'),
            'Contact: '.$booking->client_name.' · '.$booking->client_phone,
        ];

        if ($booking->vehicle_details) {
            $lines[] = 'Notes: '.$booking->vehicle_details;
        }

        $lines[] = 'Your glass will be fitted between '.$booking->scheduled_at->format('H:i').' and '
            .($booking->ends_at?->format('H:i') ?? $booking->scheduled_at->format('H:i')).'.';

        return implode("\n", $lines);
    }

    /**
     * Where the customer needs to be.
     */
    private function location(Booking $booking): string
    {
        if ($booking->location === 'mobile') {
            return $booking->client_address ?: 'Mobile fitting at your address';
        }

        return (string) ($this->settings->get('address') ?: 'Highblossom workshop, Gaborone');
    }

    /**
     * Escape the characters RFC 5545 reserves inside a text value.
     */
    private function escape(string $text): string
    {
        return str_replace(
            ['\\', ';', ',', "\r\n", "\n"],
            ['\\\\', '\\;', '\\,', '\\n', '\\n'],
            $text
        );
    }

    /**
     * Wrap any line past 75 octets, as the spec requires.
     *
     * A long address or note is not optional to fold: readers that refuse the whole
     * file rather than the offending line are common in desktop calendar apps.
     * `mb_strcut` rather than `substr` because a cut through the middle of a
     * multi-byte character makes the file unreadable.
     */
    private function fold(string $line): string
    {
        if (strlen($line) <= 75) {
            return $line;
        }

        $folded = [];
        $remaining = $line;

        while (strlen($remaining) > 75) {
            $folded[] = mb_strcut($remaining, 0, 75, 'UTF-8');
            $used = strlen($folded[count($folded) - 1]);
            $remaining = ' '.mb_strcut($remaining, $used, null, 'UTF-8');
        }

        $folded[] = $remaining;

        return implode("\r\n", $folded);
    }
}
