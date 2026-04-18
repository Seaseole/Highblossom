<?php

namespace App\Domains\Content\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

final class ContentBlock extends Model
{
    protected $fillable = ['type', 'content', 'sort_order', 'is_visible'];

    protected $casts = [
        'content' => 'json',
        'is_visible' => 'boolean',
    ];

    public function blockable(): MorphTo
    {
        return $this->morphTo();
    }
}
