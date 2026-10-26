<?php

declare(strict_types=1);

namespace App\Http\Resources\Api;

use App\Models\BookingEvent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A customer-visible milestone in a booking's history.
 *
 * @mixin BookingEvent
 */
final class BookingEventResource extends JsonResource
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
            'type' => $this->type,
            'summary' => $this->summary,
            'happened_at' => $this->happened_at?->toIso8601String(),
            'previous_scheduled_at' => $this->previous_scheduled_at?->toIso8601String(),
            'actor' => $this->whenLoaded('actor', fn () => [
                'id' => $this->actor?->id,
                'name' => $this->actor?->name,
            ]),
        ];
    }
}
