<?php

namespace App\Models;

use Database\Factories\StaffFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Staff extends Model
{
    /** @use HasFactory<StaffFactory> */
    use HasFactory;

    protected $fillable = ['name', 'role', 'bio', 'photo_path', 'order', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getPhotoUrlAttribute(): string
    {
        return $this->photo_path ? Storage::url($this->photo_path) : asset('placeholder.gif');
    }
}
