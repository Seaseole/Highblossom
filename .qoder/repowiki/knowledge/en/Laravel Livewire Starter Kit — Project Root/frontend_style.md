## Approach

The project uses a **three-tier frontend styling system** built on Vite + Tailwind CSS v4 (via `@tailwindcss/vite`), with two separate design languages coexisting in one codebase:

1. **Public site & shared components** — Tailwind v4 + Material-3–inspired semantic tokens.
2. **"Design language v1" (auth / legacy UI)** — scoped under `.hb-ui`, using unlayered CSS that intentionally wins over Tailwind's `@layer utilities` via cascade priority.
3. **Admin panel** — Bootstrap 5 + AdminLTE 4, plus a custom dark theme in `resources/css/admin.css`.

Vite bundles six entry points (`vite.config.js`):
- Public site: `resources/css/app.css`, `resources/js/app.js`, `resources/js/site-booking.js`, `resources/js/site-alpine.js`
- Design language v1: `resources/css/auth.css`, `resources/js/auth-alpine.js`
- Admin: `resources/css/admin.css`, `resources/js/admin.js`

This separation is deliberate — the comment in `app.css` states the design-language-v1 files are "scoped to layouts that opt in" so unmigrated pages render identically during migration.

## Key Files

- `vite.config.js` — declares all entry points and installs the `tailwindcss()` plugin.
- `package.json` — dependencies: `tailwindcss ^4.0.7`, `@tailwindcss/vite ^4.1.11`, `alpinejs ^3.15.11`, `bootstrap ^4.6.0`, `@ckeditor/ckeditor5-build-classic`, plus Prettier plugins for Blade and Tailwind.
- `resources/css/app.css` — single source of truth for Tailwind `@theme` tokens (fonts, colors, radii, shadows, z-index scale, easing curves) and scoped component styles (checkboxes, booking flow, grain overlay).
- `resources/css/auth.css` — the `.hb-ui` design language v1 stylesheet defining `.ui-field`, `.ui-btn`, `.ui-checkbox-*` controls; deliberately unlayered so it outranks Tailwind utilities.
- `resources/css/admin.css` — custom dark-theme variables (`--bg-primary: #0a0a0f`, `--accent: #dc2626`) and component classes (`admin-action-btn-*`, `admin-form-input`, `admin-table`, `admin-badge-*`).
- `resources/css/ckeditor-custom.css` — CKEditor overrides.
- `public/js/image-upload.js`, `public/js/video-uploader.js` — standalone public-site scripts.
- `resources/js/site-alpine.js`, `resources/js/site-booking.js`, `resources/js/auth-alpine.js`, `resources/js/admin.js` — Alpine.js behavior per layout family.
- `resources/views/flux/*` — Livewire Flux components (imported via `@import "../../vendor/livewire/flux/dist/flux.css"` in `app.css`).

## Architecture and Conventions

### Token model
All design tokens live in Tailwind's `@theme` block inside `resources/css/app.css`. They follow a strict naming scheme:
- Material-3 palette: `--color-{primary,secondary,tertiary,background,surface,...}` with matching `--color-on-*` and `--color-*-container` variants.
- Semantic layers: `--color-site-*` (public site), `--color-admin-*` (admin panel), `--color-ui-*` (design language v1), `--color-accent*` (brand accent).
- Typography: `--font-sans` = Geist, `--font-headline` = Cabinet Grotesk; text sizes via `--text-ui-*` with explicit line-height sub-properties.
- Spacing/radius/shadow/z-index: `--radius-ui-xs..xl,pill`, `--shadow-ui-*`, `--z-60..100`.
- Easings: `--ease-ui-out`, `--ease-out-expo`, `--ease-out-quint`, `--ease-in-out-quint`.

### Dark mode
Dark-mode values are declared by overriding the same `@theme` variables under a `.dark` selector (defined via `@custom-variant dark (&:where(.dark, .dark *)))`), keeping light and dark palettes in one file. The comment notes exact current-dark values override light values so dark mode renders pixel-identical to the original design.

### Custom variants
Tailwind custom variants are declared at the top of `app.css`:
- `dark` — toggled via `.dark` on `<html>` or `.dark` ancestors.
- `sidebar-collapsed` — toggled via `.sidebar-collapsed` on `<html>`.
- `os-mac` — toggled via `.os-mac` on `<html>` based on `navigator.platform`.

### Design language v1 scoping rule
From the `auth.css` header comment: everything under `.hb-ui` is (a) unlayered so it can win over Tailwind's `@layer utilities`, and (b) scoped so it never reaches a page that has not opted in. A page opts in by rendering through `layouts/base`, which puts the `.hb-ui` class on `<body>`. This is the documented enforcement mechanism.

### Control authoring rule
The same comment mandates: "The `.ui-field` / `.ui-btn` classes are the authoritative control styling — the Blade components emit them rather than assembling utility strings, because a screen driven by Alpine bindings has to put those bindings on plain elements." Overrides go through component props, not ad-hoc utility classes.

### Admin vs public separation
The admin panel does not share Tailwind tokens with the public site. It defines its own CSS variables under `:root` (`--bg-primary`, `--bg-card`, `--border-subtle`, `--text-heading`, `--accent`, etc.) and uses conventional class names (`admin-section-header`, `admin-action-btn-primary`, `admin-form-input`, `admin-table`, `admin-badge-active|inactive|featured|draft|published`). Bootstrap 4.6 and jQuery remain as dependencies but the custom dark theme provides the visual identity.

### Accessibility conventions
- Focus rings use `box-shadow: var(--shadow-ui-focus)` instead of `outline` to avoid repainting rounded controls.
- Contrast ratios are explicitly annotated in comments (e.g., `/* 16.4:1 on surface */`, `/* 4.6:1 — the only token allowed to carry copy */`, `/* 4.8:1 on accent */`).
- `prefers-reduced-motion` media queries disable transitions for checkboxes and enable smooth scrolling only when motion is preferred.
- Checkbox inputs are visually hidden (`opacity: 0; width: 0; height: 0`) with a visible `.ui-checkbox-check` sibling, following accessible patterns.

### Responsive strategy
No dedicated responsive breakpoints are defined in the inspected CSS; responsiveness is expected to come from Tailwind v4's default breakpoint set. The design language v1 uses percentage-based widths and fluid typography via CSS custom properties rather than custom breakpoint rules.

## Conventions and Constraints

- **Rule:** All color/font/shadow/token changes go through the `@theme` block in `resources/css/app.css`; views must reference Tailwind utilities derived from those tokens, not hard-coded hex values. Enforced by the centralized `@theme` structure and the explicit semantic token prefixes (`site-*`, `ui-*`, `admin-*`).
- **Rule:** Design language v1 components must be authored as `.ui-*` classes under `.hb-ui` and emitted by Blade components, never assembled from raw Tailwind utilities in templates. Enforced by the architectural comment in `resources/css/auth.css` explaining why unlayered CSS is required for Alpine-bound elements.
- **Rule:** Pages opt into design language v1 by rendering through `layouts/base`, which applies the `.hb-ui` class to `<body>`. Nothing in `auth.css` targets bare element selectors outside that scope. Enforced by the explicit scoping policy stated in the stylesheet header.
- **Rule:** Admin styles are isolated in `resources/css/admin.css` and do not share Tailwind tokens with the public site; they use their own `:root` CSS variable namespace. Enforced by the separate Vite entry point and independent variable declarations.
- **Rule:** Custom Tailwind variants are registered via `@custom-variant` at the top of `app.css` (currently `dark`, `sidebar-collapsed`, `os-mac`) and used with the `&:where(...)` pattern to minimize specificity.
- **Rule:** Animations use the project's easing variables (`--ease-ui-out`, `--ease-out-quint`, `--ease-out-expo`) rather than arbitrary cubic-bezier values, ensuring consistent motion across the design system.
- **Constraint:** Tailwind v4 is used via the new `@tailwindcss/vite` plugin (not the classic PostCSS pipeline); there is no `tailwind.config.js` — configuration lives entirely in the `@theme` block within the CSS file.