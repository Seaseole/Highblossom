# Booking Wizard UI — Implementation Plan

## Context
The booking backend already works (`BookingController`, `StoreBookingAction`, `AvailabilityService`, `StoreBookingRequest`, `BookingConfirmationMail`, `NewBookingStaffNotification`, tests). The `bookings` table already has `scheduled_at` and `location`. This plan upgrades the **public-facing UI** only: a standalone 3-step wizard at `/bookings/create` with a calendar, time-slot grid, and themed confirmation page.

## Decisions (confirmed)
1. **Placement:** Standalone page at `/bookings/create`. Add a nav link (`site-nav.blade.php`) and a homepage CTA (`welcome.blade.php`).
2. **Wizard:** 3 steps — Date/Time → Your Details → Vehicle/Location.
3. **Calendar:** Flatpickr with weekend/past-date disabling, AJAX slot loading.
4. **Time slots:** 1-hour slots generated from `workingHours` settings.
5. **API:** `GET /api/bookings/availability?date=...` returns available/booked slots.
6. **Confirmation:** Re-theme `bookings/confirmation.blade.php` to match site dark glass theme.
7. **Event:** Dispatch `BookingCreatedEvent` from `StoreBookingAction`.

## Open decisions
- **Time-slot duration:** 1 hour (recommended). Simpler, standard for inspections. 30-min slots double API calls and UI density without clear demand.
- **Availability cache TTL:** 60 seconds (recommended). Balances freshness vs DB load.
- **API location:** Add to `routes/web.php` (not `routes/api.php`, which does not exist in this Laravel 13 setup).
- **Timezone:** All slot generation uses the `timezone` setting (`Africa/Gaborone`). The frontend Flatpickr should emit ISO datetime strings; the backend combines the selected date with `workingHours` open/close times in the configured timezone.
- **Booking reference on confirmation:** Use the booking ID formatted as `#HB-000123` for v1. No new DB column needed.

## Task list

### 1. Backend — availability API
- **File:** `app/Http/Controllers/Api/BookingAvailabilityController.php` (new)
  - `availability(Request $request): JsonResponse`
  - Validate `date` (`required|date|after:today`).
  - Call `AvailabilityService::getAvailableSlots($date, workingHours)`.
  - Return JSON array of `{time, available}` objects.
  - Throttle: `throttle:30,1`.

- **File:** `app/Services/AvailabilityService.php`
  - Add `getAvailableSlots(\DateTimeInterface $date, array $workingHours, string $timezone = 'UTC'): array`.
  - Look up day name (`monday`...`sunday`) in `workingHours`.
  - If closed or missing → return `[]`.
  - Loop from `open` to `close` in 1-hour increments.
  - For each slot, call `isSlotAvailable($candidateDateTime)`.
  - Return `['08:00' => true, '09:00' => false, ...]` (associative for easy lookup).

- **File:** `app/Services/Contracts/AvailabilityServiceInterface.php`
  - Add `getAvailableSlots(\DateTimeInterface $date, array $workingHours, string $timezone = 'UTC'): array;`.

- **File:** `routes/web.php`
  - Add after the bookings routes:
    `Route::get('/api/bookings/availability', [BookingAvailabilityController::class, 'index'])->middleware('throttle:30,1')->name('api.bookings.availability');`

### 2. Backend — action event dispatch
- **File:** `app/Actions/StoreBookingAction.php`
  - After successful `BookingConfirmationMail` queue, add:
    `event(new BookingCreatedEvent($booking));`

### 3. Frontend — booking wizard
- **File:** `resources/views/site/booking.blade.php` (rewrite)
  - Wrap in `x-layouts::site` (keep existing hero/heading).
  - `x-data="{ currentStep: 1, scheduledAt: @js(old('scheduled_at')), location: @js(old('location')), clientName: @js(old('client_name')), ... }"`.
  - **Step indicator:** 3-step progress bar (1 Date/Time → 2 Details → 3 Vehicle).
  - **Step 1:**
    - Flatpickr input (disable weekends, past dates). On change → `fetch('/api/bookings/availability?date=' + date)`.
    - Time-slot grid: render buttons from API response. Disabled = `bg-white/5 text-[#71717A] line-through cursor-not-allowed`. Selected = `ring-2 ring-[#DC2626] bg-[#DC2626]/10`.
    - Selected slot stored as `scheduledAt` in ISO format (`Y-m-d\TH:i`).
    - Next button disabled until slot selected.
    - Loading state: show spinner/skeleton while fetching slots.
  - **Step 2:** client_name, client_email, client_phone inputs (reuse `form-input-premium`).
  - **Step 3:** vehicle_details textarea + location card selector (mobile/workshop).
  - Submit: single POST to `route('bookings.store')` with all fields + `_idempotency_token`.
  - Preserve `old()` values on validation back. When `old('scheduled_at')` exists, re-fetch slots for that date on init.

### 4. Frontend — calendar library
- Add `flatpickr` to package.json.
- Import in `resources/js/app.js` or a new booking-specific JS entry.
- Scope styles with wrapper class `.booking-calendar` to avoid Tailwind v4 conflicts.
- Follow existing vanilla JS patterns (no Alpine plugins for Flatpickr unless necessary).

### 5. Frontend — nav and homepage CTA
- **File:** `resources/views/partials/site-nav.blade.php`
  - Add `<a href="{{ route('bookings.create') }}" class="btn-premium text-sm py-2.5 px-5">Book Inspection</a>` alongside "Get Quote" in the desktop nav (`line 122` area).
- **File:** `resources/views/welcome.blade.php`
  - Add a "Book an Inspection" CTA in the hero section (next to "Get Your Free Quote") and/or in the bottom CTA section.

### 6. Frontend — confirmation redesign
- **File:** `resources/views/bookings/confirmation.blade.php` (rewrite)
  - Switch to `x-layouts::site`.
  - Glass-card centered layout, dark theme, animated checkmark.
  - Show: reference (`#HB-{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}`), date/time (formatted with `$booking->scheduled_at->format(...)` using `date_format` and `time_format` settings), location, vehicle, email.
  - Buttons: "Return Home" (`route('home')`), "Book Another" (`route('bookings.create')`).

### 7. Translations
- **File:** `lang/en/booking.php`
  - Add: `wizard_step_1`, `wizard_step_2`, `wizard_step_3`, `select_date`, `select_time`, `no_slots_available`, `slot_selected`, `next`, `back`, `book_another`.

### 8. Tests
- **File:** `tests/Feature/BookingSubmissionTest.php`
  - Update/extend tests to cover wizard-style submission (same payload, just verify endpoint still works).
  - Add test for `GET /api/bookings/availability`:
    - Returns JSON with slots for a valid date.
    - Returns 422 for invalid/missing date.
    - Returns 422 for past dates.

### 9. Frontend — wizard validation error handling
- When `StoreBookingAction` returns failure (e.g., slot taken between selection and submission), the controller redirects back with `withInput()`.
- The wizard should read `session('error')` and show it at the top of Step 1.
- `scheduledAt` will be preserved via `old('scheduled_at')`, so the date and time slot can be re-selected automatically.
- The wizard should scroll to the top on step change to show any error messages.

## Files changed summary
| Action | File |
|--------|------|
| New | `app/Http/Controllers/Api/BookingAvailabilityController.php` |
| Modify | `app/Services/AvailabilityService.php` |
| Modify | `app/Services/Contracts/AvailabilityServiceInterface.php` |
| Modify | `routes/web.php` |
| Modify | `app/Actions/StoreBookingAction.php` |
| Rewrite | `resources/views/site/booking.blade.php` |
| Rewrite | `resources/views/bookings/confirmation.blade.php` |
| Modify | `resources/views/partials/site-nav.blade.php` |
| Modify | `resources/views/welcome.blade.php` |
| Modify | `lang/en/booking.php` |
| Extend | `tests/Feature/BookingSubmissionTest.php` |

## Validation
- `php artisan test`
- `vendor/bin/pint --parallel`
- Manual: `/bookings/create` loads calendar, slot grid populates, submission creates booking, signed confirmation renders with dark theme, nav link works.

## Out of scope
- 30-minute or custom-duration slots.
- Recurring bookings.
- Admin slot management UI.
- SMS/WhatsApp booking confirmations.
- Multi-resource concurrency (single technician assumption holds).
- Creating `routes/api.php` (not needed; route goes in `web.php`).
