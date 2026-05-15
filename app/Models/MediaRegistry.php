<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class MediaRegistry extends Model
{
    protected $fillable = [
        'path',
        'original_name',
        'file_size',
    ];

    public function usages(): HasMany
    {
        return $this->hasMany(MediaUsage::class);
    }
}
