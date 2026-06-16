<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Polymorphic pivot linking media to models.
 * Maps to the `media_usage` database table.
 */
final class MediaUsage extends Model
{
    protected $fillable = [
        'media_registry_id',
        'model_type',
        'model_id',
        'attribute',
    ];

    /**
     * Get the media registry entry for this usage.
     */
    public function media(): BelongsTo
    {
        return $this->belongsTo(MediaRegistry::class, 'media_registry_id');
    }

    /**
     * Get the parent model that uses this media.
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
