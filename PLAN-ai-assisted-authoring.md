# Feature Enhancement Plan — AI-Assisted Authoring & Taxonomy Quick-Add

**Status:** Plan only — no application code has been changed.  
**Prepared:** 2026-11-10  
**Target codebase:** `C:/laragon/www/Highblossom` (Laravel 13 / PHP 8.2 / Livewire 4 / Alpine 3 / Tailwind 4 / Vite)  
**Current application version:** `1.38.1` (seeded 2026-10-26)

---

## 0. Executive summary

Five requested elements map to **four build components plus one release step**:

| # | Request                                       | Reality in this repo                                                  | Work type                                      |
| - | --------------------------------------------- | --------------------------------------------------------------------- | ---------------------------------------------- |
| 1 | AI suggestion context for post titles         | No AI code exists anywhere                                            | Greenfield (needs a shared AI layer first)     |
| 2 | New category modal + new tag modal            | **Already implemented** as one shared Alpine quick-add dialog         | Refactor, split, harden, test                  |
| 3 | AI username suggestions, guaranteed available | `App\Support\Username` already owns all rules + `generateFromEmail()` | New service + endpoint, reusing existing rules |
| 4 | Production readiness (testing, docs)          | `composer test` exists; CI only runs Pint in **fix** mode             | Strengthen CI, add docs/runbook                |
| 5 | Update `ApplicationVersionSeeder`             | Append-only seeder; 3 hard-coded version assertions in tests          | Release-time step, gated on #1–#4              |

**Recommended version:** `1.39.0` (semver **minor** — new, backwards-compatible features). Recompute at release time via `ApplicationVersionService::next('minor')`; if another release lands first, use that number instead.

**Estimated effort:** ~27 developer-days. ~5 calendar weeks with two developers (one PHP/back-end, one front-end-capable), ~6–7 weeks with one developer. External API key / DPA procurement is on the critical path and must start in Week 1.

> **Important:** step 5 is deliberately *after* implementation. Bumping the seeder now would leave the registry claiming features that do not exist and would immediately break `ApplicationVersionTest.php`.

---

## 1. Verified current state (files that matter)

### Post editor

- Routes: `routes/admin.php:108-116` (`admin.posts.*`, gated `can:create/update blog`).
- Controller: `app/Http/Controllers/Admin/PostController.php` (thin; `create()`/`edit()` load `Category::all()` + `Tag::all()`).
- Form Request: `app/Http/Requests/Content/StoreBlockContentRequest.php` — `'title' => ['required','string','max:'.Post::TITLE_MAX]`; slug de-duplicated by `uniqueSlugFor()`. **Title has no min length and no unique rule.**
- `app/Models/Post.php:33` → `TITLE_MAX = 120`; `getRouteKeyName() = 'slug'`.
- Views: `resources/views/admin/blog/posts/{create,edit}.blade.php` — **plain Blade forms + Alpine**, not Livewire. Title field at `partials/basic-info.blade.php:17-42` (`x-data="charCount(120)"`, `maxlength=120`).
- The only Livewire component on the page is `<livewire:block-builder>` (`app/Livewire/BlockBuilder.php`), which owns the `content` JSON block array.

### Taxonomy quick-add (already shipped)

- `resources/views/admin/blog/posts/partials/taxonomy.blade.php:127-189` — one Alpine `x-show` dialog with `role="dialog"`, `aria-modal`, Escape handling and a hand-written focus trap (`@alpinejs/focus` is **not** installed).
- `resources/js/admin.js:703-827` — `Alpine.data('taxonomy', ...)`: `fetch` POST with CSRF meta, error list, `addChip()` clones an inert `<template>` and ticks the new checkbox so unsaved post state survives.
- Server: `app/Http/Controllers/Admin/CategoryController.php:46-61` and `TagController::store()` return JSON `{id,name,slug}` (201) when `$request->wantsJson()`, else redirect. Validation in `app/Http/Requests/Admin/{CategoryRequest,TagRequest}.php`.
- Existing coverage: `tests/Feature/Admin/BlogTaxonomyTest.php` (9 tests, including duplicate names, colliding slugs, JSON vs redirect, and saving a quick-added term with the post). Similar pattern: `tests/Feature/Admin/GlassSubCategoryModalTest.php`.

### Username

- `app/Support/Username.php` — single source of truth: `MIN_LENGTH=3`, `MAX_LENGTH=30`, `PATTERN=/^[a-z0-9][a-z0-9._-]{2,29}$/`, 60+ `RESERVED` names, `normalize()`, `isValid()`, `rules()`, `generateFromEmail()`.
- `database/migrations/2026_10_20_000100_add_username_to_users_table.php` — `username` varchar(64), **nullable**, DB-unique.
- Surfaces: Fortify registration (`app/Actions/Fortify/CreateNewUser.php`, `app/Concerns/ProfileValidationRules.php`), admin users create/edit (`app/Http/Requests/Admin/UserRequest.php`, `resources/views/admin/users/{create,edit}.blade.php`), profile (`app/Http/Requests/Admin/ProfileUpdateRequest.php`, `resources/views/admin/profile/index.blade.php:281-297`). **All plain Blade POST — no Livewire component carries username.**
- `app/Http/Middleware/ThrottleUsernameAvailability.php` already throttles the 4 submit routes because `unique` is an enumeration oracle.

### AI / config

- **No AI SDK** in `composer.json`. No `Http::` AI call, no suggestion feature, no AI keys in `.env.example`.
- Two **dead** config stubs must not be mistaken for wiring: `config/seo.php:16-21` (`SEO_AI_MODEL=gpt-4o-mini`) and `config/trace-replay.php:160-173` (dev-tool "AI Fix"). Neither is read by application code.
- `resend/resend-laravel` is mail only.

### Versioning & QA

- `database/seeders/ApplicationVersionSeeder.php` — 713 lines, `private const HISTORY` (45 releases, oldest first), `run()` uses `firstOrCreate()` (idempotent, **append-only**) then `Cache::forget(ApplicationVersionService::CACHE_KEY)`. Called **last** in `database/seeders/DatabaseSeeder.php:34`.
- `app/Services/ApplicationVersionService.php` — `BASELINE='1.2.0'`, strict `SEMVER_PATTERN`, `NOTE_TYPES = ['added','changed','fixed','security']`, `next('minor')`, append-only `record()`.
- `tests/Feature/Admin/ApplicationVersionTest.php` hard-codes the version at **line 73** (`assertSee('1.38.1')`), **line 167** (full 45-item version array) and **line 171** (`assertSame('1.38.1', ...)`).
- `app/Support/AdminTopbar.php:232` + `user-menu.blade.php:212` show `v{{ $version }}` in non-production only; `ApplicationProbe.php:91` shows the release row.
- `composer test` = `config:clear` → `pint --parallel --test` → `php artisan test`; `npm run build` = `vite build`.
- `.github/workflows/lint.yml` runs **only `composer lint` in fix mode** — no tests, no build gate.
- `CONVENTIONS.md:80-103`: tiered docblocks required on every class/method (`@param`/`@return` on all; `@throws` only for explicit throws / `firstOrFail`), `declare(strict_types=1)` in every PHP file, Pint after changes.
- Docs style: `PRD.md` (numbered sections + tables), `PRODUCT.md` / `DESIGN.md` (YAML frontmatter), root-level topical files such as `CHANGELOG-welcome-accessibility.md`.

---

## 2. Architecture decisions (settle these in Week 1)

| ID | Decision               | Recommendation                                                                                                                     | Why                                                                                                                                            |
| -- | ---------------------- | ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------- |
| D1 | AI provider            | Provider-agnostic `SuggestionClient` contract + one `Http`-based OpenAI-compatible implementation; **no SDK dependency** initially | Zero AI deps today; a contract keeps vendor swap cheap and keeps tests offline. Revisit `prism-php/prism` only if multi-provider becomes real. |
| D2 | Test/dev behaviour     | `NullSuggestionClient` (deterministic local candidates) bound when no key or when disabled                                         | `php artisan test` must never touch the network or spend money.                                                                                |
| D3 | Feature flag           | `AI_SUGGESTIONS_ENABLED`, **default `false`** in production until DPA + budget sign-off                                            | Safe rollout and one-switch rollback; no schema migration needed.                                                                              |
| D4 | Key placement          | New `config/ai.php` + commented keys in `.env.example`; `config/services.php` if a provider section is preferred                   | Follows existing env-driven config convention (cf. `PUBLIC_DISK_DRIVER` block).                                                                |
| D5 | Invocation             | Explicit "Suggest" button, never on keystroke                                                                                      | Controls cost/latency and avoids firing on every edit.                                                                                         |
| D6 | UI stack               | Alpine + JSON routes (mirror `taxonomy()`), **not** Livewire                                                                       | Post/user forms are Blade+Alpine; introducing Livewire here would fight the existing submission flow.                                          |
| D7 | Availability guarantee | Suggestions are *hints*; `Username::rules()` at submit remains authoritative                                                       | Closes the TOCTOU race between suggestion and save.                                                                                            |
| D8 | Registration surface   | Ship suggestions on authenticated surfaces first; gate guest registration behind separate approval                                 | A guest-facing generator is an abuse/cost vector; see R-07.                                                                                    |
| D9 | Version                | `1.39.0` (minor), computed via `next('minor')` at release                                                                          | New features, no breaking change.                                                                                                              |

**Consolidate or explicitly deprecate** the two dead AI config blocks so there is exactly one source of truth.

---

## 3. Component plans

### C0 — Shared AI foundation (prerequisite for C1 and C3)

**Goal:** one contract, one config surface, one failure model, offline-testable.

**New files**

- `config/ai.php` — `enabled`, `provider`, `model`, `api_key`, `base_url`, `timeout_seconds` (3), `max_tokens`, `titles.count` (5), `usernames.count` (6), `rate_limit` (10/min/user), `monthly_budget` cap.
- `app/Services/Ai/Contracts/SuggestionClient.php` — `suggest(SuggestionRequest $request): SuggestionResult`.
- `app/Services/Ai/DataTransferObjects/SuggestionRequest.php` / `SuggestionResult.php` — typed prompt + structured candidates.
- `app/Services/Ai/OpenAiCompatibleClient.php` — `Http::timeout()->retry(1)->post()`, strict response shape validation, no free-form passthrough.
- `app/Services/Ai/NullSuggestionClient.php` — deterministic offline candidates.
- `app/Providers/AiServiceProvider.php` (or bind in `AppServiceProvider`) — chooses implementation from config.
- `app/Services/Ai/AiSuggestionUnavailableException.php` — mapped to a friendly 503/422 JSON response; **never** leak provider errors or payloads.
- `app/Http/Middleware/ThrottleAiSuggestions.php` — per-user + per-IP limiter distinct from `ThrottleUsernameAvailability`.

**Modified:** `.env.example` (commented `AI_*` keys with a "never commit real credentials" note), `config/seo.php` / `config/trace-replay.php` (mark as legacy or remove the `'ai'` blocks).

**Steps**

1. Write the contract + DTOs with full tiered docblocks and `declare(strict_types=1)`.
2. Implement both clients; cap prompt size server-side; map timeouts / 429 / 5xx / malformed JSON to one exception.
3. Add `Http::fake()` Feature tests: happy path, timeout, 429, malformed response, key absent (Null client), payload-size cap.
4. Add a cache layer keyed by `sha256(context)` with short TTL so re-clicking "Suggest" is free.


**Acceptance:** no test performs a real HTTP call; disabling the flag degrades to a clear inline message, never a 500.

**Challenges / mitigations:** greenfield with dead config stubs that look live → delete/annotate them; vendor lock-in → contract; non-determinism → fake + Null client; cost → explicit button, cache, token cap, budget counter.

**Timeline:** **5 dev-days** (Week 1), after D1–D4 are settled. External key/DPA procurement (3–5 business days) runs in parallel.

---

### C1 — AI suggestion context for post titles

**Definition of "context":** a server-built `TitleSuggestionContext` DTO — working title, excerpt, plain text extracted from the `content` block JSON (truncated, HTML-stripped, media blocks skipped), selected category/tag names, site name/tone from settings, locale, and a small set of recent post titles for de-duplication. The client sends only the draft field values; the server resolves names/tone from the DB so no client-supplied taxonomy IDs are trusted.

**New files**

- `app/Actions/Content/BuildTitleSuggestionContextAction.php` — builds and caps the context (≤ 2,000 chars).
- `app/Services/Ai/DataTransferObjects/TitleSuggestionContext.php` — the DTO.
- `app/Services/PostTitleSuggestionService.php` — calls client, post-processes candidates.
- `app/Http/Requests/Admin/TitleSuggestionRequest.php` — validates `title`, `excerpt`, `content`, `categories[]`, `tags[]`, `locale`.
- `app/Http/Controllers/Admin/TitleSuggestionController.php` — thin, JSON only.
- `resources/views/admin/blog/posts/partials/title-suggestions.blade.php` — button + candidate list.

**Modified:** `routes/admin.php` (POST `admin.posts.title-suggestions`, `can:create blog`, registered **before** the `{post}` wildcard), `partials/basic-info.blade.php` (button beside the counter), `resources/js/admin.js` (new `Alpine.data('titleSuggestions', ...)`).

**Steps**

1. Route + Form Request + controller, `wantsJson()` only, CSRF inherited.
2. Context action: parse the BlockBuilder JSON via the existing block registry/renderer, strip HTML, cap length, resolve category/tag names with a single `whereIn()` (no N+1).
3. Service post-processing: trim, strip control chars/HTML, `Str::limit(Post::TITLE_MAX)`, drop empties/duplicates, drop titles matching an existing `posts.title`, return 3–5 ranked candidates. **Never** write to the DB.
4. UI: "Suggest titles" button → loading/disabled state → list of buttons → click applies to `#title` and updates the `charCount` Alpine state; `aria-live="polite"`, keyboard reachable, dark-mode classes, `x-cloak`, Escape closes.
5. Graceful states: disabled, timeout, rate-limited, no candidates → inline message; the title field stays fully usable.

**Acceptance:** a draft with only a title and a draft with three content blocks both return ≤ 5 valid titles ≤ 120 chars; no network call happens when the flag is off; applying a suggestion preserves all other unsaved form values.

**Challenges:** `content` is a nested block JSON, not plain prose (needs block-aware flattening); long drafts blow token budgets; `Post::TITLE_MAX=120` must be enforced server-side even if the model ignores it; duplicate titles are allowed by schema so de-dup must be deliberate; draft content is unpublished → see R-04.

**Timeline:** **5 dev-days** (Week 2), depends on C0.

---

### C2 — New category modal and new tag modal

**This is a refactor, not a build.** The quick-add dialog already works and is covered by `BlogTaxonomyTest.php`. The enhancement should make them two first-class, reusable modals and close the existing gaps.

**New files**

- `resources/views/components/admin/quick-add-term-modal.blade.php` — shared accessible shell (overlay, focus trap hook, Escape, error region, cancel/save).
- `resources/views/components/admin/new-category-modal.blade.php` — wraps the shell; adds the optional `description` field (`Category` has `name`, `slug`, `description`).
- `resources/views/components/admin/new-tag-modal.blade.php` — wraps the shell; name only, `#` preview.
- `resources/js/admin.js` → split `taxonomy()` into `categoryQuickAdd()` / `tagQuickAdd()` sharing a factory, preserving `addChip()`'s `change` event (it refreshes the publish checklist).

**Modified:** `partials/taxonomy.blade.php`, `app/Http/Requests/Admin/CategoryRequest.php` (accept optional `description`), `app/Services/CategoryService.php`, `CategoryController::store()` JSON payload (include `description`), `tests/Feature/Admin/BlogTaxonomyTest.php`.

**Steps**

1. Extract the shared dialog shell; keep the manual focus trap and `lockAdminContentScroll()` behaviour exactly.
2. Split into two modals with distinct titles, labels, `$refs` and routes; category gains description + live slug preview.
3. Harden: server-side duplicate-name message (already custom in the Requests), `saving` guard, network-error message, restore focus to the trigger on close, `@can('create blog')` on both trigger and route, empty-state removal on first chip.
4. Keep the JSON `{id,name,slug}` contract unchanged so `addChip()` and the post save path do not regress.
5. Add tests: each modal endpoint, description persistence, duplicate rejection, chip cloning + publish checklist, keyboard/Escape assertions where testable.

**Acceptance:** both modals work on create **and** edit post pages; adding a term never navigates away or loses unsaved title/excerpt/content; all 9 existing taxonomy tests stay green.

**Challenges:** the HTML `<template>` chip clone is coupled to exact IDs (`category-chip-template` etc.) — a careless rename breaks selection; no `@alpinejs/focus` → keep the hand-rolled trap; dark-mode classes and `x-cloak` must be carried into the components; the edit page reuses the same partial, so `old()` vs `$post` selection must stay intact.

**Timeline:** **3 dev-days** (Week 2, parallel with C1), **no dependency on C0** — this is the earliest shippable component.

---

### C3 — AI username suggestions (available names only)

**New files**

- `app/Services/UsernameSuggestionService.php` — the availability guarantee.
- `app/Http/Requests/Admin/UsernameSuggestionRequest.php` — validates a `seed` (name/email local part, ≤ 100 chars), `count` (≤ 10), optional `ignore_user_id`.
- `app/Http/Controllers/Admin/UsernameSuggestionController.php` — JSON only.
- `resources/views/components/admin/username-suggestions.blade.php` + Alpine data in `resources/js/admin.js`.

**Modified:** `routes/admin.php` (POST `admin.username-suggestions`, `can:manage settings` for admin surfaces; separate guest route only if D8 approves), `resources/views/admin/users/{create,edit}.blade.php`, `resources/views/admin/profile/index.blade.php`.

**The availability pipeline (server-side, in order)**

1. Build a seed from the user's `name` / email local part; **never** trust a client-supplied username list as truth.
2. Ask the AI client for N candidate *ideas* (e.g. 12) plus a deterministic local fallback from `Username::generateFromEmail()`.
3. For each candidate: `Username::normalize()` → `isValid()` → `! isReserved()` → batch `User::whereIn('username', $candidates)->pluck('username')` → subtract taken names → dedupe → take N.
4. Return only names that passed every check. If fewer than N survive, return fewer — never pad with unverified names.
5. At submit, `Username::rules()` re-checks `unique`; on collision the UI keeps the value and shows the standard validation error (TOCTOU closed by design).

**Steps**

1. Service + DTO + Form Request + controller; add a dedicated entry to `ThrottleAiSuggestions` (and `ThrottleUsernameAvailability` if the endpoint exposes availability).
2. Wire the button into all three authenticated username fields; click a candidate to fill the input; `aria-live` announcement; loading/error/empty states.
3. Tests: reserved names filtered, duplicates filtered, uppercase/dots normalized, 30-char cap, 60-char DB column never exceeded, exhausted pool returns fewer, `Http::fake()` for AI path, Null-client path, throttle 429, guest forbidden, `RefreshDatabase` with sqlite (case-sensitive) **and** a note about MySQL `utf8mb4_unicode_ci`.

**Challenges:** sqlite tests are case-sensitive while production MySQL is not — normalize first; `username` is nullable on profile edits so suggestions must not make the field mandatory; the 60-name `RESERVED` list is easy to forget; a new "is this name free?" style endpoint is an enumeration oracle → rate limit and never add an arbitrary-name lookup; suggestions are non-deterministic → separate "format/availability" unit tests from "AI output" tests.

**Timeline:** **6 dev-days** (Weeks 3–4), depends on C0; can overlap C2.

---

### C4 — Production readiness: testing, security, documentation, CI

**Testing (mirror existing style: `RefreshDatabase`, `actingAs`, `assertDatabaseHas`, `Livewire::test` where a Livewire component is involved)**

- `tests/Feature/Admin/PostTitleSuggestionTest.php` — authorization, validation, `Http::fake` responses, 120-char cap, dedup, flag-off, timeout, rate limit, no DB writes.
- `tests/Feature/Admin/UsernameSuggestionTest.php` — as above.
- `tests/Unit/Services/Ai/` — client response parsing, DTO caps, Null client determinism.
- Extend `tests/Feature/Admin/BlogTaxonomyTest.php` for C2.
- Update `tests/Feature/Admin/ApplicationVersionTest.php` for C5.
- Accessibility/browser QA: keyboard-only path, `aria-live`, focus return, dark mode, `prefers-reduced-motion`, 200ms+ simulated latency, offline/network failure.

**Security & operations**

- CSRF on every new POST (inherit admin group), `can:` gates, no raw SQL, `Http::timeout(3)`, no logging of prompt/response bodies, no PII in prompt, per-user + per-IP rate limits, monthly budget counter, provider outage fallback.
- Privacy: unpublished draft content leaves the server → DPA / data-processing note, admin-facing "content is sent to a third party" disclosure, site-level opt-out flag, redaction of emails/phones from the context.
- Observability: structured log line per request (duration, candidate count, outcome — **no content**), plus a failed-call counter.
- Rollback: **no migrations required** → set `AI_SUGGESTIONS_ENABLED=false` to disable instantly; C2 is view/JS only.

**CI change (`.github/workflows/lint.yml`)** — currently only `composer lint` in fix mode, which auto-fixes rather than fails, and never runs tests or the Vite build. Add `composer lint:check`, `php artisan test` and `npm run build` as gates (or `composer ci:check`). This is a prerequisite for calling the release production-ready.


**Documentation**

- `PRD.md` — new numbered section: goals, non-goals (no auto-publish, no auto-title on save), user stories, acceptance criteria, privacy statement.
- New root doc `AI-SUGGESTIONS.md` — operator runbook: env keys, enabling/disabling, rate/budget limits, provider swap, failure modes, rollback, DPA owner.
- `.env.example` — commented `AI_*` keys.
- `DESIGN.md` — only if new UI primitives are introduced (button/candidate list styling).
- `ApplicationVersionSeeder.php` HISTORY notes double as the changelog entry (C5).
- Re-run `php artisan brain:generate-rules --target=agents` so `AGENTS.md` / `CONVENTIONS.md` route/service counts are not stale.

**Commands before merge:** `composer test` (`config:clear` → `pint --parallel --test` → `php artisan test`), `npm run build`, `composer lint:check`.

**Timeline:** **5 dev-days** (Week 5), after C1/C2/C3.

---

### C5 — `ApplicationVersionSeeder` update (release step — do last)

**Trigger:** all of C0–C4 merged, green, documented, and staging-smoke-tested. **Do not do this during planning.**

**Steps**

1. Compute the number with `app(Services\ApplicationVersionService::class)->next('minor')` at release time → expected `1.39.0` (not `1.38.2`: these are features, not a patch).  
   |  |  |  |  |  
   | --- | --- | --- | --- |
2. Append a **new final entry** to `private const HISTORY` in `database/seeders/ApplicationVersionSeeder.php` (currently ends at `1.38.1`, line 668-679). Fields: `version`, `summary`, `released_at` = **actual release date**, `notes` = array of `['type' => ...]` using only `added|changed|fixed|security`. Write one note per feature (title context, two modals, username suggestions, AI foundation/rate limits, CI/docs).
3. Update `tests/Feature/Admin/ApplicationVersionTest.php`: line 73 `assertSee('1.38.1')` → new version; line 167 append the new version to the full ordered array; line 171 `assertSame(..., current())`; re-check `seededVersions()` and the `HISTORY_PER_PAGE` count assertions in the timeline tests (45 → 46 releases).
4. Verify: `php artisan test --filter=ApplicationVersionTest`, full `composer test`, `npm run build`; confirm the admin Versions page and topbar badge show the new version; `Cache::forget(CACHE_KEY)` is already handled by the seeder and `record()`.
5. Deployment: `php artisan db:seed --class=ApplicationVersionSeeder --force` (idempotent `firstOrCreate`). On environments where the release was already recorded via the admin UI, note that `firstOrCreate` will **not** rewrite existing rows — fix notes/summary manually if needed.

**Challenges:** append-only registry (never edit an existing entry); three hard-coded test assertions; version is shown in the admin topbar and `ApplicationProbe`; if other releases land first, `1.39.0` is already taken and `record()` rejects a non-newer version — always recompute.

**Timeline:** **1 dev-day** inside the Week 5–6 release window.

---

## 4. File-level change summary

| Area           | New                                                                                                                                                                                                            | Modified                                                                                                                                               |
| -------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------ |
| AI foundation  | `config/ai.php`, `app/Services/Ai/**` (contract, 2 clients, 2 DTOs, exception), `app/Providers/AiServiceProvider.php`, `app/Http/Middleware/ThrottleAiSuggestions.php`                                         | `.env.example`, `config/seo.php`, `config/trace-replay.php`                                                                                            |
| Titles         | `BuildTitleSuggestionContextAction.php`, `TitleSuggestionContext.php`, `PostTitleSuggestionService.php`, `TitleSuggestionRequest.php`, `TitleSuggestionController.php`, `partials/title-suggestions.blade.php` | `routes/admin.php`, `partials/basic-info.blade.php`, `resources/js/admin.js`                                                                           |
| Taxonomy       | 3 admin Blade components                                                                                                                                                                                       | `partials/taxonomy.blade.php`, `resources/js/admin.js`, `CategoryRequest.php`, `CategoryService.php`, `CategoryController.php`, `BlogTaxonomyTest.php` |
| Username       | `UsernameSuggestionService.php`, `UsernameSuggestionRequest.php`, `UsernameSuggestionController.php`, `username-suggestions.blade.php`                                                                         | `routes/admin.php`, 3 username Blade views, `resources/js/admin.js`                                                                                    |
| Tests          | 3 new Feature test classes + Unit tests for AI DTO/client                                                                                                                                                      | `BlogTaxonomyTest.php`, `ApplicationVersionTest.php`                                                                                                   |
| Docs / release | `AI-SUGGESTIONS.md`                                                                                                                                                                                            | `PRD.md`, `.env.example`, `.github/workflows/lint.yml`, `DESIGN.md` (if needed), `AGENTS.md`/`CONVENTIONS.md` (regenerated)                            |
| Version        | —                                                                                                                                                                                                              | `database/seeders/ApplicationVersionSeeder.php`                                                                                                        |

**No new migrations are needed.** If an audit/quota table is later required, follow `YYYY_MM_DD_HHMMSS_snake_case.php` (latest is `2026_10_24_000100_...`).

---

## 5. Risk register

| ID   | Risk                                                             | Impact                                              | Mitigation                                                                                             |
| ---- | ---------------------------------------------------------------- | --------------------------------------------------- | ------------------------------------------------------------------------------------------------------ |
| R-01 | No existing AI layer; two dead config blocks look live           | Wiring to a non-existent service, duplicated config | New `config/ai.php` as single source; delete/annotate the stubs                                        |
| R-02 | Post/user forms are Blade+Alpine, not Livewire                   | Rebuilding forms unnecessarily                      | New JSON routes + Alpine data, mirroring `taxonomy()`                                                  |
| R-03 | `content` is nested block JSON                                   | Prompt bloat / broken context                       | Block-aware flatten, strip HTML, 2,000-char server-side cap                                            |
| R-04 | Unpublished draft content sent to a third party                  | Privacy / contractual exposure                      | DPA before enable, PII redaction, admin disclosure, flag **off by default**                            |
| R-05 | Latency, 429s, provider outage                                   | Editor blocked                                      | 3s timeout, one retry, cache, explicit button, friendly fallback, no writes                            |
| R-06 | Unbounded AI cost                                                | Budget overrun                                      | Per-user limiter, token cap, monthly budget counter, no keystroke triggers                             |
| R-07 | Username endpoint becomes an enumeration oracle                  | Account discovery / abuse                           | No arbitrary-name lookup, strict throttle, authenticated surfaces first, `unique` re-checked at submit |
| R-08 | TOCTOU: suggested name taken before save                         | Validation error after clicking                     | Treat suggestions as hints; authoritative `Username::rules()` on submit                                |
| R-09 | sqlite (case-sensitive) vs MySQL `utf8mb4_unicode_ci`            | Test passes, production collides                    | Always `Username::normalize()` before any check; document in tests                                     |
| R-10 | Existing chip-template IDs and publish checklist break during C2 | Taxonomy selection silently lost                    | Preserve `addChip()` contract + `change` event; keep all 9 existing tests green                        |
| R-11 | Hand-written focus trap / no `@alpinejs/focus`                   | Accessibility regression                            | Carry the trap, Escape, focus restore and `aria-*` into the extracted components                       |
| R-12 | `ApplicationVersionTest.php` hard-codes `1.38.1`                 | CI red on release                                   | Update all three assertions in the same atomic commit as the seeder entry                              |
| R-13 | CI runs Pint in fix mode only                                    | Untested/broken builds ship                         | Add `lint:check` + `php artisan test` + `npm run build` gates                                          |
| R-14 | API key / DPA procurement lead time                              | Blocks Week 1                                       | Start procurement in Phase 0; `NullSuggestionClient` lets all dev proceed offline                      |

---

## 6. Timeline

Assumptions: one PHP/back-end developer plus one front-end-capable developer; 5-day weeks; decisions D1–D9 settled in Week 1; no lengthy design-review cycles; AI key available by end of Week 1. Estimates are developer-days, not elapsed time.

| Phase | Work                                                                   | Dev-days | Calendar  | Depends on | Milestone                                    |
| ----- | ---------------------------------------------------------------------- | -------- | --------- | ---------- | -------------------------------------------- |
| P0    | Decisions D1–D9, DPA/key procurement, privacy sign-off                 | 2        | Week 1    | —          | Approved ADR + test API key                  |
| P1    | C0 AI foundation (contract, clients, config, middleware, cache, tests) | 5        | Week 1    | P0         | Offline-testable AI layer                    |
| P2    | C2 category + tag modals (independent, can run in parallel)            | 3        | Week 2    | —          | Two reusable modals, taxonomy tests green    |
| P3    | C1 post-title suggestion context + UI                                  | 5        | Week 2    | P1         | Title suggestions work on create/edit        |
| P4    | C3 username suggestions across 3 surfaces                              | 6        | Weeks 3–4 | P1         | Only available names returned                |
| P5    | C4 hardening: security review, a11y, perf, observability, CI, docs     | 5        | Week 5    | P2, P3, P4 | Full `composer test` + `npm run build` green |
| P6    | Release: staging smoke test, C5 seeder bump + test updates, deploy     | 2        | Week 6    | P5         | Version `1.39.0` recorded                    |

**Total:** ~28 developer-days. Two developers with P2/P3 parallel → **~5 calendar weeks**; one developer → **~6–7 weeks** including a 20% buffer. P0 is on the critical path because of the external DPA/key lead time.

---

## 7. Definition of Done / release checklist

- [ ] `composer test` green (`config:clear` → `pint --parallel --test` → `php artisan test`)
- [ ] `npm run build` green; CI gates updated (no longer fix-mode Pint only)
- [ ] Every new PHP file has `declare(strict_types=1)` and tiered docblocks per `CONVENTIONS.md`
- [ ] No test makes a real AI HTTP call (`Http::fake()` / `NullSuggestionClient`)
- [ ] `AI_SUGGESTIONS_ENABLED=false` cleanly disables all AI UI and endpoints
- [ ] Rate limits, timeouts, budget counter and provider-outage fallback verified
- [ ] Privacy disclosure + DPA approved; prompts logged without content
- [ ] All three username surfaces and both post create/edit pages smoke-tested, keyboard + dark mode + screen-reader labels
- [ ] All 9 `BlogTaxonomyTest` cases still pass; quick-add preserves unsaved post state
- [ ] `PRD.md`, `AI-SUGGESTIONS.md`, `.env.example` updated; `AGENTS.md` regenerated
- [ ] `ApplicationVersionSeeder.php` appended with `1.39.0` (or `next('minor')` at release) + `ApplicationVersionTest.php` assertions updated
- [ ] Rollback rehearsed: flag off, no migration to reverse

---

## 8. Open questions for the product owner

1. **Which AI provider** (and is a DPA already in place)? This determines `config/ai.php` and the procurement lead time.
2. **May unpublished draft content be sent to a third party?** If no, C1 must run on a self-hosted/local model — a materially larger scope.
3. **Should registration (guest-facing) get username suggestions in v1**, or only authenticated admin/profile surfaces (recommended)?
4. **Should the category modal expose `description`** (the column exists but the current quick-add does not use it)?
5. **Is `1.39.0` reserved**, or will another release land before this one (`next('minor')` at release decides)?
6. **Is upgrading CI to run tests + build in scope** for this release? Recommended, but it may surface unrelated pre-existing failures.
