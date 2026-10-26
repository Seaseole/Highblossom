<?php

declare(strict_types=1);

namespace App\Http\Resources\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A blog post summary for the staff app's moderation list.
 *
 * The raw block `content` is intentionally omitted: the mobile app moderates and
 * previews posts (status, excerpt, featured image) rather than rendering or
 * editing the block builder. Full content editing stays on the web admin.
 *
 * @mixin Post
 */
final class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'status' => $this->status,
            'status_key' => $this->isScheduled() ? 'scheduled' : $this->status,
            'is_live' => $this->isLive(),
            'read_time_minutes' => $this->readTimeMinutes(),
            'featured_image_url' => $this->featured_image_url,
            'published_at' => $this->published_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'author' => $this->whenLoaded('author', fn () => [
                'id' => $this->author?->id,
                'name' => $this->author?->name,
            ]),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
        ];
    }
}
