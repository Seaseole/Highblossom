<?php

declare(strict_types=1);

namespace App\Domains\Content\Models;

use Illuminate\Database\Eloquent\Model;

final class AboutUsContent extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'body',
        'hero_image',
        'mission',
        'vision',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function active(): ?self
    {
        return static::where('is_active', true)->first();
    }
}
