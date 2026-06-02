<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Theme;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\LaravelPasskeys\Models\Concerns\HasPasskeys;
use Spatie\LaravelPasskeys\Models\Concerns\InteractsWithPasskeys;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'theme', 'terms_accepted_at', 'privacy_accepted_at'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable implements HasPasskeys
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, InteractsWithPasskeys, Notifiable, TwoFactorAuthenticatable;

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
     * Get the user's initials
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
     * Get the user's blog posts
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'user_id');
    }
}
