<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

/**
 * Validate the post-login terms and privacy consent capture form.
 */
final class ConsentRequest extends FormRequest
{
    /**
     * The two policies that must both be accepted.
     *
     * @var list<string>
     */
    private const FIELDS = ['terms', 'privacy'];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the consent form.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'terms' => ['required', 'accepted'],
            'privacy' => ['required', 'accepted'],
        ];
    }

    /**
     * Plain-language messages, one per box.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'terms.required' => 'Tick the terms box to continue.',
            'terms.accepted' => 'Tick the terms box to continue.',
            'privacy.required' => 'Tick the privacy policy box to continue.',
            'privacy.accepted' => 'Tick the privacy policy box to continue.',
        ];
    }

    /**
     * Collapse the both-empty case into one instruction.
     *
     * Two "tick the … box" lines describe a single problem — the form was submitted
     * untouched — so the per-field errors are replaced by one message on the group.
     * This is the form-request equivalent of a Volt `$fail()` closure: a rule string
     * cannot express a condition about two fields at once.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $unticked = array_values(array_filter(
                self::FIELDS,
                fn (string $field): bool => $this->boolean($field) !== true,
            ));

            if ($unticked !== self::FIELDS) {
                return;
            }

            // MessageBag::forget() takes one key at a time in this Laravel version.
            foreach ($unticked as $field) {
                $validator->errors()->forget($field);
            }

            $validator->errors()->add('terms', 'Tick both boxes to continue.');
        });
    }
}
