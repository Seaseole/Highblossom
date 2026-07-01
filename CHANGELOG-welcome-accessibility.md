# Changelog — Welcome View Accessibility & UX improvements

Date: 2026-06-30

Summary:
- Added reduced-motion handling for animated elements.
- Inserted an accessible `h1` fallback and linked the animated headline with `aria-labelledby`.
- Converted interactive step cards from `div` to keyboard-focusable `button` elements and added focus-visible styling.
- Enhanced modal with `role="dialog"`, `aria-modal="true"`, `aria-labelledby` and `aria-describedby`, added autofocus behavior on open, and an accessible close button.
- Added `width`/`height` and `fetchpriority="high"` to the hero image to reduce CLS and better signal LCP.
- Added global accessibility utilities in `resources/css/app.css`: `.sr-only`, `.skip-to-content`, default `:focus-visible` styling, and `.touch-target`.

Notes & next steps:
- Run Lighthouse / axe accessibility scan and address remaining findings.
- Consider serving hero image as WebP/AVIF via CDN or build pipeline and add `srcset`/`sizes`.
- Consider centralizing design tokens further and auditing all text contrast values.

Files changed:
- resources/views/welcome.blade.php
- resources/css/app.css

CTA enhancements:
- Added `.btn-glass--accent` and `.glow-red-subtle` styles and applied them to primary CTAs: "View our work", "View all services", and "View full gallery" to increase prominence while respecting `prefers-reduced-motion`.


