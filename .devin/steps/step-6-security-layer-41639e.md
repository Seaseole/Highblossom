# Step 6: Form Requests, Policies & Security Layer

## Secure Resource Access (Rule 6, 7)

### `StoreBookingRequest.php` (Rule 7)
```php
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
                Rule::prohibitedIf(fn() => $this->isWeekend()) // Custom validation example
            ],
            'location' => ['required', Rule::in(['mobile', 'workshop'])],
        ];
    }

    private function isWeekend(): bool
    {
        return now()->isWeekend();
    }
}
```

### `BookingPolicy.php` (Rule 6)
```php
namespace App\Domains\Bookings\Policies;

use App\Models\User;
use App\Domains\Bookings\Models\Booking;
use Illuminate\Auth\Access\HandlesAuthorization;

final class BookingPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole('staff'); // Rule 6: Authorize based on roles
    }

    public function update(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id || $user->hasRole('staff');
    }

    public function delete(User $user, Booking $booking): bool
    {
        return $user->hasRole('admin');
    }
}
```

## Security Layer Hardening (Rule 6)

### Route Rate Limiting
Configured in `routes/web.php` or `RouteServiceProvider`:
```php
Route::middleware('throttle:6,1')->group(function () {
    Route::post('/bookings', [BookingController::class, 'store']); // Limit to 6 submissions per minute
});
```

### Sanitized Output (Rule 6)
All data in Blade templates will be escaped with `{{ $data }}`. `Rule 6` ensures that only trusted content uses `{!! $content !!}`.

---
**Status:** Security layer established. Dedicated `FormRequests` and `Policies` implemented. Ready for thin controllers and routing.
