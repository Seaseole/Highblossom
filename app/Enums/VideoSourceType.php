<?php

declare(strict_types=1);

namespace App\Enums;

enum VideoSourceType: string
{
    case LOCAL_FILE = 'local_file';
    case YOUTUBE = 'youtube';
    case VIMEO = 'vimeo';
    case DAILYMOTION = 'dailymotion';
    case FACEBOOK = 'facebook';
    case DIRECT_URL = 'direct_url';
    case UNKNOWN = 'unknown';

    /**
     * Check if this source type uses iframe embed.
     */
    public function usesIframe(): bool
    {
        return match ($this) {
            self::YOUTUBE, self::VIMEO, self::DAILYMOTION, self::FACEBOOK => true,
            default => false,
        };
    }

    /**
     * Get display label for this source type.
     */
    public function label(): string
    {
        return match ($this) {
            self::LOCAL_FILE => 'Uploaded File',
            self::YOUTUBE => 'YouTube Video',
            self::VIMEO => 'Vimeo Video',
            self::DAILYMOTION => 'Dailymotion Video',
            self::FACEBOOK => 'Facebook Video',
            self::DIRECT_URL => 'External Video',
            self::UNKNOWN => 'Unknown Source',
        };
    }
}
