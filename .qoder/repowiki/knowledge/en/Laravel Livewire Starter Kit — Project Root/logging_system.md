## What system/approach is used

The application uses Laravel's built-in logging facade backed by **Monolog**. There is no custom logger wrapper or framework — code calls `Log::info()` / `error()` / `warning()` / `debug()` / `notice()` directly (or `$this->info()`/`$this->warn()` on console commands). A third-party UI, **Opcodes\LogViewer**, is installed to browse log files from the browser under `admin/log-viewer`, gated behind auth.

## Key files and packages

- `config/logging.php` — defines channels and the default stack.
- `config/log-viewer.php` — configures the Opcodes Log Viewer package (`route_path => 'admin/log-viewer'`, `require_auth_in_production => true`).
- `storage/logs/laravel.log` — primary file sink for the `single` channel.
- Console commands under `app/Console/Commands/*` use `$this->info()` / `$this->warn()` / `$this->error()` for human-readable CLI output.

## Architecture and conventions

### Channel configuration (`config/logging.php`)

- Default channel: `stack` (a Monolog handler stack).
- The stack composes channels via `LOG_STACK` env var, which defaults to `single,slack` — every log goes to both a file and Slack.
- Available channels:
  - `single`: writes to `storage_path('logs/laravel.log')` at level `debug` (env `LOG_LEVEL`).
  - `daily`: same path, rotated after `LOG_DAILY_DAYS` (default 14).
  - `slack`: sends messages at `critical` level (env `LOG_SLACK_LEVEL`) to `SLACK_WEBHOOK_URL`; username emoji come from `LOG_SLACK_USERNAME` / `LOG_SLACK_EMOJI`.
  - `papertrail`: Monolog `SyslogUdpHandler` configured via `PAPERTRAIL_URL` / `PAPERTRAIL_PORT`.
  - `stderr`: `StreamHandler` writing to `php://stderr` with optional `LOG_STDERR_FORMATTER`.
  - `syslog`, `errorlog`, `null` as fallbacks.
- Deprecations are disabled (`deprecations.channel => null`).
- All file-based channels set `replace_placeholders => true` so Laravel string placeholders are expanded before being sent to Monolog.
- Both `papertrail` and `stderr` attach `Monolog\Processor\PsrLogMessageProcessor` to normalize PSR-3 context into structured fields.

### Log levels

- Application-level minimum level is controlled by `LOG_LEVEL` (defaults to `debug`).
- Slack alerts are elevated to `critical` by default (`LOG_SLACK_LEVEL`).
- Console command output uses standard Artisan helpers (`$this->info()`, `$this->warn()`, `$this->error()`) rather than the `Log` facade; these do not go through the Monolog channels but render to the terminal.

### Structured fields

There is no custom formatter or processor in this repo. Structured fields rely on Monolog's default behavior plus the `PsrLogMessageProcessor` attached to the `papertrail` and `stderr` channels. Context arrays passed to `Log::info('message', $context)` become key/value pairs in the underlying Monolog record.

### Log viewer

- Opcodes Log Viewer is enabled by default (`LOG_VIEWER_ENABLED=true`).
- Route is `admin/log-viewer` and requires authentication in production (`require_auth_in_production => true`).
- It scans `include_files` patterns (`*.log`, `**/*.log`, plus several OS-specific paths) and can be extended to aggregate external logs (Apache/Nginx/Postgres/etc.).

### Conventions observed

- Business logic does not import `Illuminate\Support\Facades\Log` explicitly in the files scanned; most call sites appear to rely on the global `Log` facade alias.
- Console commands consistently use Artisan's `$this->info()` / `$this->warn()` / `$this->error()` helpers for user-facing output instead of `Log::info()`.
- Error reporting in business code uses `Log::error()` (and occasionally `Log::warning()`); success/debug traces use `Log::info()` / `Log::debug()`.
- No custom log levels beyond Monolog's built-in ones are defined.
- No custom middleware, exception-to-log transformation, or audit logger is present in this repository (audit tables exist in migrations but are separate from the runtime logging pipeline).

### Rules enforced by configuration

- Every log message is written to the channels listed in `LOG_STACK` (default `single,slack`) because `default => env('LOG_CHANNEL', 'stack')` selects the stack.
- Slack notifications only fire at `critical` level unless overridden by `LOG_SLACK_LEVEL`.
- File rotation defaults to 14 days when using the `daily` channel.
- The Log Viewer UI is protected by its own `AuthorizeLogViewer` middleware and cannot be accessed without passing that authorization gate.