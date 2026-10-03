<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Broad device classification parsed from a session user agent.
 */
enum DeviceType: string
{
    case DESKTOP = 'desktop';
    case MOBILE = 'mobile';
    case TABLET = 'tablet';
    case BOT = 'bot';
    case UNKNOWN = 'unknown';

    /**
     * Get display label for this device type.
     */
    public function label(): string
    {
        return match ($this) {
            self::DESKTOP => 'Desktop',
            self::MOBILE => 'Mobile',
            self::TABLET => 'Tablet',
            self::BOT => 'Bot',
            self::UNKNOWN => 'Unknown',
        };
    }
}
