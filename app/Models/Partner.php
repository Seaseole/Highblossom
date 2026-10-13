<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PartnerFactory;
use Highblossom\ContentBlocks\Support\MediaUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Business partner/affiliate logos and links.
 * Maps to the `partners` database table.
 */
class Partner extends Model
{
    /** @use HasFactory<PartnerFactory> */
    use HasFactory;

    protected $fillable = ['name', 'logo_path', 'website_url', 'order', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the full URL to the partner's logo.
     */
    public function getLogoUrlAttribute(): string
    {
        return MediaUrl::asset($this->logo_path) ?? '';
    }
}
