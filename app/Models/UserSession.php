<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DeviceType;
use App\Enums\LoginMethod;
use App\Enums\SessionEndReason;
use DateTimeInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One row per session lifecycle for a user: signed in, last seen, and closed.
 * Maps to the `user_sessions` database table.
 *
 * Deliberately separate from the framework `sessions` table, which is destroyed
 * on logout and garbage-collected after the idle lifetime and so cannot hold history.
 */
final class UserSession extends Model
{
    protected $fillable = [
        'user_id', 'session_id', 'ip_address', 'last_ip_address', 'user_agent',
        'device_type', 'platform', 'browser', 'browser_version',
        'login_method', 'remembered', 'login_at', 'last_seen_at',
        'ended_at', 'end_reason', 'revoked_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'device_type' => DeviceType::class,
            'login_method' => LoginMethod::class,
            'end_reason' => SessionEndReason::class,
            'remembered' => 'boolean',
            'login_at' => 'datetime',
            'last_seen_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    /**
     * Get the owning user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who revoked the session, when an admin did so.
     */
    public function revoker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revoked_by');
    }

    /**
     * Idle lifetime in minutes, mirroring the framework session driver.
     */
    public static function lifetimeMinutes(): int
    {
        return (int) config('session.lifetime');
    }

    /**
     * Restrict to sessions still considered live.
     *
     * A row is only genuinely active if it was seen inside the framework session
     * lifetime: the driver garbage-collects rows lazily, so `ended_at` can lag
     * behind reality until the prune command reconciles it.
     *
     * @param Builder<self> $query
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->whereNull('ended_at')
            ->where('last_seen_at', '>', self::cutoff());
    }

    /**
     * Restrict to closed sessions, newest close first.
     *
     * @param Builder<self> $query
     */
    public function scopeEnded(Builder $query): Builder
    {
        return $query->whereNotNull('ended_at')->orderByDesc('ended_at');
    }

    /**
     * Restrict to rows that are still open but have aged past the session lifetime.
     *
     * @param Builder<self> $query
     */
    public function scopeStale(Builder $query): Builder
    {
        return $query
            ->whereNull('ended_at')
            ->where('last_seen_at', '<=', self::cutoff());
    }

    /**
     * Restrict to sessions belonging to one user.
     *
     * @param Builder<self> $query
     */
    public function scopeForUser(Builder $query, User|int $user): Builder
    {
        return $query->where('user_id', $user instanceof User ? $user->getKey() : $user);
    }

    /**
     * The moment a session last seen before it is no longer considered live.
     */
    public static function cutoff(): DateTimeInterface
    {
        return now()->subMinutes(self::lifetimeMinutes());
    }

    /**
     * Determine whether the session is still live.
     */
    public function isActive(): bool
    {
        return $this->ended_at === null
            && $this->last_seen_at !== null
            && $this->last_seen_at->greaterThan(self::cutoff());
    }

    /**
     * Determine whether this ledger row represents the request currently being served.
     */
    public function isCurrent(): bool
    {
        return $this->session_id !== null && $this->session_id === session()->getId();
    }

    /**
     * Readable device line built from the parsed user agent, e.g. "Safari 17.4 · macOS".
     */
    public function deviceSummary(): string
    {
        if ($this->device_type === DeviceType::BOT) {
            return $this->browser ?? 'Automated bot';
        }

        $details = array_filter([
            trim(implode(' ', array_filter([$this->browser, $this->browser_version]))),
            $this->platform,
        ]);

        if ($details === []) {
            return $this->device_type?->label() ?? 'Unknown device';
        }

        return implode(' · ', $details);
    }

    /**
     * Close the row, leaving an already-closed row untouched.
     *
     * @param Authenticatable|null $revoker The admin who revoked it, when an admin did.
     */
    public function close(SessionEndReason $reason, ?Authenticatable $revoker = null): self
    {
        if ($this->ended_at !== null) {
            return $this;
        }

        $this->forceFill([
            'ended_at' => now(),
            'end_reason' => $reason,
            'revoked_by' => $revoker?->getKey(),
        ])->save();

        return $this;
    }
}
