<?php

declare(strict_types=1);

namespace App\Domains\Content\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class GlassSubCategory extends Model
{
    protected $fillable = [
        'glass_type_id',
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
     * Get the glass type that owns the sub-category.
     */
    public function glassType(): BelongsTo
    {
        return $this->belongsTo(GlassType::class);
    }

    /**
     * Get the quotes for this sub-category.
     */
    public function quotes(): HasMany
    {
        return $this->hasMany(\App\Domains\Bookings\Models\Quote::class, 'glass_sub_category_id');
    }

    /**
     * Scope for active sub-categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered sub-categories.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    /**
     * Scope for sub-categories by glass type.
     */
    public function scopeByGlassType($query, $glassTypeId)
    {
        return $query->where('glass_type_id', $glassTypeId);
    }

    /**
     * Get the full name including glass type.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->glassType->name} - {$this->name}";
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically generate slug from name
        static::creating(function ($glassSubCategory) {
            if (empty($glassSubCategory->slug)) {
                $glassSubCategory->slug = str($glassSubCategory->name)->slug()->toString();
            }
        });

        static::updating(function ($glassSubCategory) {
            if ($glassSubCategory->isDirty('name') && empty($glassSubCategory->slug)) {
                $glassSubCategory->slug = str($glassSubCategory->name)->slug()->toString();
            }
        });
    }
}
