<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Services\ApplicationVersionService;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates a request that records a new application release.
 */
final class StoreApplicationVersionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to record application releases.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('manage versions') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'version' => ['required', 'string', 'max:20', 'regex:'.ApplicationVersionService::SEMVER_PATTERN],
            'summary' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'array', 'max:20'],
            'notes.*.type' => ['required', 'string', 'in:'.implode(',', ApplicationVersionService::NOTE_TYPES)],
            'notes.*.text' => ['required', 'string', 'max:500'],
            'released_at' => ['required', 'date_format:Y-m-d'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'version.regex' => 'Versions must follow semantic versioning, e.g. 1.2.0.',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'version' => 'version number',
            'summary' => 'release summary',
            'notes.*.type' => 'note type',
            'notes.*.text' => 'note text',
        ];
    }
}
