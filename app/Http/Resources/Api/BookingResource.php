<?php

declare(strict_types=1);

namespace App\Http\Resources\Api;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A customer booking. The list view ships the summary fields; the detail view
 * eager-loads the inspection and the customer-visible milestone timeline.
 *
 * @mixin Booking
 */
final class BookingResource extends JsonResource
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
            'reference' => $this->displayReference(),
            'status' => $this->status,
            'is_cancelled' => $this->isCancelled(),
            'client' => [
                'name' => $this->client_name,
                'email' => $this->client_email,
                'phone' => $this->client_phone,
                'prefers_whatsapp' => (bool) $this->prefers_whatsapp,
                'address' => $this->client_address,
            ],
            'service_type' => $this->service_type?->value,
            'service_type_label' => $this->service_type?->label(),
            'damage_type' => $this->damage_type?->value,
            'damage_type_label' => $this->damage_type?->label(),
            'vehicle' => [
                'make' => $this->vehicle_make,
                'model' => $this->vehicle_model,
                'year' => $this->vehicle_year,
                'details' => $this->vehicle_details,
                'summary' => $this->vehicleSummary(),
            ],
            'scheduled_at' => $this->scheduled_at?->toIso8601String(),
            'ends_at' => $this->ends_at?->toIso8601String(),
            'duration_minutes' => $this->durationMinutes(),
            'location' => $this->location,
            'total_price' => $this->total_price !== null ? (float) $this->total_price : null,
            'damage_photo_url' => $this->damage_photo_path !== null ? media_url($this->damage_photo_path) : null,
            'created_at' => $this->created_at?->toIso8601String(),
            'inspection' => new InspectionResource($this->whenLoaded('inspection')),
            'events' => BookingEventResource::collection($this->whenLoaded('events')),
        ];
    }
}
