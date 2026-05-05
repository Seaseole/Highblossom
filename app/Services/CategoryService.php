<?php

declare(strict_types=1);

namespace App\Services;

use App\Domains\Content\Models\Category;
use Illuminate\Support\Str;

final class CategoryService
{
    public function create(array $data): Category
    {
        $data['slug'] = Str::slug($data['name']);

        return Category::create($data);
    }

    public function update(Category $category, array $data): Category
    {
        $data['slug'] = Str::slug($data['name']);

        $category->update($data);

        return $category->fresh();
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }
}
