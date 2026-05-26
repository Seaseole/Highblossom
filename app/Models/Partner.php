<?php

namespace App\Models;

use Database\Factories\PartnerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Partner extends Model
{
    /** @use HasFactory<PartnerFactory> */
    use HasFactory;

    protected $fillable = ['name', 'logo_path', 'website_url', 'order', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getLogoUrlAttribute(): string
    {
        return Storage::url($this->logo_path);
    }
}
