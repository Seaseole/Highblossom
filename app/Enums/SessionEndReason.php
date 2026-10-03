<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Why a session ledger row was closed.
 */
enum SessionEndReason: string
{
    case LOGOUT = 'logout';
    case EXPIRED = 'expired';
    case REVOKED_BY_USER = 'revoked_by_user';
    case REVOKED_BY_ADMIN = 'revoked_by_admin';
    case PASSWORD_CHANGED = 'password_changed';

    /**
     * Get display label for this end reason.
     */
    public function label(): string
    {
        return match ($this) {
            self::LOGOUT => 'Signed out',
            self::EXPIRED => 'Expired',
            self::REVOKED_BY_USER => 'Revoked by user',
            self::REVOKED_BY_ADMIN => 'Revoked by admin',
            self::PASSWORD_CHANGED => 'Password changed',
        };
    }
}
