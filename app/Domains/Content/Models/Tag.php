<?php

declare(strict_types=1);

namespace App\Domains\Content\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

final class Tag extends Model
{
    use HasFactory;

    protected static ?string $factory = \Database\Factories\TagFactory::class;

    protected $fillable = ['name', 'slug'];

    public function posts(): MorphToMany
    {
        return $this->morphedByMany(Post::class, 'taggable', 'taggables');
    }

    public function pages(): MorphToMany
    {
        return $this->morphedByMany(Page::class, 'taggable', 'taggables');
    }

    public function scopeByType($query, string $type)
    {
        return $query->whereHas('posts')
            ->orWhereHas('pages');
    }
}
