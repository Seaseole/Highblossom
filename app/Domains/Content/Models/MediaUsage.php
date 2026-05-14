<?php

namespace App\Domains\Content\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

final class MediaUsage extends Model
{
    protected $fillable = [
        'media_registry_id',
        'model_type',
        'model_id',
        'attribute',
    ];

    public function media(): BelongsTo
    {
        return $this->belongsTo(MediaRegistry::class, 'media_registry_id');
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
