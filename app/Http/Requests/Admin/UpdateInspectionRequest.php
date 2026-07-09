<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validate inspection updates.
 */
final class UpdateInspectionRequest extends FormRequest
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
            'staff_id' => ['sometimes', 'exists:users,id'],
            'scheduled_at' => ['sometimes', 'date'],
            'ended_at' => ['nullable', 'date', 'after:scheduled_at'],
            'location' => ['sometimes', 'string', 'max:255'],
            'type' => ['sometimes', Rule::in(['mobile', 'workshop'])],
        ];
    }
}
