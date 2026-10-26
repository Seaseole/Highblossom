<?php

declare(strict_types=1);

namespace App\Http\Resources\Api;

use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A customer quote request. Glass/service names are eager-loaded by the controller;
 * the full glass description folds in the sub-category when present.
 *
 * @mixin Quote
 */
final class QuoteResource extends JsonResource
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
            'status' => $this->status,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'vehicle' => [
                'type' => $this->vehicle_type,
                'make_model' => $this->make_model,
                'reg_number' => $this->reg_number,
                'year' => $this->year,
            ],
            'mobile_service' => (bool) $this->mobile_service,
            'message' => $this->message,
            'glass' => [
                'description' => $this->full_glass_description,
                'glass_type_id' => $this->glass_type_id,
                'glass_type_name' => $this->whenLoaded('glassType', fn () => $this->glassType?->name),
                'glass_sub_category_id' => $this->glass_sub_category_id,
                'service_type_id' => $this->service_type_id,
                'service_type_name' => $this->whenLoaded('serviceType', fn () => $this->serviceType?->name),
            ],
            'image_url' => $this->image_path !== null ? media_url($this->image_path) : null,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
