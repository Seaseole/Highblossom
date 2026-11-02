## Overview

The application uses Laravel's built-in logging system, which is a thin wrapper around **Monolog**. All log output is configured via `config/logging.php` and consumed through the `Illuminate\Support\Facades\Log` facade. A secondary integration — **Opcodes\LogViewer** (`config/log-viewer.php`) — provides a browser-based admin UI for browsing the same log files.

## Configuration & Sinks

`config/logging.php` defines:

- **Default channel**: `stack` (controlled by `LOG_CHANNEL`).
- **Stack channel**: fans out to channels listed in `LOG_STACK`, defaulting to `single,slack`.
- **`single` channel**: writes to `storage_path('logs/laravel.log')` at `LOG_LEVEL` (default `debug`), with placeholder replacement enabled.
- **`daily` channel**: same file path as `single`, rotated after `LOG_DAILY_DAYS` (default 14).
- **`slack` channel**: sends `critical`-level messages to `SLACK_WEBHOOK_URL`; username/emoji are configurable.
- **`papertrail` channel**: Monolog `SyslogUdpHandler` targeting `PAPERTRAIL_URL:PORT` with `PsrLogMessageProcessor`.
- **`stderr` channel**: `StreamHandler` on `php://stderr`, also using `PsrLogMessageProcessor` and an optional `LOG_STDERR_FORMATTER`.
- **`syslog` / `errorlog` / `null` / `emergency`** fallback channels.
- Deprecation logging is routed to `null` by default (`LOG_DEPRECATIONS_CHANNEL=null`, `LOG_DEPRECATIONS_TRACE=false`).

The stack's `ignore_exceptions => true` ensures that a failing sink does not break request handling.

## Usage Conventions

Across the codebase, logging is done exclusively via `Log::info()` / `Log::warning()` / `Log::error()` from `Illuminate\Support\Facades\Log`. There are no direct `Monolog\Logger` instantiations in application code. The observed patterns are:

- **Error context**: errors include `$e->getMessage()` concatenated into the message string; structured arrays are used sparingly (e.g. `[reason => $e->getMessage()]`, `['host' => ...]`).
- **Warning level**: non-fatal failures such as skipped hero variants, unreadable policy settings, oEmbed resolution failures, and failed image deletions are logged at `warning`.
- **Debug level**: URL-resolution tracing in `VideoSourceDetector`.
- **Info level**: operational milestones like session pruning completion.

No custom logger service or PSR-3 logger interface is injected; callers depend directly on the `Log` facade.

## Structured Fields

Structured field usage is minimal and ad-hoc. Where present, it takes the form of associative arrays passed as the second argument to `Log::warning()` / `Log::error()`, e.g.:

```php
Log::warning('Discarding unreadable system overview cache entry', [
    'cache_key' => $key,
]);
Log::error('Settings mail_mailer missing', ['host' => $settings['mail_host']]);
```

There is no central schema for log fields, no dedicated `Context` class, and no monolog processor adding contextual metadata beyond the bundled `PsrLogMessageProcessor` applied to `papertrail` and `stderr` channels.

## Log Levels Strategy

Levels are chosen descriptively rather than enforced by type-level rules:
- `debug`: diagnostic traces (e.g. Facebook share URL resolution).
- `info`: successful but noteworthy operations (session prune completion).
- `warning`: recoverable problems (file deletion failure, unreadable config, oEmbed failure).
- `error`: operation-ending failures (image/video processing exceptions, SMTP misconfiguration).
- `critical` (via Slack): reserved for severe conditions sent to Slack.

## Routing & Viewing

- Production logs land in `storage/logs/laravel.log` (or split per-day under `storage/logs/`).
- Slack delivers `critical` alerts via webhook.
- Optional Papertrail integration ships over UDP/TLS.
- `Opcodes\LogViewer` is enabled by default (`LOG_VIEWER_ENABLED=true`) and exposed at route path `admin/log-viewer` under the `web` + `AuthorizeLogViewer` middleware. It reads the same `*.log` files and can aggregate additional paths (Apache/Nginx/PHP-FPM/Postgres/Redis/Supervisor) via `include_files`.
- `require_auth_in_production = true` gates access.

## Key Files

- `config/logging.php` — channel definitions, sinks, processors.
- `config/log-viewer.php` — Opcodes Log Viewer configuration.
- `storage/logs/laravel.log` — primary log file.
- Application call sites scattered across `app/Services/*`, `app/Actions/*`, `app/Livewire/*`, `app/Listeners/*`, `packages/ContentBlocks/src/Services/*` (29 files use `Log::`).

## Rules Enforced by the Code

- Default log channel is `stack` unless overridden by `LOG_CHANNEL`.
- Default stack includes both `single` and `slack`; changing `LOG_STACK` rewrites the fan-out set.
- Slack only emits at `critical` unless `LOG_SLACK_LEVEL` is changed.
- Daily rotation defaults to 14 days via `LOG_DAILY_DAYS`.
- Deprecation logs are suppressed by default (`LOG_DEPRECATIONS_CHANNEL=null`).
- Log Viewer requires authentication in production (`require_auth_in_production = true`) and is mounted at `admin/log-viewer`.