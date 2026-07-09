<?php

declare(strict_types=1);

namespace App\Http\Requests\Bookings;

use Carbon\Carbon;
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
            'client_email' => ['required', 'email:filter', 'max:255'],
            'client_phone' => ['required', 'string', 'regex:/^[0-9]{7,20}$/'],
            'vehicle_details' => ['required', 'string', 'max:255'],
            'scheduled_at' => [
                'required',
                'date_format:Y-m-d\TH:i:s',
                'after:now',
                Rule::prohibitedIf(fn () => $this->isWeekend()),
            ],
            'client_address' => ['nullable', 'string', 'max:500'],
            'location' => ['required', Rule::in(['mobile', 'workshop'])],
            '_idempotency_token' => ['required', 'string', 'max:64'],
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'client_name.required' => 'Please enter your full name.',
            'client_name.max' => 'Your name must not exceed 255 characters.',
            'client_email.required' => 'Please enter your email address.',
            'client_email.email' => 'Please enter a valid email address.',
            'client_email.max' => 'Email must not exceed 255 characters.',
            'client_phone.required' => 'Please enter your phone number.',
            'client_phone.regex' => 'Phone number must contain only digits (7–20 characters).',
            'vehicle_details.required' => 'Please provide your vehicle details.',
            'vehicle_details.max' => 'Vehicle details must not exceed 255 characters.',
            'scheduled_at.required' => 'Please select a date and time for your booking.',
            'scheduled_at.date_format' => 'Please provide a valid date and time format.',
            'scheduled_at.after' => 'The booking time must be in the future.',
            'location.required' => 'Please select a service location.',
            'location.in' => 'Please select a valid service location.',
            '_idempotency_token.required' => 'Session token missing. Please refresh the page.',
        ];
    }

    private function isWeekend(): bool
    {
        return Carbon::parse($this->input('scheduled_at'))->isWeekend();
    }
}
