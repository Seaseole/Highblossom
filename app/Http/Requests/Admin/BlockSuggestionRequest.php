<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Actions\Content\BuildBlockSuggestionContextAction;
use App\Models\Post;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

/**
 * Validate the block editor's request for AI copy suggestions.
 *
 * The route already carries `can:create blog`; this repeats the check so the
 * endpoint stays guarded even if it is moved or reused. Only field values are
 * accepted — the copy kind and placement are resolved on the server from the
 * block type and field, never from a client-supplied prompt.
 */
final class BlockSuggestionRequest extends FormRequest
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
            'block_type' => ['required', 'string', Rule::in(array_keys(BuildBlockSuggestionContextAction::FIELD_KINDS))],
            'field' => ['required', 'string', 'max:50'],
            'title' => ['nullable', 'string', 'max:'.Post::TITLE_MAX],
            'content' => ['nullable', 'string', 'max:100000'],
            'current' => ['nullable', 'string', 'max:60000'],
            'tone' => ['nullable', 'string', Rule::in(array_keys((array) config('ai.tones', [])))],
        ];
    }

    /**
     * Reject fields the block type does not offer for suggestions.
     *
     * @return Closure(Validator): void
     */
    public function after(): Closure
    {
        return function (Validator $validator): void {
            // After-hooks run even when the base rules failed, so the inputs
            // may still be arrays here; casting those would 500 instead of 422.
            $type = $this->input('block_type', '');
            $field = $this->input('field', '');

            if (is_string($type) && is_string($field)
                && isset(BuildBlockSuggestionContextAction::FIELD_KINDS[$type])
                && ! isset(BuildBlockSuggestionContextAction::FIELD_KINDS[$type][$field])) {
                $validator->errors()->add('field', 'The selected field does not belong to this block type.');
            }
        };
    }
}
