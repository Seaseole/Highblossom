# Frontend Style System

## Approach

The project uses **Tailwind CSS v4** (via `@tailwindcss/vite`) as the primary styling engine for the public site and shared components, with a custom design-token layer defined in `resources/css/app.css`. The admin panel is a separate visual system built on **Bootstrap 5 + AdminLTE 4** plus a hand-written dark-theme stylesheet (`resources/css/admin.css`). Livewire's component library **Flux** (`livewire/flux`) is imported alongside Tailwind. Alpine.js provides lightweight interactivity; CKEditor 5 is bundled for rich text.

Vite entry points (see `vite.config.js`) split the frontend into two independent bundles:
- Public site: `app.css`, `app.js`, `site-booking.js`, `site-alpine.js`
- Auth pages: `auth.css`, `auth-alpine.js` (scoped to the "design language v1")
- Admin panel: `admin.css`, `admin.js` (separate Bootstrap/AdminLTE bundle)

## Key Files

- `resources/css/app.css` — single source of truth for Tailwind theme tokens, light/dark variants, the Material-3 palette, the scoped `.hb-ui` design language, booking-flow tokens, and global focus/accessibility rules.
- `resources/css/auth.css` — unlayered `.hb-ui` control styles (`.ui-field`, `.ui-btn*`) that intentionally sit outside Tailwind's `@layer utilities` so they win over utility classes rendered by Alpine bindings.
- `resources/css/admin.css` — standalone dark-themed admin design system using CSS custom properties (`--bg-primary`, `--accent`, etc.) and BEM-style class names (`admin-action-btn-primary`, `admin-table`, `admin-badge-*`).
- `resources/css/ckeditor-custom.css` — CKEditor skin overrides.
- `vite.config.js` — declares all CSS/JS entry points and registers `@tailwindcss/vite`.
- `package.json` — pins `tailwindcss ^4.0.7`, `@tailwindcss/vite ^4.1.11`, `alpinejs ^3.15.11`, `bootstrap ^4.6.0`, `@tailwindcss/oxide-linux-x64-gnu`, `prettier-plugin-tailwindcss`.
- `config/adminlte.php` — AdminLTE configuration for the admin UI.
- `resources/views/layouts/` — Blade layouts that opt pages into the `.hb-ui` scope and the `.dark` theme.

## Architecture and Conventions

### Three token namespaces

`app.css` defines four parallel semantic token sets under `@theme { --color-* }`:

| Namespace | Scope | Purpose |
|---|---|---|
| `--color-site-*` | Public site | Backgrounds, surfaces, lines, muted/faint, accent, link, glass. Light values live here; dark overrides are inside `@layer theme .dark`. |
| `--color-admin-*` | Admin panel | Neutral gray scale with red accent, used by both Tailwind tokens and the standalone `admin.css`. |
| `--color-ui-*` | Design language v1 | Component-grade tokens (panel, line, muted, danger/success/info/warning soft fills, text sizes, radii, shadows, z-index). Pages migrate by switching layout rather than editing these. Contrast ratios are documented inline (e.g. `/* 16.4:1 on surface */`). |
| `--booking-*` | Booking wizard flow | Scoped to `.booking-flow`; keeps wizard card tokens one edit away from a site-wide sweep. |

Material-3 tokens (`--color-primary`, `--color-surface-container-lowest`, …) are also declared in `@theme` to support any legacy usage.

### Scoped design language

The comment at the top of `auth.css` documents the rule: everything in this file is (a) unlayered so it beats Tailwind utilities, and (b) scoped under `.hb-ui` so it cannot leak onto pages that have not opted in. Opt-in happens via the base layout adding `.hb-ui` to `<body>`. This protects Alpine-driven controls whose bindings render onto plain DOM elements.

### Dark mode

Dark mode is activated by toggling the `.dark` class (and an additional `sidebar-collapsed` / `os-mac` custom variant defined via `@custom-variant`). All semantic tokens get `.dark` overrides inside `@layer theme`. The booking flow has its own `.dark .booking-flow` override block. Reduced-motion preferences are honored via `@media (prefers-reduced-motion: reduce)` for the booking flow.

### Global accessibility baseline

`app.css` ships a `*:focus-visible` rule that replaces default outlines with a box-shadow ring, plus `.sr-only` and `.skip-to-content` helpers. Select elements use `color-scheme: light dark` so OS-native dropdowns respect the theme.

### Admin vs. public separation

The admin is deliberately isolated: it imports Bootstrap 4.6 and AdminLTE 4, then layers a hand-authored dark theme on top. It does not share Tailwind tokens with the public site. The public site and auth pages share Tailwind tokens but keep their control styles in separate files (`app.css` vs. `auth.css`).

### Custom utilities and variants

- `no-scrollbar` utility for hiding scrollbars.
- `sidebar-collapsed` and `os-mac` custom variants driven by HTML attributes/classes set by scripts.
- Z-index extensions `--z-60` through `--z-100`.
- Easing tokens `--ease-ui-out`, `--ease-out-expo`, `--ease-out-quint`, `--ease-in-out-quint`.

## Conventions and Constraints

- **Tokens over literals**: Colors, fonts, radii, shadows, and motion durations are consumed via CSS custom properties defined in `@theme` or `.hb-ui` scope, not hard-coded hex values in views.
- **Design language v1 is opt-in**: Views must render through the base layout that adds `.hb-ui` to `<body>` to receive the component styles; otherwise Tailwind utilities alone are available.
- **Admin is a separate visual system**: Admin Blade templates target `.admin-*` classes and CSS variables from `admin.css`; they do not rely on Tailwind tokens.
- **Booking flow is self-contained**: Styles under `.booking-flow` and variables under `--booking-*` isolate the wizard from site-wide token changes.
- **Unlayered `.hb-ui` rules beat Tailwind utilities**: The `auth.css` header explicitly states this ordering decision because Alpine bindings render onto plain elements where `:disabled="expr"` compiles as PHP, making component props the only safe override mechanism.
- **Contrast ratios are documented on tokens**: Comments next to `--color-ui-text`, `--color-ui-muted`, `--color-ui-link`, etc. record the computed contrast ratio against the surface, serving as an accessibility contract enforced by the token layer.
- **Reduced motion**: Animations are disabled under `prefers-reduced-motion: reduce` for the booking flow.
- **Prettier plugin**: `prettier-plugin-tailwindcss` is present in devDependencies, indicating Tailwind class sorting is expected during formatting.