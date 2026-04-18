# Step 2: Folder Structure & Layered Architecture

## Directory Tree

```text
app/
├── Domains/                (Core Business Entities & Logic)
│   ├── Bookings/
│   │   ├── Models/         (Rule 2, 3: Strict Eloquent models)
│   │   ├── Enums/          (Strict PHP 8.1+ Enums)
│   │   └── Events/         (Rule 10: Event Discovery)
│   ├── Content/
│   │   ├── Models/
│   │   └── Scopes/         (Rule 2: Global query logic)
│   └── Leads/
│       └── Models/
├── Actions/                 (Rule 1, 8: Single-purpose invokable classes)
│   ├── Bookings/
│   │   ├── CreateBookingAction.php
│   │   └── ScheduleInspectionAction.php
│   ├── Content/
│   │   ├── PublishPageAction.php
│   │   └── RenderPageAction.php
│   └── Leads/
│       └── ProcessLeadCaptureAction.php
├── Infrastructure/          (External integrations, Mail, Notifications)
│   ├── Services/           (Code to Interfaces: Rule 1)
│   ├── Mail/               (Rule 9: ShouldQueue + afterCommit)
│   └── Notifications/      (Rule 9)
├── Http/
│   ├── Controllers/        (Rule 8: Thin controllers, Resource-based)
│   ├── Requests/           (Rule 7: Dedicated Form Requests)
│   └── Resources/          (Rule 8: API Transformation)
└── Providers/
    └── DomainServiceProvider.php (Binding Interfaces to Implementations)
```

## Layer Binding Example (Rule 1 & Rule 8)

### Service Provider Binding

```php
// app/Providers/DomainServiceProvider.php
public function register(): void
{
    // Binding an interface for a booking service (e.g., availability checker)
    $this->app->bind(
        \App\Infrastructure\Contracts\AvailabilityServiceInterface::class,
        \App\Infrastructure\Services\LocalAvailabilityService::class
    );
}
```

### Constructor DI (Rule 1)

```php
// app/Actions/Bookings/CreateBookingAction.php
namespace App\Actions\Bookings;

use App\Infrastructure\Contracts\AvailabilityServiceInterface;

final class CreateBookingAction
{
    // Constructor Injection only - NO app() or resolve()
    public function __construct(
        private readonly AvailabilityServiceInterface $availabilityService
    ) {}

    public function __invoke(array $data): void
    {
        // Business logic execution
    }
}
```

---
**Status:** Folder structure defined following clean architecture. Service Provider binding confirmed. Ready for database schema design.
