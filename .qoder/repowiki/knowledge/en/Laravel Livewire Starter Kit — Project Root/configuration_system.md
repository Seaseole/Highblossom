# Configuration System

## Approach

The application follows the standard Laravel configuration model: runtime settings live in `config/*.php` files that wrap `env(<KEY>, <default>)` calls, and secret/operational values are supplied via a `.env` file (and per-environment overrides such as `.env.testing`). The application is bootstrapped through `bootstrap/app.php`, which uses `Application::configure(...)->withRouting(...)->withMiddleware(...)->withExceptions(...)->create()` — no custom config loader.

In addition to framework-level configuration, the app provides a **runtime-editable environment variable subsystem** so administrators can change certain non-secret `.env` values from the UI without touching the server. This is implemented by `app/Services/EnvEditor.php` and gated behind `App\Services\CompanySettingService::handleEnvUpdate()` plus a `manage environment` permission gate.

## Key Files

- `bootstrap/app.php` — single entry point configuring routing, middleware aliases, exception rendering; no custom config loading.
- `config/app.php` — application name, env, debug, URL, timezone, locale, cipher/key, maintenance driver/store.
- `config/services.php` — third-party service credentials (Postmark, Resend, SES, Slack).
- `config/{database,mail,filesystems,cache,queue,session,...}.php` — all use `env()` for their transport/connection parameters.
- `app/Services/EnvEditor.php` — read/write `.env` parser with atomic writes and a strict allowlist of editable keys.
- `app/Services/SmtpSettingService.php` — dedicated writer for `MAIL_*` variables (uses raw `set()`, not the admin allowlist) and invokes `Artisan::call('config:clear')` after changes.
- `app/Services/CompanySettingService.php` — orchestrates persisted company settings (`CompanySetting` model) and delegates `.env` edits through `EnvEditor::setEditable()`.
- `app/Models/CompanySetting.php` (plus its migration `2026_04_17_110000_create_company_settings_table.php`) — database-backed key/value store for business settings distinct from `.env`.
- `packages/ContentBlocks/config/content-blocks.php` — example of a package providing its own config file.

## Architecture and Conventions

1. **Two-tier configuration:**
   - *Deployment secrets* (DB credentials, API keys, `APP_KEY`, etc.) → `.env` / `config/*.php` via `env()`.
   - *Business/runtime settings* (company name, logo, working hours, hero copy, social links, announcement flag, registration toggle) → `company_settings` table via the `CompanySetting` model and `SettingsManager`.

2. **Config access pattern:** code reads through Laravel's `config()` helper or `Config` facade; there is no custom registry. Service classes receive dependencies rather than reaching for config directly, except where a setting is truly global (e.g. `SmtpSettingService::getSettings()`).

3. **Runtime `.env` editing is opt-in and allowlisted.** `EnvEditor::SAFE_EDITABLE_KEYS` enumerates the only keys the admin UI may view/edit:
   ```
   APP_NAME, APP_URL, APP_TIMEZONE, APP_LOCALE, APP_FAKER_LOCALE, APP_FALLBACK_LOCALE
   ```
   `isSafeEditableKey()` additionally allows any key prefixed with `FEATURES_`. Keys like `APP_KEY`, `DB_*`, `REDIS_*`, `MAIL_*`, session/queue drivers are deliberately excluded so they cannot be exposed to the browser or overwritten from untrusted request input.

4. **Atomic `.env` persistence.** `EnvEditor::writeAtomically()` writes to a temp file (`.env.tmp.<random>`) then `rename()`s it over the original under `LOCK_EX`, preventing partial writes.

5. **Write paths diverge by trust level:**
   - Untrusted admin UI input → `EnvEditor::setEditable()` (allowlist-checked).
   - Trusted service code (SMTP editor) → `EnvEditor::set()` (key validated by regex `^[A-Za-z_][A-Za-z0-9_]*$`, but value is never returned to the client).

6. **Config cache invalidation.** Both `SmtpSettingService::update()` and `CompanySettingService::handleEnvUpdate()` call `Artisan::call('config:clear')` after mutating `.env`, forcing Laravel to re-read the file on the next request.

7. **Package config convention.** Third-party packages ship their own files under `config/` (e.g. `adminlte`, `auditify`, `fortify`, `livewire`, `passkeys`, `permission`, `seo`, `debugbar`, `log-viewer`, `jobs-monitor`, `scheduler-list`, `trace-replay`, `decomposer`); the project's own code lives in `config/app.php`, `config/services.php`, and `config/session.php`.

8. **Feature flags via `FEATURES_*` prefix.** The allowlist explicitly whitelists `FEATURES_*` keys, giving teams a namespaced way to toggle capabilities at runtime without adding new entries to `SAFE_EDITABLE_KEYS`.

## Conventions and Constraints

- Every deployment secret is referenced exclusively through `env(<KEY>, <default>)` inside a `config/*.php` file — never hard-coded. Across the repo this pattern appears ~357 times in 18 config files.
- Runtime-editable `.env` keys must be declared in `EnvEditor::SAFE_EDITABLE_KEYS`; anything else is silently dropped by `setEditable()` (enforced by the allowlist check).
- Arbitrary key names written via `EnvEditor::set()` are rejected by a regex validator; an `InvalidArgumentException` is thrown on invalid identifiers.
- Values containing whitespace, quotes, `$`, `#`, backticks, or backslashes are quoted when persisted by `EnvEditor::quote()` so dotenv casting remains correct.
- `.env` mutations are performed atomically via a temp file + rename under exclusive lock — no partial `.env` state is possible.
- Changes to `.env` always trigger `config:clear` so newly written values take effect immediately.
- Secrets (`APP_KEY`, `DB_*`, `REDIS_*`, `MAIL_*`, session/queue drivers) are excluded from the admin allowlist and never rendered by `displayable()` — enforced by `isSafeEditableKey()`.
- Company-wide business settings are persisted to the `company_settings` table, not to `.env`; the two stores are kept separate (documented in `CompanySettingService`'s docblock).
- Package configuration files follow Laravel's convention of one PHP file per package under `config/`, returning an associative array.