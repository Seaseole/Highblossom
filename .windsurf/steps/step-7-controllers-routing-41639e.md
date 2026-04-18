# Step 7: Controllers & Routing

## Thin Resource Controllers (Rule 8)

### `BookingController.php` (Rule 8)
```php
namespace App\Http\Controllers\Bookings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bookings\StoreBookingRequest;
use App\Actions\Bookings\CreateBookingAction;
use Illuminate\Http\RedirectResponse;

final class BookingController extends Controller
{
    // Rule 8: constructor DI only
    public function __construct(
        private readonly CreateBookingAction $createBookingAction
    ) {}

    /**
     * Rule 8: Keep methods < 10 lines by delegating to Actions.
     */
    public function store(StoreBookingRequest $request): RedirectResponse
    {
        // Rule 7: Only use ->validated()
        $booking = ($this->createBookingAction)($request->validated());

        return redirect()
            ->route('bookings.confirmation', $booking)
            ->with('success', 'Your inspection booking has been received.');
    }
}
```

### `PageController.php` (Rule 8, 4)
```php
namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Domains\Content\Models\Page;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

final class PageController extends Controller
{
    /**
     * Rule 8: Implicit route model binding & thin methods.
     */
    public function show(Page $page): View
    {
        // Rule 4: Flexible caching for page rendering
        $data = Cache::flexible("page_{$page->slug}", [600, 1200], function () use ($page) {
            return $page->load(['contentBlocks' => fn($q) => $q->orderBy('sort_order')]);
        });

        return view('pages.show', ['page' => $data]);
    }
}
```

## Scoped Routing (Rule 8)

### `routes/web.php`
```php
use App\Http\Controllers\Bookings\BookingController;
use App\Http\Controllers\Content\PageController;
use Illuminate\Support\Facades\Route;

// Public Dynamic Routing (Rule 8: Implicit Binding)
Route::get('/pages/{page:slug}', [PageController::class, 'show'])->name('pages.show');

// Booking Flow (Rule 6: Rate Limiting)
Route::middleware('throttle:6,1')->group(function () {
    Route::resource('bookings', BookingController::class)->only(['create', 'store']);
});

// Admin Portal (Rule 6: Protected)
Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    Route::resource('pages', \App\Http\Controllers\Admin\PageController::class);
    Route::resource('bookings', \App\Http\Controllers\Admin\BookingController::class)->except(['create', 'store']);
});
```

## Controller Efficiency (Rule 8)
By delegating all logic to Actions and using Form Requests for validation, controllers remain purely as HTTP entry points. Methods are concise, easy to read, and adhere to the single-responsibility principle.

---
**Status:** Thin controllers and scoped routing defined. Ready for notifications, mailables, and queues.
