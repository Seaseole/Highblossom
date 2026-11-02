## Approach

This is a Laravel 13 + Livewire application. Error handling follows the framework's defaults with one explicit customization in `bootstrap/app.php`:
- No custom exception classes are defined anywhere in `app/`; business validation failures use PHP built-ins (`InvalidArgumentException`, `RuntimeException`) or domain exceptions from third-party packages (e.g. `ImageException`, `DeadlockException`, `UniqueConstraintViolationException`).
- Global error rendering is only customized for `ValidationException` on passkey routes, returning structured JSON `{message, reason, errors}` via an `Exceptions::render` callback that delegates to `ResolvePasskeyLoginFailure`.
- HTTP-level errors are raised with `abort()` (only found once: `SiteController` returns 404).
- There is no `catch`/`throw` sentinel-error hierarchy of the project's own; all `throw new ...Exception` calls in the codebase use either `InvalidArgumentException`, `RuntimeException`, or package-provided types.

## Key Files

- `bootstrap/app.php` — single place where global exception rendering is hooked (`withExceptions`). It also wires middleware aliases (`role`, `permission`, `verified.if_required`, `consent.required`, `registration.enabled`).
- `app/Actions/Fortify/AuthenticateSignIn.php` — catches `Throwable` when resolving passkeys and delegates failure reporting to `ResolvePasskeyLoginFailure`.
- `lang/lang/en/validation.php` — provides user-facing messages for form validation errors.
- Blade view components under `resources/views/components/ui/field-errors.blade.php`, `resources/views/flux/error.blade.php`, and the many `flux/*` field views render per-field validation errors returned by Livewire/Laravel validators.
- `resources/views/errors/506.blade.php`, `resources/views/errors/403.blade.php` — custom error pages for those status codes (Laravel convention: one file per HTTP status). Other statuses fall back to default Laravel error templates.

## Architecture & Conventions

**1. Validation-driven flow control**
Most input paths go through Laravel Form Requests or Livewire property validation rather than manual checks. When validation fails, `ValidationException` is thrown automatically and rendered as HTML field errors by the Flux/UI field components or as JSON for API/Livewire requests.

**2. Service-layer guard clauses throw typed built-in exceptions**
Service methods validate preconditions and throw `InvalidArgumentException` (invalid arguments) or `RuntimeException` (operational failures like "Unable to write {$file}"), e.g.:
- `DataTransferObjects\SeoMetadata` throws `InvalidArgumentException` for out-of-range priority / invalid changefreq.
- `BookingTimeline` throws `InvalidArgumentException` for unknown milestone/event mappings.
- `EnvEditor` throws `InvalidArgumentException` for invalid env keys.
- `CacheManagerService` throws `InvalidArgumentException` for unknown/unoptimizable cache groups.
- `FaviconSetGenerator` throws `RuntimeException` for filesystem-write failures.
- `SystemOverview\ProbeRegistry` throws `InvalidArgumentException` for unknown probes.

**3. Catch sites log and degrade gracefully**
Catch blocks follow a consistent pattern: catch a specific exception type, call `Log::error(...)` (or `Log::warning`/`Log::debug`/`Log::info` depending on severity), then either rethrow, return a fallback value, or continue processing. Examples include image upload failures across `ImageUploadService`, `AboutUsService`, `CompanySettingService`, `GalleryService`, `MediaLibraryService`, `ProfileService`, `SeoService`, `ServiceService`; email sending in `SendContactMessageEmail`; video thumbnail generation in `VideoUploadService`; system-overview probe execution in `ProbeRunner`.

**4. Logging levels used descriptively**
- `Log::error` — failed operations (image/video processing, mail send, quote submission, favicon generation).
- `Log::warning` — recoverable issues (unavailable settings, unreadable cache entries, failed deletions, skipped hero variants, orphaned temp files).
- `Log::info` — completed background work (session pruning, temp-file cleanup).
- `Log::debug` — diagnostic traces (Facebook URL resolution).

**5. Domain-specific catch targets**
The codebase catches concrete exception types rather than generic `Exception` wherever possible:
- `ImageException` (intervention/image library) in image-handling actions/services.
- `DeadlockException` in `StoreBookingAction` for concurrent booking inserts.
- `UniqueConstraintViolationException` in `PollController` and `UserSessionService` for duplicate votes/session collisions.
- `JsonException` in `ProbeRunner`, `PackageCatalog`, `PhpRuntimeProbe` for malformed cached JSON.
- `Throwable` at boundaries (Fortify authentication, console commands, system-overview probes, hero asset generation) to prevent unhandled crashes.

**6. Passkey validation response shaping**
A dedicated `ResolvePasskeyLoginFailure` action translates `ValidationException`s from `/passkeys` and `/user/passkeys` routes into a flat JSON envelope `{ message, reason?, errors }`, overriding Laravel's default behavior so the passkey client can display the specific failure reason.

**7. No repository-wide sentinel error types**
There is no `App\Exceptions` namespace and no custom base exception class. All domain errors propagate as PHP built-ins or third-party exceptions, which callers catch explicitly.

## Conventions Observed

- Failures in I/O-heavy service methods are caught locally, logged at the appropriate level, and never allowed to bubble up as unhandled exceptions.
- Validation failures are expressed through Laravel's validator/Form Request pipeline; controllers/actions do not manually construct error arrays.
- User-facing error messages live in `lang/lang/en/*.php` (e.g. `validation.php`, `auth.php`, `passwords.php`, feature-specific language files); Blade templates resolve them via `__()` or `$errors` bags.
- Custom HTTP error pages exist for selected codes under `resources/views/errors/<code>.blade.php` (403, 506 observed); others use Laravel defaults.
- The only global exception hook is the passkey `ValidationException` renderer in `bootstrap/app.php`; there is no global `report` override or global try/catch wrapper around the request lifecycle.