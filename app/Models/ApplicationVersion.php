<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['version', 'major', 'minor', 'patch', 'summary', 'notes', 'released_at', 'created_by'])]
/**
 * Immutable record of an application release identified by a semantic version.
 * Maps to the `application_versions` database table.
 */
final class ApplicationVersion extends Model
{
    protected $casts = [
        'major' => 'integer',
        'minor' => 'integer',
        'patch' => 'integer',
        'notes' => 'array',
        'released_at' => 'date',
    ];

    /**
     * The user who recorded the release.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Order releases newest semantic version first.
     *
     * @param Builder<static> $query
     *
     * @return Builder<static>
     */
    public function scopeNewestFirst(Builder $query): Builder
    {
        return $query->orderByDesc('major')->orderByDesc('minor')->orderByDesc('patch');
    }

    /**
     * The semantic version as a numeric tuple for comparison.
     *
     * @return array{int, int, int}
     */
    public function tuple(): array
    {
        return [$this->major, $this->minor, $this->patch];
    }
}
