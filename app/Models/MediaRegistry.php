<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Central media file registry for uploaded assets.
 * Maps to the `media_registry` database table.
 */
final class MediaRegistry extends Model
{
    protected $fillable = [
        'path',
        'original_name',
        'file_size',
    ];

    /**
     * Get all model usages of this media file.
     */
    public function usages(): HasMany
    {
        return $this->hasMany(MediaUsage::class);
    }
}
