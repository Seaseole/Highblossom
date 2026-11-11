---
name: ai-opportunity-scan
description: Audit the Highblossom codebase and propose ranked AI integration opportunities with file evidence, grounded in the shipped ai.* suggestion pipeline. Use when asked "where could AI help", "scan for AI opportunities", "what should we automate with AI", or to re-run the AI roadmap review. Produces a report only — never implements.
---

# AI Opportunity Scan

Audit this repo and produce a ranked list of AI integration opportunities, each backed by file evidence and a concrete fit with the existing suggestion pipeline. This is a read-only review: propose, rank, stop. Implementation always needs separate, explicit approval.

## Workflow

1. **Load the shipped map.** Read `references/pipeline.md` (relative to this skill directory). It lists every AI surface that already exists and the extension recipe.
2. **Verify the map before trusting it.** It can drift. Spot-check with Grep/Read that the listed files still exist and no new suggestion endpoints, tones, or nudge signals have appeared since (`routes/admin.php` suggestion routes, `config/ai.php`, `app/Services/Ai/`). Never re-propose something that already ships — check first.
3. **Sweep candidate surfaces.** Look for repetitive human writing or judgement, not places to bolt on a chatbot:
   - Admin forms with rich text or repeated copy: posts, services, gallery, testimonials, About Us, homepage settings (`resources/views/admin/`).
   - Public/customer forms: quote, contact, booking (`app/Http/Controllers/`, `resources/views/`).
   - Content gaps the nudges already measure: missing SEO meta, missing alt text, stale drafts (`app/Services/NudgeService.php`).
   - Media uploads without alt text or captions (`app/Http/Controllers/Admin/ImageUploadController.php`, media library).
   - Staff API and scheduled commands (`routes/api.php`, `app/Console/Commands/`).
   Use SearchWorkspace for broad sweeps; Grep for targeted checks.
4. **Score each candidate** on the rubric below. Every claim about the code needs a `path:line` citation you actually read.
5. **Write the report** in the output format below. End with a recommended next slice and the approval it needs. Do not create files, edit code, or call any AI provider during the scan.

## Ranking rubric

Score each axis 1–3, then rank by (value + fit) − (effort + risk):

- **Value**: how much human time it saves or how much quality it adds, weekly.
- **Fit**: 3 = reuses `SuggestionBroker` + a context action + the shared panel as-is; 2 = needs a new domain service on the same broker; 1 = needs a new provider capability (e.g. vision) or a queue.
- **Effort**: 1 = one endpoint + panel + tests (the block/about pattern); 2 = new model/migration or provider capability; 3 = new subsystem.
- **Risk**: 3 = touches customer PII (quote/contact/booking data) or auto-publishes; 2 = sends staff-written content off-server; 1 = local-only or already-redacted admin copy.

Anything scoring Risk 3 is labelled **"needs privacy review + separate approval"** and must not be recommended as the next slice unless the user asks for it.

## Guardrails (apply to every proposal)

- Reuse the existing gates: `ai.enabled` flag, `ai.throttle` middleware, `SuggestionBroker` cache, `PreparesAiPromptText` redaction. No parallel pipeline, no new provider SDK.
- Human-in-the-loop always: suggestions are drafted, a person applies and saves. Never auto-publish, never auto-send a customer reply.
- Customer PII (names, emails, phones, addresses in quotes/contact messages) must be redacted before any prompt, and the proposal must say what is redacted.
- Every proposal includes the offline `NullSuggestionClient` path and feature tests; a provider outage must degrade to a readable message, never a 500.
- Token and latency budgets stay bounded (`ai.max_tokens`, `ai.timeout`, per-kind ceilings); long jobs go to the queue, not the request.

## Output format

```
## AI opportunity scan — <date>

Already shipping: <one line per verified surface, with route or command name>

| # | Opportunity | Value | Fit | Effort | Risk | Evidence |
|---|-------------|-------|-----|--------|------|----------|
| 1 | <name>      | 3     | 3   | 1      | 1    | path:line |

### 1. <name>
- What it does: <one sentence>
- Evidence: <path:line of the surface and the gap>
- Pipeline fit: <which existing pieces it reuses, what is new>
- Privacy: <what leaves the server, what is redacted>
- Human checkpoint: <where a person reviews before anything is saved/sent>
- Effort sketch: <files to add/change, test plan>

Recommended next slice: <name> — needs <approval scope>.
```

## Resources

- `references/pipeline.md` — the shipped AI pipeline map, extension recipe, tone presets, nudge system, and test idioms. Read it first, verify it second.