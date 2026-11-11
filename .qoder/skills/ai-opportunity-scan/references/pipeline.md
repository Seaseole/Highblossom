# Shipped AI pipeline map

Verify against the code before relying on this file; it is a snapshot, not a guarantee.

## Config and gates

- `config/ai.php` — single source of truth: `enabled` (env `AI_SUGGESTIONS_ENABLED`, default off), provider/key/base_url/model, `timeout` (20s), `max_tokens` (700, raised per-kind by the service), `temperature`, `rate_limit`, `cache_ttl`, `context_max_chars` (2000), `copy.count` (4), `tones` presets, `nudges` thresholds.
- `app/Providers/AiServiceProvider.php` — binds `SuggestionClient` to `OpenAiCompatibleClient` or `NullSuggestionClient` (offline, deterministic).
- `app/Services/Ai/SuggestionBroker.php` — the one gate: flag check, short cache, structured outcome log (never logs prompts/completions). Throws `AiSuggestionException`.
- `app/Http/Middleware/ThrottleAiSuggestions.php` — alias `ai.throttle` (`bootstrap/app.php`), per-user rate limit, readable 429.
- `app/Concerns/PreparesAiPromptText.php` — `clean()` strips tags + redacts email/phone; `body()` flattens block JSON via `Post::plainText()` and caps at `context_max_chars`.

## Copy suggestions (provider-backed)

Flow: FormRequest → context action → `CopySuggestionService` → broker → client.

- DTOs: `app/Services/Ai/DataTransferObjects/CopySuggestionContext.php` (kind, subject, body, current, keywords, siteName, locale, placement, tone — append-only constructor, named args at call sites), `SuggestionRequest.php`, `SuggestionResult.php`.
- Domain service: `app/Services/CopySuggestionService.php` — per-kind system prompts + `MAX_LENGTHS` (excerpt 300, meta_title 70, meta_description 160, block_paragraph 600, block_heading 120, block_text 300, block_button 40, about_body 1200, about_statement 600), token budget estimated from the kind ceiling, tone guidance appended to the system prompt, deterministic local fallback merged into every answer, post-process strips/dedups/excludes current/caps.
- Context actions (`app/Actions/Content/`): `BuildExcerptSuggestionContextAction`, `BuildSeoMetaSuggestionContextAction` (server-resolved page labels), `BuildBlockSuggestionContextAction` (`FIELD_KINDS` map), `BuildAboutSuggestionContextAction`.
- Endpoints (`routes/admin.php`, all `can:*` + `ai.throttle`, registered before wildcards):
  - `admin.posts.excerpt-suggestions` — `can:create blog`
  - `admin.posts.block-suggestions` — `can:create blog`
  - `admin.seo.meta-suggestions` — `can:manage seo`
  - `admin.about-us.suggestions` — `can:manage pages`
- Requests mirror the route gate in `authorize()`; cross-field checks use the `after(): Closure` idiom and guard `is_string()` before casting.
- UI: `resources/views/components/admin/copy-suggestions.blade.php` (one shared panel; form mode via `target`/`fields`, block mode via `block`/`blockFields` JS expression, CKEDITOR via `editor`; tone select seeded from the `ai_tone` company setting) + `copySuggestions` in `resources/js/admin.js` (payload/apply; block mode reads live builder state and `#content-input`).

## Tone personas

- `config('ai.tones')`: `professional`, `friendly`, `seo` — each `label` + one-sentence `guidance` appended to the system prompt. Unknown/empty tone = no append. Offline client ignores tone.
- Company default: `ai_tone` setting (`SettingsManager::getDefaults()`, `CompanyValidationRules`, `CompanySettingService::SIMPLE_FIELDS`, settings General tab "AI Writing" block, rendered only when `ai.enabled`). Panel sends `tone` per request; requests validate against the config keys.

## Nudges (no provider)

- `app/Services/NudgeService.php` — deterministic signal scan (stale quotes, stale drafts, missing SEO, quiet blog, upcoming unconfirmed bookings…), `nudges` table (key, severity, message, action_url, status, fingerprint), `visible()` capped at `ai.nudges.max_active`.
- `app/Console/Commands/ScanNudgesCommand.php` — `nudges:scan`, scheduled hourly.
- Surfaces: dashboard + System Overview `hb-*` band partial; dismiss/snooze endpoints. Never calls a provider.

## Other suggestion services (same broker)

- `app/Services/PostTitleSuggestionService.php`, `app/Services/UsernameSuggestionService.php` with `TitleSuggestionContext`.

## Extension recipe (the block/about pattern)

1. Add the kind to `CopySuggestionContext::KINDS`, `SYSTEM_PROMPTS`, `MAX_LENGTHS`, `noun()`, and a `localCandidates()` arm in `CopySuggestionService`.
2. New context action using `PreparesAiPromptText`; resolve kind/placement server-side from a fixed map.
3. New FormRequest (authorize mirrors route gate; validate against the same map; `after(): Closure` for cross-field).
4. Thin controller: catch `AiSuggestionException` → `{message, suggestions: []}` with its HTTP status; success → `{suggestions}`.
5. Route before any wildcard, `can:*` + `ai.throttle`.
6. Reuse `<x-admin.copy-suggestions>`; no new Alpine unless the surface needs it.
7. Feature tests: guest 401, permission 403, flag-off 422, allowlist 422, offline determinism, caps, dedup, current-exclusion, 503 no-leak, empty→fallback, redacted+capped prompt, panel toggle, rate limit. `Http::preventStrayRequests()` + `fakeProvider()` helper; never a real key.

## Docs and tests

- `AI-SUGGESTIONS.md` (repo root) — operator + architecture documentation; keep it in sync.
- `tests/Feature/Admin/*SuggestionTest.php`, `NudgeTest.php`, `AiToneSettingTest.php`.