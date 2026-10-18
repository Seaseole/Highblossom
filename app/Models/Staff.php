<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasSortOrder;
use Database\Factories\StaffFactory;
use Highblossom\ContentBlocks\Support\MediaUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    /** @use HasFactory<StaffFactory> */
    use HasFactory;

    use HasSortOrder;

    /**
     * This table names its ordering column `order`, not `sort_order`.
     */
    public function sortOrderColumn(): string
    {
        return 'order';
    }

    protected $fillable = ['name', 'role', 'bio', 'photo_path', 'order', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get the full URL to the staff member's photo.
     */
    /**
     * Get the full URL to the staff member's photo.
     */
    public function getPhotoUrlAttribute(): string
    {
        return $this->photo_path ? MediaUrl::asset($this->photo_path) : asset('placeholder.gif');
    }
}
