<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validate the About Us form's request for AI copy suggestions.
 *
 * The route already carries `can:manage pages`; this repeats the check so the
 * endpoint stays guarded even if it is moved or reused. Only field values are
 * accepted — the copy kind and placement are resolved on the server.
 */
final class AboutSuggestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('manage pages') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'field' => ['required', 'string', Rule::in(['body', 'mission', 'vision'])],
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string', 'max:60000'],
            'mission' => ['nullable', 'string', 'max:60000'],
            'vision' => ['nullable', 'string', 'max:60000'],
            'tone' => ['nullable', 'string', Rule::in(array_keys((array) config('ai.tones', [])))],
        ];
    }
}
