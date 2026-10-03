<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Theme;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Passkeys\Contracts\PasskeyUser;
use Laravel\Passkeys\PasskeyAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'phone', 'password', 'theme', 'avatar_path', 'terms_accepted_at', 'privacy_accepted_at'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
/**
 * The authenticatable user model with roles, passkeys, and two-factor authentication.
 * Maps to the `users` database table.
 */
class User extends Authenticatable implements MustVerifyEmail, PasskeyUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable, PasskeyAuthenticatable, TwoFactorAuthenticatable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'terms_accepted_at' => 'datetime',
            'privacy_accepted_at' => 'datetime',
            'password' => 'hashed',
            'theme' => Theme::class,
        ];
    }

    /**
     * Get the full URL to the user's avatar, or null when none has been uploaded.
     */
    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar_path ? Storage::url($this->avatar_path) : null;
    }

    /**
     * Get the user's initials.
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->trim()
            ->replaceMatches('/\s+/', ' ')
            ->explode(' ')
            ->filter(fn ($word) => Str::length(trim($word)) > 0)
            ->take(2)
            ->map(fn ($word) => mb_strtoupper(Str::substr(trim($word), 0, 1)))
            ->implode('');
    }

    /**
     * Get the user's blog posts.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    /**
     * Get the bookings created by the user.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    /**
     * Get the booking milestones the user caused.
     */
    public function bookingEvents(): HasMany
    {
        return $this->hasMany(BookingEvent::class, 'user_id');
    }

    /**
     * Get the internal inspection notes the user wrote.
     */
    public function inspectionNotes(): HasMany
    {
        return $this->hasMany(InspectionNote::class, 'user_id');
    }

    /**
     * Get the session ledger rows recording every device this user has signed in from.
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(UserSession::class, 'user_id');
    }

    /**
     * Get the sessions this user personally revoked via an admin action.
     */
    public function revokedSessions(): HasMany
    {
        return $this->hasMany(UserSession::class, 'revoked_by');
    }
}
