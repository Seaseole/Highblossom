<?php

namespace App\Models;

use App\Models\GlassType;
use App\Models\ServiceType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'vehicle_type',
        'make_model',
        'reg_number',
        'year',
        'glass_type_id',
        'glass_sub_category_id',
        'service_type_id',
        'message',
        'image_path',
        'mobile_service',
        'status',
    ];

    protected $casts = [
        'mobile_service' => 'boolean',
        'year' => 'integer',
    ];

    /**
     * Scope for pending quotes.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for latest quotes.
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Get the glass type for this quote.
     */
    public function glassType()
    {
        return $this->belongsTo(GlassType::class);
    }

    /**
     * Get the service type for this quote.
     */
    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    /**
     * Get the glass sub-category for this quote.
     */
    public function glassSubCategory()
    {
        return $this->belongsTo(\App\Models\GlassSubCategory::class);
    }

    /**
     * Get the full glass description including sub-category.
     */
    public function getFullGlassDescriptionAttribute(): string
    {
        if ($this->glassSubCategory) {
            return $this->glassSubCategory->full_name;
        }
        
        return $this->glassType ? $this->glassType->name : 'Unknown';
    }
}
