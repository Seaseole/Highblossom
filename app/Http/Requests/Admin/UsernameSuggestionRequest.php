<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validate a request for AI username suggestions.
 *
 * The admin group already requires a signed-in user; this repeats the check so
 * the endpoint never becomes a public availability oracle if it is moved. There
 * is deliberately no "is this specific name free?" field - the service decides
 * which names to check, so a caller cannot walk a wordlist through this route.
 */
final class UsernameSuggestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'max:255'],
            'count' => ['nullable', 'integer', 'min:1', 'max:10'],
            'ignore_user_id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }

    /**
     * Get the validated payload with a clamped, typed count.
     *
     * @return array{name: string, email: string, count: int, ignore_user_id: int|null}
     */
    public function suggestionInput(): array
    {
        $data = $this->validated();

        return [
            'name' => (string) ($data['name'] ?? ''),
            'email' => (string) ($data['email'] ?? ''),
            'count' => max(1, min((int) ($data['count'] ?? config('ai.usernames.count', 6)), 10)),
            'ignore_user_id' => isset($data['ignore_user_id']) ? (int) $data['ignore_user_id'] : null,
        ];
    }
}
