<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class GlassType extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
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

    /**
     * Get the sub-categories for this glass type.
     */
    public function subCategories(): HasMany
    {
        return $this->hasMany(GlassSubCategory::class);
    }

    /**
     * Get the active sub-categories for this glass type.
     */
    public function activeSubCategories(): HasMany
    {
        return $this->subCategories()->active()->ordered();
    }

    /**
     * Get the quotes for this glass type.
     */
    public function quotes(): HasMany
    {
        return $this->hasMany(\App\Models\Quote::class);
    }
}
