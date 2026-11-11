# AI-Assisted Authoring — Operator Runbook

**Applies to:** Highblossom `1.39.0` and later  
**Status:** shipped, **disabled by default**  
**Owner:** site operator (configuration) / engineering (provider changes)

---

## 1. What this feature does

Four small, opt-in AI helpers in the admin panel (plus the non-AI taxonomy dialogs):

| Surface                           | Control                                 | Endpoint                                                               | Result                                                                                 |
| --------------------------------- | --------------------------------------- | ---------------------------------------------------------------------- | -------------------------------------------------------------------------------------- |
| Post create / edit, title field   | **Suggest titles with AI**              | `POST admin/posts/title-suggestions` (`admin.posts.title-suggestions`) | Up to `ai.titles.count` headline options; clicking one fills the title input            |
| Post create / edit, excerpt field | **Suggest excerpts with AI**            | `POST admin/posts/excerpt-suggestions` (`admin.posts.excerpt-suggestions`) | Up to `ai.copy.count` excerpt options (≤ 300 chars); clicking one fills the excerpt textarea |
| SEO settings, per-route edit/create, meta title + description | **Suggest meta titles/descriptions with AI** | `POST admin/seo/meta-suggestions` (`admin.seo.meta-suggestions`) | Up to `ai.copy.count` meta titles (≤ 70 chars) or descriptions (≤ 160 chars) for the requested `field`; clicking one fills the field |
| Post create / edit, block builder (paragraph, heading, CTA, alert) | **Suggest … with AI** inside the block editor | `POST admin/posts/block-suggestions` (`admin.posts.block-suggestions`) | Up to `ai.copy.count` lines for the selected block field (paragraph ≤ 600, headings ≤ 120, CTA/alert text ≤ 300, button labels ≤ 40 chars); clicking one fills the field, CKEDITOR included |
| About Us, body / mission / vision | **Suggest body/mission/vision with AI** | `POST admin/about-us/suggestions` (`admin.about-us.suggestions`) | Up to `ai.copy.count` lines for the requested `field` (body ≤ 1200, mission/vision ≤ 600 chars); clicking one fills the rich-text editor |
| Admin user create / edit, profile | **Suggest available usernames with AI** | `POST admin/username-suggestions` (`admin.username-suggestions`)       | Up to `ai.usernames.count` names verified free in the database; clicking one fills the username input |
| Post create / edit, taxonomy      | **New category** / **New tag** dialogs  | `POST admin/categories/store`, `POST admin/tags/store`                 | Creates a term without leaving the post editor                                         |

The taxonomy dialogs are **not** AI features; they are separate Blade components that share one Alpine state so unsaved post fields survive opening a dialog. Categories additionally offer an optional `description`; tags do not.

> **Nothing is generated on keystroke.** Every AI call is the result of an explicit button click. The controls are only rendered when `ai.enabled` is true.

The title control has a scoped motion arc in `resources/css/admin.css`: while the request is in flight the button and an indeterminate sweep carry a moving violet → pink → amber → cyan gradient, then each returned headline fades/slides in on its own `85ms` stagger with a per-item hue. `titleSuggestions()` increments a `run` counter and uses it in the `x-for` key so a second request replays the reveal instead of reusing old DOM nodes. All motion collapses to static colour under `prefers-reduced-motion: reduce`.

---

## 2. The default is offline

`AI_SUGGESTIONS_ENABLED=false` in `.env.example`. With the flag off:

- no draft text leaves the server;
- the suggestion endpoints answer `422` with `AI suggestions are switched off on this site.`;
- the suggestion controls are not rendered in the UI;
- the test suite never touches the network.

Even with the flag **on**, a blank `AI_SUGGESTIONS_KEY` (or `AI_SUGGESTIONS_PROVIDER=null`) resolves the offline `NullSuggestionClient`. It returns deterministic local candidates, so the feature still works in a staging environment with no third-party account — it just produces mechanical rather than creative suggestions.

**Do not enable this on a production site until the data-processing terms with the provider are agreed.** The admin UI discloses that a redacted summary of the draft is sent for processing; that disclosure is a product requirement, not a substitute for a DPA.

---

## 3. Configuration

All settings live in `config/ai.php` — the single source of truth. The `'ai'` blocks in `config/seo.php` and `config/trace-replay.php` are legacy placeholders and are **not read by any application code**.

| `.env` variable              | `config()` key   | Default                     | Purpose                                               |
| ---------------------------- | ---------------- | --------------------------- | ----------------------------------------------------- |
| `AI_SUGGESTIONS_ENABLED`     | `ai.enabled`     | `false`                     | Master switch                                         |
| `AI_SUGGESTIONS_PROVIDER`    | `ai.provider`    | `openai-compatible`         | `openai-compatible` for HTTP, `null` to force offline |
| `AI_SUGGESTIONS_KEY`         | `ai.key`         | empty                       | Bearer token; **secrets manager only, never commit**  |
| `AI_SUGGESTIONS_BASE_URL`    | `ai.base_url`    | `https://api.openai.com/v1` | Any OpenAI-compatible chat-completions base URL       |
| `AI_SUGGESTIONS_MODEL`       | `ai.model`       | `gpt-4o-mini`               | Model identifier sent to the provider                 |
| `AI_SUGGESTIONS_TIMEOUT`     | `ai.timeout`     | `20`                        | Whole completion budget, seconds (connect capped at 5s) |
| `AI_SUGGESTIONS_MAX_TOKENS`  | `ai.max_tokens`  | `700`                       | Completion ceiling; reasoning models need 700+          |
| `AI_SUGGESTIONS_TEMPERATURE` | `ai.temperature` | `0.7`                       | Sampling temperature                                  |
| `AI_SUGGESTIONS_RATE_LIMIT`  | `ai.rate_limit`  | `10`                        | Requests per user+IP per minute                       |
| `AI_SUGGESTIONS_CACHE_TTL`   | `ai.cache_ttl`   | `300`                       | Seconds a result is cached for an unchanged brief     |

Hard-coded (intentionally **not** environment-configurable):

| Key                    | Value  | Why                                             |
| ---------------------- | ------ | ----------------------------------------------- |
| `ai.context_max_chars` | `2000` | Maximum flattened draft prose in a title prompt |
| `ai.titles.count`      | `8`    | Headlines per batch                             |
| `ai.usernames.count`   | `8`    | Names per batch                                 |
| `ai.usernames.pool`    | `18`   | Candidate ideas generated/checked per batch     |
| `ai.copy.count`        | `4`    | Excerpts / meta lines per batch                 |
| `ai.tones`             | 3 presets | Voice personas: `professional`, `friendly`, `seo` (see §13) |

These are code-level defaults, not `.env` variables. Both endpoints read them from
`config('ai.*')` — the browser does not send a count, so changing them here changes
both the UI and the server in one place. An explicit `count` in a POST body is still
honoured (clamped to 1–10), which is what the feature tests use.

> **Timeout is the one setting most likely to break a working provider.** It is the
> *whole* completion budget, not just the TCP connect. A 3-second value is too close
> to the first-token latency of a free-tier hosted model and produces the generic
> `503 Suggestions are unavailable right now` even with a valid key, base URL and
> model. 20s is the default; the connect phase is separately capped at `min(5, timeout)`
> so an unreachable host still fails quickly.

After changing `.env`, clear the config cache:

```bash
php artisan config:clear
```

`AI_SUGGESTIONS_TIMEOUT` is optional in `.env` — omitting it picks up the `20` default
from `config/ai.php`. Set it explicitly if the deployment pins its own latency budget.

> **Reasoning models are the second most common surprise.** Models such as
> `nvidia/nemotron-3.5-lightning:free` write their chain of thought into a
> separate `reasoning` field. With `max_tokens=300` the entire budget is spent
> there: OpenRouter answers **HTTP 200** with `content: null` and
> `finish_reason: length`, producing no suggestions. `700` returns real content
> with `finish_reason: stop`. `max_tokens` is a ceiling, not a fixed spend — a
> normal instruct model still stops after ~100 tokens, so raising it does not
> raise cost for those models. If you prefer 300, use a non-reasoning model.

```bash
# Quick check that every variable in .env really resolves (key never printed):
php artisan tinker --execute="echo 'enabled='.var_export(config('ai.enabled'),true).' timeout='.config('ai.timeout').' max_tokens='.config('ai.max_tokens').' model='.config('ai.model').PHP_EOL;"
```

**If `php artisan config:cache` is used in production, `.env` is no longer read at
runtime** — the cached array wins. Re-run `php artisan config:cache` after every AI
variable change, or `php artisan config:clear` while developing. This is the usual
reason "I changed `.env` and nothing happened".



---

## 4. Data flow

```
Admin browser                Laravel (server)                         Provider
     |  click "Suggest"            |                                        |
     |  POST field values -------> |                                        |
     |                            | FormRequest validates + drops unknown  |
     |                            | fields (no arbitrary candidate lookup) |
     |                            | Action/DTO builds a bounded brief:     |
     |                            |  - server-resolved taxonomy names     |
     |                            |  - block JSON -> plain text, capped    |
     |                            |  - markup stripped, email/phone redacted|
     |                            | SuggestionBroker:                      |
     |                            |  flag -> cache -> rate limit gate      |
     |                            | ---------------- POST /chat/completions ->|
     |                            | <--------------- plain lines -----------|
     |                            | Domain service filters + de-duplicates  |
     |  <------ JSON list -------- | (username: subtract taken rows in DB)  |
     |  click a row -> fill input  |                                        |
```

The **server** builds the prompt. The browser never supplies prompt text, taxonomy labels, a model name, or a list of candidates to check. Unknown POST fields are dropped by the Form Requests, so these routes cannot be used as a username-availability wordlist oracle.

### What is sent for a title

Working title, excerpt, up to 2000 characters of flattened block prose (`Post::plainText()`), selected category/tag **names** resolved from IDs, site name, locale, and up to 10 recent post headlines (as "do not reuse" examples). Free text is stripped of markup/control characters and email addresses and phone numbers are replaced with `[email redacted]` / `[phone redacted]`.

### What is sent for a username

The free-text `name` field (redacted and truncated to 100 characters), **only the local part** of the email address before `@`, and a normalised seed. The full email address is never sent. A global XSS firewall (`arpanihan/auditify`) blocks script-shaped input before it reaches the controller; service-side `clean()` is defence in depth.

### What is sent for an excerpt or SEO meta line

For excerpts: the working title, the current excerpt, and up to 2000 characters of flattened block prose — the same `Post::plainText()` path and redaction as the title brief (shared `PreparesAiPromptText` trait). For SEO meta: the requested `field`, the current meta title/description/keywords, and a subject resolved **on the server** — the page label from `SeoService::getRouteLabel()` via `route_id` (edit) or an allowlisted `route_name` (create), or the site name for site-wide defaults. A client-supplied label is never trusted. Meta descriptions are capped at 160 characters (the optimal SERP length) even though the column allows 300.

### What is sent for a block field or an About Us field

For a block: the `block_type` and `field` (validated against a fixed per-type map in `BuildBlockSuggestionContextAction::FIELD_KINDS` — paragraph/heading `content`, CTA `title`/`description`/`button_text`, alert `title`/`content`), the working post title, the current field value (redacted; the prompt shows its first 500 characters, while current-value exclusion compares the full value), and up to 2000 characters of flattened prose from the live block JSON (`#content-input`, the same `Post::plainText()` path). The copy kind and a "Placement" line (e.g. `call-to-action button`) are resolved on the server; the browser never supplies prompt text. For About Us: the requested `field` (`body`, `mission`, `vision`), the title and subtitle as the subject, the field's current value, and the prose of the *other two* fields as grounding context — all redacted and capped the same way. Suggestions are plain text; the panel escapes them and wraps them in a single `<p>` before handing them to CKEDITOR, and the existing save-time sanitizers remain authoritative.

### What is never sent or logged

Full email addresses, phone numbers, user IDs, credentials, provider response bodies, prompts, and raw completions. On failure `SuggestionBroker` logs a single warning line containing only `status`, a fixed `reason`, and a short machine-readable `cause`; cache keys are SHA-256 fingerprints, never prompt text. The `cause` is a fixed label from application code — never provider text — and is never rendered in the admin panel.

---

## 5. Username availability guarantee

The provider supplies **ideas only**. `UsernameSuggestionService` is authoritative:

1. strip list markers and normalise with `Username::normalize()`;
2. reject anything failing `Username::isValid()` or matching `Username::isReserved()`;
3. de-duplicate;
4. subtract taken rows in **one** `User::whereIn('username', ...)` query;
5. on edit surfaces, exclude the row being edited via `ignore_user_id` (so a user's own current username remains available);
6. slice to the requested count (clamped to 1–10).

Suggestions are **hints, not reservations**. `Username::rules()` re-checks uniqueness on submit, which closes the time-of-check/time-of-use race between clicking a name and saving the form. No row is inserted or reserved at suggestion time.

Only authenticated admin surfaces (user create/edit, own profile) expose the control. Guest registration is deliberately out of scope for this release.

---

## 6. Failure behaviour

| Condition                                           | HTTP                           | Body                                                                                     | UI                     |
| --------------------------------------------------- | ------------------------------ | ---------------------------------------------------------------------------------------- | ---------------------- |
| Feature disabled                                    | `422`                          | `{ "message": "AI suggestions are switched off on this site.", "titles": [] }`           | Controls hidden        |
| Unauthorised / guest                                | `401` (`403` on some surfaces) | framework JSON                                                                           | Control never rendered |
| Validation failure                                  | `422`                          | standard errors                                                                          | Inline message         |
| Rate limit hit                                      | `429`                          | `{ "message": "Too many suggestion requests...", "titles": [], "usernames": [] }`        | Inline message         |
| Missing key, timeout, non-2xx, structurally invalid response | `503`                   | `{ "message": "Suggestions are unavailable right now. Please try again shortly.", ... }` | Inline message |
| Well-formed `200` with empty/`null` `content` (reasoning model out of tokens, content filter) | `200` | local deterministic candidates | Normal list |
| Provider returned fewer usable lines than requested | `200`                  | live answer merged with local candidates                                                  | Normal list    |

Provider errors are normalised by `AiSuggestionException`; no third-party text is echoed into
the admin panel. A `200` that carries a valid `message` but no `content` is **not** an outage:
the client returns no candidates, logs a classified warning, and the domain service fills the
list with its deterministic local candidates. Only a response with no recognisable
`choices.0.message` is treated as `malformed_completion`. Empty answers are deliberately
**not cached**, so the next click can succeed once `max_tokens` or the model is corrected.

Because every failure deliberately looks identical in the UI, the **server log** carries a
`cause` label alongside the warning `AI suggestion request failed`:

| `cause`                 | Meaning                                                    | First thing to check                                     |
| ----------------------- | ---------------------------------------------------------- | -------------------------------------------------------- |
| `missing_key`           | `ai.key` resolved to an empty string                       | Secret is present and config cache was cleared           |
| `connection_timeout`    | The completion exceeded `ai.timeout`                       | Raise `AI_SUGGESTIONS_TIMEOUT`; check provider latency   |
| `connection_error`      | DNS / TLS / refused connection                              | Egress firewall, base URL, provider status               |
| `provider_http_<code>`  | Non-2xx from the provider (e.g. `provider_http_401`)       | Key validity, model access, quota, request shape         |
| `malformed_completion`  | HTTP 200 but `choices.0.message` was missing/not an object | Model behaviour, content filters, response format        |
| `unknown`               | A failure raised elsewhere without a classified cause      | Stack trace / surrounding log lines                      |

A weaker outcome that does **not** raise an exception is logged as
`AI suggestion returned no usable content` with its own label:

| `cause`                     | Meaning                                                            | Fix                                         |
| --------------------------- | ------------------------------------------------------------------ | ------------------------------------------- |
| `completion_truncated`      | `finish_reason: length` — the token budget ran out before the answer | Raise `AI_SUGGESTIONS_MAX_TOKENS` to 700+   |
| `reasoning_only_completion` | Only a `reasoning` / `reasoning_details` field came back            | Raise tokens or use a non-reasoning model   |
| `empty_completion`          | Well-formed message with empty content and no other signal         | Check content filters / model behaviour     |

No provider text is written to either log line — only fixed labels. This is what makes a
`503` diagnosable: without it, "valid config but still unavailable" and "no key at all"
were indistinguishable to an operator.

---

## 7. Cost and abuse controls

- **Explicit click only** — no keystroke or autosave triggers.
- **`ai.throttle` middleware** — `RateLimiter` keyed by user ID + IP, default 10/minute, applied to both suggestion routes. This is separate from `ThrottleUsernameAvailability`, which covers the three submit routes.
- **Cache** — a SHA-256 fingerprint of the brief (system + prompt + model + caps + temperature + fallbacks) is cached for `ai.cache_ttl` seconds. A second click on an unchanged draft is free. Failures **and empty answers** are not cached.
- **Caps** — 2000 characters of prose, a `max_tokens` ceiling (700 default, raised automatically from the kind's length ceiling so long-copy batches are not truncated mid-list), a 20-second completion budget with a 5-second connect cap, at most 40 parsed candidate lines.
- **Config-only prompt settings** — a caller cannot widen the prompt or raise the token ceiling from the browser.

---

## 8. Enabling in production — checklist

1. Confirm the provider's data-processing terms cover sending unpublished draft excerpts to a third party.
2. Store `AI_SUGGESTIONS_KEY` in the platform secrets manager; confirm it is absent from `.env` files under version control.
3. Set `AI_SUGGESTIONS_ENABLED=true` and, if not using OpenAI, set `AI_SUGGESTIONS_BASE_URL` / `AI_SUGGESTIONS_MODEL` to an endpoint that speaks `POST {base}/chat/completions`. If the model is a reasoning model, keep `AI_SUGGESTIONS_MAX_TOKENS` at 700 or higher.
4. `php artisan config:clear` (and re-warm `config:cache` if the site uses one).
5. Confirm the cache store is shared and healthy; a working `ai.cache_ttl` matters for cost.
6. Smoke-test as an admin: open a post draft, click **Suggest titles with AI**, apply one; open a user form, click **Suggest available usernames with AI**, apply one and save.
7. Watch logs for `AI suggestion request failed` warnings over the first hour; the `cause` field distinguishes a slow model from a bad key.
8. Confirm the disclosure text under the title control is still present in the deployed Blade output.


To roll back, set `AI_SUGGESTIONS_ENABLED=false` and clear config. No migration, no data cleanup, and no cache flush is required — cached suggestions simply stop being read.

---

## 9. Testing

```bash
php artisan config:clear
php artisan test --filter='Suggestion|BlogTaxonomyTest'
composer test          # config:clear + pint --parallel --test + php artisan test
npm run build
```

> **Repository policy:** `.gitignore` excludes `/tests` and `/phpunit.xml`  
> ("tests are for local development only and are not shipped to production").  
> The test suite is therefore a **local** gate, not a CI one, and the new test  
> files are untracked by design. CI runs `composer lint:check`,  
> `php artisan view:cache` (every Blade template compiles) and `npm run build`.  
> If the team wants the suite shipped and gated in CI, un-ignore those two paths  
> and add `composer test` back to `.github/workflows/lint.yml` in one deliberate  
> change — do not do it piecemeal, because a checkout without `phpunit.xml`  
> cannot run `php artisan test`.

Coverage map:

| Test                                              | Covers                                                                                                |
| ------------------------------------------------- | ----------------------------------------------------------------------------------------------------- |
| `tests/Feature/Admin/PostTitleSuggestionTest.php` | Context building, redaction, caps, permissions, failures, rendering                                   |
| `tests/Feature/Admin/ExcerptSuggestionTest.php`   | Excerpt endpoint: redaction, caps, current-value exclusion, permissions, failures, rendering          |
| `tests/Feature/Admin/SeoMetaSuggestionTest.php`   | Meta endpoint: field/route allowlists, server-resolved labels, SERP caps, redaction, panels           |
| `tests/Feature/Admin/BlockSuggestionTest.php`     | Block endpoint: per-type field allowlist, per-kind caps, redaction, current-value exclusion, panels   |
| `tests/Feature/Admin/AboutSuggestionTest.php`     | About Us endpoint: field allowlist, caps, cross-field grounding, redaction, panels                    |
| `tests/Feature/Admin/AiToneSettingTest.php`       | Company default voice: settings rendering toggle, persistence, allowlist, absent-key guard            |
| `tests/Feature/Admin/UsernameSuggestionTest.php`  | Availability, reserved/invalid/taken filtering, edit surfaces, rate limiting, redaction, XSS firewall |
| `tests/Feature/Admin/BlogTaxonomyTest.php`        | Both dialogs, description persistence, validation, slug collisions                                    |
| `tests/Unit/SuggestionRequestTest.php`            | Fingerprint stability/sensitivity, no prompt in cache key                                             |
| `tests/Unit/SuggestionBrokerTest.php`             | Flag short-circuit, cache hit/miss, failure logging without prompts                                   |
| `tests/Unit/OpenAiCompatibleClientTest.php`       | Request shape, parsing, 40-line cap, all failure modes                                                |
| `tests/Unit/AiServiceProviderTest.php`            | Offline-by-default client resolution                                                                  |

All AI tests use `Http::preventStrayRequests()`, `Http::fake()` and `NullSuggestionClient`. **No test reaches a real provider or requires a real key.**

### Known baseline (pre-existing, unrelated)

The repository currently has **13 failing tests** that predate this feature, all in  
`tests/Feature/DashboardTest.php` and `tests/Feature/Settings/{ProfileUpdateTest,SecurityTest,SmtpSettingTest}.php`.  
They reference starter-kit artefacts this application replaced — Livewire page  
components such as `pages::settings.profile` / `pages::settings.security`, and  
named routes such as `smtp.index` and `profile.edit`. Failure messages are  
`ComponentNotFoundException` / `RouteNotFoundException`, not assertion failures.  
They were present before any AI work and are unchanged by it (baseline: 880  
passed / 13 failed; after this feature: 927 passed / 13 failed). They need to be  
rewritten against the current admin settings screens or removed before a full  
`php artisan test` CI job can be green. They are deliberately left untouched by  
this release.

---

## 10. Troubleshooting

| Symptom                                                     | Likely cause                                                 | Action                                                                    |
| ----------------------------------------------------------- | ------------------------------------------------------------ | ------------------------------------------------------------------------- |
| Controls missing                                            | `ai.enabled` false, or config cached                         | Check `.env`, run `php artisan config:clear`                              |
| `422 AI suggestions are switched off` on a rendered control | Config changed after page render                             | Reload the page, re-check `ai.enabled`                                    |
| `503` on every click, config otherwise valid                | **Timeout too short** (the classic one: a healthy provider answering in ~3s against a 3s cap) | Set `AI_SUGGESTIONS_TIMEOUT=20`, run `php artisan config:clear`, retry; verify `cause` is `connection_timeout` in the log |
| `503` on every click                                        | Missing key, wrong base URL, firewalled egress, provider 4xx | Read the `cause` in the `AI suggestion request failed` warning, then act on the table in §6 |
| `200` but suggestions look mechanical (`name-2`, `name-3`) | Reasoning-only / truncated completion, **or** the offline client is active | Check log for `AI suggestion returned no usable content`; raise `AI_SUGGESTIONS_MAX_TOKENS` to 700+, or switch to a non-reasoning model; then confirm `ai.key` is non-empty |
| Edited `.env` but nothing changed                           | `bootstrap/cache/config.php` exists (`config:cache` was run) | `php artisan config:clear` in dev, or re-run `php artisan config:cache` after the edit |
| Provider answers `200` with `content: null`                 | Token budget consumed by the `reasoning` field              | Raise `AI_SUGGESTIONS_MAX_TOKENS`; verify `finish_reason` becomes `stop`  |
| No network call at all, suggestions always mechanical      | Offline `NullSuggestionClient` is active                    | Check `ai.key` is non-empty and `ai.provider` is `openai-compatible`      |
| `429`                                                       | Rate limit                                                   | Wait a minute or raise `AI_SUGGESTIONS_RATE_LIMIT`                        |
| Same suggestions on every draft                             | Cache                                                        | Wait `AI_SUGGESTIONS_CACHE_TTL` seconds or change the draft               |
| Applied username rejected on save                           | Someone claimed it in between                                | Expected — pick another; submit-time `Username::rules()` is authoritative |

---

## 11. File index

| Path                                                                                       | Responsibility                                                          |
| ------------------------------------------------------------------------------------------ | ----------------------------------------------------------------------- |
| `config/ai.php`                                                                            | All AI settings                                                         |
| `.env.example`                                                                             | Documented environment variables                                        |
| `app/Providers/AiServiceProvider.php`                                                      | Live vs offline client binding                                          |
| `app/Services/Ai/Contracts/SuggestionClient.php`                                           | Provider boundary                                                       |
| `app/Services/Ai/NullSuggestionClient.php`                                                 | Offline deterministic client                                            |
| `app/Services/Ai/OpenAiCompatibleClient.php`                                               | HTTP chat-completions client                                            |
| `app/Services/Ai/SuggestionBroker.php`                                                     | Flag, cache, failure logging                                            |
| `app/Services/Ai/AiSuggestionException.php`                                                | User-safe failure, HTTP status, log-only `cause`                        |
| `app/Services/Ai/DataTransferObjects/*`                                                    | Request, result, title and copy contexts                              |
| `app/Concerns/PreparesAiPromptText.php`                                                    | Shared redaction + block flattening for all context builders          |
| `app/Actions/Content/BuildTitleSuggestionContextAction.php`                                | Server-built redacted title brief                                       |
| `app/Actions/Content/BuildExcerptSuggestionContextAction.php`                              | Server-built redacted excerpt brief                                     |
| `app/Actions/Content/BuildSeoMetaSuggestionContextAction.php`                              | Server-resolved SEO meta brief (page label or site defaults)            |
| `app/Actions/Content/BuildBlockSuggestionContextAction.php`                                | Server-resolved block copy brief (per-type field map, placement)        |
| `app/Actions/Content/BuildAboutSuggestionContextAction.php`                                | Server-resolved About Us brief (other fields as grounding prose)        |
| `app/Services/PostTitleSuggestionService.php`                                              | Title candidate validation                                              |
| `app/Services/CopySuggestionService.php`                                                   | Excerpt / meta candidate validation                                     |
| `app/Services/UsernameSuggestionService.php`                                               | Username availability filtering                                         |
| `app/Http/Requests/Admin/*SuggestionRequest.php`                                           | Payload validation                                                      |
| `app/Http/Controllers/Admin/*SuggestionController.php`                                     | JSON endpoints                                                          |
| `app/Http/Middleware/ThrottleAiSuggestions.php`                                            | Per-user/IP limiter                                                     |
| `routes/admin.php`                                                                         | Route + middleware registration                                         |
| `resources/views/admin/blog/posts/partials/title-suggestions.blade.php`                    | Title control                                                           |
| `resources/views/components/admin/username-suggestions.blade.php`                          | Username control                                                        |
| `resources/views/components/admin/copy-suggestions.blade.php`                              | Shared excerpt / SEO meta / block / About Us control (`copySuggestions()` Alpine data) |
| `resources/views/components/admin/quick-add-term-modal.blade.php`                          | Shared taxonomy dialog shell                                            |
| `resources/views/components/admin/new-category-modal.blade.php`, `new-tag-modal.blade.php` | The two dialogs                                                         |
| `resources/js/admin.js`                                                                    | `taxonomy()`, `titleSuggestions()`, `usernameSuggestions()`, `copySuggestions()` Alpine data |
| `app/Services/Settings/SettingsManager.php`, `app/Trait/CompanyValidationRules.php`, `app/Services/CompanySettingService.php` | `ai_tone` company default: seed, validate, persist |
| `.qoder/skills/ai-opportunity-scan/`                                                       | Repeatable AI opportunity audit skill (see §14)                       |

---

## 12. Dashboard suggestions (nudges) — no provider involved

The **Suggestions** band on the day-desk dashboard and System Overview is a separate, rule-based feature. It makes **no AI provider calls**: messages are fixed templates, so it costs nothing and sends nothing off-server. It shares the `ai.enabled` master switch — with the flag off, the scanner is a no-op, the bands are hidden, and the action endpoints answer `404`.

- **Scanner:** `php artisan nudges:scan`, scheduled hourly (`withoutOverlapping(10)`, `onOneServer`, log at `storage/logs/nudges-scan.log`). Seven rules: stale pending quotes (> 48h), unconfirmed bookings inside 7 days (warn inside 48h), week-old drafts, pages missing SEO meta, active services without an image, gallery images without a description, and a blog quiet for 30+ days.
- **Store:** one `nudges` row per rule key. A SHA-256 fingerprint of the signal payload prevents churn; when the signal clears the row resolves, and a recurrence opens it again. Dismissal is sticky for the current occurrence; snoozing hides the row for `ai.nudges.snooze_days` (3) days.
- **Display:** `NudgeService::visible()` shows only nudges whose `permission` the viewer holds, warn-first, capped at `ai.nudges.max_active` (3).
- **Actions:** `POST admin/nudges/{nudge}/dismiss` and `.../snooze` re-check the row's permission per request.
- **Config:** `config/ai.php` → `ai.nudges.*` (thresholds are code-level, not `.env`).
- **Tests:** `tests/Feature/Admin/NudgeTest.php` (20 tests).

---

## 13. Voice presets (tone personas)

Every copy-suggestion panel (excerpt, SEO meta, block, About Us) carries a small voice select next to the suggest button. The presets live in `config/ai.php` → `ai.tones`:

| Key            | Label         | Voice                                                                 |
| -------------- | ------------- | --------------------------------------------------------------------- |
| `professional` | Professional  | Polished, factual business voice; plain words, no slang or hype (default) |
| `friendly`     | Friendly      | Warm, conversational, like a helpful local expert; short sentences      |
| `seo`          | SEO-focused   | Leads with the subject keyword, works listed keywords in naturally, no stuffing |

- **Company default:** the `ai_tone` company setting seeds every panel's initial selection. Edit it under **Settings → General → AI Writing** (the block only renders while `ai.enabled` is true). It is validated against the configured preset keys and saved through the normal company-settings path.
- **Per-request override:** the panel sends the selected `tone` key with each request; the Form Requests validate it against `array_keys(config('ai.tones'))`. The browser never sends prompt text — the server appends the preset's one-sentence guidance to the kind's system prompt. An unknown or absent tone leaves the prompt unchanged.
- **Shape rules win:** tone steers voice only. Length ceilings, the plain-list format, redaction and current-value exclusion are identical for every preset.
- **Offline:** `NullSuggestionClient` ignores tone; deterministic local candidates are the same for every voice.
- **Cache:** the system prompt is part of the cache fingerprint, so switching voice is a cache miss, not a stale answer.
- **Tests:** tone guidance and validation are covered in each `*SuggestionTest.php`; the company default is covered by `tests/Feature/Admin/AiToneSettingTest.php`.

---

## 14. Opportunity scan skill

`.qoder/skills/ai-opportunity-scan/` is a repeatable, project-level agent skill that audits the codebase and proposes **ranked AI integration opportunities with file evidence**, grounded in the pipeline documented here. Invoke it with `/ai-opportunity-scan` (or ask "where could AI help in this codebase?").

- **Read-only:** it produces a report — ranked table plus per-opportunity evidence, pipeline fit, privacy notes and a human checkpoint — and never edits code or calls a provider.
- **Verify-first:** it re-checks the shipped map (`references/pipeline.md`) against the code on every run, so it never re-proposes something that already ships and never trusts a stale snapshot.
- **Guardrails baked in:** every proposal must reuse the existing flag/throttle/broker/redaction gates, keep a human review checkpoint, redact customer PII, and include the offline path and tests. Anything touching customer PII (quote/contact reply drafts, alt-text from images) is labelled "needs privacy review + separate approval" — that is the Phase 4 boundary.
