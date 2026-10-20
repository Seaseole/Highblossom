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

