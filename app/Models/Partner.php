<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasSortOrder;
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

    use HasSortOrder;

    /**
     * This table names its ordering column `order`, not `sort_order`.
     */
    public function sortOrderColumn(): string
    {
        return 'order';
    }

    protected $fillable = ['name', 'logo_path', 'website_url', 'order', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get the full URL to the partner's logo.
     */
    public function getLogoUrlAttribute(): string
    {
        return MediaUrl::asset($this->logo_path) ?? '';
    }
}
