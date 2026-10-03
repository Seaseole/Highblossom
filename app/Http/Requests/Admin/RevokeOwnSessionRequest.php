<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\UserSession;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Authorize revoking a session from the account owner's own profile page.
 *
 * Only the row's owner may use this endpoint; administrators act through the
 * dedicated session management screen instead.
 */
final class RevokeOwnSessionRequest extends FormRequest
{
    /**
     * Determine whether the authenticated user owns the session being revoked.
     */
    public function authorize(): bool
    {
        $session = $this->route('session');

        return $session instanceof UserSession
            && $session->user_id === $this->user()?->getKey();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [];
    }
}
