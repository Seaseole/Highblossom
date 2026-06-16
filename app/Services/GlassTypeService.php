<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\GlassType;
use Illuminate\Support\Str;

/**
 * Service for managing glass types.
 */
final class GlassTypeService
{
    /**
     * Create a new glass type.
     */
    public function create(array $data): GlassType
    {
        $data['slug'] = Str::slug($data['name']);

        return GlassType::create($data);
    }

    /**
     * Update an existing glass type.
     */
    public function update(GlassType $glassType, array $data): GlassType
    {
        $data['slug'] = Str::slug($data['name']);

        $glassType->update($data);

        return $glassType->fresh();
    }

    /**
     * Delete a glass type.
     */
    public function delete(GlassType $glassType): void
    {
        $glassType->delete();
    }
}
