<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validate a status change applied to several posts at once.
 */
final class BulkPostStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(Post::STATUSES)],
            'posts' => ['required', 'array', 'min:1', 'max:100'],
            'posts.*' => ['integer', 'exists:posts,id'],
        ];
    }
}
