<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\GlassType;
use Illuminate\Support\Str;

final class GlassTypeService
{
    public function create(array $data): GlassType
    {
        $data['slug'] = Str::slug($data['name']);

        return GlassType::create($data);
    }

    public function update(GlassType $glassType, array $data): GlassType
    {
        $data['slug'] = Str::slug($data['name']);

        $glassType->update($data);

        return $glassType->fresh();
    }

    public function delete(GlassType $glassType): void
    {
        $glassType->delete();
    }
}
