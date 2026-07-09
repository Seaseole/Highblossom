# Booking Confirmation System — Implementation Plan

## Context
The public booking flow is currently non-functional. `routes/web.php` has no booking
routes, `SiteController` has no booking methods, and the `bookings` table lacks the
`scheduled_at` / `location` columns that `StoreBookingRequest` validates and that the
confirmation view/email read. As a result: no public form, no creation path, no
availability enforcement, and confirmation/notification mailers are never dispatched.

This plan delivers a stable, production-ready public booking → availability check →
confirmation → notification flow, mirroring the established `StoreQuoteAction` pattern.

## Decisions (confirmed)
1. **Schema:** Add `scheduled_at` + `location` to the `bookings` table and
   `Booking::$fillable`. The Booking record holds the requested appointment.
2. **Confirmation access:** Signed URL (`signed` middleware) to prevent ID enumeration.
3. **Inspection:** NOT auto-created. Booking stays `pending`; staff create the
   `Inspection` later from admin (relation + model already exist).

## Files to change / create

### 1. Migration — add columns
`database/migrations/2026_07_07_000000_add_scheduled_fields_to_bookings_table.php`
- `Schema::table('bookings', fn (Blueprint $t) => {`
  - `$t->timestamp('scheduled_at')->nullable();`
  - `$t->string('location')->nullable();` // 'mobile' | 'workshop'
  - `});`
- `down()` drops both columns.
- Note: nullable in DB (safe for any existing rows); required at the form layer.

### 2. Model — `app/Models/Booking.php`
- Add `'scheduled_at', 'location'` to `$fillable`.
- Add `'scheduled_at' => 'datetime'` to `$casts`.

### 3. Request — `app/Http/Requests/Bookings/StoreBookingRequest.php`
- Fix `isWeekend()` to evaluate the **selected** date, not today:
  `return \Carbon\Carbon::parse($this->input('scheduled_at'))->isWeekend();`
- Keep `scheduled_at` (`required|date|after:now|prohibitedIf weekend`) and
  `location` (`required|in:mobile,workshop`).

### 4. Service — `app/Services/AvailabilityService.php`
- Extend `isSlotAvailable()` to also reject slots already taken by a non-cancelled
  `Booking` at the same `scheduled_at` (currently only checks `Inspection`):
  `use App\Models\Booking;`
  `return ! Booking::where('scheduled_at', $date)->where('status','!=','cancelled')->exists()
        && ! Inspection::where('scheduled_at', $date)->exists();`
- (Weekend + staff-absence checks remain.)

### 5. Action — `app/Actions/StoreBookingAction.php` (new)
Mirror `StoreQuoteAction` (idempotency + `DB::transaction` + post-commit notify):
- Constructor DI: `Booking`, `IdempotencyService`, `AvailabilityServiceInterface`.
- `execute(Request $r): array`
  - Idempotency check via `_idempotency_token` (reuse `IdempotencyService`).
  - `if (! $this->availabilityService->isSlotAvailable($r->input('scheduled_at')))` →
    return `['success'=>false,'message'=>'That time slot is no longer available.']`.
  - `DB::transaction(fn () => $booking = Booking::create([...validated...]))`
    (only fillable fields: client_name, client_email, client_phone, vehicle_details,
    scheduled_at, location, status='pending', total_price=0).
  - After commit: send customer `BookingConfirmationMail` and notify staff
    (`User::permission('update bookings')->get()` → `Notification::send(..., new NewBookingStaffNotification($booking))`; skip if none).
  - `try/catch` → `Log::error` + failure array (same shape as `StoreQuoteAction`).

### 6. Notification fix — `app/Notifications/NewBookingStaffNotification.php`
- Change `via()` to `return ['mail'];` (drop `'database'`) — there is no `toArray()`
  and no admin inbox reading it, so the `database` channel would throw at runtime.
  (Mail `toMail()` with "View Booking" link to `admin.bookings.show` stays.)

### 7. Controller — `app/Http/Controllers/BookingController.php` (new, public)
Thin; delegates to the action.
- `create(): View` → `view('site.booking')`.
- `store(StoreBookingRequest $r): RedirectResponse`
  - `$result = $this->storeBookingAction->execute($r);`
  - success → `redirect()->to(URL::signedRoute('bookings.confirmation', $booking))`
    (pull `$booking` from `$result['booking']`).
  - failure → `redirect()->back()->with('error', $result['message'])->withInput()`.
- `confirmation(Booking $booking): View` → `view('bookings.confirmation', compact('booking'))`.

### 8. Routes — `routes/web.php`
Add (after the quote/contact block, before `require settings.php`):
```php
Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
Route::post('/bookings', [BookingController::class, 'store'])->middleware('throttle:3,1')->name('bookings.store');
Route::get('/bookings/{booking}/confirmation', [BookingController::class, 'confirmation'])
    ->middleware('signed')->name('bookings.confirmation');
```

### 9. Public form — `resources/views/site/booking.blade.php` (new)
Mirror `resources/views/site/quote.blade.php` structure (`x-layouts::site`):
- Fields: client_name, client_email, client_phone, vehicle_details (textarea),
  scheduled_at (`type="datetime-local"`), location (select mobile/workshop).
- `@csrf`, hidden `_idempotency_token` (same session pattern as quote),
  submit handler with `isSubmitting` state, `@error` blocks.
- Post to `route('bookings.store')`.

### 10. Confirmation view — `resources/views/bookings/confirmation.blade.php`
- Switch layout from `x-layouts::app` (admin) to `x-layouts::site`.
- Keep reading `$booking->scheduled_at` (now valid), status, client_email.
  It already uses `lang/en/confirmation.php`.

### 11. Locale — `lang/en/booking.php` (new)
Form labels (client_name, client_email, client_phone, vehicle_details,
scheduled_at, location, location_mobile, location_workshop, submit, success_message).

### 12. Tests — `tests/Feature/BookingSubmissionTest.php` (new)
Mirror `StoreQuoteAction`/quote tests:
- Successful submission → 302 to signed confirmation URL, `Booking` created with
  correct `scheduled_at`/`location`, `BookingConfirmationMail` queued.
- Weekend `scheduled_at` → validation error.
- Slot already booked → `isSlotAvailable` false path returns failure message.
- Missing required fields → 302 back with errors.
- Unsigned GET to `bookings.confirmation` → 403.

## Risks / pitfalls
- `NewBookingStaffNotification` `database` channel would fatal without the fix in step 6.
- Datetime parsing: validate `scheduled_at` with `Carbon` in request + service; ensure
  client sends a parseable value (`datetime-local` gives `Y-m-d\TH:i`).
- Existing `bookings` rows: columns are nullable so migration is safe; new bookings
  always set them via the form.
- `Booking` route-model binding on a signed route is fine (no auth scope).

## Validation (run before finishing)
- `vendor/bin/pint --parallel`
- `php artisan test` (new `BookingSubmissionTest` + existing `AvailabilityServiceTest`)
- `php artisan migrate --force` on a copy / staging
- Manual: submit `/bookings/create`, receive signed confirmation, verify customer email
  + staff notification sent; verify weekend/duplicate slots are rejected.
