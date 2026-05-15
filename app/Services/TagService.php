<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tag;
use Illuminate\Support\Str;

final class TagService
{
    public function create(array $data): Tag
    {
        $data['slug'] = Str::slug($data['name']);

        return Tag::create($data);
    }

    public function update(Tag $tag, array $data): Tag
    {
        $data['slug'] = Str::slug($data['name']);

        $tag->update($data);

        return $tag->fresh();
    }

    public function delete(Tag $tag): void
    {
        $tag->delete();
    }
}
