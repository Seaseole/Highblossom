---
name: Highblossom
description: Premium architectural glass and aluminum solutions.
register: product
---

# Highblossom Design System

## Overview
Highblossom is a premium architectural glass and aluminum provider. The design system reflects the precision, durability, and high-end nature of the products. It balances industrial brutalist utility with a premium dark aesthetic.

**Creative North Star:** "Architectural Precision as a Digital Interface"

**Key Characteristics:**
- High contrast and deep levels of black
- Glass-morphism and subtle transparencies
- Brutalist grid with strong borders
- High-performance motion and interactions
- Authoritative Swiss typography

## Colors

### Palette
The palette is dominated by deep neutrals with high-energy red accents to indicate brand identity and interaction.

- **Admin Background** (`oklch(14.47% 0.015 285.82)`): #0A0A0F. The deep base for all administrative surfaces.
- **Admin Surface** (`oklch(22.14% 0.015 285.82)`): #16161D. Used for cards and secondary panels.
- **Admin Accent** (`oklch(53.24% 0.225 25.12)`): #DC2626. High-energy red for primary actions and highlights.
- **Admin Text** (`oklch(98.11% 0.003 285.82)`): #FAFAFA. Off-white for maximum readability.
- **Admin Text Muted** (`oklch(69.59% 0.009 258.31)`): #A1A1AA. Secondary labels and body text.

### Named Rules
- **The Contrast Rule.** Avoid middle-gray values. Every surface must be either a deep black or a high-contrast white/accent to maintain the premium feel.
- **The State Rule.** Every interactive element must have a visible transition to the accent color on hover or focus.

## Typography

### Hierarchy
- **Headline Font:** Cabinet Grotesk. A bold, architectural sans-serif used for high-impact titles and navigation.
- **Body Font:** Geist. A clean, mono-influenced sans-serif optimized for data density and readability.

- **H1 (Large Display)** (Cabinet Grotesk, weight 700, size 2.25rem): Primary page titles.
- **H2 (Section Header)** (Cabinet Grotesk, weight 700, size 1.875rem): Major content groupings.
- **Body (Standard)** (Geist, weight 400, size 0.875rem): Standard UI text and data.
- **Label (Small)** (Geist, weight 600, size 0.75rem, uppercase): Metadata, badges, and table headers.

## Elevation

### Shadows
- **Accent Glow** (`box-shadow: 0 0 30px rgba(220, 38, 38, 0.4)`): Used on primary buttons and active states to create a "laser-cut" glow effect.
- **Surface Focus** (`box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1)`): Used for focus rings on inputs and interactive elements.

## Components

### Glass Cards
Premium containers mimicking the physical glass products.
- **Background:** `var(--color-admin-surface)` with `backdrop-filter: blur(20px)`
- **Border:** `1px solid var(--color-admin-border-subtle)`
- **Interaction:** Shifts to `var(--color-admin-surface-alt)` on hover.

### Premium Buttons
- **Shape:** `rounded-full` (9999px)
- **Background:** #DC2626 (Red)
- **Shadow:** Accent Glow on hover.
- **Motion:** `translateY(-2px)` on hover.

### Admin Table
- **Radius:** `rounded-2xl` (1.5rem)
- **Header:** 10% accent tint background.
- **Interaction:** Row hover adds 5% accent tint.

## Do's and Don'ts

### Do
- Use high-quality architectural photography.
- Maintain consistent 1px borders for grid structure.
- Use Geist Mono for numerical data or codes.
- Apply `backdrop-blur` to all floating panels.

### Don't
- Use soft, generic shadows on cards.
- Apply gradients to text or headings.
- Use more than one accent color per view.
- Use fluid typography that breaks architectural alignment.

---

# Design language v1 — the `ui` token layer

Introduced with the auth redesign (login and the seven screens around it) as the
reusable base for rolling this system across the public pages one at a time.

Everything below is **additive and opt-in**. It lives beside the legacy systems
above rather than replacing them, so no unmigrated page changes.

## How it coexists

One CSS bundle is shared by every surface: `layouts/site` and `layouts/auth-premium`
load `app.css` + `app.js`; `layouts/admin` loads `app.css` + `admin.css`. `app.css`
is therefore untouchable as a styling lever — repointing `--color-site-*`,
`--color-admin-*`, `--booking-*` or `--color-primary` restints site, admin and auth
in one edit.

| Layer | Namespace | Used by | Status |
|---|---|---|---|
| Material-3 / Flux | `--color-primary`, `--color-surface-container-*` | Livewire Flux components | frozen |
| Admin | `--color-admin-*` | admin panel | frozen |
| Public site | `--color-site-*` | public pages | frozen |
| Booking flow | `--booking-*` (scoped to `.booking-flow`) | quote + booking wizard | frozen |
| **Design language v1** | `--*-ui-*` | anything rendered through `x-layouts::base` | **active** |

Migrating a page means swapping its layout tag and replacing its components.
Nothing global changes, so a half-migrated app is a normal, safe state.

### Specificity rules that are not obvious

1. **`app.css` component classes are unlayered, and unlayered CSS beats
   `@layer utilities`.** So `focus:ring-*` on a new control loses to the global
   `*:focus-visible` rule in `app.css`. `resources/css/auth.css` answers it the same
   way: unlayered, but scoped under `.hb-ui`, which is only ever on a body rendered
   by `x-layouts::base`.
2. **`.ui-field` and `.ui-btn` are authoritative.** Add a utility class to an element
   carrying them and it will not win. Change the CSS once, or use the component prop.
3. **Alpine bindings go on plain elements.** `:disabled="submitting"` on a Blade
   component tag compiles as *PHP*, not as an Alpine binding. The two-factor screen
   therefore puts `.ui-field` / `.ui-btn` on raw `<input>` / `<button>` — same look,
   no compiled PHP.

## Tokens

Defined in `resources/css/app.css` (`@theme`) with dark values in the existing
`@layer theme { .dark { … } }` block. Contrast is stated because the tokens, not
the views, enforce it.

### Colour

| Token | Light | Dark | Contrast |
|---|---|---|---|
| `ui-bg` | `#f7f5f3` | `#0a0a0f` | page ground |
| `ui-surface` | `#ffffff` | `#121218` | card, field |
| `ui-surface-raised` | `#fbfaf9` | `#16161d` | hover fill, recovery panel |
| `ui-line` | `rgba(23,18,18,.10)` | `rgba(255,255,255,.07)` | hairline |
| `ui-line-strong` | `rgba(23,18,18,.20)` | `rgba(255,255,255,.14)` | field border |
| `ui-text` | `#1a1414` | `#fafafa` | 16.4:1 / 17:1 |
| `ui-muted` | `#71717a` | `#a1a1aa` | 4.6:1 / 6.6:1 — the only token allowed to carry copy |
| `ui-faint` | `#9a9aa2` | `#71717a` | icons, disabled, decoration. **Never text.** |
| `ui-accent` | `#dc2626` | `#dc2626` | fills. White label on it = 4.8:1 |
| `ui-accent-hover` | `#b91c1c` | `#b91c1c` | |
| `ui-accent-ink` | `#ffffff` | `#ffffff` | |
| `ui-accent-soft` | `rgba(220,38,38,.07)` | `rgba(220,38,38,.14)` | tints, focus halo |
| `ui-link` | `#dc2626` | `#f87171` | 4.8:1 / 6.1:1 — dark lightens to clear AA; `#dc2626` text on this ground is only 3.5:1 |
| `ui-danger` | `#b91c1c` | `#f87171` | field errors |
| `ui-success` | `#047857` | `#34d399` | |
| `ui-info` | `#0369a1` | `#38bdf8` | |
| `ui-warning` | `#b45309` | `#fbbf24` | |
| `ui-panel` | `#1c1917` | `#08070a` | brand column |
| `ui-on-panel` | `#fafaf9` | `#fafaf9` | 15.9:1 on panel |
| `ui-on-panel-muted` | `rgba(250,250,249,.66)` | `.60` | |
| `ui-on-panel-line` | `rgba(250,250,249,.13)` | `.10` | |

Accent is **red `#dc2626`** — what the site, admin and booking flow already render.
The declared oxblood `--color-primary: #73081d` is *not* used here; see "Brand colour
debt" below.

### Type

One family. **Geist**, weights 400/500/600/700, from Google Fonts with
`display=swap` and a real fallback stack.

> The "Headline Font: Cabinet Grotesk" rule at the top of this file has never
> worked: Google Fonts does not host it (the request returns 400 for that family
> and serves only Geist), so every display heading in the app has been falling back
> to the generic sans stack. v1 drops the display face and carries hierarchy on
> weight instead. Fixing `--font-headline` globally would change site + admin
> headings in one edit — deliberately deferred to rollout step 2.

| Token | Size / line-height | Weight | Use |
|---|---|---|---|
| `text-ui-xs` | 12 / 16 | 400–500 | labels, captions, footer |
| `text-ui-sm` | 13 / 18 | 400 | body, errors, helper links |
| `text-ui-md` | 15 / 22 | 400–600 | field text, buttons (default) |
| `text-ui-lg` | 17 / 24 | 500 | large button label, panel copy |
| `text-ui-xl` | 20 / 26 | 500 | panel line, OTP box digit |
| `text-ui-2xl` | 24 / 30 | 600 | card heading (`h1`) |
| `text-ui-3xl` | 30 / 36 | 600 | reserved for page heroes |

Three steps carry the hierarchy: `2xl`/600 heading → `md`/400 body →
`xs`/500 uppercase `tracking-[0.08em]` label. Labels are the only uppercase text.

### Spacing, radius, shadow, motion

- Spacing uses Tailwind's 4px ramp; the auth card is `p-8 sm:p-10`, fields sit on
  `space-y-5`, groups on `space-y-4`.
- Radius: `ui-xs` 6 · `ui-sm` 8 · `ui-md` 12 (fields, buttons, alerts) · `ui-lg` 16
  (cards) · `ui-xl` 20 · `ui-pill`. No `rounded-2xl`/`[2rem]` in new work.
- Shadow: `ui-xs` (fields) · `ui-sm` · `ui-card` (cards) · `ui-focus` (rings). Dark
  mode zeroes `xs`/`sm`/`card` — separation there comes from surface steps and
  hairlines, matching `.dark --shadow-site: 0 0 0`.
- Motion lives in `auth.css`: `--ui-dur-fast` 120ms · `base` 180ms · `slow` 240ms ·
  `enter` 320ms, with `--ease-ui-out cubic-bezier(.22,1,.36,1)`. One `ui-rise`
  entrance for the card — not a per-field stagger. Reduced motion is a single block
  in `auth.css`, never per component.

### Photographic hero surface — the one scoped exception

The homepage cinematic hero (`<x-hero.cinematic>`, `.hb-hero`) is the only place in
the app that paints type and cards over a photograph instead of a flat surface. The
`ui` rules above are written for the latter, so this surface is allowed four
deviations — and only these, only inside `.hb-hero`, and only through the
page-scoped `--hero-*` custom properties declared on that root (never in `app.css`,
never as globals, never touching `--*-ui-*`):

| Token | Value | Why the `ui` token cannot stand in |
|---|---|---|
| `--hero-radius` | 28px | The card is full-bleed at 1920px; `ui-xl` 20px reads as a chip at that span. |
| `--hero-word-weight` | 800 | The giant gradient word sits behind the subject; 700 disappears into the photo. Geist `400..900` is now in the site font request purely for this. |
| `--hero-on` / `--hero-on-muted` | `#fafafa` / `rgba(250,250,250,.72)` | Fixed light values: the text sits on the scrim, not on `--ui-surface`, so it must not flip with the theme. Contrast is carried by `--hero-scrim`, not by the ink. |
| `--hero-scrim` | bottom gradient `rgba(10,10,15,.86)` → transparent at 75%, plus a corner vignette | The only AA lever available over unknown photography. Body copy must land on the gradient's ≥ 60% opacity band. |

Rules that come with the exception:

- **Gradient-fill display type is legal here.** `background-clip: text` on the giant
  word is the exception to "no gradient text" — it is a photographic treatment of one
  decorative word (`aria-hidden`, never the `h1`), not a heading style.
- **Sizes stay on the ramp.** Anything that is not the giant word uses the existing
  `text-ui-*` steps; the word itself is `clamp()`-sized and must never force
  horizontal overflow at 360px.
- **Everything outside `.hb-hero` keeps the `ui` contract** — spacing, radius, shadow,
  theme-flipping ink. The header, announcement bar, footer and the sections below the
  hero are unchanged.
- **Motion reuses `--ease-ui-out`** and the `ui` durations; the hero adds no new ease.
  Reduced motion kills autoplay, count-up and the crossfade from one
  `prefers-reduced-motion` block inside the component.

## Components

In `resources/views/components/ui/`, addressed as `<x-ui.*>`.

| Component | Props | Notes |
|---|---|---|
| `button` | `variant=primary\|secondary\|ghost`, `size=sm\|md\|lg`, `block`, `href`, `type`, `disabled`, `loading`, `icon` | `href` renders an `<a>`. `loading="Signing in…"` opts into the vanilla pending mechanism and prevents double submit. |
| `input` | `name`, `id`, `type`, `value`, `reveal`, `invalid` | `name` is the validation key: it derives `aria-invalid`, `aria-describedby` and `old()`. Everything else passes through. |
| `field` | `label`, `for`, `field`, `required`, `hint` + `action` slot | Label + control + hint + inline errors. |
| `label` | `for`, `required` | |
| `card` | `tone=surface\|raised\|plain`, `padding=none\|sm\|md\|lg` | |
| `divider` | `label` | `aria-hidden`; the labelled form splits password from passkey. |
| `link` | `href`, `size`, `tone=accent\|muted\|strong`, `underline`, `external` | `external` adds `rel="noopener noreferrer"` — the legacy markup had `target="_blank"` alone. |
| `alert-banner` | `notification` | Page-level only. |
| `field-errors` | `field` | Field-level only. |
| `brand-mark` | `size=sm\|md\|lg\|xl` | DB `business_logo`, else `public/favicon.svg`. No generated artwork. |
| `checkbox` | unchanged | 43 admin usages. Theming is via the `--checkbox-label` hooks `auth.css` already sets. |
| `icon` | unchanged + new paths | Added `key`, `lock-closed`, `envelope`, `eye`, `eye-slash`, `bolt`, `arrow-left`, `arrow-right`, `arrow-refresh`, `exclamation-circle`. |

### Do (v1)
- Render through `x-layouts::base`; that puts `.hb-ui` on the body and opts the page in.
- Use `ui-muted` for any secondary copy at body size.
- One accent per view, and only on things you can act on.
- Let `x-ui.field` own error presentation; never hand-write a red border.
- Keep focus visible: `.hb-ui :focus-visible` draws `--shadow-ui-focus`; don't add
  `outline-none` without it.

### Don't (v1)
- Don't edit the legacy token groups to restyle a page.
- Don't put `:expr="…"` Alpine bindings on a component tag.
- Don't use `ui-faint` for text, or a placeholder colour below `ui-muted`.
- Don't add a display font, gradient text, backdrop blur, or hover lift to a card.
- Don't re-stagger entrances; one `ui-rise` per screen.

## DOM contracts (js/test-owned, not styling)

Restyle freely; **do not rename or remove** these.

| Hook | Owner |
|---|---|
| `#passkey-signin-btn`, `#passkey-signin-label` + `data-sign-in`/`-waiting`/`-unsupported` | `passkey-signin.js` |
| `#passkey-error` + `data-default` | `passkey-signin.js` |
| `[data-passkey-recovery="<reason>"]` × 4, `[data-passkey-signin]`, `hidden`-class toggling | `passkey-signin.js` |
| `meta[name=csrf-token]` | `passkey-signin.js` (primary token source; falls back to the XSRF cookie) |
| `[data-submit-pending-button]` + `[data-idle-state]` / `[data-pending-state]` | `submit-pending.js` |
| `[data-toggle-password="<id>"]`, `[data-icon-eye]`, `[data-icon-eye-off]`, `data-show/hide-label`, `aria-expanded`/`-controls` | `app.js` |
| `[data-generate-password]`, `[data-confirm-target]` | `app.js` |
| `#<field>-error` + `aria-describedby` | `x-ui.input` ↔ `x-ui.field-errors` |
| `[data-auth-alert]` | `AuthNotificationBannerTest` |
| `#pw-hint`, `passwordrules`, `data-min`/`data-max` | register inline script |
| field names `email`, `password`, `remember`, `code`, `recovery_code`, `token`, `terms`, `privacy` | Fortify |
| `novalidate` on all seven auth forms with a typed field (login, register, forgot, reset, consent, two-factor, confirm password) | `AuthValidationTest` — it is what stops native browser bubbles |
| `x-dialog="<flag>"` on an overlay's panel, `data-dialog-initial` on the control that should take focus, `data-dialog-close="<expr>"` when Escape must run something other than putting the flag back | `admin.js` — the one overlay contract: Tab stays in, Escape shuts it, the desk's scroller sleeps, focus returns to whatever asked. Any new modal wears this instead of writing its own trap |
| `form[data-confirm]` (+ optional `data-confirm-title`, `-yes`, `-danger`) | `admin.js` — a delegated capture-phase listener asks through `adminConfirm` and only then calls `form.submit()`. Native `confirm()` is not used anywhere in the admin |
| `window.adminConfirm({title, message, confirm, cancel, danger})` → `Promise<boolean>`, backed by `Alpine.store('adminConfirm')` and `<x-admin.confirm-dialog />` in the admin layout | `admin.js` — settles as a refusal on `livewire:navigated`, so a question can never be answered against a page that has moved on |
| any control that is not a stored value stays outside `.hb-field` (the passkeys add and rename boxes are raw `<input class="hb-input">`) | `admin-sheet.js` — it scores every `.hb-field` on the sheet, so a name typed into a list row would otherwise raise a phantom mark in the index rail |

## Validation is server-side, and the app owns the sentences

The brief's "do not change validation rules" line is **superseded for the auth forms** (user
instruction, 2026-10-19). Auth *logic*, routes, middleware, throttling and the credential
check are still untouched.

Every auth form with something to type carries `novalidate`, so the browser never pre-empts
the server with a native bubble. `required`, `autocomplete` and `inputmode` stay: they are what
accessibility tools and password managers read, not what validates the submit. Rules moved to
first-party classes, each with `messages()` + `attributes()` so an error names the box the user
is looking at and states the fix. Global `lang/en/validation.php` is deliberately unchanged —
the copy is per-form.

| Screen | Rules live in | What the user reads |
|---|---|---|
| Login | `App\Http\Requests\Auth\LoginRequest`, bound over Fortify's in `FortifyServiceProvider::register()` | `Enter your email address.` / `Enter a complete email address, like name@example.com.` / `Enter your password.` |
| Forgot password | `App\Http\Requests\Auth\SendPasswordResetLinkRequest`, bound the same way | `Enter the email address on your account.` + the login's format sentence |
| Register | `App\Actions\Fortify\CreateNewUser` (`MESSAGES`/`ATTRIBUTES` consts) | `That email address already has an account — sign in, or reset the password.` |
| Reset password | `App\Actions\Fortify\ResetUserPassword` | `The new password must be at least 12 characters.` (attribute renamed so it says "new password"), `The two passwords don’t match. Retype it in both boxes.` |
| Consent | `App\Http\Requests\ConsentRequest::withValidator()` | one sentence: `Tick both boxes to continue.` or `Tick the privacy policy box to continue.` |
| Confirm password | `App\Http\Responses\FailedPasswordConfirmationResponse` (Fortify's response contract, no request class exists here) | `Enter your password to continue.` when the box is empty, Fortify's own `The provided password was incorrect.` when it was wrong |

`bail` on each typed field keeps it to **one message per field** — without it a blank email
fails `required` *and* `email` and the field shows two sentences about one problem.

**Banner vs field.** `App\Support\AuthNotification` keeps them disjoint. A page-level sentence
(failed credentials, lockout, invalid token, the `passwords.*` replies) owns the banner;
everything else stays under its own input, wired with `aria-describedby`. A bag holding only
field errors now gets the neutral `Please check the highlighted fields.` rather than having one
field's sentence promoted into the banner — promoting it left that box red with nothing written
under it, which is the opposite of what the split is for.

`ConsentRequest::withValidator()` → `$validator->after()` is the request-class equivalent of a
Volt `$fail()` closure: it rewrites a set of per-field failures into one cross-field sentence.
Note `MessageBag::forget()` takes **one key** in this Laravel version; an array throws a
`TypeError`. Loop it.

Two deliberate omissions:

- **`remember` gets no rule.** A checkbox only ever sends `on`, `1`, or nothing, so there is no
  mistake a sentence could correct — and Laravel's `boolean` rule is strict enough to reject the
  browser's own `on` default, which would fail a valid sign-in attempt (it did, in
  `UserSessionTrackingTest`).
- **Two-factor `code` keeps Fortify's `nullable|string`.** Alpine auto-submits at six digits, so
  a typed-out partial code never reaches validation, and a bad code is already answered by a
  calm banner.

Enumeration: login returns exactly `These credentials do not match our records.` for a known
address with a wrong password and for an address that does not exist (`AuthValidationTest` pins
both counts). The **forgot-password screen is not symmetric** — `passwords.user` renders
"We can't find a user with that email address.", which confirms an account exists. That is
pre-existing framework copy, asserted by `AuthNotificationBannerTest`, and changing it is a
product decision rather than a restyle; flagged, not changed.

## Rate limiting is a status page, not a banner

Fortify's login throttle throws with status 429, so the sixth attempt is rendered by
`resources/views/errors/429.blade.php` — already a calm branded page, already asserted
by `AuthNotificationBannerTest::test_a_lockout_is_answered_by_the_throttle_response_not_the_banner`.
Attempts 1–5 flash the generic `auth.failed` banner; the seconds-remaining copy cannot
be surfaced inside the login view without changing exception handling, which is out of
scope for a visual pass.

## Rollout plan

Swap the layout, then the components. Never globally.

| # | Page group | Needs from v1 | Risks |
|---|---|---|---|
| 1 | Auth (done) | full set | none left |
| 2 | Contact, quote, booking | `field`, `input`, `button`, `alert-banner`, `checkbox` | `.booking-input` / `.booking-flow` CSS overlaps `ui-field`; decide per component. `site-booking.js` availability widget must survive. |
| 3 | Services, blog, gallery detail | `card`, `link`, `divider`, image surfaces | slug route binding, `@push` regions (content after a component close is never emitted), `media_url()` disk swapping |
| 4 | List/grid pages | `card` grid variant | visual only, but N+1s are already documented |
| 5 | Homepage | everything above | heaviest: ContentBlocks, parallax JS, SEO. Convert last. |

Deferred decisions that step 2 must settle: `--font-headline` (fix globally, which
changes site + admin headings), the oxblood-vs-red brand colour debt, and whether the
admin adopts v1 at all.

Dead code left in place for this pass: `layouts/auth/{card,simple,simple-split,split,split-simple}.blade.php`,
`components/auth-header.blade.php`, `components/auth-session-status.blade.php` (all
unreferenced), and `layouts/auth-premium.blade.php`, now a two-line alias. Delete when
nothing points at them.

## Verification checklist

Both themes (set `localStorage['hb-theme']` to `light`/`dark`/`system`), at 375, 768
and 1440.

- [ ] login: clean, error banner, field error, loading (double click), lockout (6th attempt)
- [ ] validation: submit each form blank, then with `eugene@` — one sentence per field, and no
      native browser bubble anywhere (one appears the moment a `novalidate` is dropped)
- [ ] consent unticked reads `Tick both boxes to continue.` once, not two field errors
- [ ] passkey: success, all four recovery reasons, unsupported browser (disabled + label swap)
- [ ] password reveal: type, both icons, `aria-label`, `aria-expanded`; Generate → reveal stays in sync
- [ ] remember me checked survives a failed attempt
- [ ] forgot → success banner; reset with valid token, and with an expired one
- [ ] two-factor: typing 6 digits auto-submits, caret tracks, recovery toggle, error border on bad code
- [ ] verify-email: resend success banner, logout
- [ ] confirm-password, consent, register (toggle on)
- [ ] already signed in: `/login` and `/dashboard` redirects, intended() after a gated route
- [ ] keyboard-only: tab order, every focus ring visible, no focus trap
- [ ] reduced motion: no rise/caret, states still legible
- [ ] regressions: public page and admin unchanged; `npm run build`, `php artisan test`

---

# The day desk — admin dashboard and System Overview

Built to the Awwwards craft bar (Design 40 / Usability 30 / Creativity 20 / Content 10) but
scaled to the brief: this is an operations surface for a glass shop in Gaborone, not a portfolio,
so it gets no loader, no WebGL and no cursor follower. The craft goes into type, measure,
composition and one signature moment.

## Brief

- **Subject:** `/dashboard` (the Overview row in the admin rail) and `/admin/system-overview`.
- **Audience:** the owner and the two or three staff who open it — on a phone in the yard, on a
  desktop in the office. They are not here to be impressed, they are here to decide what happens
  next.
- **Job of the page:** in under three seconds, say what today is, where in it you are, and what is
  late. Then how the pipeline and the week look.
- **Assets:** none. All content is real data already in the database (`bookings`, `inspections`,
  `quotes`, `booking_events`, `contact_messages`, `users`), read through models and settings.
- **Language:** English, `lang/en/admin-dashboard.php`.

## Concept: the three that were on the table

| | Idea | Anchor | Could it move to another brand? |
|---|---|---|---|
| **A. The day rail** | A job occupies the whole of its span, so the day is drawn as a measured rail: every booking is a bar whose width literally *is* its duration, and one hairline marks where the day has reached | `AvailabilityService` already refuses a slot that would run past closing, holds a bay for a 90-minute windscreen, and re-reads per-location capacity and closure dates. The rule is in the code, not invented for the page | 1/5 — a different company without computed slot spans has nothing to draw |
| B. Plate and pane | Each job identified by its registration and make, set like a Botswana number plate | `quotes.reg_number`, `bookings.vehicle_make/model/year` | 3/5 — any vehicle trade could take it |
| C. The glazing schedule | The whole page is one numbered specification document; every fact is a clause on a schedule | the shop's own job cards | 4/5 — it is a style, not a rule system |

**A wins**, on the anchor and because it generates decisions for layout (lanes and spans, not
cards), motion (set and lock along a scale), type (mono for anything measured), colour (red is the
cut line and nothing else) and copy ("14:30 to 16:00, Bay 1"). B is kept as one detail: the vehicle
chip inside a span. C is kept as discipline: numbered rows only where the sequence is real.

### Concept card

```
Idea:        Today is drawn as a measuring rail; every job is a span whose length is its real
             duration, and a hairline marks the minute you are in.
Metaphor:    A glazier's setting-out drawing — nothing is "a card", everything is placed against
             one scale, and a job that runs past closing does not exist.
Signature:   The Day Rail (clip-path wipe laid left-to-right along the scale + a now line that
             keeps advancing on the real clock).
Emotion:     Calm authority. You know where the day is before you read a word.
Motion verb: "measure and set" — things arrive at their measured place and lock; the rail draws,
             spans settle, figures roll up, the now line glides. Nothing bounces or floats.
Screenshot:  10:42 on a Tuesday: a 90-minute windscreen still open under the now line, the mobile
             lane empty until 14:00, and three quotes aged over a day stamped in amber below.
```

## Palette (roles, both themes)

No global token is repointed. The dashboard reads its own `--dash-*` values, defined once in
`admin.css` under `.hb-dash` and re-declared inside `html.dark`, so a light/dark switch flips the
whole surface through one place.

| Role | Light | Dark | Used for |
|---|---|---|---|
| `ground` | `#f8f9fa` (`--color-admin-bg`) | `#0a0a0f` | page behind the panels |
| `panel` | `#ffffff` | `#121218` | every panel face |
| `set` | `#f3f4f6` | `#16161d` | hover fill, the rail's track, inactive spans |
| `ink` | `#111827` | `#fafafa` | 16:1 on panel |
| `muted` | `#6b7280` | `#a1a1aa` | the only token allowed to carry copy (4.6:1 / 6.6:1) |
| `line` | `rgba(17,24,39,.10)` | `rgba(255,255,255,.08)` | hairlines, the scale ticks |
| `line-strong` | `rgba(17,24,39,.22)` | `rgba(255,255,255,.16)` | the rail baseline, focus rings |
| `cut` (accent) | `#dc2626` | `#dc2626` | **reserved**: in-progress span, the now line, focus. Never decorative |
| status | amber `#b45309`, green `#047857`, blue `#0369a1`, zinc `#6b7280` | `#fbbf24`, `#34d399`, `#38bdf8`, `#a1a1aa` | waiting / done / measuring / closed |

Dark values are lifted to clear AA where the light value would not (same rule that made `ui-link`
lighten in dark mode). Pure black and pure white are used only inside the panel pair, per the
Contrast Rule above.

## Type

Three roles, all already available to the admin bundle:

- **Geist 600** — `h1` at `clamp(2.15rem, 1.55rem + 1.9vw, 3.1rem)`, tracking `-0.03em`, leading
  `1.02`; section titles 17/24 at `-0.01em`.
- **Geist 400/500** — body and row text 15/22 and 13/18; labels 12/16, uppercase,
  `tracking-[0.08em]`. Labels are the only uppercase text.
- **Geist Mono 500/600** — every *measured* value: times, durations, counts, deltas, references,
  registration plates, `ms`. `font-variant-numeric: tabular-nums`. This makes the legacy rule
  "use Geist Mono for numerical data or codes" true for the first time.

Geist Mono is added to the admin font request (`display=swap`). `--font-mono` is **not** repointed
in `@theme`, because that would restint Flux and every other admin page; the dashboard uses a
scoped `.hb-dash-mono` class instead.

## Grid, rhythm, shape

12 columns inside `max-w-[86rem]`, gutter 24px (16px at phone). One alignment rule: every panel
shares the same left line and the same top hairline, and nothing is centred inside a panel.
Rhythm uses three moods, assigned on purpose: tight (12/16) for data rows and the colophon, normal
(24/32) for lists, airy (40/48) before the rail. Radius: **10px panels, 4px marks, no pill on a
panel**. No soft shadows anywhere; separation in dark comes from surface steps and hairlines, and
in light from a single 1px `line`.

## Composition plan (one layout family per section)

```
1  masthead      [split, 7/5]   h1 + welcome            |  measured day block: clock (mono, live),
                                 hairline between         date, hours or "Closed", bay + mobile capacity
2  DAY RAIL      [full 12, airy] scale 08:00–17:00 with hour ticks, two lanes (Bay / Mobile),
                                 spans width = real duration, now line, legend, "next job at"
3  attention     [asymmetric 7/5 paired with 4]          ordered index rows: type, who, aged, open
4  pipeline      [data 5]        stacked measure bar + four status figures + 14-day arrivals sparkline
5  seven days    [data, full 12] forward-looking bar row, today marked, weekday labels
6  mix + team    [split 6/6]     service mix distribution | staff load this week
7  activity      [vertical rail] BookingEvent milestones on a 24h scale (same clock as the rail)
8  colophon      [tight, full 12] measured-at, timezone, what is excluded, System Overview link
```

Phone (390px) is recomposed, not shrunk: the rail becomes a vertical timeline with the time in the
gutter (spans stop being positioned and become rows), masthead stacks, the pipeline measure bar stays
full-bleed, sections 6 and 7 stack, and the type ramp drops a step. The signature keeps working
because the ordering and the durations are the information, not the horizontal position.

## Motion language

One ease family, the project's own: `cubic-bezier(.22, 1, .36, 1)` (`--ease-ui-out`).

| Thing | Duration | Note |
|---|---|---|
| hover / press / focus | 160ms | feedback is never slower than that |
| a span settling on the rail | 320ms, stagger 40ms | `clip-path` wipe from its own start edge |
| a section entering | 220ms, 12px rise | once per section, in reading order |
| the now line | once a minute | position from the server's minute offset, advanced by the same clock |

Figures are never animated. A count-up was planned and removed on the second QA pass: it made the
headline numbers wrong for the first half-second, and a dashboard's numbers are its content.

Everything animates `transform`, `opacity`, `clip-path` and `width` of an absolutely positioned
span. No layout property of a flowing element moves, and no `filter`/`box-shadow` tweens.

**One clock, no new dependencies.** GSAP, Lenis and OGL are not installed here and are not added:
the repo and the deploy stay lean, and the shell already owns its scroll (`main.admin-main` is the
scroller, not the window, so Lenis would be wrong anyway). `resources/js/admin.js` gains one
dashboard module that owns a single `requestAnimationFrame` loop — the now line, the clock readout
and the day's state are its only consumers, and it stops outright when the shop is closed — plus an
`IntersectionObserver` rooted on `.admin-main` for the entries. The loop stops when the document is
hidden, unmounts on `livewire:navigated`, and never starts under `prefers-reduced-motion`.

**Idle motion is the concept itself.** The now line advances and the clock digits change because
the day is actually moving. Nothing decorative wobbles. On `/admin/system-overview` the page is
deliberately still at rest: its measurements are snapshots, and pretending a probe re-runs by
animating it would be a lie. Its life is carried by the calibration strip and the hover/focus
treatments. That is a written decision, not an oversight.

## Interaction treatments (one per role)

- **Rail span** (primary): it settles 2px toward the viewer, its own fill steps one surface level,
  and the duration caption that is clipped at rest unhides. Focus does the same thing. Touch gets
  the same states on `:active`, and the caption is always visible on phone.
- **Index row** (primary): the hairline under the row brightens to `line-strong`, the row's fill
  steps to `set`, and the trailing arrow nudges 4px along its direction.
- **Header link / "View all"** (secondary): underline draws from the left, no movement elsewhere.
- **Utility links** (colophon, footer): colour only. Nobody spends motion on a logout.
- **Re-measure / Measure now** (primary, System Overview): the icon rotates once on press and the
  label swaps to the loading string (already wired through `wire:loading`).

No custom cursor. The native cursor stays everywhere.

## Semantics and honesty

- One `h1` per page, ordered `h2` per section, `<time>` for every timestamp, `<dl>` for measured
  pairs, `<ol>` for the attention list, and the rail as an `<ol>` of real `<a>` links to the
  booking. Charts are `<svg>` marked `aria-hidden` with the same numbers present as text.
- Every number is a real count from this database. Deltas compare a real previous window and say
  which one. No percentage appears without its two absolutes.
- Sections and their links are permission-gated with the same abilities the routes require
  (`view bookings`, `view inspections`, `view contact messages`, `manage users`), so the dashboard
  cannot offer a 403.
- Empty states are designed and specific ("No jobs scheduled before closing. The next booking is
  Thursday 09:00, Bay 1."), never a dashed box.

## Constraints this page must keep

| Constraint | Why |
|---|---|
| `admin.css` is **unlayered** | any `display` declared there outranks `hidden`, `sm:hidden`, `lg:flex`. New rules set `position`, `width`, `clip-path`, `border`, `background` — and only declare `display` inside the media query that needs it |
| Alpine bindings go on plain elements | `:expr` on a component tag compiles as PHP |
| `@json` inside an attribute is unsafe here | local PHP ignores `JSON_HEX_QUOT`; use `data-*` written with `json_encode()` |
| Content after `</x-layouts::admin>` is never emitted | any `@push` must come before the close |
| `DashboardTest` asserts `/dashboard` is 200 for an authenticated user | keep the route and the view resolvable with an empty database |
| `SystemOverviewTest` asserts exact strings and `wire:poll.4s` | restyle freely, keep every label, button string and Livewire attribute |
| no cache writes for dashboard data | model objects serialise as `__PHP_Incomplete_Class` across the Laragon PHP runtimes, and settings caches are stale by default |
| queries must run on sqlite too | the suite refreshes a sqlite database; group in PHP over a bounded window instead of dialect-specific SQL |

## Genericness test

Given "make the admin dashboard awesome", a default model ships a row of four rounded stat cards,
a Chart.js line, and an activity list. What changed here: there is no stat-card row at all; the
headline object is the day's measured spans, which only exist because this codebase computes slot
durations and bay capacity; the accent red is rationed to three meanings; the radius rule killed
`rounded-3xl`; shadows are gone in favour of hairlines; mono is used only on measured values; and
every figure carries its own window ("last 7 days", "since 00:00").

## QA log

Measured with the Awwwards skill's `shoot.mjs` (Chromium, production build, `https://highblossom.test`,
authenticated as a throwaway Super Admin) plus `audit.mjs`. Both pages, desktop 1440, phone 390 at
4x CPU / slow 4G, reduced motion, keyboard and hover pairs.

### What the first pass found, and what was done

| Finding | Evidence | Fix |
|---|---|---|
| The stacked day bar printed on desktop as well as the scale | `d-01-hero`: two measures of the same day | The bar sat outside the rail's container, so `@container rail` could not see it. Moved inside `.hb-rail-wrap` |
| A 61-day-old quote aged as `1467h 03m` | `d-08-scroll` | `measureAge()` now switches to days past a day; durations keep hours |
| Text inside a running span fell to 1.34:1 on the red | axe contrast, `hb-span--in_progress .hb-span-cap .hb-muted` | Every word in that state is pure white (4.8:1) with no opacity; the plate border lightens with it |
| `label-content-name-mismatch` (serious) on spans, brand link, bell and user chip | axe `a11y` | Spans dropped `aria-label` for visible text plus an `sr-only` tail; the three shell controls now carry their visible words inside the name |
| Search field label at 4.39:1 | axe contrast | Darkened for that one control only; `--admin-nav-label` is used across the shell and was not moved |
| Tap targets of 18 and 20 px | `mobile.smallTargets` | `.hb-dash .hb-link` carries a 24 px box, the next-job link 32 px, a stacked span 44 px |
| No skip link | `keyboard.skipLinkFirst: false` | Added as the first stop in the admin shell, pointing at `#admin-content` (the shell's own scroller) |
| A sentence repeated in both lanes when the day was empty | copy | Two lane-specific empties: the bay is free, the mobile team is off the road |
| A dead `href="#"` when the reader could not open the next booking | markup | Renders as plain text without a link |
| Counters still rolling when the phone screenshot was taken | `m-01-hero` showed `0 m` | Roll cut to 420 ms and the span stagger cap to 240 ms |
| Google Fonts blocked the first paint and asked for a family that answers 400 | `loadMs`, one failed request | Geist and Geist Mono self-hosted in `public/fonts`, preloaded, the `<link>` removed from the admin head |

### Still true after the fixes, stated plainly

- **Throttled mobile LCP is dominated by the shell, not the desk.** The admin loads the shared
  `app.css` (60.6 kB gzipped, every Tailwind utility the site uses), `admin.css` (8 kB), the
  Livewire and Alpine runtime, and `admin.js` (12.5 kB). The desk's own layer is about 14 kB of
  CSS and one module inside `admin.js`. Cutting the first paint further means splitting the admin
  bundle, which is shell work this pass was not asked to do.
- **The desk is still at rest.** The now line and the clock advance from the real clock, which at
  540 minutes across 1078 pixels is about 1.5 px an hour. `idleMotion: false` is the honest result,
  not a miss: the alternative is a decoration that lies about the day. The hovers, the settle and
  the signature carry the life, which is the exception the standard allows.
- **The seven `hover.colorOnly` targets are all shell controls** (rail group headings, collapse,
  search, bell, fullscreen, user chip). The rail's group headings gained a chevron lean; the rest
  are icon utilities, where colour is a sufficient answer. Nothing in the desk is colour-only.

### Fixes taken outside the two pages, and why

Five shell files changed: `layouts/admin.blade.php` (font head, skip link, `#admin-content`,
`robots noindex`), `sidebar/brand.blade.php`, `topbar/notifications.blade.php`,
`topbar/user-menu.blade.php` (accessible names), plus one colour rule for the search label. Each is
an accessibility or first-paint correction that the desk's own pages are scored on, not a restyle:
no layout, spacing or component in the shell was redesigned.

### Second pass, read from the frames rather than the intent

| Finding | Where it showed | Fix |
|---|---|---|
| "Who carried the week" read `0 jobs` on a day with two appointments running | the window ended at the current minute, so this afternoon's work was outside it | Window now runs to the end of today |
| `3 3 sections need attention` | the count was printed beside a sentence that already carries it | Dropped the separate figure |
| `15h 30m hours on the glass` | the label repeated the unit the value already stated | Label is now "on the glass" |
| `38.46%` | two decimals is precision nobody acts on | Distribution shares round to whole percent; the bar keeps its exact width |
| The Application card had a hole under its footer | a short card stretched to meet a tall one in the grid | `align-items: start` |
| Hovers could answer up to 240 ms late | the settle's `transitionDelay` was never cleared, so it stayed on the span for the rest of the visit | The delay is removed when the span has landed |
| Actor, service and probe hints were set in `faint` | faint is the decoration token; these are copy | Moved to `muted`, and the type floor raised to 11 px |
| Screenshots of every scroll step showed the same first screen | the capture wheel-scrolls, and the pointer was parked over the sidebar | The capture script now parks the pointer inside the scroller (harness only, not the app) |

Numbers after both passes, production build, authenticated: one `h1` per page, no heading jumps,
zero console errors, zero failed requests of mine, `overflowX: false`, `reduced.hiddenText: 0`,
`a11y.serious: 0`, `noHoverChange: []`, desktop 60 fps, unthrottled mobile LCP 876 ms with CLS 0.
The throttled slow-4G LCP (12.9 s) is the shell's payload, not the desk's; see above.

### Third pass, after an outside critic scored the frames

A juror that has never seen the intent reads the screenshots, and two of its findings overturned
decisions written above.

- **The count-up is gone.** Figures were painted from `0` and eased to their value. That made the
  two most-read numbers on the page transiently wrong ("Waiting on you 0", "left in the day 9 m"),
  it is what pushed desktop CLS past 0.05 as the digit count changed width, and it kept the first
  screen moving for seconds after it had arrived. A dashboard's headline numbers are not a
  reveal. The motion language loses one device and keeps the settle, the wipe and the clock.
  `motion.md`'s "Counter (real numbers only)" recipe is therefore *not* used here.
- **The hover delay leaked.** The settle staggered spans with an inline `transitionDelay` that was
  never cleared, so a span six positions down the rail answered a pointer 240 ms late. Feedback
  that arrives late is not feedback; the delay is now removed when the span lands.
- **Bars got a reserved label band.** A bar at 100% put its own value and its weekday label inside
  itself, dark on red. The plot now has padding above and below the baseline, drawn by `::after`,
  so a label can never share its bar's pixel.
- **Re-measure answers in the verb.** The overview's signature action used to tint on hover. It now
  floods, inverts its label and turns the arrow through 180°, which is the page's own idea
  performed by its own button.
- **Dependencies stands full width.** Under the card it carries a searchable inventory; held to one
  column it squeezed the table and left the other half of the row blank.
- **The brand mark no longer breaks.** A `business_logo` pointing at a file that is not on disk
  painted a broken-image glyph beside the company name on every page of the panel and cost one
  failed request per view. The monogram is now always in the box and the stored logo sits over it,
  removing itself if it cannot load.
- **Copy corrected where it double-stated itself**: `3 3 sections need attention`,
  `15h 30m hours on the glass`, `38.46%`, and the overview's promise that "secrets are never
  listed here" next to the configured mail sender, which is not a secret but was not what the
  sentence said either.
- **Type floor raised** so no information sits at 10 px, and the `faint` token was pulled back to
  decoration only: actors, services and probe hints are `muted`.

One finding deliberately not acted on: the critic asked for OG and Twitter tags. These pages are
behind a sign-in, carry `noindex, nofollow`, and are disallowed in `robots.txt`; a social card for
an admin panel would be a card for a login wall.

### Fourth pass, from reading the frames rather than the reports

| Finding | Fix |
|---|---|
| The weekday row printed over the foot of every bar | The bar list now reserves a band under its baseline and the foot is anchored into it, so a label can never sit on a bar it names |
| Seven day columns at 390 px are about 45 px each, and "CLOSED" is wider than that | Below 46 rem the word is dropped: the hatch is the mark at that size, and the day's own link says in words whether it is today or closed |
| A large figure with an underline directly beneath it reads as a stray rule | Ledger values lose the resting underline and keep it on hover and focus. Inline links in copy keep theirs |
| "Busiest 1 Sun 9 May" | Now "Busiest Sun 9 May (1)": the date is the fact, the count is its measure |
| The skip link stayed 1x1 while focused | `sr-only` plus `focus:not-sr-only` loses in this bundle, where unlayered rules outrank utilities. The link is now a hand-written `.admin-skip` that clips itself off-screen and unclips on focus (verified 123x36 while focused) |
| The next-job link answered a pointer in colour alone | It nudges 3px, which is the verb |
| The bar's icon controls (search, bell, fullscreen, collapse, account) answered a pointer with a background shade | One pixel of lift, the same answer the desk's spans give. Nothing else about them changed |
| The overview's two-column card grid left a void beside whichever column ran out first | The cards pack down two columns instead of across rows, so the page ends where its content ends |

### Closed on the last pass

`hover.colorOnly` is empty on both pages, `noHoverChange` empty, `a11y.violations` empty,
`consoleErrors` empty, and the only `smallTargets` entry left is the skip link, which is 1x1 until
it is focused and 123x36 while focused. The bar rows, the weekday band and the ledger cells were
re-shot and read clean at 1440 and at 390.

### Fifth pass, from a juror that read the final frames

- **A real data bug, found by a date in a screenshot.** "Busiest Sun 9 May (1)" inside a window that
  ran from 22 October to 4 November. `$perDay` is keyed by date, so `array_search()` returns the
  *key*, and the code cast that key to an int and added it as a day count. The busiest day now
  parses the key it is given. The same pattern in the week row was already correct, which is why
  only one line was wrong.
- **A chip narrower than a name now drops the service, not the name.** `.hb-span` is its own
  container, and under 9.5 rem the service word goes; the hover caption still carries duration,
  fitter and plate. The caption truncates with an ellipsis instead of cutting "Runs 45 minutes" in
  half at the chip's edge, and the warning itself reads `45m past closing`.
- **The running chip failed contrast again, in a way the first fix missed.** The consolidated
  white-on-red rule listed six descendants and left out `.hb-span-who`, while an earlier rule still
  gave it `opacity: 0.8` — 3.53:1. The two older rules are gone; one block owns the state now.
  Verified 0 axe violations on both pages.
- **The overview's strip explains itself**: "A taller mark is a worse verdict. Each card below names
  its own." Bar height was encoding severity with nothing saying so.
- **Mobile LCP under the throttle fell from 13.6 s to about 11–12 s** as the font waterfall went
  away, and mobile CLS is 0.001. The remaining throttled number is the shell's payload.

### Known and not fixed, with the reason

- **Desktop CLS 0.088 / 0.105.** The shift sources are `div.space-y-1` at x=16, 223 px wide, going
  from 452 px to 40 px: that is the sidebar's collapsible groups, which the server renders open and
  Alpine closes on boot. The topbar contributes a smaller one when it swaps `Ctrl` for `⌘`. Both are
  shell behaviour that predates this work. The fix is a server-decided initial hidden state on
  `sidebar/group.blade.php` keyed on the `$hasActive` prop it already computes; it touches the rail
  shared by all 27 admin modules, so it is offered rather than shipped.
- **Throttled mobile LCP ~13 s.** The admin loads the shared `app.css` (60.6 kB gzipped: every
  Tailwind utility the public site uses), `admin.css` (8 kB), the Livewire and Alpine runtime, and
  `admin.js` (12.5 kB). Unthrottled, the same page measures LCP 876 ms at CLS 0 on a phone.
- **One failed request per admin view** for `storage/settings/<hash>.jpg`: the local database
  points at a logo file that is not on disk. The monogram fallback means nothing broken is drawn.
  A server-side existence check would remove the request too, but it costs a `stat` on every page
  and throws on object storage, which this project swaps in for the public disk.

---

# The scoring bench — Company Settings and Profile

Built to the same Awwwards craft bar as the day desk (Design 40 / Usability 30 / Creativity 20 /
Content 10) and on purpose: these are the two pages where a staff member changes what the public
site says and does, so the craft goes into legibility, feedback and one signature mark, not into
theatre. No loader, no WebGL, no cursor follower, no new dependency.

## Brief

- **Subject:** `/admin/settings` (Company Settings — 9 groups, ~115 inputs, 8 Alpine repeaters,
  one `<form>` posting to `admin.settings.update`) and `/admin/profile` (Profile Settings — 5
  groups, four separate forms, plus the `@livewire('passkeys')` island and the sessions partial).
- **Audience:** the owner and the two or three staff who edit the company's own copy, hours and
  branding. Same people as the day desk, same phone in the yard and same desktop in the office.
- **Job of the page:** change one value correctly and know that it took.
- **The real failure mode, which is what this design is about.** These two pages are the largest
  forms in the app and the only ones where a hundred and fifteen boxes share a single submit.
  So the honest problems are not "is this pretty", they are: *where is the thing*, *what else did
  I already touch*, and *did that save*. The current markup answers none of them — a change is
  invisible until the button is pressed, and after a redirect the page looks exactly as it did
  whether the save succeeded or was refused.
- **Assets:** none. Every value is a real row in `company_settings` or on the `users` record.
- **Language:** English, inline (this view has never used a lang file; introducing one for 115
  labels is a separate job and would churn every pinned string).

## Concept: the three that were on the table

| | Idea | Anchor | Could it move to another brand? |
|---|---|---|---|
| **A. The scoring bench** | A glazier measures, lays a straightedge, scores the pane, and only then breaks it: nothing here is installed until it is marked and set, so every box that differs from what the shop has stored carries a red score down its left edge | `admin.settings.update` is one PUT for the whole page, so a value genuinely sits in two states at once — the row in `company_settings` and the characters in the box. The mark *is* that delta, and it is computable in the browser | 1/5 — a page that saves each field on blur has nothing to mark; the concept only exists because one commit stands behind a hundred and fifteen inputs |
| B. The sample board | The company's own sample board: you run your hand along it and pick the name, the hours, the hero | the branding and homepage tabs really are the shop's public face | 4/5 — a presentation metaphor any settings screen could wear |
| C. The glazing schedule | The page is one numbered specification and every setting is a clause | settings do have keys and defaults in `SettingsManager::getDefaults()` | 3/5 — and the day desk already took the setting-out drawing; the schedule was rejected as a style once already, in this file |

**A wins**, on the anchor and because it turns the concept into the fix for the actual failure
mode. B survives as one detail: the theme and navigation pickers are samples, painted with the
values they set rather than described. C survives as discipline: a setting key is printed in mono
beside its label, because it is the name the value has in the code and the staff who report
problems use it.

### Concept card

```
Idea:        Nothing on this page is live until it is marked and set. A box that disagrees with
             what the shop has stored carries a red score down its left edge, and the group and
             the rail count how many scores are still open.
Metaphor:    A glazier's bench — measure, lay the straightedge, score, break. A mark that is not
             on the line is scrap, and a pane that is scored but not snapped is still on the table.
Signature:   The score line. `clip-path` draws a 2px rule top-to-bottom along the row's left edge
             the moment its value diverges, the group head and the rail pick up a mono count, and
             the sticky foot wakes with "3 marks on 2 groups".
Emotion:     Nothing is hidden from you. You can see what you have done before you do it.
Motion verb: "mark and set" — a rule is drawn, a value is placed against it and locks. Nothing
             floats, nothing fades up. On commit the scores are wiped away one group at a time.
Screenshot:  The Hours tab with two closed-shop days and a 45-minute duration marked red, the rail
             reading "Booking Rules 1 · Weekly Hours 1", and the foot saying 2 marks on 2 groups.
```

## Palette

**No new colours and no repointed token.** The bench reads the day desk's `--dash-*` values, so
`.hb-sheet` is added to the two token blocks in `admin-desk.css` (light and dark) and inherits
them. Consistency with `/dashboard` is a Design win, not a shortcut: it is the same shop, the same
people, the same screen.

One deliberate tightening against the desk. There the cut is rationed to three claims (fitting
now, the now line, focus). **Here it has exactly one claim: a value that is not what is stored.**
Focus, links, the primary button and everything decorative therefore leave red alone — focus uses
`--dash-line-strong` plus the shell's own `--color-ui-accent-soft` halo, which is a ring rather
than a fill, and the Save control is a filled `cut` face because pressing it *is* the act of
committing marks. Status keeps the desk's amber/green/blue/zinc, which are not the accent.

## Type

Three roles, all already self-hosted by the desk layer:

- **Geist 600** — `h1` at `clamp(1.9rem, 1.5rem + 1.15vw, 2.5rem)`, `-0.035em`. Group heads
  17/24 at `-0.01em`. Smaller than the desk's `h1` on purpose: a form's headline competes with its
  fields, a dashboard's does not.
- **Geist 400/500** — labels and control text 15/22, hints 13/18, group meta 12/16.
- **Geist Mono 500/600, `tabular-nums`** — every *measured* or *identifying* value: the setting key
  under its label, minutes and durations, field counts, mark counts, dates, the `env[KEY]` names,
  IP addresses in the sessions list, recovery codes. This continues the desk's rule rather than
  inventing a new one.

Labels are sentence case and are the exact strings the tests and the staff already know. The
uppercase `xs` label (`tracking-[0.08em]`) is reserved for the small caps that head a measure
column — "Group", "Marks", "Last set" — and nothing else.

## Grid, rhythm, shape

The page is one measure, and the measure is the unit of this design:

```
| 20px gutter | 13rem meta | 1fr control |
```

A row is a `<div class="hb-row">` holding a label + key + hint block in the measure column and the
control in the fluid column, with a hairline under the row and the score line at `inset-inline-start`
of the row box. This is what makes the page not look like every other settings screen: there is no
card per field, no label stacked above its input, and 115 fields read as one aligned sheet because
every control starts on the same vertical line. Under `@container sheet (max-width: 46rem)` the
measure column stops being a column and the label sits above the control, which is the only layout
that works at 390px.

Groups are `--dash-panel` faces with a hairline, a head (title, mono count, last-set), and rows.
Radius stays the desk's: 10px on a face, 4px on a mark, no pill on a panel. No shadows anywhere;
separation is hairlines in light and surface steps in dark.

Rhythm has three moods, assigned on purpose: 10px between a row's own label and control, 16px
between rows, 32px between groups. The index rail is a separate 12rem column that becomes a
horizontal strip on a phone.

## Composition plan

Settings, top to bottom. **The measured row is a system, not a section — see the exception below.**

```
1  head       [split]  h1 "Company Settings" + one-line lede  |  measured block: Last set (mono,
                     ...                                        elapsed from real updated_at), Groups 9,
                     Fields on this sheet 115, plus a "view what it changes" line
2  rail       [12rem]  the 9 groups, each: label, mono field count, a red tick + count when marked
3  panes      [1fr]    one group per pane, still inside the single <form> so one submit commits all
4  general    [rows]   identity, contacts, map, then "Account Security" as its own group head
5  hours      [rows]   7 day-rows with the Closed control and open/close pair inline
6  booking    [rows]   numbers in mono; Job Duration is a row per service type; closed dates → link
7  assets     [plate]  the logo/favicon get .hb-plate tiles with a "set / would be removed" state
8  homepage   [mix]    hero plate, style samples, then repeaters as numbered stacks (`.hb-stack`)
9  locale /   [rows]   selects and url fields; WhatsApp repeater is a stack
   social /
   notif.
10 announc.   [stack]  one card per announcement, with its own mark line
11 env        [rows]   locked-by-default rows with an "Edit value" affordance; the allowlist is the
                        only thing that appears, and the sheet says so
12 foot       [sticky] 0 marks / "n marks on m groups", "Show only marks", Save All Changes
13 colophon   [tight]  where these values land: links to the public pages they control, timezone,
                        and what this page does not touch
```

Profile reuses 1, 2, 4, 12 and 13 and adds:

```
1  identity   [split]  avatar plate + name at display size + role chips + verified dot-and-word
2  activity   [ledger] the desk's .hb-ledger for counts, then recent bookings and posts as rows
3  appearance [sample] three theme tiles painted with the real surface tokens, two nav tiles drawn
                        as the shape they give you
4  security   [state]  the password rows, then 2FA as a state card (dot + word + what it means),
                        the QR plate, recovery codes as mono rows
5  passkeys   [rows]   the Livewire island, unchanged DOM, re-skinned by the layer's own rules
6  sessions   [rows]   device rows with a mono IP, "This device" marked in the verb
```

**The exception, stated rather than hidden.** The awwwards method asks for a different layout
family per section. On a form that is wrong: variety between groups would mean a different label
placement per group, which is the fastest way to make 115 fields unreadable. So the repeated row is
the concept's own rule (one straightedge for the whole sheet), and the variation is placed where it
carries meaning — the plate faces, the repeater stacks, the sample tiles, the state card, and the
env rows. Every one of those is a different family, and each one exists because the content it
holds is genuinely a different kind of thing.

## Motion language

One ease, the desk's: `--dash-ease` `cubic-bezier(.22, 1, .36, 1)`, with `--dash-fast` 160ms,
`--dash-base` 220ms, `--dash-settle` 340ms.

| Thing | Duration | Note |
|---|---|---|
| a score being drawn | 180ms | `clip-path: inset(0 0 100% 0)` → `inset(0 0 0 0)`, top-down, on the row's own rule |
| a score being wiped (on commit) | 140ms | bottom-up, staggered 18ms by row so a group clears as one gesture |
| the foot waking | 220ms | `translateY(100%)` → `0`; it is the only thing that moves on the whole page besides the scores |
| hover / focus / press on any control | 160ms | never slower than that |
| a group entering on first paint | none | see below |

**No scroll reveals on this page.** A form whose fields arrive as you scroll is a form you cannot
tab through predictably, and the desk's `data-dash-reveal` exists because a dashboard is read in
bands. Every field is present and painted at once. The layer's reveal rule is therefore *not*
applied here, and the only entrance is the foot and the scores.

**One clock, no new dependencies.** `resources/js/admin-sheet.js` runs inside the existing
`admin.js` bundle. It owns no `requestAnimationFrame` loop of its own: it listens to `input`,
`change` and `click` on the form (delegated, so Alpine's `x-for` rows need no wiring), recomputes
the marks in one pass, and schedules the redraw through `requestAnimationFrame` *only* as a
coalescer, which it cancels immediately. The elapsed "Last set" readout uses a single `setTimeout`
aligned to the next minute boundary, stops when the document is hidden, and never runs when the
value is absent. Reduced motion kills the wipe, the draw, the foot's slide and the rail's width
transition, and leaves every mark, count and sentence exactly where it was — the state is carried
by colour and by text, never by movement.

**Idle motion is real, or it is nothing.** The one thing that changes while the visitor does
nothing is the "Last set" elapsed readout, and it changes because the shop's settings genuinely
were last set at a real `updated_at` on a real row. That is the desk's rule again: the concept
breathes only where the underlying fact is also moving. There is no decorative wobble.

## Interaction treatments (one per role)

- **A control at rest** (primary): hairline `--dash-line`, face `--dash-panel`. On hover the
  hairline steps to `--dash-line-strong` and the row's fill steps to `--dash-set` — the whole row
  answers, not the box, because the row is the object. Focus adds the shell's halo. The mark line
  thickens from 1px to 2px on hover when it is already scored.
- **A marked row** (the signature): score drawn at `inset-inline-start`, its label gains 6px of
  padding-inline-start so the text does not move under the line, and a mono "unsaved" tick appears
  in the measure column.
- **Rail row** (primary): fill steps to `--dash-set`, the group's count nudges 3px along its
  inline axis, and the active row keeps a 2px `--dash-line-strong` rule rather than an accent fill.
- **Save All Changes** (primary): floods and inverts its label, and the arrow in it turns through
  180° — it is the commit, so it performs the commit. Its disabled state at 0 marks is a face with
  no fill and the sentence "Nothing to set", which is the honest reading.
- **Show only marks** (primary toggle): the sheet filters; the control itself crosses between two
  words rather than changing colour alone.
- **Repeater add / remove** (secondary): the new row is drawn *in* from its own top edge with the
  same 180ms score ease, so adding a slide and marking a field are the same verb.
- **Utility links** (colophon, "open the closed dates page", the public-page links): colour only.
  Nobody spends motion on a logout or a footer.

No custom cursor; the native cursor stays everywhere. Nothing on touch depends on hover — every
state the hover carries is also carried by `:active` and by the printed count.

## Semantics and honesty

- One `<h1>` per page, an `<h2>` per group, `<dl>` for any measured pair, `<ol>` for repeaters and
  the sessions list, `<time datetime>` for every timestamp, and a real `<button>`/`<a>` for every
  action. Every control keeps its `<label for>`; the ones the old markup wrote as a bare
  `<label>` now point at an `id` that the view already knows.
- The mark counts are announced through one `aria-live="polite"` region in the foot, so a screen
  reader hears "2 marks on 2 groups" when a field changes and never hears 115 individual events.
- Marked rows are the *only* thing the filter hides, and it hides them with `display: none` so they
  also leave the tab order rather than being tabbable-but-invisible.
- No number appears without its unit, and no state appears in colour alone: every mark, verdict and
  on/off has a word beside it.

## Constraints this design must keep

| Constraint | Why |
|---|---|
| Every `name=`, hidden twin, `x-ref`, Alpine key and repeater `:name` template stays verbatim | ~90 of them are asserted by name in 13 test files; the form is one POST and the controller reads `tab` back out of it |
| Attribute **order** is pinned in two places | `name="enable_registration" value="1" checked` (regex, `EnableRegistrationSettingTest`) and `value="top" … checked` (`AdminNavLayoutTest`). Those two controls stay hand-written raw markup and are never converted to a component |
| `x-ref="enable2faForm"` and `recoveryCodes: [&quot;` must survive as literal text | `TwoFactorSettingsTest` asserts them in the served HTML, so the recovery codes stay escaped inside the `x-data` attribute and cannot move to a JSON script block |
| Exactly one `data-pw-for="password"` | `UsernameSignInTest` asserts `substr_count(...) === 1`; `<x-ui.password-rules>` appears once |
| Tab keys stay `general\|hours\|assets\|homepage\|localization\|social\|notifications\|announcements\|system_config` and `profile\|appearance\|security\|passkeys\|sessions`, under `?tab=` | `AdminTopbarTest` hard-codes `/admin/profile?tab=…` and the layout's `aria-current` line names the section from the query |
| Pinned visible strings are not renamed | `Account Security`, `Enable registration`, `Secondary Phone`, `Session idle timeout (minutes)`, `Default admin layout`, `Hero Layout`, `Email verified`, `Member since`, `Activity`, `Inspection notes`, `Blog posts`, `Recent blog posts`, `No blog posts yet.`, `Active devices`, `Safari 17.4 · iOS`, `Mobile`, `Sign out other devices`, `This device`, `Previously active`, `Signed out`, `Scan this QR code`, `Two-factor authentication is enabled.`, `Confirm Password`, `For your security`, `Desktop navigation`, `Top bar`, `Save All Changes` |
| New copy never contains `env[APP_KEY]`, `env[DB_PASSWORD]`, `env[FEATURES_REGISTRATION_ENABLED]` or `minLen` | four `assertDontSee` guards |
| `admin.css` is unlayered | `display` declared here outranks `hidden` and every `sm:` utility, so the layer sets `display` only on its own elements and puts `[hidden]` back in its rights, exactly as the desk layer does |
| Alpine bindings go on plain elements | `:expr` on a Blade component tag compiles as PHP |
| `@json()` inside an attribute is unsafe | local PHP ignores `JSON_HEX_QUOT`; the stored values the marks engine needs go through `data-*` written by `json_encode()` — and never for the env rows |
| Content after `</x-layouts::admin>` is never emitted | every `@push` precedes the close |
| The single `<form enctype="multipart/form-data">` and its `tab` hidden input stay | a file, a tab and 115 fields commit together or the page's own premise fails |
| The marks engine must survive a full page swap *and* `livewire:navigated` | the admin shell is a `@persist` island, so state is rebuilt per page rather than kept in a module singleton |
| Baseline is taken from the DOM, and a control whose row already carries a validation error is excluded from it | after a refused save the box shows rejected input, not stored input — so that row must read as uncommitted until it is fixed and committed, not as clean |

## Genericness test

Given "make the settings and profile pages awesome", a default model ships rounded cards with a
label above each input, a purple focus ring, a 3-across tab bar, and either nothing or a
fade-up-on-scroll. What changed here: the field is not a card, it is a measured row aligned to one
straightedge; the accent is not decoration, it is a claim about state that is false at rest and true
only when a value diverges; there is no scroll reveal at all on a page the method would normally
apply one to, and that is written down rather than quietly skipped; the counts, keys and durations
are mono because they are measured; the theme picker paints the values it sets instead of naming
them; the page tells you when it was last set from a real timestamp; and there is a way to see only
what you have changed on a 1,587-line form, which did not exist before and is the reason the concept
is worth having.


## Build notes, corrections and the QA log

Written after the pass, from what the measurements said rather than what the plan expected.

### Things the plan above got wrong, corrected in the build

- **The score does not "wipe away one group at a time."** A commit is a POST and a redirect, so
  the page reloads and every mark is simply gone. What retracts is a mark the person *undoes* by
  retyping a value back to what is stored, and it retracts to its top edge on the same 180ms draw.
  The staggered clear described above does not exist and would have been theatre over a page
  refresh.
- **Focus is ink, not the accent.** The plan said the shell's red halo would carry focus; on a
  sheet where red already means "unsaved", a red ring on every tab stop would have made the one
  claim mean two things. `.hb-sheet :focus-visible` is `2px solid var(--dash-ink)`.
- **The rail breakpoint is 66rem, not 74rem.** The content column at a 1440 viewport is 1120px
  (70rem) because the sidebar takes 256px, so a 74rem query never fired and desktop got the phone
  strip. Both pages are now measured against their own container: settings at `max-w-[82rem]`
  resolves to 70rem and gets the rail; profile at `max-w-[72rem]` gets it too.
- **A hint belongs under its control, not in the measure column.** Written above the line, a
  three-sentence hint wrapped into a paragraph in a 14rem column and read as a separate object.
  The measure column now carries the label and the key, and the explanation sits under the box it
  explains.
- **There is no "Undo my marks" control.** Restoring a baseline is easy for a text box and wrong
  for a repeater row that does not exist in the database yet, and a half-working undo is worse
  than none. Reload is the undo; the foot says what is unsaved rather than offering to lose it.
- **The filter is settings-only.** Five fields on screen do not need a "show only what I changed";
  a hundred and five do. The profile foot carries the sentence and the live region, not the toggle.
- **A switch carries no word.** Three of them were labelled "Active" or "Autoplay" beside a control
  that was off, which is a sentence that contradicts the thing it describes and cannot be made
  honest without a second state in the markup. Position and fill carry it; the row's own label says
  what it does ("Show the marquee"), and the hint under the control says what happens either way.
- **A row that contains rows is a container, not a field.** Eight repeaters are wrapped in a
  labelled row of their own, so the wrapper holds every control inside it. Left as a field it
  would have counted each change twice, drawn a score down nine rows at once, and made "Fields
  111" a number nobody could check. The engine asks the DOM on every pass rather than once at
  boot, because an empty repeater has no rows inside it yet — the slides wrapper is a field until
  the first slide is added, and a container from then on. `Fields` reads 105, which is the count
  of rows that actually hold a value.
- **The commit sentence is one string, server-rendered and engine-rendered alike**: "Nothing
  marked. Everything on this sheet is set." Four bespoke sentences were written first and all four
  were overwritten on the first repaint, which is what a single source of truth does.

### Bugs the pass found in things that were already there

| Found by | What it was | Now |
|---|---|---|
| Comparing the served HTML with the browser's answer | The sidebar's remembered open/closed branches are written to a cookie by the browser, but `AdminSidebar::remembered()` read it through `Request::cookie()`, which decrypts. A plaintext cookie never decrypts, so the server always answered "nothing remembered" and painted every branch that owns the page open. Alpine then closed the ones the person had closed. That snap was the largest layout shift anywhere in the panel | The cookie is on `EncryptCookies`' exception list in `bootstrap/app.php`, so the server's first frame is the remembered state. Verified by reading the served HTML: the branch bodies now arrive with an inline `display: none` on the ones that should be shut |
| Measuring CLS with the shift sources attached | `x-show` is inert until Alpine runs, so any element it hides is painted expanded on the first frame. The settings and profile panes each carried a hard-coded inline `display: none` on every pane *except* the first, so a deep link arrived blank and then filled in at 1.9s — CLS 0.582 on one page | The server decides which pane is open from the query, validated against the group list, and marks the others. An unknown tab value falls back to the first group instead of painting nothing |
| A click that did nothing | `sheetRefresh` boots on `DOMContentLoaded` and again on `livewire:navigated`, which Livewire also fires after an ordinary load. The module unbound its delegated listeners on teardown but not the button listeners, so the filter had two handlers and switched itself on and straight back off in one click | Every listener the module adds is recorded and removed on teardown |
| Reading the accessibility tree | The time inputs in the seven weekly-hours rows had no accessible name at all, and the repeater inputs inside the Alpine templates had none either, because their visible label is a paragraph. Both are on every settings page | `aria-label` on each, worded with the row's own subject, like "Monday opens" or "Slide word" |
| Reading the accessibility tree | Fourteen label elements in the old markup wrapped no control and pointed at no `id`, so every field on both pages was unnamed to a screen reader | Every control has a `for`/`id` pair now, derived from its own `name` |
| Looking at the appearance samples | The theme tiles painted their caption *inside* the swatch. On the Dark sample that put the ink token on `#0a0a0f`, which measured 1.11:1, and the Auto tile had no legible half at all | The swatch is only a picture now. Word, sub and the `set` mark sit on the panel, where the contrast rules hold |
| Grepping the shared components | `x-ui.checkbox` themes its tick with the accent red, which is fine everywhere else in the app and gave this page a second meaning for red | Inside `.hb-sheet` only, the tick is ink. The component is untouched: 43 other admin usages read it as red and should keep doing so |
| Counting the divs | The profile view was one closing div short before this pass, and the settings view briefly had two after the first pane rewrite | Both nest correctly now: the body holds the index and the panes, then the foot inside the form, then the colophon |

### Measurements, production build, authenticated, `https://highblossom.test`

`shoot.mjs` per page state (nine states), plus a shift probe with the sources attached and a
behavioural probe that drives the marks engine the way a person does.

- **Tests:** 477 passed / 12 failed across `tests/Feature/Admin`, `Auth` and `Settings`. All 12 are
  the known stale trio (`ProfileUpdateTest`, `SecurityTest`, `SmtpSettingTest`) failing on a route
  that does not exist and missing components; they were failing at the same count before a line was
  changed. `SessionPruningTest > the card reports valid and stale sessions separately` failed once
  inside the full suite and passed 18/18 twice in isolation — it is order dependent, not markup
  dependent.
- **Cumulative layout shift, desktop 1440:** dashboard 0.0000, System Overview 0.0000, settings
  0.0000, profile 0.0000. Before the two fixes above: 0.087, 0.087, 0.582 and 0.114. This is the
  first time any admin page in this project has measured zero.
- **Mobile 390:** CLS 0.001 to 0.02, no horizontal overflow, and the only sub-24px target on either
  page is the skip link, which is 1x1 until focused and 123x36 while focused.
- **axe:** zero violations on all nine page states, both themes.
- **Console:** zero errors and zero failed requests of mine on every state.
- **Keyboard:** skip link first, no invisible focus stops, no focus landing on an invisible element,
  nothing stuck.
- **Pointer:** `noHoverChange` empty, `covered` empty, and `colorOnly` empty once the commit control
  gained its arrow. Before that, the most important button on the page answered a pointer with a
  tint.
- **Reduced motion:** no hidden text, nothing moving. Every mark, count and sentence survives; only
  the draw, the foot's slide and the arrow's travel stop.
- **Frame rate:** 59 to 61 fps on desktop, 61 fps on a throttled phone, on a page of 105 inputs.
- **Marks engine, driven in the browser:** typing one value marks one row and one group; toggling a
  switch marks a second; the foot reads "2 marks on 2 groups not yet set"; retyping the first value
  back to what is stored unmarks it and the sentence drops to one; the filter leaves exactly the
  marked rows visible and takes the rest out of the tab order; adding a hero slide marks its eight
  fields as unsaved because none of them are in the database yet.
- **`audit.mjs` could not be used here** — it fetches HTML without a session, and these pages are
  behind a sign-in. The semantic checks that it would have made were run against the served HTML of
  all fourteen page states in an authenticated browser instead: one `h1` per page, no heading-level
  jumps, no duplicate `id`, no image without `alt`, no control without an accessible name except
  the shell's own search field, no placeholder copy, `lang` present, `robots` set to
  `noindex, nofollow`.
- **Titles:** nine settings groups and five profile groups previously shared two titles between
  fourteen URLs. The tab watcher now writes the group into `document.title`, so a stack of them in
  the browser history is distinguishable.

### Known and not fixed, with the reason

- **Throttled mobile LCP is 12 to 13 s.** Same cause as the day desk and unchanged by this work:
  the admin bundle carries `app.css` (60.6 kB gzipped, every Tailwind utility the public site
  uses), `admin.css` (now with two scoped layers in it), the Livewire and Alpine runtime, and
  `admin.js`. Unthrottled, the same pages measure LCP 1.0 to 1.3 s. Splitting the admin bundle is
  shell work.
- **`idleMotion: false`.** The one thing that moves by itself is the "Last set" elapsed readout,
  and it advances on a minute boundary because that is how often the sentence can honestly change.
  A five-second capture cannot see it, which is the correct result and not a miss. Nothing
  decorative wobbles to make the number read true.
- **The rail force-opens the branch that owns the page.** `adminNavGroup.syncOnNavigate()` says it
  only ever opens, because silently shutting a branch the person left open is worse than one that
  stays open after they leave it — so a branch you closed while standing in it comes back after a
  reload. The server's `open` and Alpine's rule disagree for that one case. It is pre-existing
  intent, it costs nothing measurable (the branch body is inside the rail's own scroller, so
  opening it moves no page content, and CLS is 0.0000 with it happening), and changing it is a
  product decision about the sidebar rather than part of a visual pass.
- **No OG or Twitter tags on either page.** Behind a sign-in, `noindex, nofollow`, disallowed in
  `robots.txt`. A social card for a settings screen would be a card for a login wall.

### Round two, from a juror that had never seen the intent

A fresh critic scored the frames of the nine page states. It read the idea correctly ("a *mark and
set* sheet ... the idea is legible and unmistakable"), weighted it 7.7 overall, and put Design,
Creativity and Content at 8, Accessibility at 9, Responsive at 8. Its fix list is below, with what
was done and what was not.

| Its finding | Evidence it cited | What happened |
|---|---|---|
| The commit control is saturated red even when nothing is marked, so the one loud thing on the screen says nothing is needed | `settings-general/d-01`, `profile-identity/d-01` | **Fixed.** The button is quiet — panel face, ink label — while the foot reports zero marks, and takes the mark colour the moment a value diverges. It stays clickable, because re-saving an unchanged sheet is a thing people do after moving a file on disk. Verified in the browser: `rgb(255,255,255)` at rest, `rgb(220,38,38)` with one mark, back to white when the mark is undone. |
| No frame shows what happens after Save, so the concept's payoff is asserted and never demonstrated | absence across the `x-` frames | **Fixed, and it found a real gap.** The controller has flashed `settings_saved` all along and the sheet never read it: the only answer to a commit was a toast in the shell's voice saying "Settings saved successfully." The foot now carries the result in the page's own verb — "Set. What is on this sheet is what the site serves." — painted from the flash, and retired the first time anything diverges again so it cannot survive into a new edit. Verified by an actual commit against the local database, with the values written back unchanged. |
| The sheet's own controls have no hover evidence; every sampled target was shell chrome | `hover.targets` on all nine states | **Partly an evidence gap, partly real.** The rows, group pills and the commit control did answer; a text control itself did not, because the design said "the row is the object". A person aims at the box, so the box now steps its border too. Re-measured directly: row `bg`, input `border`, pill `bg+border+color`, commit `bg+border`, colophon link colour only (a utility link). |
| Every page state shares one title within its URL | `desktop.title` on all nine | **Fixed.** The group goes into `document.title` on load as well as on change — a watcher only fires when something moves, and a deep link already knows where you are. `Hours - Company Settings - …`, `Security - Profile - …`. |
| The resting profile frame is a QA account: "Desk QA", `desk-qa@example.test`, `+1 555 000 1234` | `profile-identity/d-01` | **Not a placeholder in the product.** That is the throwaway Super Admin the capture harness signs in as, because a screenshot of an admin panel has to be a screenshot of someone. The same frame set taken as a real staff account reads correctly. Flagged rather than fixed. |
| The live Google Maps API key is printed in plaintext | `settings-general/d-03` | **Left alone, deliberately.** That key is shipped to every visitor's browser by design, which is why the row's own sentence says to restrict it in Google Cloud. Masking it in the admin would hide a value that is not secret and add a reveal step to the field people actually have to check. If the intent is "should this be here at all", that is a product decision, not a restyle. |
| Mobile LCP 10–41 s and `settings-branding` mobile CLS 0.068 | `mobile.vitals` | **The CLS is fixed, the LCP is not mine.** Branding went 0.068 → 0.020 by cloaking the two mutually exclusive button labels that were both painted until Alpine chose between them, and reserving the height of a masthead value that starts empty. The remaining 0.02 on every page is the shell's topbar icon changing size before paint. The throttled LCP is the admin bundle, unchanged by this work and written up under "Known and not fixed". |
| The sessions and passkeys panes are legacy cards inside the new sheet | `profile-sessions/d-01`, `profile-passkeys/d-01` | **Found on re-reading the frames, not by the critic, and fixed.** Both were islands of the old Tailwind look — nested rounded-3xl faces, a green chip, a dashed empty box — and the pane wrapper put a card inside a card with two headings for one subject. `sessions-tab.blade.php`, `x-admin.session-status`, `x-admin.session-device` and `livewire/passkeys.blade.php` are now on the layer. Every pinned string survived, and so did every `wire:*` hook, the `data-passkey-id`/`data-passkey-name` pair the rename handler reads, and the `window.Passkeys.register` path: 18 passkey, session and avatar tests green. One behaviour fixed on the way: Rename and Remove used to sit at `opacity: 0` until a pointer entered the row, which made them unreachable on a phone and invisible to a keyboard user standing on them. |
| A block head printed "0 fields" for a list with no fields | `profile-sessions/d-01` | **Fixed.** A number that describes nothing is not a measurement. The rail already stayed quiet about it; the head does now. |

After these, on the same harness and the same nine page states: zero axe violations, zero console
errors, no horizontal overflow, `noHoverChange` and `colorOnly` empty, `hiddenText: 0` under
reduced motion, desktop CLS 0.001–0.020, mobile CLS 0.001–0.020, 52–60 fps, and 485 tests passing
with only the twelve known stale failures.

### Round three, from a second fresh juror

A new critic, given only the frames of the eleven page states and no memory of the first, weighted
the work 7.3 and read the idea correctly again ("a *mark-and-set* ledger ... the idea is legible,
not a guess"). Two of its findings were real and are fixed; three were the evidence lying, and the
way the evidence lied is itself worth recording.

| Its finding | Verdict | What happened |
|---|---|---|
| `x-02-committed` shows the pre-edit values after a save, so the commit appears to revert | **The evidence lied.** Two different scripts produced the two frames: the marks frame left the value changed, the commit frame had restored it first so nothing would be written | Rebuilt as one continuous sequence in one browser session: type, shoot, submit, shoot. `08:00 → 09:30 → committed → 09:30` with the sentence flipping to "Set." and back to quiet. The shop's stored value was then written back to `08:00` and committed again, so the local database ends exactly where it was found. |
| An edited field leaves MARKS at 0 while a native browser bubble covers the sheet | **Half evidence, half real.** The 0 came from the harness setting `input.value` directly, which fires no event a person would fire — real typing marks correctly, verified. The bubble is real | The bubble stays, deliberately. `CompanySettingRequest` has no email rule, so `type="email"` is the only thing between a mistyped address and a saved one. Dropping in `novalidate` the way the auth screens do would have traded a visual inconsistency for a data bug. The gap it exposes is a server-side rule that does not exist yet, and that is a product decision, not a restyle. |
| The commit control and the group rail were never sampled in an `h-` pair | **Fair.** The harness picks its own targets and picked shell chrome | Measured directly instead: the commit control changes `backgroundColor` and `borderColor` and its arrow translates 4px; a rail row changes `background`, `border` and `color`; a text control changes its border; the colophon link changes colour only, which is what a utility link is allowed to do. |
| Five contrast misses on the sessions pane | **Real.** All five were `.hb-sep` — the `·` between a device and its IP, at 2.54:1 | The separator takes the muted step. It is decoration, but it sits inside copy, and a hairline that fails is a hairline that fails. |
| The profile title reads "Profile - Profile - app" | **Real**, and mine | Now "Security - My account - app". The group never repeats the surface it belongs to. |
| Throttled mobile LCP 10–11 s on every state | **True and not mine** | The admin bundle: `app.css` at 60.6 kB gzipped carrying every Tailwind utility the public site uses, plus `admin.css` with two scoped layers now in it, plus Livewire, Alpine and `admin.js`. Unthrottled the same states measure LCP 1.0–2.0 s. Splitting the admin bundle is shell work and is listed under "Known and not fixed". |

One more thing the frames showed, which neither critic named: until Alpine boots, the shell's own
navigation paints **both** icon variants of every group heading, because `x-show` is inert before
Alpine and `sidebar/group.blade.php` carries a solid and an outline copy of each icon with opposite
conditions. It is the same class of bug this pass fixed twice — on the settings panes and on the
branding button labels — and it is pre-existing shell markup, so it is written down rather than
changed here.

### Round four, and the two things the third juror found that were genuinely mine

| Finding | What was wrong | What is now true |
|---|---|---|
| An invalid field produced no mark while the bar claimed "Everything on this sheet is set", and a native browser bubble covered the sheet | Half of it was the harness — it set `input.value` directly, which fires no event a keyboard fires, and real typing marks correctly. The other half was real: the sheet had no answer at all to a value the browser refuses | A second claim, in a second colour. A refused field gets an **amber** rule and a sentence in its own row — "That is not an email address. It needs a name and a domain, like name@example.com." — and the bar says "1 field needs attention before this can be set." The browser's bubble is suppressed (`invalid` is caught on the capture phase and cancelled; the constraint still blocks the submit), the caret is moved to the field by hand because the bubble would have done it, and the refusal owns the state line until the field is edited again. Verified end to end: type `eugene-at-example`, submit, `invalidRows: 1`, `scoreColor: rgb(180, 83, 9)`, the sentence above; fix it, and the row returns to a clean sheet. |
| At 390 px the close time wrapped under the open time with "to" orphaned from its box | The hours cluster was one flex row that wrapped wherever it ran out of room | The open and close pair is one object and breaks as one: the day's state on the first line, `from 08:00 to 17:00` on the second, never between a word and its box. |
| The sheet's own primary controls appeared in no hover pair | The harness picks its own targets and picked shell chrome twice running | Directly measured and captured as `h-09` … `h-13`: rail row `background + border + color`, commit `background + border + color` with the arrow translating 4px, field row `background`, text control `border`, toggle inert at zero marks because it is disabled. |
| The focus ring on the rail was a thin pale outline | Not this layer. `.hb-sheet :focus-visible` is 2px of `--dash-ink` at 16:1; the pale ring is `app.css`'s global `*:focus-visible`, which the shell's own nav rows inherit | Left alone and named. It is the rail shared by all 27 modules, the same class of change as the sidebar one. |
| The avatar control rendered as a native "Choose File" | The file input was still taking `.hb-input`, which is built for text | `x-sheet.input` now gives a file control `.hb-file` — a dashed edge, a bordered button, and the accept list stated in the hint rather than implied. |
| Native number spinners | `type="number"` with `min`/`max`/`step` is what enforces 15-minute intervals and the 480-minute cap | Kept. Removing the spinner to make the frame cleaner would trade a detail for the rule. |

### What this pass wrote to the local database, and put back

Two of the verification sequences had to be real to be honest, because the claim under test was
"what happens after you press Save".

- The hours sequence changed Monday's open from `08:00` to `09:30`, committed it, screenshotted the
  result, then set it back to `08:00` and committed that. Final read: `08:00`, matching the value
  found.
- The refusal sequence changed `primary_email` to a malformed address, submitted, read the state,
  restored `info@highblossom.co.bw`, and committed. Final read: the original value.

Both write through the normal request and validation path, so `updated_at` on the rows touched moves
forward and the settings cache flushes on each save, as it does for any real edit. Nothing else was
written, and no value was left different from what it was found as.

---

# Round six: the ask had no face

The three profile panes the owner pointed at — Passkeys, Sessions, Security's two-factor block — and
"the UI modal is bad". They had been through the bench pass once already, so this was not a restyle:
it was a hunt for why they still read as unfinished. Every defect below was found by measuring the
live page, not by reading the code, and several were invisible in the markup.

## The one that mattered

`<x-admin.confirm-dialog />` is mounted in `layouts/admin.blade.php`. It is the single overlay every
destructive action on the desk asks through — signing out other devices, discarding a two-factor key,
removing a passkey, deleting an account, across all 27 modules. Its panel read
`background: var(--dash-panel)`.

`--dash-panel` is declared on `.hb-dash, .hb-desk, .hb-sheet`. The dialog is on none of them. A custom
property with no value makes `background` fall back to its initial value, which is **transparent**. So
the panel drew its heading and its sentence directly on top of whatever text was on the page behind it,
with only a backdrop to separate them. Measured: `backgroundColor: rgba(0, 0, 0, 0)`, and the border
resolved to the *dark* hairline on a light page, because that one had a global fallback and this one
did not.

The fix is not to repoint a global token, which is the rule this whole layer is built on. An overlay is
not on the sheet, so `.hb-modal` now declares the fourteen `--dash-*` values it consumes, light and
dark, on itself. It is correct wherever it is mounted, including in a module that has never heard of
the bench.

## The button ladder was three buttons deep and two wide

| Measured at rest | Was | Now |
|---|---|---|
| `.hb-btn--primary` | `--dash-cut` red fill — the mark's own colour, on a pane that has no marks to set | `--dash-ink` fill, `--dash-panel` label |
| `.hb-btn--primary:hover` | restated the rest rule. `Register a device`, `Confirm`, `Verify`, `Enable Two-Factor` — the four controls that matter on these panes — answered a pointer with **nothing** | a 2px ground ring and a 2px ink ring drawn around the face |
| `.hb-btn--danger` | `border-color: --dash-line-strong; color: --dash-ink` — the two properties the default button already has, so the irreversible control was pixel-identical to the one that does nothing | the same edge plus `inset 0 0 0 1px`, which reads as 2px where everything else reads as 1px |

The layer's own comment, written in the bench pass, already said a destructive control "says so with a
heavier edge". It did not. The comment was a plan that had not been implemented.

Red inside the sheet therefore keeps exactly one meaning on the panes — a value that is not what is
stored — and the commit control keeps its sanctioned second. Inside an **ask** there are no values and
no marks, so red is free again and now belongs on the answer that destroys: `Discard the key`, `Switch
it off`, `Sign them out`, `Delete it`. That is the split, and it is why the same class paints two ways
depending on where it sits.

## Focus was two indicators, and one of them lied

`app.css` draws `*:focus-visible { box-shadow: 0 0 0 4px rgba(220,38,38,.12), 0 0 0 2px
rgba(220,38,38,.22) }` unlayered and globally. The sheet draws `outline: 2px solid var(--dash-ink)`.
Both applied, so every focus stop arrived as a black rectangle inside a red glow — and red on this page
is the sentence "this value is not what is stored". The bench pass had already written "focus is ink,
not the accent" and then never removed the halo. Inside `.hb-sheet` and `.hb-modal`, `box-shadow` on
`:focus-visible` is now `none`.

## The setup pane was cutting its own input off

`x-sheet.input` brings the sheet's 14rem measure column with it. Inside `.hb-setup`, which is two
columns inside one block, each pane is about 440px, so the control was left roughly 170px and the
placeholder rendered as `Enter authentic` at the block's edge. The layer's stacking rule is keyed on
`@container dash (max-width: 46rem)` — the *page* container, which never sees a pane and stayed at
886px. So `.hb-setup__pane` is now its own container (`setup-pane`) and stacks its own fields below
34rem. Measured after: input 379px in a 427px pane, `scrollWidth === clientWidth`, nothing cut.

Two things went with it. The amber "Waiting for the first code" band moved out of the left pane to span
both columns, because it describes the job rather than one half of it, and the two `.hb-repeat-bar`
rows — one per form, each drawing its own hairline — became one bar holding both answers. The forms
stay separate elements, because they post to different endpoints with different CSRF tokens and one of
them carries the delegated ask; the buttons reach them with `form=`.

## Everything else found, and what it became

| Found by | What it was | Now |
|---|---|---|
| Reading the delete-account frame | It was the only overlay on the page that was not a dialog: no `x-dialog`, no `role`, no `aria-modal`, no label, no `data-dialog-initial`. Escape did nothing, Tab walked out into the sheet behind it, the desk kept scrolling under the backdrop, and the caret never arrived in the box | Full contract. Measured: focus lands in the password box, `scrollerLocked: true`, Escape closes and returns focus, `role="dialog"` named by its heading |
| Following a refused delete password | `ProfileController::destroy` does `back()->withErrors(['password' => …])`. The modal's flag lives only in Alpine, so a wrong answer came back **closed**, with one red sentence lost on a page of forty fields | The flag is seeded from the error bag, the sentence renders inside the dialog, the box takes `aria-invalid`. Safe because `password` is only ever the delete key here: the password update writes `current_password`, and the two-factor check answers as JSON and never reaches the session bag |
| Reading the modal's own copy | "Are you sure you want to delete your account? All of your data will be permanently removed. This action cannot be undone." — the starter kit's sentence, the only copy on the page not written in this design's voice, and an over-claim about data this code does not touch | What the code actually does, and what the person can act on: the record goes, every device signs out, only another Super Admin can put it back |
| Counting the dialog's focus stops | Two controls both named "Close" (the X and the footer button), the caret landing on the X, and `Copy` sitting among the dismissals | The X is `Close recovery codes`, the footer is `Copy all` (primary) and `Done`, and `Copy all` takes focus |
| Looking at the recovery codes | Four boxes, each with a 1px hairline, each looking exactly like a text input. None of them takes a caret | A numbered list on the filled `set` ground, no border, `user-select: all` kept |
| Comparing the two blocks on the sessions pane | `.hb-block__head` insets its text by `score + gutter` = 23px; `.hb-block__body` had **no** inline padding at all. Every list, bar and empty sentence started 23px left of its own heading | Rows take the inset as padding so the hover fill still spans the block; boxes take it as margin so their edge is not doubled |
| Opening the accessibility tree | `region` (moderate) on every admin page: the sidebar's brand row sat outside every landmark, because the rail's `<nav>` starts under it. `image-redundant-alt` on the same row: the logo's `alt` repeated the company name the link already said twice | `role="region" aria-label="Company"` on the desktop half only — the mobile copy is already inside a dialog, which is a region. The logo's `alt` is empty. Both rules now report zero violations |
| Reading the served HTML, not the DOM | All five profile URLs shipped the same `<title>`: "Profile - highblossom Pty Ltd". The bench pass had fixed this in `document.title`, which is Alpine, which a crawler and a first-paint screen reader never wait for | The group is decided server-side and goes into the layout tag. The `@php` block had to move **above** the tag: Blade evaluates a component's opening attributes before it runs the slot body, so `$profileTabs` did not exist yet |
| Comparing the frames with the plan | `Pinned visible strings are not renamed: … For your security` — the earlier uncommitted rewrite of the enable-2FA lede had dropped it, and `TwoFactorSettingsTest` asserts it | Restored as the lede's first clause, in the bench's voice rather than the kit's |

## What is deliberately not changed

- **The overlay entrance was Tailwind's, not the layer's**: `transition-opacity ease-out duration-300`
  and `scale-95`. `cubic-bezier(0,0,.2,1)` at 300ms is a different curve from every other move on the
  page. Replaced with six classes on the layer's own `--dash-ease` at `--dash-base`. Alpine adds and
  removes them around the transition, so nothing is left holding a transform.
- **A shadow on the modal panel.** The sheet's rule is separation by hairlines, and that holds — but the
  sheet is one plane. An overlay sits above another plane, and a face with nothing under it floats. This
  is the one place in the layer allowed a shadow, and it is only on `.hb-modal__panel`.
- **The throttled mobile LCP**, unchanged and still the shell's payload: `app.css` at 60.6 kB gzipped
  carrying every Tailwind utility the public site uses, plus `admin.css` with three scoped layers now in
  it, plus Livewire, Alpine and `admin.js`.
- **`Company Settings` has the same single-title defect** across its nine tabs, in the same shape. Not
  touched: it is outside what was asked for, and the fix is the same two lines. Offered rather than
  shipped.
- **One em-dash in visible copy**, in the media-library upload dialog: "Added to the library and selected
  for you — hold Ctrl to pick several". The rule says no em or en dashes in visible text. It is a
  one-character fix on a surface this pass was not asked about.
- **The QA account's own data.** Every frame is `Desk QA`, `desk-qa@example.test`, and its sessions read
  `Headless · Bot · 127.0.0.1` because the capture browser is the thing that made them. That is the
  harness, not the product; a real staff account reads `Chrome 131 · Windows`.

## Measurements, production build, authenticated, `https://highblossom.test`

`bench-jury6.mjs` (hover pairs driven with real pointer moves, keyboard, reduced motion, phone) and
`bench-audit6.mjs` (the served body, fetched with the session, because `audit.mjs` would otherwise
audit the login screen).

- **axe: zero violations** on all three panes, all three overlays, both themes. The pass before this one
  had five colour-contrast misses; none are left. `region` and `image-redundant-alt` are gone.
- **Console: zero errors, zero failed requests** on every state.
- **Hover, every primary target answers.** Panes: `Show Recovery Codes` and `Regenerate Recovery Codes`
  `bg,color,border,outline`; `Disable TFA` adds `shadow`; `Register a device` `shadow` (the ring is the
  whole answer, and the face cannot get darker than ink); `Rename` `bg,color,border`; `Remove` and both
  sign-out controls `bg,color,border,shadow`. Overlays: `Copy all` `shadow`, `Done` `bg,color,border`,
  `Switch it off` and `Delete it` `bg,border` (the flood to `--dash-cut-hover`). Nothing in
  `noHoverChange`.
- **Reduced motion: `hiddenText: 0`** on all three panes. The rings, the floods and the heavier edges are
  colour and geometry, so they survive; only the entrance stops.
- **Phone 390: `overflow: 0`** on all three, one `h1` each, and the only sub-24px target is the skip
  link, which is 1x1 until focused and 123x36 while focused.
- **Served HTML, all five tabs**: unique title each, `lang=en`, one `h1`, no heading-level jump, no
  duplicate `id`, no `img` without `alt`, no `href="#"`, no `window.confirm`, no `onsubmit="return
  confirm"`, no `@json` inside an attribute, `robots: noindex, nofollow`.
- **Tests: 478 passed, 12 failed** across `tests/Feature/Admin`, `Settings` and `Auth`. The 12 are the
  known stale trio (`ProfileUpdateTest`, `SecurityTest`, `SmtpSettingTest`) failing on a route that does
  not exist and a Volt component that never did — the same 12 that were failing before this pass
  started. `TwoFactorSettingsTest > profile page renders the enable two factor password modal` was
  failing because of the dropped pinned string and is green again.

## A functional bug the visual pass found on the way

`admin.profile.destroy` is registered as a **DELETE** route. The delete-account form posted `POST` with
`@csrf` and no `@method('DELETE')`. Every answer to that dialog was therefore a `405 Method Not
Allowed`, from either side — a correct password included. The account has never been deletable from this
page. The two session forms on the Sessions pane already carry the override, so this was one form
missed rather than a design decision.

It surfaced because the round-six check was "what happens when the password is refused", and getting a
refusal required the request to reach the controller. Found by the browser, not by the suite: the test
that would have caught it (`ProfileUpdateTest > user can delete their account`) is one of the twelve
stale failures that never get that far.

Verified after the fix, against the local database, with a deliberately wrong password so nothing could
be deleted: the dialog submits as DELETE, comes back **open**, `#delete-account-error` reads "The
password is incorrect.", the box carries `aria-invalid` and `aria-describedby`, focus is in the box, the
scroller is still locked behind it, and the account is still there.

## Round seven, from a juror that had not seen the intent

A fresh critic scored the round-six frames 6.90 weighted and its one-line reading of the idea was
correct ("an operations ledger ... monospace for every machine value"). Four of its findings were real,
one was my harness's fault, and two were the evidence being read for something it does not say.

| Its finding | Verdict | What happened |
|---|---|---|
| The sticky commit bar covers the New Password input at 390px, and clips a sentence mid-word on desktop | **Real.** A sticky card floating over a form taller than a phone ate 15% of the screen and the field you are typing into | The bar stops being a card — no side border, no radius, a top rule and a fill — and below a 46rem sheet it stops being sticky at all. A submit control that hides the field is worse than one you scroll to. Measured after: `footPosition: static` on phone, `footCoversInputs: []` on all three panes, `overlaps: []` on desktop |
| Destructive and routine controls are indistinguishable outside dialogs. `Remove` and `Rename` are the same pill; `Disable TFA` is `Show Recovery Codes` with a 1px thicker border | **Real.** A 1px inset ring next to a 1px border measured as a difference and read as none, and I had put the irreversible control third in a row of equals | The edge is a genuine 2px border with the padding taken back off so the box does not grow, and the control that cannot be taken back moves below its own rule. The band carries no caption: a sentence there would either repeat the ask or claim "cannot be undone" about a control that can be, by setting two-factor up again |
| `Copy all` answers a pointer with 1.7% of pixels | **Real, and the harness was lying by omission.** The hover answer was a double ink ring, and the button already had focus, so the ring landed on top of the focus ring | Computed-style diffs were replaced with a PNG reader and a per-pixel comparison. The primary hover now **inverts** the face, which is the desk's own documented verb and a different mechanism from focus. Measured: `Copy all` 1.7% → 47.7%; `Update Password` mean channel diff 11.6 → 112.6; every target on the three panes now sits between 48% and 74% of pixels changed |
| The phone rows are narrowed, not recomposed | **Real.** At 390px a list row floated its control into the middle of a two-line meta wrap, and the three two-factor buttons stepped right by their own width differences | Rows become one column with the controls on their own line at full width; the bar that heads a list puts its action where the reading starts; the state's controls stack on the card's left line |
| `modal-adminconfirm` puts the first focus stop on "Switch it off", the irreversible one | **The evidence was misread.** `k-01-tab` is the frame after one Tab. The component puts `data-dialog-initial` on Cancel on purpose, and the round-six probe measured `focusName: "Cancel"` on open | Not a defect, but the pack did not prove it. Every dialog now gets a `k-00-on-open` frame captured before any key is pressed. Measured: recovery → `Copy all`, adminConfirm → `Cancel` (`focusIsDestructive: false`), delete → the password box, enable → the password box |
| An unknown `?tab=` returns 200 rather than 404 | **A decision, and I am keeping it.** The fallback to the first group exists because an unknown tab used to paint nothing and shift the layout at 1.9s | On a signed-in `noindex` panel, a stale bookmark landing on a working page beats an error page. Written down rather than changed |
| `tab-passkeys` has ~500px of bare canvas under one row | **Not a defect.** The card ends where its content ends; the emptiness is the page below it, and this account has one passkey | Left alone |
| WPO scored 6 because there were no vitals | **Fair.** The pack had one byte-count line and nothing else | Added a throttled pass (4x CPU, slow-4G): mobile CLS 0.0000 to 0.0027, overflow 0, tap targets 40px. LCP is 12.6 to 12.9s and is still the shell's payload, unchanged by this work |

Two things the juror reported that were **my audit script's** errors rather than the page's, recorded so
the next reader does not chase them: "form controls 100, with a name or a label target 44" counted only
`name=`/`aria-label`/`alt` and ignored a button's own text, which is a name; and "bare `alert()`: 1"
matched a word inside a JavaScript comment that ships to the browser. Neither is a defect.

One real copy defect it did not look for, found while writing the audit: the media-library upload dialog
says "Added to the library and selected for you — hold Ctrl to pick several". That is an em-dash in
visible text, which the rules forbid. One character, on a surface outside this pass.

## Round eight, and the bug the fixes caused

The second juror scored 7.10 (up from 6.90) and its central verdict was the one that hurt:

> Can a person tell which control cannot be taken back? **No. The rule works in exactly one place.**

It was right, and it was right about something the measurements had said all along: `h-Remove-rest` and
`h-Rename-rest` differ by one pixel of a border drawn at 27% opacity. A 2px border of a faint colour is
not a heavier edge. And `h-Delete-it-hover` was the same navy as `h-Keep-the-accou-hover` — the
irreversible answer in the delete dialog turned into the safe one the moment a hand moved toward it.

### The ladder had three owners

That second half was a bug I had just written. `.hb-sheet .hb-btn--danger:hover` and
`.hb-modal .hb-btn--danger:hover` have the same specificity (two classes and a pseudo-class), so the one
that happened to appear later in the file won — and round seven's copy appeared later than round six's.
Three rounds had each added a button rule in their own section, and the file's order, not any decision,
was picking the winner.

All four roles now live in one block at the end of `admin-sheet.css`, ordered so the overlay rules come
last. Nothing else in the file may state a button's paint.

### The restatement that silenced every plain button

The same block opened by restating the secondary control — panel face, hairline, ink label — which the
base `.hb-btn` in `admin-desk.css` already is. Written as
`:not(.hb-btn--primary):not(.hb-btn--danger):not(.hb-btn--commit)` that is four classes deep, so it
outranked the base `.hb-btn:hover` on specificity and **a plain button stopped answering at all**:
`Show Recovery Codes` and `Regenerate Recovery Codes` measured 0% of pixels changed, `Keep the account`
0%, while `Cancel` in the layout-mounted ask — outside `.hb-sheet`, so untouched by the rule — measured
45.8%. The one place the bug could not reach is the place that still worked.

The rule is deleted. A restatement that wins on specificity is an override, and an override written to
say nothing is a defect.

### What the destructive edge is now

`border: 1px solid var(--dash-ink)` plus `inset 0 0 0 1px var(--dash-ink)`: two pixels of the darkest
value on the page, against one pixel at 27% for everything else, with the padding giving the extra pixel
back so the box does not grow. `Remove` and `Rename` sit in the same row at the same size and are now
distinguishable in a crop, which is the test the juror applied.

Inside an ask, red stays red on hover and deepens to `--dash-cut-hover`. It never becomes ink.

### The primary stopped demoting itself

The same juror rejected round seven's invert: "the primary demotes itself to a ghost. Hover should
darken/lift the fill, not remove it." Fair — an ink face turning into a hairline box on contact makes the
page's main action look like the least important thing on it when the hand arrives.

It now keeps the ink face and the light label, draws a detached rule (3px of ground, then 3px of ink) and
settles 1px up. Focus is a different mechanism so the two never cancel: a light line hugging the face at
1px, then ink at 4px. Measured, `Register a device` 25% / mean 41.3, `Copy all` 19.6% / mean 25.9,
`Verify` 34.4% / mean 51.3 — smaller pixel numbers than the invert produced, and the right ones, because
the change is a rule and a settle rather than the whole face swapping colour.

### Two things the juror reported that were my harness, not the page

- **`clipped 24`** in `states.json`. The field was `block.right - input.right`, which is the 24px of
  breathing room the body inset adds. It was read as 24px of clipping. Renamed to `insetRightPx`.
- **`modal-enable-2fa: open false, focusBox 1440x900, hover []`.** The enable dialog only exists while
  two-factor is off, and the harness never put the account in that state, so it clicked a button that was
  not there. The dialog now moves the account's own columns before it and restores them after; measured
  open, focus in the password box, scroller locked, both buttons answering.

Both are recorded because a harness that mislabels its own numbers produces a fix list that cannot be
acted on, and the second one cost a whole dialog its evidence.

### Also from this round

- **A nonsense `?tab=` is a soft 404.** It used to fall back to the first group inside the view and
  answer 200 with a different title. `ProfileController` now owns the list of groups as a const and
  answers **302 to `?tab=profile`**, so the status, the address bar and the title describe the same page.
  Verified: `does-not-exist` → 302 with a Location, `SECURITY` → 302 (the keys are case-sensitive on
  purpose), `profile`/`sessions` → 200, an empty `tab` → 200.
- **`<title>` was served with newlines and indentation inside it**, because the admin layout wrote it
  across three lines. Browsers trim it for the tab strip; history, a screen reader reading the name and
  anything comparing two pages' titles do not. One line now.
- **The named-controls count was wrong and it cost a point.** The check looked only at `aria-label`,
  `alt` and `name=`, ignoring a `<label for>` and a button's own text, and reported 56 of 100 unnamed.
  Rewritten to resolve all four: **21 of 23**, and the two it still lists are the ask's buttons, whose
  labels arrive through `x-text` after Alpine runs — empty in the served body, present in the live page,
  which axe confirms with zero violations.
- **The phone rows keep their rule.** `.hb-list__end` gains a top hairline below 46rem, so a control band
  under a device row is visibly a band and not a two-column grid that wrapped in the middle.
- **`/dashboard` has a serious axe violation that is not from this pass**: seven `label-content-name-mismatch`
  on the day desk's `.hb-bar-link` elements, whose `aria-label` is `"Wed 4 Nov: 0 jobs, today "` while
  the visible text inside is the weekday alone. It only appears once the page is scrolled, which is why
  the desk pass's own sweep missed it. It is in `dashboard.blade.php`, not in anything asked for here, and
  the dangling `, today ` is a copy bug as well as an accessibility one. **Offered, not shipped.**

### Where this pass stands, measured

Production build, authenticated, `https://highblossom.test`.

- **Hover, from PNG pixels.** Panes: `Show Recovery Codes` 51.1%, `Regenerate Recovery Codes` 52.3%,
  `Disable TFA` 43.6% / mean 83.4, `Update Password` 55.1% / mean 112.6, `Register a device` 25%,
  `Rename` 73.3%, `Remove` 67.1%, `Sign out other devices` 46.3%, `Sign out` 75.1%. Dialogs: `Copy all`
  19.6%, `Done` 46.5%, `Cancel` 45.8%, `Switch it off` 50.2% (stays red), `Keep the account` 50.6%,
  `Delete it` 46.6% (stays red), `Verify` 34.4%. No target at zero.
- **Dialogs, at the instant they open.** recovery → `Copy all`; adminConfirm → `Cancel`; delete → the
  password box; enable → the password box. `focusIsDestructive: false` in all four, `scrollerLocked:
  true` in all four, focus inside the panel in all four.
- **The commit bar overlaps no input** on desktop (`footH: 60, overlaps: []`) and is `position: static` on
  phone with `footCoversInputs: []`.
- **axe: zero violations** on all three panes, all four dialogs and the phone pass.
- **Reduced motion: `hiddenText: []`, `animationsRunning: []`** on all three panes; the recovery dialog
  still opens, still shows all four codes, `transitionDuration: 0s`.
- **Phone 390, throttled 4x CPU and slow-4G: `overflow: 0`, CLS 0.0013 to 0.0027, INP 0**, tap targets
  40px (one 28px, which clears the 24px floor). LCP 12.5 to 13.1 s is the admin bundle and is unchanged by
  this work; unthrottled the same pages measure 1.0 to 1.3 s.
- **Console: zero errors** across every state.
- **Tests: 478 passed, 12 failed** — the same known stale trio, unchanged from before the pass began.
  `vendor/bin/pint --test` passes on the controller.

## Round nine — the emphasis was inverted, and it was my own doing

The third juror scored 7.30 (6.90 → 7.10 → 7.30) and answered its own central question in two halves,
both correct:

> **Before the mouse moves: yes** — the destructive control carries a heavier ink edge and the safe one a
> thin grey outline. **After the hover: no, and it actively misleads.**

It was right, and the cause was the ladder I had just consolidated. At rest the destructive control is
the heavier object; under the pointer the safe one flooded to solid ink (mean channel diff 92 to 99)
while the red one merely deepened (13.9 and 14.1). So the moment a hand arrived, the irreversible answer
became the quietest thing in the dialog. A second instance of the same fault: `Remove` and `Rename` both
ended as the same navy.

### The fix, and why the numbers moved the way they did

A hover answer has two jobs: be large, and be in the right direction.

- **Destructive, in an ask** — the face deepens, a detached rule draws in the same red, and the control
  settles 1px. It stays red and it becomes the loudest object in the panel: `Delete it` 46.6% → **67.3%
  of pixels changed**, `Switch it off` 50.2% → **66.8%**. Their mean channel diff (51.5, 41.9) is still
  below the safe button's (98.7, 91.6), and that is arithmetic rather than a shortfall: red moving to a
  darker red covers more pixels and travels a shorter distance per pixel than white moving to navy. The
  emphasis is now carried by area, which is what a person sees, and the direction is no longer wrong.
- **Destructive, on the sheet** — same treatment in ink. `Remove` 67.1% → **76.8% / mean 112.1**, against
  `Rename` at 73.3% / 92.3, so the two are no longer the same object on contact.
- **Primary** — the juror's earlier finding that the ink face "demotes itself to a ghost" when it
  inverts, and that its ring-only answer measured 19.6%, are both answered by deepening the face to the
  pure end of the scale instead of removing it. A new token, `--dash-ink-deep` (`#000000` light,
  `#ffffff` dark), added to the desk block and mirrored on `.hb-modal`. `Register a device` 25% →
  **63.3%**, `Copy all` 19.6% → **56.5%**, `Verify` → **63.8%**. The face stays dark and gets darker, so
  the main action never looks less important at the moment it is being aimed at.

### Two more, both from evidence the harness had produced badly

- **`Done` "shows dark-filled in `d-01-open` but outline in `k-00-on-open`"** was read as an inconsistent
  default state. It was the mouse still sitting on the button: the panel frame was captured *after* the
  hover loop. The whole-panel shot now happens before anything is hovered.
- **The `reduced/` folder and the axe numbers were "absent from the pack".** They had been generated, and
  then `bench-jury7.mjs` deleted them at startup with an `rmSync` of the shared output root. It now
  removes only the folders it writes, and the reduced-motion pass, the axe run and a `k-01-tab` frame were
  folded into that same script so one run produces the whole pack. Recorded because the cost was real:
  two rubric areas were scored at their floor for want of frames that existed.

### Also

`Generate Secure` was the one 28px target on a phone. Below 46rem a small button takes the same 40px
minimum as everything else; nothing else about it changed. Measured tap targets are now `40, 40, 40` on
all three panes.

### Final measurements, production build, authenticated

- **Hover, from PNG pixels.** `Show Recovery Codes` 51.1%, `Regenerate Recovery Codes` 52.3%, `Disable
  TFA` 55.8% / mean 111.6, `Update Password` 55.1% / mean 112.6, `Register a device` 63.3%, `Rename`
  73.3%, `Remove` 76.8% / mean 112.1, `Sign out other devices` 58.0% / mean 121.8, `Sign out` 77.5%,
  `Copy all` 56.5%, `Done` 48.2%, `Cancel` 45.8%, `Switch it off` 66.8%, `Keep the account` 50.6%,
  `Delete it` 67.3%, `Verify` 63.8%. Every target above 44%, and **every destructive one now at or above
  the safe one beside it.**
- **Dialogs.** On open: `Copy all` / `Cancel` / the password box / the password box, `focusIsDestructive:
  false` and `scrollerLocked: true` in all four. After one Tab: `Done`, `Switch it off`, `Keep the
  account`, `Cancel` — still inside the panel each time, with a visible 2px ring (light on the red face,
  ink elsewhere).
- **Commit bar** overlaps no input on desktop and is static on phone.
- **axe: zero violations** on all three panes, all four dialogs, the phone pass and the reduced-motion
  pass.
- **Reduced motion:** `hiddenText: []` on all three panes; the recovery dialog opens, holds all four
  codes, `transitionDuration: 0s`.
- **Phone 390, 4x CPU / slow-4G:** `overflow: 0`, CLS 0.0013 to 0.0027, INP 0, tap targets 40px. LCP
  12.5 to 13.2 s is the admin bundle; unthrottled the same pages measure 1.0 to 1.3 s.
- **Console: zero errors.** **Tests: 478 passed, 12 failed** — the same known stale trio.

### The one item left, and why it is offered rather than done

The juror ranked the throttled mobile LCP first and correctly: it is the single largest score item still
open, and it is not in these panes. Splitting the admin bundle so `app.css`'s public-site Tailwind
utilities stop shipping to `/admin/*` is a shell change that touches every public page's CSS delivery and
the Vite entry graph. It has been named as the cost of this architecture since the day-desk pass. Say the
word and it is the next piece of work.




