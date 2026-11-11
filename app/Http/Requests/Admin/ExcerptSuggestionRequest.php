<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validate the post editor's request for AI excerpt suggestions.
 *
 * The route already carries `can:create blog`; this repeats the check so the
 * endpoint stays guarded even if it is moved or reused. Only field values are
 * accepted — the prompt subject and body are resolved on the server.
 */
final class ExcerptSuggestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create blog') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:'.Post::TITLE_MAX],
            'excerpt' => ['nullable', 'string', 'max:'.Post::EXCERPT_MAX],
            'content' => ['nullable', 'string', 'max:100000'],
            'tone' => ['nullable', 'string', Rule::in(array_keys((array) config('ai.tones', [])))],
        ];
    }
}
