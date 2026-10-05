<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ApplicationVersion;
use App\Models\User;
use App\Services\ApplicationVersionService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

/**
 * Seeds the application version registry with its release history, starting at
 * the 1.2.0 baseline and ending at the version the application is on today.
 *
 * Idempotent: versions that already exist are left untouched, so this is safe
 * to re-run on a database that already holds recorded releases.
 */
class ApplicationVersionSeeder extends Seeder
{
    /**
     * The release history, oldest first. Each entry mirrors a real recorded
     * release: version, one-line summary, release date and change notes.
     *
     * @var list<array{version: string, summary: string, released_at: string, notes: list<array{type: string, text: string}>}>
     */
    private const HISTORY = [
        [
            'version' => '1.2.0',
            'summary' => 'Baseline release',
            'released_at' => '2026-10-01',
            'notes' => [
                ['type' => 'added', 'text' => 'Application version registry introduced.'],
            ],
        ],
        [
            'version' => '1.2.1',
            'summary' => 'Fix admin-wide crash when reading the cached current version',
            'released_at' => '2026-10-01',
            'notes' => [
                ['type' => 'fixed', 'text' => 'ApplicationVersionService::current() cached an Eloquent model object; stale cross-process cache entries deserialized as __PHP_Incomplete_Class and violated the return type, crashing every page using x-layouts::admin (including /admin/versions). The cache now stores only the release primary key and the model is re-queried by key; the poisoned cache entry was purged.'],
            ],
        ],
        [
            'version' => '1.3.0',
            'summary' => 'Per-announcement expiry for Company Settings announcements',
            'released_at' => '2026-10-01',
            'notes' => [
                ['type' => 'added', 'text' => 'Each announcement now has an optional "Active Until" date/time; it hides automatically from the marquee once that moment passes.'],
                ['type' => 'changed', 'text' => 'The announcement ticker bar hides entirely when every announcement has expired. Blank/legacy entries never expire (backward compatible).'],
            ],
        ],
        [
            'version' => '1.3.1',
            'summary' => 'Rename user-facing "Inspection" terminology to "Appointment"',
            'released_at' => '2026-10-01',
            'notes' => [
                ['type' => 'changed', 'text' => 'Admin appointments UI, customer booking flow, dashboard stats, sidebar, flash messages, emails and notification subjects now read "Appointment".'],
                ['type' => 'changed', 'text' => 'Terminology-only rename: route names, classes, DB tables/columns, permissions and generic service descriptions (e.g. "windshield inspection") are unchanged.'],
            ],
        ],
        [
            'version' => '1.4.0',
            'summary' => 'Two-factor authentication repair and hardening',
            'released_at' => '2026-10-03',
            'notes' => [
                ['type' => 'fixed', 'text' => 'The two-factor setup step dumped raw Alpine JavaScript onto the Profile Settings page as visible text and the recovery-codes modal never opened. The flashed codes were interpolated into the x-data attribute with @json, which in this Laravel version drops the JSON_HEX_QUOT flag, so the first quote in the payload closed the attribute early. The codes are now HTML-escaped before render, so the QR step and the recovery-codes modal display correctly.'],
                ['type' => 'fixed', 'text' => 'Valid authenticator codes were intermittently rejected during setup and at login: no verification window was configured, so only the exact 30-second slot passed. fortify.php now sets window => 1, tolerating a minute of clock drift.'],
                ['type' => 'fixed', 'text' => 'Codes pasted with separators (e.g. "123 456") are now normalized before verification instead of failing outright.'],
                ['type' => 'added', 'text' => 'The pending two-factor step has a "Cancel setup" action that discards an unconfirmed secret; it refuses to touch an already-confirmed configuration.'],
                ['type' => 'added', 'text' => 'The login two-factor challenge now accepts a recovery code, closing a lockout path where the backend honored recovery codes but no field existed to enter one.'],
                ['type' => 'security', 'text' => 'Enabling two-factor now requires re-entering the account password through a confirm-password modal, backed by a throttled endpoint and the password.confirm gate on the enable route, so 2FA cannot be armed by a hijacked session alone.'],
                ['type' => 'changed', 'text' => 'Enabling two-factor is idempotent: a repeat request no longer rotates the secret behind a QR code already shown on screen.'],
            ],
        ],
        [
            'version' => '1.5.0',
            'summary' => 'Profile account details, post-login consent capture and optional email verification',
            'released_at' => '2026-10-03',
            'notes' => [
                ['type' => 'added', 'text' => 'Profile Settings now opens with an account summary — email-verification status, assigned roles, member-since date and the recorded terms/privacy consent dates (each reading "Not recorded" until captured) — plus an Activity panel of booking, milestone and note counts. Phone and avatar fields were added to profile editing.'],
                ['type' => 'fixed', 'text' => 'The registration form\'s required terms checkbox was silently discarded, so terms_accepted_at and privacy_accepted_at stayed NULL and the profile always read "Not recorded". Registration now validates both terms and a new privacy checkbox and stamps their timestamps.'],
                ['type' => 'added', 'text' => 'A /consent page and consent.required middleware now capture terms/privacy consent after login for accounts that have none, including seeded and imported users, since registration is optional. Login, logout, passkey and two-factor routes stay unguarded so the gate cannot lock anyone out.'],
                ['type' => 'added', 'text' => 'A new Company Settings > General "Require email verification" switch (off by default) gates authenticated areas behind verified.if_required middleware. Users now implement MustVerifyEmail, so verification emails are actually sent — the interface was previously commented out, which had left the existing verified middleware inert.'],
                ['type' => 'fixed', 'text' => 'The sidebar notification badges used a nonexistent isDark() Alpine binding, logging an "isDark is not defined" console error on every admin page. They now use static Tailwind dark-mode classes, which also makes the badges recolor correctly in the dark theme.'],
            ],
        ],
        [
            'version' => '1.6.0',
            'summary' => 'Session ledger and device management',
            'released_at' => '2026-10-03',
            'notes' => [
                ['type' => 'added', 'text' => 'A per-user session ledger records each sign-in: device, browser and OS detected from the user agent via jenssegers/agent, IP address, login method, first/last activity and end reason. Rows open on login and refresh as activity is seen; a daily sessions:prune command closes expired sessions and trims device history.'],
                ['type' => 'added', 'text' => 'A new "Sessions" tab on the Profile page lists active devices with a "This device" marker that carries no revoke action, lets you sign out a single device or every other session, and keeps a previously-active history.'],
                ['type' => 'added', 'text' => 'Admins with the new "manage user sessions" permission get a searchable cross-user overview at /admin/sessions, a per-user session list on the users screen, and per-row plus "sign out every session" revocation.'],
                ['type' => 'fixed', 'text' => 'A session re-established from the remember cookie was recorded with login method "Unknown". TrackUserSession now passes Auth::viaRemember() into the ledger, so it is recorded as remembered.'],
                ['type' => 'security', 'text' => 'Changing a password now signs the user\'s other sessions out by default, and each revocation records who performed it, giving admins a way to cut off a compromised account.'],
            ],
        ],
        [
            'version' => '1.7.0',
            'summary' => 'Collapsible admin sidebar rail',
            'released_at' => '2026-10-03',
            'notes' => [
                ['type' => 'added', 'text' => 'The desktop admin sidebar collapses to an 80px icon rail from a toggle on its edge. Group and action icons stay visible in both states while the text labels, company name and disclosure chevrons hide, and the toggle exposes aria-label and aria-expanded so the control is announceable.'],
                ['type' => 'added', 'text' => 'Clicking a group icon while the rail is collapsed expands the sidebar and opens that group in one action, so the collapsed state stays navigable rather than reduced to the always-visible items.'],
                ['type' => 'added', 'text' => 'The collapsed state persists in localStorage and a head script reapplies it to the html element before first paint, so the rail keeps its width across full-page admin navigation instead of re-widening and snapping back. It is driven by a Tailwind sidebar-collapsed custom variant, and the Alpine store seeds itself from that pre-painted class, so neither a page reload nor a Livewire re-render can reset it.'],
                ['type' => 'fixed', 'text' => 'The Content, Media and System group headers rendered no glyph at all: they requested icon names the icon component does not define (document, image, cog), which resolved to an empty SVG path. They now use document-text, photo and cog-6-tooth.'],
                ['type' => 'fixed', 'text' => 'The Team & Access group omitted admin.sessions from its active-route list, so the group neither opened nor highlighted while the sessions screen was in view.'],
                ['type' => 'changed', 'text' => 'The icon component now accepts multi-path glyphs and forwards caller classes onto the svg instead of fixing its own, so navigation icons can be centered and resized per context. Existing action icons are unaffected.'],
            ],
        ],
        [
            'version' => '1.8.0',
            'summary' => 'Progressive release history with a fade-out load more',
            'released_at' => '2026-10-03',
            'notes' => [
                ['type' => 'fixed', 'text' => 'The Application Versions release history rendered every recorded release in one request, an unbounded list that grew with each release forever. It now loads five at a time.'],
                ['type' => 'added', 'text' => 'A "Load more releases" control appends the next five entries without a page reload, and a gradient mask fades the cut-off entries out at the foot of the timeline — white on the light theme, matching the panel ink in dark mode — so the list reads as unfinished rather than truncated. A "Showing 5 of 9 releases" line reports progress and settles on "All 9 releases shown" once the timeline is exhausted, at which point the fade and the control disappear.'],
                ['type' => 'added', 'text' => 'A versions/history fragment endpoint returns only the timeline markup, so loading more transfers the list instead of re-rendering the hero, bump strip and record modal. The fragment carries its own page, shown, total and has-more values, so the browser reads pagination state from the server rather than inferring it from what it has seen.'],
                ['type' => 'added', 'text' => 'The amount loaded is carried in the page URL, so it survives a reload and the control still works with JavaScript disabled as a plain link. A failed request leaves an inline "Could not load more releases — try again" instead of silently doing nothing.'],
                ['type' => 'changed', 'text' => 'The timeline lives in its own partial shared by the page and the fragment response, so the two cannot drift apart, and the swapped region deliberately holds no Alpine bindings — the fade and the control are siblings driven by component state, so re-rendered entries need no re-initialisation.'],
            ],
        ],
        [
            'version' => '1.9.0',
            'summary' => 'Security remediation: output escaping, env editor lockdown, rich-text sanitisation and upload hardening',
            'released_at' => '2026-10-04',
            'notes' => [
                ['type' => 'security', 'text' => 'The booking confirmation page printed the visitor-supplied name and vehicle details with an unescaped echo inside the translated message, so markup submitted at booking time executed on the confirmation page. The message is now escaped.'],
                ['type' => 'security', 'text' => 'JSON-LD blocks (seo/meta and blocks/seo) emitted raw JSON inside a script tag, letting a closing script sequence in saved SEO metadata break out of the element. They now encode with JSON_HEX_TAG and JSON_HEX_AMP.'],
                ['type' => 'fixed', 'text' => 'The blog list search appended a bare OR clause for the excerpt match, which escaped the published constraint and let a search term surface draft posts. The two LIKE conditions are now grouped together.'],
                ['type' => 'security', 'text' => 'The settings environment tab now reads and writes only a fixed allowlist of non-secret keys (app name, URL, timezone, locales, FEATURES_*). Secret keys such as APP_KEY, DB_*, REDIS_* and MAIL_* are ignored in request input and never rendered back to the browser.'],
                ['type' => 'security', 'text' => 'Environment writes require the new manage environment gate (Super Admin only), which also guards the decomposer route. Writes go through a temp file and rename so concurrent saves cannot truncate .env, and values containing quotes, dollar signs, backticks or newlines are escaped so they round-trip instead of being interpolated.'],
                ['type' => 'security', 'text' => 'The SMTP screen logged its whole settings array, including the mail password, and repopulated the password into the form. The log call is gone and the password is now write-only: an empty submission keeps the stored value.'],
                ['type' => 'security', 'text' => 'Rich text that the site prints unescaped is now sanitised with HTMLPurifier: block-builder paragraphs and accordion item bodies at render time, and About-Us body, mission and vision at save time. Scripts, event handlers, javascript: URLs and iframes are stripped from stored HTML.'],
                ['type' => 'security', 'text' => 'Embed blocks keep their provider iframe but the oEmbed payload passes through an embed-safe definition: http/https only, iframe attributes allowlisted, scripts and inline handlers removed, and an iframe declared to contain no nested markup.'],
                ['type' => 'security', 'text' => 'Content-block and About-Us text fields carry explicit length limits (60,000 characters for rich bodies, 255 for titles and classes), so an oversized payload is rejected instead of overflowing the underlying TEXT columns.'],
                ['type' => 'security', 'text' => 'Paths submitted as an already-uploaded file (image_path, featured_image_path, business_logo_path, favicon_path) are no longer free-form. A new rule accepts only a relative path the application itself generated: known upload folder, hashed file name, image extension. Traversal, absolute paths, URLs and .php or .svg targets are rejected before the code adopts or deletes anything.'],
                ['type' => 'security', 'text' => 'The public quote form can no longer name a storage path as its attachment; only a real uploaded image is accepted. The AJAX uploader restricts its folder parameter to the directories the application writes into, and relocation from the temp disk refuses traversal or absolute references read from block attributes.'],
                ['type' => 'security', 'text' => 'Upload rules now declare accepted mime types everywhere they were missing, which removes SVG from every image allowlist (it can carry script when opened directly) and limits the block builder video property to the container formats it already validated.'],
                ['type' => 'fixed', 'text' => 'Video uploads trusted the client file name and moved the raw file into the public folder with move_uploaded_file. The service now stores on the public disk under a generated name after checking the extension, so a crafted file name cannot escape the videos directory.'],
                ['type' => 'fixed', 'text' => 'The service edit form submitted the asset() URL in its hidden image_path instead of the stored relative key, a value the new path rule rightly rejects; it now round-trips the relative path so saving an unchanged image still works.'],
                ['type' => 'added', 'text' => 'The Google Maps key field now states that the value ships to every visitors browser, so it must be domain-restricted in Google Cloud, and points server-only keys to .env.'],
            ],
        ],
        [
            'version' => '1.10.0',
            'summary' => 'Search engine readiness: complete sitemap, site-wide SEO settings and cache management',
            'released_at' => '2026-10-04',
            'notes' => [
                ['type' => 'added', 'text' => 'The sitemap now lists every indexable page: the nine static routes (including About Us, Blog, Terms and Privacy), all published posts and all active gallery images, each with its last-modified date. Publishing or editing content invalidates the cached sitemap immediately, so Google Search Console never sees a stale URL set.'],
                ['type' => 'added', 'text' => 'A new SEO Settings page holds site-wide defaults — meta title, description, keywords and a share image — with live character counters, a Google search-result preview and a social-card preview that update as you type. Pages without their own SEO entry fall back to these values.'],
                ['type' => 'added', 'text' => 'A Google Search Console verification token can be pasted into the settings page and is emitted on every public page, covering the HTML-tag verification method without a file upload.'],
                ['type' => 'added', 'text' => 'A new Cache Management page lists five groups — company settings, SEO output, application version, application cache and framework cache — with entry counts and sizes, and lets you clear or optimize each one individually or all at once. User sessions live in their own table and are never touched.'],
                ['type' => 'security', 'text' => 'Cache group actions are driven by a fixed registry behind the new manage cache permission, so no request ever names a raw cache key, artisan argument or filesystem path.'],
                ['type' => 'fixed', 'text' => 'The Terms and Privacy pages moved from closure routes to controller actions, which unblocks route caching for the framework optimize action.'],
                ['type' => 'fixed', 'text' => 'A static robots.txt stub in the public folder was shadowing the dynamic route on the web server, so the sitemap reference and admin disallow rules never reached crawlers. The stub is gone and the generated file is served.'],
                ['type' => 'changed', 'text' => 'robots.txt now also disallows the booking and API paths alongside the existing admin and auth routes.'],
            ],
        ],
        [
            'version' => '1.11.0',
            'summary' => 'Sitemap reliability and static SEO editing fixes',
            'released_at' => '2026-10-05',
            'notes' => [
                ['type' => 'fixed', 'text' => 'Google Search Console reported the sitemap was missing a required tag. The nine static SEO routes were never part of the default database seed, so a fresh deployment with no published posts or active gallery images produced an empty sitemap with no URL entries. The static-route seeder now runs on every seed, so those pages are always listed.'],
                ['type' => 'fixed', 'text' => 'The sitemap can no longer be emitted empty: if no indexable content is found it falls back to the home page, guaranteeing at least one valid URL with a location for crawlers to read.'],
                ['type' => 'fixed', 'text' => 'Re-seeding the static SEO routes now clears the cached sitemap and robots.txt, so a deployment no longer serves a stale or empty sitemap for up to 24 hours.'],
                ['type' => 'fixed', 'text' => 'Editing a static SEO route failed with "The route name field is required." The route name is fixed when the entry is created and is deliberately absent from the edit form, but validation demanded it on every save. It is now required only when creating an entry and can no longer be changed afterwards.'],
                ['type' => 'fixed', 'text' => 'Saving a static SEO route also raised a server error once validation passed, so editing an entry had never actually worked. Updates now persist correctly and leave the route name untouched.'],
            ],
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $baseline = ApplicationVersionService::BASELINE;
        $author = User::query()->orderBy('id')->value('id');

        foreach (self::HISTORY as $release) {
            $parts = array_map('intval', explode('.', $release['version']));

            ApplicationVersion::query()->firstOrCreate(
                ['version' => $release['version']],
                [
                    'major' => $parts[0],
                    'minor' => $parts[1],
                    'patch' => $parts[2],
                    'summary' => $release['summary'],
                    'notes' => $release['notes'],
                    'released_at' => $release['released_at'],
                    // The baseline predates the registry's authorship tracking;
                    // later releases are attributed to the first admin user.
                    'created_by' => $release['version'] === $baseline ? null : $author,
                ]
            );
        }

        // The current release id is cached, so drop it to avoid a stale entry
        // pointing at a release that no longer reflects the registry.
        Cache::forget(ApplicationVersionService::CACHE_KEY);
    }
}
