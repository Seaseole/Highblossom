<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * How a session was established.
 */
enum LoginMethod: string
{
    case PASSWORD = 'password';
    case TWO_FACTOR = 'two_factor';
    case PASSKEY = 'passkey';
    case REMEMBER = 'remember';
    case UNKNOWN = 'unknown';

    /**
     * Get display label for this login method.
     */
    public function label(): string
    {
        return match ($this) {
            self::PASSWORD => 'Password',
            self::TWO_FACTOR => 'Password + 2FA',
            self::PASSKEY => 'Passkey',
            self::REMEMBER => 'Remembered device',
            self::UNKNOWN => 'Unknown',
        };
    }
}
