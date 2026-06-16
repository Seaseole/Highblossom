<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * About Us page content with mission and vision.
 * Maps to the `about_us_contents` database table.
 */
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

    /**
     * Get the active About Us content entry.
     *
     * @return static|null
     */
    public static function active(): ?self
    {
        return self::where('is_active', true)->first();
    }
}
