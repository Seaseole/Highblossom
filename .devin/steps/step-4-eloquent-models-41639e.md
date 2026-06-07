# Step 4: Eloquent Models & Performance-Optimized Queries

## Model Definitions (Rule 2, 6)

### `Page.php`
```php
namespace App\Domains\Content\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;

final class Page extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'slug', 'seo_metadata', 'is_published', 'published_at']; // Rule 6

    protected $casts = [
        'seo_metadata' => 'json',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function contentBlocks(): MorphMany
    {
        return $this->morphMany(ContentBlock::class, 'blockable')->orderBy('sort_order');
    }
}
```

### `Booking.php`
```php
namespace App\Domains\Bookings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Booking extends Model
{
    protected $fillable = [
        'user_id', 'client_name', 'client_email', 'client_phone', 
        'vehicle_details', 'status', 'total_price'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function inspection(): HasOne
    {
        return $this->hasOne(Inspection::class);
    }
}
```

## Performance-Optimized Query Examples (Rule 2)

### Refactoring `whereHas()` to `whereIn(subquery)`
Instead of a slow `whereHas()`, we use a more index-friendly `whereIn()` with a subquery:
```php
// BEFORE (Slow)
$activeBookings = Booking::whereHas('inspection', function($query) {
    $query->where('scheduled_at', '>', now());
})->get();

// AFTER (Optimized - Rule 2)
$activeBookings = Booking::whereIn('id', function($query) {
    $query->select('booking_id')
          ->from('inspections')
          ->where('scheduled_at', '>', now());
})->get();
```

### Using `addSelect()` for Correlated Subqueries
Instead of loading the entire `inspection` model just for one date, we use `addSelect()`:
```php
// Optimized fetching of bookings with their last inspection date (Rule 2)
$bookings = Booking::addSelect(['last_inspection_at' => 
    Inspection::select('scheduled_at')
        ->whereColumn('booking_id', 'bookings.id')
        ->latest()
        ->limit(1)
])->with(['user:id,name'])->get(); // Column constraints (Rule 2)
```

## Global Safeguards (Rule 2)
We prevent lazy loading in development via `AppServiceProvider`:
```php
// app/Providers/AppServiceProvider.php
public function boot(): void
{
    Model::preventLazyLoading(! app()->isProduction());
}
```

---
**Status:** Eloquent models implemented with strict $fillable and casts. Performance query patterns defined. Ready for Action layer implementation.
