<?php

declare(strict_types=1);

namespace App\Services\Contact;

use App\Services\Settings\SettingsManager;
use Illuminate\Support\Collection;

/**
 * The single source of truth for every public contact link.
 *
 * Built once per request from company settings so the nav, footer, CTA bands,
 * urgency band, mobile bar, WhatsApp bubble and structured data can never
 * disagree about which number or address the business publishes.
 */
final readonly class ContactDetails
{
    /** @var list<string> Day keys in display order, mapped to their short label */
    private const DAY_LABELS = [
        'monday' => 'Mon',
        'tuesday' => 'Tue',
        'wednesday' => 'Wed',
        'thursday' => 'Thu',
        'friday' => 'Fri',
        'saturday' => 'Sat',
        'sunday' => 'Sun',
    ];

    /**
     * @param array<string, array{open?: string|null, close?: string|null, is_closed?: bool}> $workingHours
     */
    public function __construct(
        public string $companyName,
        public string $primaryPhone,
        public string $secondaryPhone,
        public string $whatsappNumber,
        public string $email,
        public string $address,
        public array $workingHours,
        public string $timeFormat,
        public string $quoteMessage,
    ) {}

    /**
     * Resolve contact details from the stored company settings.
     */
    public static function fromSettings(SettingsManager $settings): self
    {
        return new self(
            companyName: (string) $settings->get('company_name', ''),
            primaryPhone: (string) $settings->get('primary_phone', ''),
            secondaryPhone: (string) $settings->get('secondary_phone', ''),
            whatsappNumber: (string) $settings->get('whatsapp_number_default', ''),
            email: (string) $settings->get('primary_email', ''),
            address: (string) $settings->get('address', ''),
            workingHours: (array) $settings->get('working_hours', []),
            timeFormat: (string) $settings->get('time_format_display', '12'),
            quoteMessage: (string) $settings->get('home_whatsapp_message', ''),
        );
    }

    /**
     * Whether a callable primary number exists.
     */
    public function hasPhone(): bool
    {
        return $this->telHref($this->primaryPhone) !== null;
    }

    /**
     * Whether a WhatsApp number exists.
     */
    public function hasWhatsapp(): bool
    {
        return $this->whatsappHref() !== null;
    }

    /**
     * Build a tel: href for any stored number, keeping a leading + for E.164.
     */
    public function telHref(?string $number): ?string
    {
        $digits = $this->normaliseDigits($number);

        return $digits === null ? null : 'tel:'.$digits;
    }

    /**
     * Build a wa.me href. WhatsApp requires bare digits with no plus sign.
     *
     * With no message passed, the admin-configured quote preamble is attached so
     * every surface that opens WhatsApp starts the same conversation.
     */
    public function whatsappHref(?string $message = null): ?string
    {
        $digits = $this->normaliseDigits($this->whatsappNumber);

        if ($digits === null) {
            return null;
        }

        $url = 'https://wa.me/'.ltrim($digits, '+');
        $message ??= $this->quoteMessage;

        return trim($message) === '' ? $url : $url.'?text='.urlencode($message);
    }

    /**
     * Strip formatting and promote a 00-international prefix to E.164.
     *
     * @return string|null Digits with a leading +, or null when nothing usable remains
     */
    private function normaliseDigits(?string $number): ?string
    {
        $trimmed = trim((string) $number);

        if ($trimmed === '') {
            return null;
        }

        $digits = preg_replace('/[^\d+]/', '', $trimmed) ?? '';

        if ($digits === '' || $digits === '+') {
            return null;
        }

        if (str_starts_with($digits, '00')) {
            $digits = '+'.substr($digits, 2);
        }

        return $digits;
    }

    /**
     * Build a wa.me href with no pre-filled message.
     */
    public function whatsappHrefBare(): ?string
    {
        return $this->whatsappHref('');
    }

    /**
     * Build a mailto: href.
     */
    public function emailHref(): ?string
    {
        return $this->email === '' ? null : 'mailto:'.$this->email;
    }

    /**
     * Every distinct phone number the business publishes, in precedence order.
     *
     * @return list<string>
     */
    public function phoneNumbers(): array
    {
        return array_values(array_unique(array_filter([
            $this->primaryPhone,
            $this->secondaryPhone,
            $this->whatsappNumber,
        ], fn (string $number) => trim($number) !== '')));
    }

    /**
     * Working hours collapsed into readable groups, e.g. "Mon–Fri · 08:00 – 17:00".
     *
     * Days sharing the same hours and running consecutively fold into one range so
     * the strip and the footer never render seven near-identical lines.
     *
     * @return Collection<int, string>
     */
    public function openingHours(): Collection
    {
        if ($this->workingHours === []) {
            return collect();
        }

        $format = $this->timeFormat === '24' ? 'H:i' : 'g:i A';
        $perDay = [];
        $closed = [];

        foreach (self::DAY_LABELS as $key => $label) {
            $day = $this->workingHours[$key] ?? null;

            if ($day === null || ($day['is_closed'] ?? false)) {
                $closed[] = $label;

                continue;
            }

            $open = $day['open'] ?? null;
            $close = $day['close'] ?? null;

            if (blank($open) || blank($close)) {
                $closed[] = $label;

                continue;
            }

            $perDay[$key] = [
                'label' => $label,
                'hours' => date($format, strtotime($open)).' – '.date($format, strtotime($close)),
            ];
        }

        $groups = $this->groupConsecutiveDays($perDay);

        foreach ($closed as $label) {
            $groups[] = $label.' · Closed';
        }

        return collect($groups);
    }

    /**
     * Fold runs of consecutive days that open and close at the same time.
     *
     * @param array<string, array{label: string, hours: string}> $perDay
     *
     * @return list<string>
     */
    private function groupConsecutiveDays(array $perDay): array
    {
        $groups = [];
        $current = null;

        foreach (array_keys(self::DAY_LABELS) as $key) {
            if (! isset($perDay[$key])) {
                continue;
            }

            $day = $perDay[$key];

            if ($current !== null && $current['hours'] === $day['hours']) {
                $current['labels'][] = $day['label'];
            } else {
                if ($current !== null) {
                    $groups[] = $this->formatGroup($current);
                }
                $current = ['labels' => [$day['label']], 'hours' => $day['hours']];
            }
        }

        if ($current !== null) {
            $groups[] = $this->formatGroup($current);
        }

        return $groups;
    }

    /**
     * Render one day group as a label plus its hours.
     *
     * @param array{labels: list<string>, hours: string} $group
     */
    private function formatGroup(array $group): string
    {
        $labels = $group['labels'];
        $dayRange = count($labels) === 1 ? $labels[0] : $labels[0].'–'.end($labels);

        return $dayRange.' · '.$group['hours'];
    }
}
