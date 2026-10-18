<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * The kind of glass work a customer is booking in for.
 *
 * Each case carries a fallback duration. The `booking_service_durations`
 * setting can override any of them without a code change, which is what lets
 * the shop adjust how long a fleet job takes without a deploy.
 */
enum BookingServiceType: string
{
    case Windscreen = 'windscreen';
    case SideRearGlass = 'side_rear';
    case HeavyMachinery = 'heavy_machinery';
    case Fleet = 'fleet';

    /**
     * Every service a customer may pick, in display order.
     *
     * @return list<self>
     */
    public static function selectable(): array
    {
        return [
            self::Windscreen,
            self::SideRearGlass,
            self::HeavyMachinery,
            self::Fleet,
        ];
    }

    /**
     * The duration used when the settings override is missing or unusable.
     */
    public function defaultDurationMinutes(): int
    {
        return match ($this) {
            self::Windscreen => 90,
            self::SideRearGlass => 45,
            self::HeavyMachinery => 120,
            self::Fleet => 60,
        };
    }

    /**
     * Resolve the language key for this service.
     */
    public function label(): string
    {
        return __('booking.service_'.$this->value);
    }

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
