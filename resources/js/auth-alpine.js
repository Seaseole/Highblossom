/**
 * Alpine for the auth screens that need it (two-factor challenge).
 *
 * app.js deliberately boots no Alpine, and site-alpine.js is the public site's
 * instance with every site component registered on it. This entry exists so the
 * challenge page can drive its own toggles from the same same-origin,
 * version-pinned copy as the rest of the app instead of a CDN build — an
 * external dependency in a sign-in flow is worth avoiding on its own.
 *
 * Load this on at most one entry per page: Alpine supports a single instance.
 */
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
