<?php

declare(strict_types=1);

namespace App\Http\Resources\Api;

use App\Models\InspectionNote;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * An internal, timestamped note on an inspection. Never customer-facing.
 *
 * @mixin InspectionNote
 */
final class InspectionNoteResource extends JsonResource
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
            'body' => $this->body,
            'is_action' => (bool) $this->is_action,
            'is_open' => $this->isOpen(),
            'done_at' => $this->done_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'author' => $this->whenLoaded('author', fn () => [
                'id' => $this->author?->id,
                'name' => $this->author?->name,
            ]),
        ];
    }
}
