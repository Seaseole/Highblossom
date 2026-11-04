## Approach

This is a Laravel 13 application that relies on the framework's built-in exception/error infrastructure rather than defining custom domain exception types. Errors are handled through three main channels:

1. **HTTP-level errors** — `abort()`, `abort_unless()`, and explicit `throw new NotFoundHttpException` / `ModelNotFoundException` propagate to Laravel's global exception handler.
2. **Domain/database exceptions** — Framework-provided `Illuminate\Database\UniqueConstraintViolationException`, `Illuminate\Database\DeadlockException`, and third-party `Illuminate\Image\ImageException` are caught at call sites to translate into user-facing responses.
3. **Logging via `Log` facade** — `Log::error`, `Log::warning`, and `Log::info` are used throughout services, controllers, listeners, and commands; there is no custom logger wrapper or structured log level policy beyond this facade usage.

There are no custom `App\Exceptions\*` classes and no `app/Exceptions` directory in the tree — error taxonomy is delegated entirely to Symfony/Laravel exception classes.

## Key Files and Patterns

- **Controllers** — `SiteController.php` uses `abort(404)` and `abort_unless(..., 404)` for missing resources; it also throws `NotFoundHttpException` / `ModelNotFoundException` where model binding fails. The `PollController` catches `UniqueConstraintViolationException` from DB transactions and returns JSON with 422 status; generic `\Exception` catch blocks return 500 JSON responses.
- **Actions** — `StoreBookingAction.php` wraps its `execute()` method in a broad `catch (\Exception $e)` block that logs via `Log::error` and returns a normalized array `['success' => false, 'message' => ...]`. Its `reserve()` method retries on `DeadlockException` up to `LOCK_ATTEMPTS` with a fixed `usleep(120_000)` backoff before giving up.
- **Services** — Image-processing services (`ImageUploadService`, `GalleryService`, `ProfileService`, `CompanySettingService`, `SeoService`, `AboutUsService`, `MediaLibraryService`, `ServiceService`) all import `Illuminate\Image\ImageException` and catch it to log an error message then continue (often falling back to storing the raw file or skipping processing). `VideoUploadService` throws `\RuntimeException` for unsupported file types and storage failures; `GlassSubCategoryService` throws a bare `\Exception` for business-rule violations ("Cannot delete sub-category with associated quotes").
- **System Overview probes** — `ProbeRunner.php` and individual probe classes use `try/catch (Throwable $e)` to isolate probe failures so one failing probe does not abort the entire system overview report; failures are logged via `Log::warning`.
- **ContentBlocks package** — `AbstractBlock.php` and `OEmbedResolver.php` follow the same pattern: validation/rendering failures are caught, logged, and the block is skipped gracefully.
- **Views** — Blade templates under `resources/views/errors/` provide per-status HTML pages (404, 500, etc.) rendered by Laravel's default renderer; some Flux icon views throw `\Exception` for unsupported variants as developer-facing guard clauses.

## Architecture and Conventions

- **No custom exception hierarchy.** All thrown exceptions are either framework types (`NotFoundHttpException`, `ModelNotFoundException`, `UniqueConstraintViolationException`, `DeadlockException`, `InvalidArgumentException`) or plain `\RuntimeException` / `\Exception`. There is no `App\Exceptions` namespace.
- **Catch-and-log at boundaries.** Services typically `catch` specific exceptions (`ImageException`, `JsonException`, `Throwable`) inside their own methods, log them, and either return a fallback result or rethrow nothing — making them "fail-open" rather than surfacing errors upward. Broader `\Exception` catches live in controller/action entry points to normalize response shapes.
- **Structured logging via `Log` facade.** Every catch block logs context (poll_id, image path, reason, request data) using `Log::error` or `Log::warning`; successful operations often log via `Log::info` (e.g., poll vote attempts/completion, session pruning).
- **Idempotency + retry patterns.** Booking submissions use an idempotency token service plus explicit retry loops for `DeadlockException`, treating database deadlocks as transient rather than fatal.
- **Validation-first approach.** Request validation happens via Form Requests (`ContactFormRequest`, `QuoteFormRequest`) and manual `Validator::make(...)->fails()` checks, which short-circuit early and avoid throwing exceptions for user input errors.

## Conventions and Constraints

- Catching broad `\Exception` or `Throwable` is used in strategic boundary layers (actions, controllers, probe runners) to prevent unhandled exceptions from reaching the global handler — but catching it deep in business logic (e.g., `PasswordPolicy.php`, `HeroAssets.php`) is observed as well, sometimes swallowing the exception after logging.
- Image-processing failures are consistently handled by catching `Illuminate\Image\ImageException`, logging the exception message, and continuing execution rather than aborting the caller — a deliberate fail-open convention across multiple services.
- Database constraint and deadlock errors are treated as distinct control-flow signals: `UniqueConstraintViolationException` maps to 422 user-recoverable responses; `DeadlockException` triggers bounded retries with fixed sleep intervals.
- No `panic`/`recover` equivalent exists (this is PHP); `throw` is used only for exceptional conditions, while normal error signaling goes through returned arrays (`['success' => false, 'message' => ...]`) or HTTP status codes.
- The repository has no lint rule or CI check enforcing exception naming or handling style; the patterns above are purely observed conventions.