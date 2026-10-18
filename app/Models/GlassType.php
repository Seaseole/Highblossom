<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasSortOrder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Types of glass available for quotes/services with sub-categories.
 * Maps to the `glass_types` database table.
 */
final class GlassType extends Model
{
    use HasSortOrder;

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

    /**
     * Scope query to only include active glass types.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope query to order by sort order, with an id tie-break for rows outside the
     * same uniqueness scope.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderByDesc('id');
    }

    /**
     * Get the sub-categories for this glass type, in display order.
     */
    public function subCategories(): HasMany
    {
        return $this->hasMany(GlassSubCategory::class)->orderBy('sort_order')->orderByDesc('id');
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
        return $this->hasMany(Quote::class);
    }
}
