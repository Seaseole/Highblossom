<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * What is actually wrong with the glass.
 *
 * This drives nothing but triage and quoting: the slot duration comes from the
 * service type, because a chip repair and a full swap of the same panel are
 * booked as the same job until a technician has seen it.
 */
enum BookingDamageType: string
{
    case Chip = 'chip';
    case Crack = 'crack';
    case Shattered = 'shattered';
    case SealLeak = 'seal_leak';
    case Other = 'other';

    /**
     * Resolve the language key for this damage type.
     */
    public function label(): string
    {
        return __('booking.damage_'.$this->value);
    }

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
