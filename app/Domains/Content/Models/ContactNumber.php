<?php

namespace App\Domains\Content\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'phone_number',
        'is_primary',
        'is_whatsapp',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_whatsapp' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope for active numbers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for primary number.
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope for WhatsApp number.
     */
    public function scopeWhatsapp($query)
    {
        return $query->where('is_whatsapp', true);
    }

    /**
     * Scope for ordered numbers.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    /**
     * Get the formatted phone number for display.
     */
    public function getFormattedNumberAttribute(): string
    {
        $number = $this->phone_number;
        // Format: +267 XX XXX XXX
        if (strlen($number) >= 10) {
            return substr($number, 0, 4) . ' ' . substr($number, 4, 2) . ' ' . substr($number, 6, 3) . ' ' . substr($number, 9);
        }
        return $number;
    }
}
