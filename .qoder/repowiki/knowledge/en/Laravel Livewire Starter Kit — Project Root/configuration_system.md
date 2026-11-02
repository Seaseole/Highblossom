# Configuration System

## Approach

The application uses Laravel's built-in configuration system. All runtime configuration is defined as PHP arrays under `config/` and values are resolved through the global `env()` helper, which reads from the `.env` file (or environment variables). There is no custom configuration loader — the framework handles loading, caching, and merging.

## Key Files and Packages

- `config/app.php` — core app settings (`APP_NAME`, `APP_ENV`, `APP_DEBUG`, `APP_URL`, `APP_TIMEZONE`, `APP_LOCALE`, `APP_KEY`, `APP_PREVIOUS_KEYS`, maintenance driver/store).
- `config/database.php` — default MySQL/MariaDB/PostgreSQL/SQL Server/SQLite connections plus Redis `default` and `cache` clusters.
- `config/mail.php` — mailer transports (`smtp`, `ses`, `postmark`, `resend`, `sendmail`, `log`, `array`) with failover and round-robin groups.
- `config/services.php` — third-party credentials (Postmark, Resend, SES, Slack) keyed by service name.
- `config/seo.php` — site metadata, OG/Twitter defaults, AI model settings, static route list.
- `config/{fortify,auth,sanctum,livewire,session,cache,queue,logging,filesystems,permission,passkeys,auditify,debugbar,decomposer,icons,jobs-monitor,log-viewer,scheduler-list,trace-replay,user-sessions}` — one file per package.
- `packages/ContentBlocks/config/content-blocks.php` — vendor package configuration (published alongside its source).

## Architecture and Conventions

1. **Single source of truth is a PHP array.** Every file in `config/` returns a plain associative array. No YAML, TOML, or JSON config files are used.

2. **Environment overrides via `env(key, default)`.** Values that vary per deployment go through `env()`, with sensible defaults hard-coded in the array. Examples:
   - `database.php`: `'host' => env('DB_HOST', '127.0.0.1')`, `'port' => env('DB_PORT', '3306')`, `'sslmode' => env('DB_SSLMODE', 'prefer')`.
   - `mail.php`: `'default' => env('MAIL_MAILER', 'log')`, `'url' => env('MAIL_URL')`.
   - `services.php`: `'key' => env('POSTMARK_API_KEY')`, `'region' => env('AWS_DEFAULT_REGION', 'us-east-1')`.
   - `app.php`: `'key' => env('APP_KEY')`, `'previous_keys' => [...explode(',', (string) env('APP_PREVIOUS_KEYS', ''))...]`.

3. **Secrets live only in env.** Database passwords, API keys, SMTP credentials, AWS access keys, Slack tokens, Redis auth, and encryption keys are all read via `env()` from `config/*.php`. No secrets are committed in code.

4. **Per-feature config files, not one monolith.** Each concern gets its own file: `fortify.php`, `sanctum.php`, `livewire.php`, `seo.php`, `permission.php`, `passkeys.php`, etc. This keeps related options grouped while keeping individual files small.

5. **Nested groupings for complex settings.** Arrays use nested keys to organize related options (e.g., `maintenance.driver`/`maintenance.store`, `redis.default` vs `redis.cache`, `ai.model`/`ai.temperature`/`ai.max_tokens`/`ai.rate_limit`, `static_routes` list).

6. **Vendor packages follow the same pattern.** The `ContentBlocks` package ships its own `config/content-blocks.php` alongside its source, so consumers can publish it into the project's `config/` directory if they need to override defaults.

7. **No feature-flag service.** Feature toggles are not implemented as a separate flag system; instead, behavior is gated on environment values (e.g., `APP_ENV`, `APP_DEBUG`) or database-backed settings (see `CompanySetting` model and `CompanySettingService` / `SmtpSettingService` for runtime-persisted settings).

8. **Runtime settings stored in DB, not env.** Application-level settings that change without redeploying (SMTP settings, company profile data) are persisted via models (`CompanySetting`, `User`, `Staff`, etc.) and accessed through dedicated services (`CompanySettingService`, `SmtpSettingService`). These are distinct from deploy-time configuration in `config/`.

## Constraints and Rules

- **All config values are resolved through `config('file.key')`; raw env calls are avoided in application code.** The `config/` files themselves are the place where `env()` is used.
- **Deployment-sensitive values must have a default in the config file.** Every `env()` call in `config/*.php` provides a second argument unless the value is intentionally required at boot (e.g., `APP_KEY` has no default, so missing it will fail fast).
- **Third-party credentials belong in `config/services.php` keyed by service name**, following Laravel convention so packages can discover them via `config('services.<name>.<key>')`.
- **Package configs should be published, not edited in-place.** Vendor-provided config files (like `ContentBlocks/config/content-blocks.php`) are meant to be copied into the project's `config/` directory for customization.
- **Runtime-configurable business settings are modeled as Eloquent entities** (e.g., `CompanySetting`, `SeoStaticRoute`) and manipulated via services, never read directly from `env()`.