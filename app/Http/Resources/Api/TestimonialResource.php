<?php

declare(strict_types=1);

namespace App\Http\Resources\Api;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A customer testimonial.
 *
 * @mixin Testimonial
 */
final class TestimonialResource extends JsonResource
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
            'name' => $this->name,
            'role' => $this->role,
            'rating' => (int) $this->rating,
            'content' => $this->content,
            'is_featured' => (bool) $this->is_featured,
            'is_published' => (bool) $this->is_published,
            'sort_order' => (int) $this->sort_order,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
