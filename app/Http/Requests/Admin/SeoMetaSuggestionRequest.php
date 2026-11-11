<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validate an SEO form's request for AI meta suggestions.
 *
 * The route already carries `can:manage seo`; this repeats the check so the
 * endpoint stays guarded if moved. The page label is resolved on the server
 * from route_id or an allowlisted route_name, never from a client label.
 */
final class SeoMetaSuggestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('manage seo') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'field' => ['required', 'string', Rule::in(['meta_title', 'meta_description'])],
            'route_id' => ['nullable', 'integer', 'exists:seo_static_routes,id'],
            'route_name' => ['nullable', 'string', Rule::in(config('seo.static_routes', []))],
            'meta_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'tone' => ['nullable', 'string', Rule::in(array_keys((array) config('ai.tones', [])))],
        ];
    }
}
