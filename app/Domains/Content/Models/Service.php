<?php

declare(strict_types=1);

namespace App\Domains\Content\Models;

use Illuminate\Database\Eloquent\Model;

final class Service extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'icon',
        'short_description',
        'full_description',
        'features',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    public function getFullImageUrlAttribute(): ?string
    {
        if ($this->image_url) {
            return $this->image_url;
        }

        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }

        return null;
    }
}
