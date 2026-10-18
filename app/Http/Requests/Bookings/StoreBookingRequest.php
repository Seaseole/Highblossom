<?php

declare(strict_types=1);

namespace App\Http\Requests\Bookings;

use App\Enums\BookingDamageType;
use App\Enums\BookingServiceType;
use App\Services\Contracts\AvailabilityServiceInterface;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validate and authorize booking submissions from public users.
 */
final class StoreBookingRequest extends FormRequest
{
    /**
     * Character ceiling shared by every free-text column on the `bookings` table.
     *
     * The columns are varchar(191), so a looser validation ceiling would let an
     * over-long address or vehicle note reach MySQL and lose the booking.
     */
    private const MAX_TEXT_LENGTH = 191;

    /** Ceiling on the damage photo, in kilobytes. */
    private const MAX_PHOTO_KILOBYTES = 5120;

    /**
     * Which wizard step owns each field.
     *
     * A failed submission sends the customer back to the earliest step that owns one
     * of the errors, so this map is the single place that decides where a field
     * lives. A field added to the form but not to this map fails silently on a step
     * the customer has already left.
     *
     * @var array<int, list<string>>
     */
    public const STEP_FIELDS = [
        1 => ['service_type', 'damage_type', 'vehicle_make', 'vehicle_model', 'vehicle_year', 'location', 'client_address'],
        2 => ['scheduled_at'],
        3 => ['client_name', 'client_email', 'client_phone', 'prefers_whatsapp', 'vehicle_details', 'damage_photo'],
    ];

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
            'service_type' => ['required', 'string', Rule::enum(BookingServiceType::class)],
            'damage_type' => ['required', 'string', Rule::enum(BookingDamageType::class)],
            'vehicle_make' => ['required', 'string', 'max:'.self::MAX_TEXT_LENGTH],
            'vehicle_model' => ['required', 'string', 'max:'.self::MAX_TEXT_LENGTH],
            'vehicle_year' => ['required', 'integer', 'min:1970', 'max:'.((int) date('Y') + 1)],
            'location' => ['required', Rule::in(['mobile', 'workshop'])],
            'client_address' => [$this->isMobile() ? 'required' : 'nullable', 'string', 'max:'.self::MAX_TEXT_LENGTH],
            'scheduled_at' => [
                'required',
                'bail',
                'date_format:Y-m-d\TH:i:s',
                $this->slotIsBookable(),
            ],
            'client_name' => ['required', 'string', 'max:'.self::MAX_TEXT_LENGTH],
            'client_email' => ['required', 'email:filter', 'max:'.self::MAX_TEXT_LENGTH],
            'client_phone' => ['required', 'string', 'regex:/^[0-9]{7,20}$/'],
            'prefers_whatsapp' => ['nullable', 'boolean'],
            'vehicle_details' => ['nullable', 'string', 'max:'.self::MAX_TEXT_LENGTH],
            'damage_photo' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:'.self::MAX_PHOTO_KILOBYTES],
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
            'service_type.required' => 'Please tell us which glass needs attention.',
            'damage_type.required' => 'Please tell us what the damage looks like.',
            'vehicle_make.required' => 'Please enter the vehicle make.',
            'vehicle_model.required' => 'Please enter the vehicle model.',
            'vehicle_year.required' => 'Please enter the year the vehicle was made.',
            'vehicle_year.min' => 'That year is too old for us to fit glass to.',
            'vehicle_year.max' => 'That model year has not been released yet.',
            'client_name.required' => 'Please enter your full name.',
            'client_name.max' => 'Your name must not be longer than '.self::MAX_TEXT_LENGTH.' characters.',
            'client_email.required' => 'Please enter your email address.',
            'client_email.email' => 'Please enter a valid email address.',
            'client_email.max' => 'Email must not be longer than '.self::MAX_TEXT_LENGTH.' characters.',
            'client_phone.required' => 'Please enter your phone number.',
            'client_phone.regex' => 'Phone number must contain only digits (7–20 characters).',
            'vehicle_details.max' => 'Notes must not be longer than '.self::MAX_TEXT_LENGTH.' characters.',
            'client_address.required' => 'Please tell us where the vehicle is parked.',
            'client_address.max' => 'Address must not be longer than '.self::MAX_TEXT_LENGTH.' characters.',
            'scheduled_at.required' => 'Please select a date and time for your booking.',
            'scheduled_at.date_format' => 'Please provide a valid date and time format.',
            'location.required' => 'Please select a service location.',
            'location.in' => 'Please select a valid service location.',
            'damage_photo.image' => 'That file is not an image.',
            'damage_photo.mimes' => 'Damage photos must be a JPG, PNG or WebP file.',
            'damage_photo.max' => 'The damage photo must be under 5MB.',
            '_idempotency_token.required' => 'Session token missing. Please refresh the page.',
        ];
    }

    /**
     * Field names as the customer should read them in a generated message.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'service_type' => 'service',
            'damage_type' => 'damage type',
            'damage_photo' => 'damage photo',
            'vehicle_year' => 'year',
        ];
    }

    /**
     * Determine whether the customer asked for a mobile fitting.
     */
    private function isMobile(): bool
    {
        return $this->input('location') === 'mobile';
    }

    /**
     * Reject any slot the availability service would not have offered.
     *
     * Sharing one check between the slots API and the submission is what keeps the
     * picker and the server from disagreeing about the same slot. The service and
     * location travel with the check because both change what is bookable: how long
     * the slot is held for, and which capacity pool it is held from.
     */
    private function slotIsBookable(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail): void {
            $status = app(AvailabilityServiceInterface::class)->slotStatus(
                (string) $value,
                is_string($this->input('service_type')) ? $this->input('service_type') : null,
                is_string($this->input('location')) ? $this->input('location') : null,
            );

            if ($status !== AvailabilityServiceInterface::STATUS_AVAILABLE) {
                $fail($this->unavailableMessage($status));
            }
        };
    }

    /**
     * Map a slot status onto the message a customer should see.
     */
    private function unavailableMessage(string $status): string
    {
        return match ($status) {
            AvailabilityServiceInterface::STATUS_CLOSED => __('booking.slot_closed_day'),
            AvailabilityServiceInterface::STATUS_TOO_LATE => __('booking.slot_too_late'),
            default => __('booking.slot_already_taken'),
        };
    }
}
