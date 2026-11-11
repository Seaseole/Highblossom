<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * One rule-based suggestion shown on the day desk and the system overview.
 *
 * A row is owned by its rule key: the scanner upserts it as the signal fires,
 * clears and recurs. Dismissal is sticky for the current occurrence — the row
 * only becomes eligible to reopen once the signal has fully cleared (resolved).
 */
#[Fillable(['key', 'fingerprint', 'severity', 'message', 'action_url', 'action_label', 'permission', 'status', 'snoozed_until'])]
final class Nudge extends Model
{
    public const STATUS_OPEN = 'open';

    public const STATUS_DISMISSED = 'dismissed';

    public const STATUS_SNOOZED = 'snoozed';

    public const STATUS_RESOLVED = 'resolved';

    public const SEVERITY_INFO = 'info';

    public const SEVERITY_WARN = 'warn';

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'snoozed_until' => 'datetime',
    ];

    /**
     * Rows the viewer should see right now: open, or snoozed past their deadline.
     *
     * @param Builder<self> $query
     *
     * @return Builder<self>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where(function (Builder $query): void {
            $query->where('status', self::STATUS_OPEN)
                ->orWhere(function (Builder $query): void {
                    $query->where('status', self::STATUS_SNOOZED)
                        ->where('snoozed_until', '<=', now());
                });
        });
    }
}
