# Step 3: Database Schema & Migrations

## Core Tables and Schema Strategy (Rule 3)

### 1. `pages`
```php
Schema::create('pages', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('slug')->unique(); // Index on WHERE slug
    $table->json('seo_metadata')->nullable();
    $table->boolean('is_published')->default(false)->index();
    $table->timestamp('published_at')->nullable();
    $table->timestamps();
    $table->softDeletes();
    
    $table->index(['is_published', 'published_at']); // Compound index for filtering active pages
});
```

### 2. `content_blocks` (Rule 3)
```php
Schema::create('content_blocks', function (Blueprint $table) {
    $table->id();
    $table->morphs('blockable'); // blockable_id, blockable_type (index included)
    $table->string('type'); // Hero, Teaser, TrustBar, Services, Gallery, etc.
    $table->json('content');
    $table->integer('sort_order')->default(0);
    $table->timestamps();
    
    $table->index(['blockable_id', 'blockable_type', 'sort_order']); // Optimization for loading blocks
});
```

### 3. `bookings` (Rule 3)
```php
Schema::create('bookings', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
    $table->string('client_name');
    $table->string('client_email')->index();
    $table->string('client_phone');
    $table->string('vehicle_details');
    $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending')->index();
    $table->decimal('total_price', 10, 2)->default(0.00);
    $table->timestamps();
    
    $table->index(['status', 'created_at']); // Optimization for dashboard lists
});
```

### 4. `inspections` (Rule 3)
```php
Schema::create('inspections', function (Blueprint $table) {
    $table->id();
    $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
    $table->foreignId('staff_id')->constrained('users');
    $table->dateTime('scheduled_at')->index();
    $table->dateTime('ended_at')->index();
    $table->string('location');
    $table->enum('type', ['mobile', 'workshop'])->index();
    $table->timestamps();
    
    $table->index(['scheduled_at', 'ended_at', 'staff_id']); // Critical for availability checks
});
```

### 5. `staff_absences` (Rule 3)
```php
Schema::create('staff_absences', function (Blueprint $table) {
    $table->id();
    $table->foreignId('staff_id')->constrained('users')->cascadeOnDelete();
    $table->dateTime('starts_at')->index();
    $table->dateTime('ends_at')->index();
    $table->string('reason')->nullable();
    $table->timestamps();
    
    $table->index(['starts_at', 'ends_at', 'staff_id']); // Critical for availability checks
});
```

## Reversible Migrations (Rule 3)
Every migration will include a robust `down()` method:
```php
public function down(): void
{
    Schema::dropIfExists('staff_absences');
}
```

## Data Access Optimization (Rule 2)
By using `foreignId()->constrained()`, we enforce referential integrity at the database level. Indexes are placed strategically on every column used in `WHERE`, `ORDER BY`, or `JOIN` operations to guarantee performance at scale.

---
**Status:** Database schema designed. Key indexes and constraints applied. Ready for Eloquent model implementation.
