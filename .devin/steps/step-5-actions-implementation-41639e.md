# Step 5: Actions Layer Implementation

## Single-Purpose Invokable Classes (Rule 1)

### `CreateBookingAction.php`
```php
namespace App\Actions\Bookings;

use App\Domains\Bookings\Models\Booking;
use App\Domains\Bookings\Events\BookingCreatedEvent;
use App\Infrastructure\Contracts\AvailabilityServiceInterface;
use Illuminate\Support\Facades\DB;
use Throwable;

final class CreateBookingAction
{
    // Rule 1: Constructor DI only
    public function __construct(
        private readonly AvailabilityServiceInterface $availabilityService
    ) {}

    /**
     * @throws Throwable
     */
    public function __invoke(array $data): Booking
    {
        // Rule 1: Validate business logic (e.g., availability)
        if (! $this->availabilityService->isSlotAvailable($data['scheduled_at'])) {
            throw new \Exception('Slot is no longer available.');
        }

        return DB::transaction(function () use ($data) {
            $booking = Booking::create($data); // Rule 3 (Attributes mirrored in model)

            // Rule 10: Event Discovery (ShouldDispatchAfterCommit)
            BookingCreatedEvent::dispatch($booking);

            return $booking;
        });
    }
}
```

### `ScheduleInspectionAction.php` (Rule 1, 2)
```php
namespace App\Actions\Bookings;

use App\Domains\Bookings\Models\Booking;
use App\Domains\Bookings\Models\Inspection;
use Illuminate\Support\Facades\DB;

final class ScheduleInspectionAction
{
    public function __invoke(Booking $booking, array $inspectionData): Inspection
    {
        return DB::transaction(function () use ($booking, $inspectionData) {
            // Rule 2: Correlated subquery/constrained checks within Action if needed
            $inspection = $booking->inspection()->create($inspectionData);
            
            // Rule 9: Trigger notification via event/defer()
            defer(fn() => $booking->notifyStaffOfNewInspection($inspection));
            
            return $inspection;
        });
    }
}
```

### `PublishPageAction.php` (Rule 1, 4)
```php
namespace App\Actions\Content;

use App\Domains\Content\Models\Page;
use Illuminate\Support\Facades\Cache;

final class PublishPageAction
{
    public function __invoke(Page $page): Page
    {
        $page->update([
            'is_published' => true,
            'published_at' => now(),
        ]);

        // Rule 4: Forget page cache on publish
        Cache::forget("page_{$page->slug}");
        
        return $page->refresh();
    }
}
```

## Maintenance & Scalability (Rule 1)
- **Granularity:** Each business operation has its own class.
- **Dependency Clarity:** `CreateBookingAction` clearly shows it depends on `AvailabilityServiceInterface`.
- **Atomic Operations:** Uses `DB::transaction()` where multiple models or operations are involved.
- **Post-Response Work:** Uses `defer()` for non-critical side effects to keep response times low (Rule 1).

---
**Status:** Core business Actions implemented. Transactional safety and event triggering confirmed. Ready for security and form request implementation.
