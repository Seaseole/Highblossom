---
name: laravel-livewire-flux-best-practices
description: Debugging, error prevention, testing patterns, and performance optimization for Laravel 13 + Livewire v4 + Flux UI v2. Use when troubleshooting Livewire components, writing browser tests, optimizing reactivity, or handling Flux component edge cases. Complements @laravel-best-practices, @laravel-specialist, and @php-pro.
license: MIT
metadata:
  author: Eugene (Highblossom)
  version: "1.0.0"
  domain: frontend-backend-integration
  triggers: Livewire, Flux, debugging, testing, performance, reactivity, Livewire v4, Flux UI
  role: specialist
  scope: debugging-optimization
  output-format: code
  related-skills: laravel-best-practices, laravel-specialist, php-pro
---

# Laravel 13 + Livewire v4 + Flux UI v2 Best Practices

Comprehensive guide for debugging, error prevention, testing, and performance optimization when building reactive UIs with Laravel 13, Livewire v4, and Flux UI v2.

## When to Apply

Reference this skill when:
- Debugging Livewire component issues (state not updating, events not firing)
- Writing tests for Livewire components with Flux UI elements
- Optimizing Livewire reactivity and reducing unnecessary network requests
- Handling Flux component edge cases (dark mode, form validation, dynamic content)
- Troubleshooting file uploads, real-time validation, or polling
- Converting between single-file and multi-file Livewire components

## Laravel 13 Key Changes (Affecting Livewire/Flux)

### PHP 8.3+ Required
- Use `readonly` classes for DTOs passed to Livewire components
- Leverage typed class constants for component configuration
- Use `#[Override]` attribute when extending Flux components

### New Attributes (Replace Properties)
```php
<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Routing\Attributes\Controllers\Middleware;

#[Middleware(['auth', 'verified'])]  // Laravel 13 attribute
final class Dashboard extends Component
{
    // Use typed properties (PHP 8.3+)
    public string $search = '';
    public ?int $selectedId = null;
    
    // NOT: protected $queryString - use attributes instead
    #[\Livewire\Attributes\Url]
    public string $filter = 'all';
}
```

### Cache::touch() for Livewire State
```php
// Extend cached computed property TTL without re-querying
#[\Livewire\Attributes\Computed]
public function posts(): Collection
{
    return Cache::remember(
        key: "user:{$this->userId}:posts",
        ttl: 300,
        callback: fn() => Post::forUser($this->userId)->get()
    );
}

// In another method, extend the TTL without re-fetching
public function refreshPosts(): void
{
    Cache::touch("user:{$this->userId}:posts");  // Laravel 13 feature
}
```

## Livewire v4 Patterns

### Single-File Components (Default)
```php
<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Post;

// File: resources/views/components/post/⚡list.blade.php
new class extends Component {
    public string $search = '';
    
    #[Computed]  // Memoized for request, not persisted between requests
    public function posts()
    {
        return Post::search($this->search)->paginate(10);
    }
    
    public function render()
    {
        return view('livewire.post-list');
    }
};
?>

<div>
    <flux:input wire:model.live.debounce.300ms="search" label="Search" />
    
    @foreach($this->posts as $post)
        <flux:card wire:key="{{ $post->id }}">
            <flux:heading>{{ $post->title }}</flux:heading>
        </flux:card>
    @endforeach
</div>
```

### Component Converting
```bash
# Auto-detect and convert
php artisan livewire:convert post.create

# Explicitly to multi-file (better for complex components)
php artisan livewire:convert post.create --mfc

# Back to single-file
php artisan livewire:convert post.create --sfc
```

### Typed Properties & Validation
```php
<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\Booking;

final class BookingForm extends Component
{
    // Typed properties with validation attributes (Livewire v4)
    #[Validate('required|string|max:255')]
    public string $customerName = '';
    
    #[Validate('required|email')]
    public string $email = '';
    
    #[Validate('required|date|after:today')]
    public ?string $scheduledDate = null;
    
    // Protected properties (never sent to frontend)
    protected string $apiSecret;  // Use $this->apiSecret in template
    
    public function save(): void
    {
        // Validation runs automatically with typed data
        $validated = $this->validate();
        
        Booking::create($validated);
        
        $this->dispatch('booking-created');
        $this->reset();
    }
}
```

## Debugging Livewire Components

### 1. Use `dd()` vs `dump()` Correctly
```php
// dump() - Shows in browser devtools console (preferred for Livewire)
public function debugState(): void
{
    dump($this->search, $this->selectedItems);
}

// dd() - Stops execution (use sparingly in actions)
public function save(): void
{
    dd($this->validate());  // Will halt, but breaks Livewire state
}
```

### 2. Browser DevTools
```blade
{{-- Add wire:debug for verbose logging --}}
<div wire:debug>
    <flux:input wire:model="email" />
</div>
```

### 3. Laravel Telescope for Livewire
```php
// config/telescope.php - Monitor Livewire requests
'watchers' => [
    Watchers\RequestWatcher::class => [
        'enabled' => env('TELESCOPE_REQUEST_WATCHER', true),
        'size_limit' => 64,
        'ignore_patterns' => [],  // Don't ignore Livewire routes
    ],
],
```

### 4. Common Issues & Solutions

| Symptom | Cause | Solution |
|---------|-------|----------|
| State not persisting | Property not `public` | Only `public` properties persist between requests |
| `wire:model` not updating | Missing `.live` | Use `wire:model.live` for real-time updates |
| Events not firing | Wrong event name | Check `$this->dispatch()` vs `$this->emit()` (v4 uses dispatch) |
| File upload fails | Missing trait | Add `use WithFileUploads;` |
| Validation errors not showing | `defer` mode | Use `wire:model.live` for real-time validation |
| Component re-renders entire list | Missing `wire:key` | Always use `wire:key` on loops |

### 5. Error Boundaries (Livewire v4)
```php
<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\ErrorBoundary;

#[ErrorBoundary(view: 'errors.livewire-boundary')]
final class DataTable extends Component
{
    public function render()
    {
        // If this throws, the boundary catches it
        return view('livewire.data-table');
    }
}
```

```blade
{{-- errors/livewire-boundary.blade.php --}}
<flux:callout variant="danger">
    <flux:heading>Something went wrong</flux:heading>
    <flux:text>{{ $exception->getMessage() }}</flux:text>
    <flux:button wire:click="$refresh">Retry</flux:button>
</flux:callout>
```

## Flux UI v2 Patterns

### Dark Mode (Auto/Light/Dark)
```php
<?php

namespace App\Livewire;

use Livewire\Component;

final class ThemeToggle extends Component
{
    public string $theme = 'auto';  // auto | light | dark
    
    public function toggleTheme(): void
    {
        $this->theme = match ($this->theme) {
            'auto' => 'light',
            'light' => 'dark',
            'dark' => 'auto',
            default => 'auto',
        };
        
        $this->dispatch('theme-changed', theme: $this->theme);
    }
}
```

```blade
{{-- In layout or component --}}
<div x-data="{ theme: @entangle('theme') }" x-init="
    const update = () => {
        const isDark = theme === 'dark' || (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches);
        document.documentElement.classList.toggle('dark', isDark);
    };
    $watch('theme', update);
    update();
">
    <flux:button wire:click="toggleTheme">
        {{ $theme === 'dark' ? '🌙' : ($theme === 'light' ? '☀️' : '🖥️') }}
    </flux:button>
</div>
```

### Form Validation with Flux
```blade
<form wire:submit="save">
    <flux:input 
        wire:model.live="email" 
        label="Email"
        :invalid="$errors->has('email')"
        :error="$errors->first('email')"
    />
    
    <flux:select wire:model="role" label="Role">
        <option value="">Select role</option>
        <option value="admin">Admin</option>
        <option value="editor">Editor</option>
    </flux:select>
    
    <flux:button type="submit" :loading="$wire.isSaving">
        Save
    </flux:button>
</form>
```

### Dynamic Flux Components
```blade
{{-- Using dynamic component names with Flux --}}
@php
$component = $type === 'danger' ? 'flux:callout' : 'flux:card';
@endphp

<x-dynamic-component :component="$component" variant="{{ $type }}">
    {{ $message }}
</x-dynamic-component>
```

## Testing Patterns

### Livewire Component Testing (Pest)
```php
<?php

use App\Livewire\BookingForm;
use App\Models\User;
use function Pest\Laravel\actingAs;

// Basic component render test
it('renders booking form', function () {
    Livewire::test(BookingForm::class)
        ->assertOk()
        ->assertSee('Create Booking');
});

// Form validation test
it('validates required fields', function () {
    Livewire::test(BookingForm::class)
        ->set('customerName', '')
        ->set('email', 'invalid-email')
        ->call('save')
        ->assertHasErrors(['customerName' => 'required', 'email' => 'email']);
});

// Successful submission test
it('creates booking with valid data', function () {
    $user = User::factory()->create();
    
    Livewire::actingAs($user)
        ->test(BookingForm::class)
        ->set('customerName', 'John Doe')
        ->set('email', 'john@example.com')
        ->set('scheduledDate', now()->addDay()->format('Y-m-d'))
        ->call('save')
        ->assertDispatched('booking-created')
        ->assertDatabaseHas('bookings', [
            'customer_name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
});

// Testing with dependencies
it('loads existing booking data', function () {
    $booking = \App\Models\Booking::factory()->create([
        'customer_name' => 'Jane Doe',
    ]);
    
    Livewire::test(BookingForm::class, ['booking' => $booking])
        ->assertSet('customerName', 'Jane Doe');
});
```

### Browser Testing with Flux
```php
<?php

use function Pest\Laravel\actingAs;

// Requires pestphp/pest-plugin-browser and Playwright
it('can create booking through browser', function () {
    $user = User::factory()->create();
    
    Livewire::actingAs($user)
        ->visit(BookingForm::class)
        ->type('[wire\:model="customerName"]', 'Test Customer')
        ->type('[wire\:model="email"]', 'test@example.com')
        ->select('[wire\:model="scheduledDate"]', now()->addDay()->format('Y-m-d'))
        ->press('Save')
        ->assertSee('Booking created successfully');
});
```

### Flux Component Testing
```php
<?php

use function Pest\Laravel\get;

it('renders flux sidebar with correct navigation', function () {
    $user = User::factory()->create();
    
    actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Dashboard')
        ->assertSee('Bookings')
        ->assertSee('Settings');
});

it('shows active state for current route', function () {
    $user = User::factory()->create();
    
    actingAs($user)
        ->get(route('admin.bookings.index'))
        ->assertOk()
        // Check for active class on navigation item
        ->assertSee('aria-current="page"', false);
});
```

## Performance Optimization

### 1. Debounce Input (Critical for Search)
```blade
{{-- Without debounce: Every keystroke triggers network request --}}
<flux:input wire:model="search" />  

{{-- With debounce: Only after 300ms of no typing --}}
<flux:input wire:model.live.debounce.300ms="search" />

{{-- Long debounce for expensive operations --}}
<flux:input wire:model.live.debounce.1s="search" />
```

### 2. Lazy Loading Heavy Components
```blade
{{-- Defer loading until visible in viewport --}}
<div wire:init="loadHeavyData">
    @if($loaded)
        {{-- Expensive content --}}
    @else
        <flux:skeleton />  {{-- Flux loading state --}}
    @endif
</div>
```

```php
public bool $loaded = false;
public array $heavyData = [];

public function loadHeavyData(): void
{
    $this->heavyData = // expensive query
    $this->loaded = true;
}
```

### 3. Computed Properties (Memoization)
```php
use Livewire\Attributes\Computed;

#[Computed]  // Cached for current request only
public function filteredPosts()
{
    return Post::search($this->search)
        ->with(['author', 'tags'])  // Eager load
        ->paginate(10);
}
```

### 4. Prevent N+1 with Eager Loading
```php
// BAD: N+1 queries in loop
public function render()
{
    return view('livewire.posts', [
        'posts' => Post::all(),  // 1 query
    ]);
}
// Each $post->author triggers another query (N+1)

// GOOD: Eager load relationships
#[Computed]
public function posts()
{
    return Post::with(['author', 'comments.user'])->paginate(10);
}
```

### 5. Optimize File Uploads
```php
use Livewire\WithFileUploads;

final class ImageUploader extends Component
{
    use WithFileUploads;
    
    public $image;
    
    // Real-time validation while uploading
    public function updatedImage(): void
    {
        $this->validate([
            'image' => 'image|max:1024',  // 1MB max
        ]);
    }
    
    public function save(): void
    {
        $path = $this->image->store('uploads', 's3');
        
        // Clear from memory after upload
        $this->reset('image');
    }
}
```

### 6. Disable Polling When Hidden
```blade
{{-- Stop polling when tab is not visible --}}
<div wire:poll.2s.visible="checkStatus">
    Status: {{ $status }}
</div>

{{-- Or use keep-alive for background updates --}}
<div wire:poll.keep-alive.5s>
    Background sync running
</div>
```

## Security Practices

### 1. Sanitize HTML Content (ContentBlocks Pattern)
```php
<?php

namespace App\ContentBlocks;

use App\ContentBlocks\AbstractBlock;

final class HtmlBlock extends AbstractBlock
{
    public function getValidationRules(): array
    {
        return [
            'content' => 'required|string',
        ];
    }
    
    public function render(): string
    {
        // Sanitize on SAVE, not on render
        $clean = $this->sanitizeHtml($this->attributes['content']);
        
        return view('content-blocks::html', ['content' => $clean])->render();
    }
    
    private function sanitizeHtml(string $html): string
    {
        $allowedTags = config('content-blocks.allowed_html_tags', [
            'p', 'br', 'strong', 'em', 'ul', 'ol', 'li', 'a'
        ]);
        
        $allowedAttrs = config('content-blocks.allowed_html_attrs', [
            'href', 'title', 'target'
        ]);
        
        return strip_tags($html, '<' . implode('><', $allowedTags) . '>');
    }
}
```

### 2. CSRF in Livewire
```php
// Livewire handles CSRF automatically via meta tag
// But verify in forms:
<form wire:submit="save">
    @csrf  {{-- Not needed for Livewire, but good for fallback --}}
    <flux:input wire:model="email" />
</form>
```

### 3. Authorization in Components
```php
<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Routing\Attributes\Controllers\Authorize;

#[Authorize('viewAny', Post::class)]  // Laravel 13 attribute
final class PostList extends Component
{
    // Or in methods
    public function delete(int $postId): void
    {
        $post = Post::findOrFail($postId);
        
        $this->authorize('delete', $post);
        
        $post->delete();
    }
}
```

### 4. Lock Sensitive Properties
```php
use Livewire\Attributes\Locked;

final class PaymentForm extends Component
{
    #[Locked]  // Cannot be modified from frontend
    public int $orderId;
    
    #[Locked]
    public float $amount;
    
    public function mount(int $orderId): void
    {
        $this->orderId = $orderId;
        $this->amount = Order::find($orderId)->amount;
    }
}
```

## Common Error Prevention

### 1. Always Use `wire:key` on Lists
```blade
{{-- WRONG: Entire list re-renders on any change --}}
@foreach($items as $item)
    <div>{{ $item->name }}</div>
@endforeach

{{-- CORRECT: Only changed item re-renders --}}
@foreach($items as $item)
    <div wire:key="item-{{ $item->id }}">
        {{ $item->name }}
    </div>
@endforeach

{{-- For nested lists, use unique composite keys --}}
@foreach($categories as $category)
    <div wire:key="cat-{{ $category->id }}">
        @foreach($category->items as $item)
            <div wire:key="cat-{{ $category->id }}-item-{{ $item->id }}">
                {{ $item->name }}
            </div>
        @endforeach
    </div>
@endforeach
```

### 2. Handle Empty States
```blade
<flux:card>
    @if($this->posts->isEmpty())
        <flux:empty>
            <flux:heading>No posts found</flux:heading>
            <flux:text>Try adjusting your search filters.</flux:text>
        </flux:empty>
    @else
        @foreach($this->posts as $post)
            {{-- post content --}}
        @endforeach
    @endif
</flux:card>
```

### 3. Validate Before Dispatch
```php
public function confirmDelete(int $id): void
{
    // Validate ID exists before dispatching modal
    $post = Post::find($id);
    
    if (!$post) {
        $this->dispatch('notify', 
            message: 'Post not found', 
            type: 'error'
        );
        return;
    }
    
    $this->dispatch('open-modal', modal: 'confirm-delete', id: $id);
}
```

### 4. Type Safety with DTOs
```php
<?php

namespace App\Data;

readonly class BookingData
{
    public function __construct(
        public string $customerName,
        public string $email,
        public \DateTimeImmutable $scheduledAt,
        public ?string $notes = null,
    ) {}
    
    public static function fromLivewire(array $data): self
    {
        return new self(
            customerName: $data['customer_name'],
            email: $data['email'],
            scheduledAt: \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data['scheduled_at']),
            notes: $data['notes'] ?? null,
        );
    }
}
```

## Quick Reference

| Task | Pattern |
|------|---------|
| Real-time search | `wire:model.live.debounce.300ms` |
| Form validation | `#[Validate]` attributes + `wire:model.live` |
| File upload | `WithFileUploads` trait + `updated{Property}` |
| Event to parent | `$this->dispatch('event-name', data: $value)` |
| Listen to event | `#[On('event-name')]` on method |
| Computed data | `#[Computed]` property (memoized) |
| Lazy loading | `wire:init` + skeleton state |
| Locked property | `#[Locked]` to prevent tampering |
| Error boundary | `#[ErrorBoundary(view: 'view-name')]` |
| Browser testing | `Livewire::visit()` + Playwright |

## Troubleshooting Checklist

1. **State not updating?**
   - Check property is `public` (not `protected` or `private`)
   - Verify `wire:model` binding (use `.live` for real-time)
   - Check for JavaScript errors in console

2. **Events not firing?**
   - Use `$this->dispatch()` not `$this->emit()` (v4 syntax)
   - Verify event name matches exactly
   - Check component hierarchy (parent/child relationships)

3. **Validation not working?**
   - Use `wire:model.live` for real-time validation
   - Check `updated{Property}()` method naming convention
   - Verify validation rules in `rules()` or `#[Validate]`

4. **Performance issues?**
   - Add `wire:key` to all list items
   - Use `#[Computed]` for expensive operations
   - Debounce input with `wire:model.live.debounce`
   - Eager load Eloquent relationships

5. **Flux components not styling?**
   - Check `dark` class on `<html>` element
   - Verify Flux CSS is loaded in layout
   - Ensure no CSS conflicts with Tailwind classes
