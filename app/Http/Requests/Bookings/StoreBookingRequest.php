<?php

declare(strict_types=1);

namespace App\Http\Requests\Bookings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validate and authorize booking submissions from public users.
 */
final class StoreBookingRequest extends FormRequest
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
            'client_name' => ['required', 'string', 'max:255'],
            'client_email' => ['required', 'email', 'max:255'],
            'client_phone' => ['required', 'string', 'max:20'],
            'vehicle_details' => ['required', 'string'],
            'scheduled_at' => [
                'required',
                'date',
                'after:now',
                Rule::prohibitedIf(fn () => $this->isWeekend()),
            ],
            'location' => ['required', Rule::in(['mobile', 'workshop'])],
        ];
    }

    private function isWeekend(): bool
    {
        return now()->isWeekend();
    }
}
