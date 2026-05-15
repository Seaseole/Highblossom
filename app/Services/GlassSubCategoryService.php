<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\GlassSubCategory;
use App\Models\GlassType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

final class GlassSubCategoryService
{
    /**
     * Get all sub-categories with pagination.
     */
    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return GlassSubCategory::with('glassType')
            ->ordered()
            ->paginate($perPage);
    }

    /**
     * Get sub-categories by glass type.
     */
    public function getByGlassType(GlassType|int $glassType): Collection
    {
        $glassTypeId = $glassType instanceof GlassType ? $glassType->id : $glassType;
        
        return GlassSubCategory::where('glass_type_id', $glassTypeId)
            ->active()
            ->ordered()
            ->get();
    }

    /**
     * Get active sub-categories for public quote form.
     */
    public function getActiveForQuote(): Collection
    {
        return GlassSubCategory::with('glassType')
            ->active()
            ->whereHas('glassType', fn($query) => $query->active())
            ->ordered()
            ->get();
    }

    /**
     * Create a new sub-category.
     */
    public function create(array $data): GlassSubCategory
    {
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = str($data['name'])->slug()->toString();
        }

        // Ensure unique slug
        $originalSlug = $data['slug'];
        $counter = 1;
        
        while (GlassSubCategory::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        return GlassSubCategory::create($data);
    }

    /**
     * Update a sub-category.
     */
    public function update(GlassSubCategory $glassSubCategory, array $data): GlassSubCategory
    {
        // Generate slug if name changed and slug not provided
        if (isset($data['name']) && !isset($data['slug'])) {
            $data['slug'] = str($data['name'])->slug()->toString();
        }

        // Ensure unique slug (excluding current record)
        if (isset($data['slug'])) {
            $originalSlug = $data['slug'];
            $counter = 1;
            
            while (GlassSubCategory::where('slug', $data['slug'])
                ->where('id', '!=', $glassSubCategory->id)
                ->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        $glassSubCategory->update($data);
        return $glassSubCategory->fresh();
    }

    /**
     * Delete a sub-category.
     */
    public function delete(GlassSubCategory $glassSubCategory): bool
    {
        // Check if sub-category has quotes
        if ($glassSubCategory->quotes()->exists()) {
            throw new \Exception('Cannot delete sub-category with associated quotes.');
        }

        return $glassSubCategory->delete();
    }

    /**
     * Toggle sub-category active status.
     */
    public function toggleStatus(GlassSubCategory $glassSubCategory): GlassSubCategory
    {
        $glassSubCategory->update(['is_active' => !$glassSubCategory->is_active]);
        return $glassSubCategory->fresh();
    }

    /**
     * Reorder sub-categories within a glass type.
     */
    public function reorder(array $subCategoryIds): bool
    {
        foreach ($subCategoryIds as $index => $id) {
            GlassSubCategory::where('id', $id)->update(['sort_order' => $index]);
        }

        return true;
    }

    /**
     * Get sub-categories grouped by glass type for dropdowns.
     */
    public function getGroupedByGlassType(): array
    {
        $subCategories = $this->getActiveForQuote();
        $grouped = [];

        foreach ($subCategories as $subCategory) {
            $glassTypeName = $subCategory->glassType->name;
            
            if (!isset($grouped[$glassTypeName])) {
                $grouped[$glassTypeName] = [];
            }
            
            $grouped[$glassTypeName][$subCategory->id] = $subCategory->name;
        }

        return $grouped;
    }
}
