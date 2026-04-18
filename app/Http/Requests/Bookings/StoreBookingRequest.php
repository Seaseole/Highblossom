<?php

namespace App\Http\Requests\Bookings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreBookingRequest extends FormRequest
{
    /**
     * Rule 6: Authorize every action
     */
    public function authorize(): bool
    {
        return true; // Public users can create bookings (leads)
    }

    /**
     * Rule 7: Strict validation with Rule objects
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
                Rule::prohibitedIf(fn() => $this->isWeekend())
            ],
            'location' => ['required', Rule::in(['mobile', 'workshop'])],
        ];
    }

    private function isWeekend(): bool
    {
        return now()->isWeekend();
    }
}
