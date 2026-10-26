<?php

declare(strict_types=1);

namespace App\Http\Resources\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The authenticated staff member plus the ability list the app uses to gate its own UI.
 *
 * @mixin User
 */
final class UserResource extends JsonResource
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
            'username' => $this->username,
            'email' => $this->email,
            'avatar_url' => $this->avatar_path !== null ? media_url($this->avatar_path) : null,
            'roles' => $this->getRoleNames()->values(),
            'abilities' => $this->getAllPermissions()->pluck('name')->values(),
        ];
    }
}
