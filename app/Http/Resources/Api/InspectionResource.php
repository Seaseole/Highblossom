<?php

declare(strict_types=1);

namespace App\Http\Resources\Api;

use App\Models\Inspection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * An on-site inspection appointment linked to a booking.
 *
 * @mixin Inspection
 */
final class InspectionResource extends JsonResource
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
            'booking_id' => $this->booking_id,
            'status' => $this->status,
            'type' => $this->type,
            'location' => $this->location,
            'scheduled_at' => $this->scheduled_at?->toIso8601String(),
            'started_at' => $this->started_at?->toIso8601String(),
            'ended_at' => $this->ended_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'staff' => $this->whenLoaded('staff', fn () => [
                'id' => $this->staff?->id,
                'name' => $this->staff?->name,
            ]),
            'booking' => new BookingResource($this->whenLoaded('booking')),
            'notes' => InspectionNoteResource::collection($this->whenLoaded('notes')),
        ];
    }
}
