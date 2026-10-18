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
        [
            'version' => '1.12.0',
            'summary' => 'Separate the public gallery from the media library, restore the admin flash messages, and move registration to the database',
            'released_at' => '2026-10-10',
            'notes' => [
                ['type' => 'fixed', 'text' => 'Images added to a blog post or page through a content block\'s gallery picker also appeared on the public /gallery page and in sitemap.xml. The media library and the curated gallery share one table and the public gallery filtered only on is_active, so every library asset was effectively published. A new source column records whether a row is a curated gallery image or a library asset; the public gallery, its detail pages, the home-page featured strip, related images and the sitemap now read curated rows only.'],
                ['type' => 'changed', 'text' => 'Uploading from the media library no longer marks the image active, since a library asset is not a gallery publication. Rows the admin Gallery form never saved were migrated to the library source and deactivated.'],
                ['type' => 'fixed', 'text' => 'Creating, updating or deleting a Gallery Category, Blog Category, Glass Sub-Category or Blog Tag flashed a raw translation key such as messages.gallery_category_created instead of a message. The twelve missing strings now exist in lang/en/messages.php.'],
                ['type' => 'fixed', 'text' => 'The admin Gallery and Gallery Categories lists had no way to delete an entry: the destroy route and service existed but no control ever reached them. Both lists now show a permission-gated Delete action beside Edit, each behind a confirmation prompt.'],
                ['type' => 'added', 'text' => 'The "Enable Registration" switch moved from the environment file to Company Settings, General tab, "Account Security", beside the verified-email toggle. A database value cannot be read while config files load, so /register and register.store are no longer stripped at boot; an EnsureRegistrationIsEnabled middleware returns 404 while the setting is off and short-circuits on every other Fortify route.'],
                ['type' => 'changed', 'text' => 'Registration now defaults to off, so a fresh install accepts no public sign-ups until an admin turns the switch on. The FEATURES_REGISTRATION_ENABLED key and its config/features.php file are gone.'],
            ],
        ],
        [
            'version' => '1.13.0',
            'summary' => 'Durable media: the public disk can move to object storage without losing existing files',
            'released_at' => '2026-10-11',
            'notes' => [
                ['type' => 'added', 'text' => 'The public disk — where every uploaded image, video and document is written — is now driver-switchable through PUBLIC_DISK_DRIVER: a local folder behind the /storage symlink in development, or an S3-compatible bucket in production. Media therefore survives a Laravel Cloud deployment instead of living on the ephemeral instance disk. Uploads still target the disk by name, so no call site changed, and league/flysystem-aws-s3-v3 is now required to make the previously dead s3 disk block usable.'],
                ['type' => 'added', 'text' => 'One MediaUrl resolver (plus the media_url() helper) understands the four shapes a stored media value can legitimately hold — disk-relative path, legacy /storage/ path, legacy absolute URL, and a genuinely external URL it must leave untouched — and resolves the first three against whichever disk is active. An external CDN link is no longer mistaken for our own media.'],
                ['type' => 'changed', 'text' => 'Uploads now persist a disk-relative path instead of an absolute URL. The temp-upload relocation action, video upload service and the image, gallery and video content blocks all wrote asset("storage/…") strings into the database, so every one of those rows was bound to the host that wrote it and went stale on a domain change or a move to a bucket; they now store the path and resolve it at render time.'],
                ['type' => 'fixed', 'text' => 'Media that would break as soon as the disk left the local filesystem now resolves through it: user avatar, staff and partner images, post featured images, service and gallery URLs, the admin sidebar logo, the site head, nav and footer partials, the error and minimal layouts, and the About-Us and SEO screens. Most called Storage::url(), which resolves the default disk rather than the public one and only worked by accident; the rest used asset("storage/…") and bypassed disk config entirely.'],
                ['type' => 'added', 'text' => 'A migration normalises the media already stored in the database into disk-relative paths, including structured attributes inside saved content blocks, so historical rows become portable. Rich-text HTML deliberately keeps its absolute URLs: a relative path in stored HTML resolves against the page, not the media disk. The reverse is a no-op because the original URLs are not retained.'],
                ['type' => 'added', 'text' => 'A media:migrate-to-s3 command copies the local media folder into the bucket for the cutover, with --dry-run to see the plan, --prefix to move one folder, --force to overwrite, and existing keys skipped so it is safe to re-run.'],
                ['type' => 'fixed', 'text' => 'The video poster frame is now extracted from the PHP upload temp file before the video is stored, because an object store exposes no readable local path for FFmpeg to open, and storing the upload moves the temp file away. Storing a video whose frame cannot be read returns the video with no thumbnail instead of failing.'],
                ['type' => 'changed', 'text' => 'The bucket-backed public disk sets throw and report, so a rejected upload fails loudly and is logged instead of silently discarding media, and the block builder resolves its editor previews against the active disk base URL rather than assuming /storage.'],
                ['type' => 'security', 'text' => '.env.example documents the cutover: PUBLIC_DISK_DRIVER drives media storage, FILESYSTEM_DISK never resolves it and must stay local, AWS_DEFAULT_REGION must be "auto" for Cloud Object Storage, and the bucket credentials exist only as Laravel Cloud shared secrets and are never committed.'],
                ['type' => 'added', 'text' => 'Coverage for the resolver and for every media-carrying block: MediaUrl unit tests, and a block test that renders relative, legacy-path, legacy-absolute and external values through the production renderer and asserts the URL each one emits.'],
            ],
        ],
        [
            'version' => '1.14.0',
            'summary' => 'Video block embeds reliably: deferred YouTube player and a stated referrer policy',
            'released_at' => '2026-10-11',
            'notes' => [
                ['type' => 'fixed', 'text' => 'On the hosted site a YouTube video block reported "too many redirects" where its preview thumbnail should have appeared, and nothing played. The player iframe was built during page render, so the visitor first had to clear the platform\'s third-party consent-cookie exchange — browsers that block third-party cookies loop there instead of showing the video. The block now renders the platform thumbnail with a play control and builds the iframe only on click.'],
                ['type' => 'added', 'text' => 'The thumbnail layer is a real link to the video\'s watch page, so the block still works where JavaScript is unavailable and never depends on the embed host to render something. The play control carries an aria-label.'],
                ['type' => 'changed', 'text' => 'Every embedded provider iframe — YouTube, Vimeo, Dailymotion and Facebook — and the block builder\'s live preview now declare referrerpolicy="strict-origin-when-cross-origin", matching the attribute in the embed snippet each provider hands out.'],
                ['type' => 'changed', 'text' => 'YouTube embed URLs carry ?feature=oembed in both renderers, so the editor preview and the published page request the same player instead of two different ones. Tracking parameters never reach the embed URL: a link pasted as youtu.be/ID?si=… still resolves to the bare ID.'],
                ['type' => 'added', 'text' => 'VideoEmbedFacadeTest asserts that a YouTube block ships zero iframes and issues no request to the embed host before the click, that the deferred player carries the correct source and referrer policy, that all four providers declare the policy, and that uploaded files and non-YouTube providers keep their previous markup.'],
            ],
        ],
        [
            'version' => '1.15.0',
            'summary' => 'System overview dashboard: cost-tiered diagnostics, package inventory, posture checklist and a redacted export',
            'released_at' => '2026-10-15',
            'notes' => [
                ['type' => 'added', 'text' => 'A new /admin/system-overview dashboard reports the running application as nine independently measured sections: application, security posture, PHP runtime, database, backing stores, queue and scheduler, sessions, storage and cache, and dependencies. Each caches on its own, so one unavailable service cannot blank the page, and a probe that throws becomes a failed card with the exception left in the log. Values come from the running configuration, never from .env.'],
                ['type' => 'added', 'text' => 'Sections are separated by cost rather than by topic. Six light sections are measured while the page renders, about 47 ms for the whole page. The two heavy ones — the storage walk and the dependency lock files — are never measured inside a page request: a queue job warms them twice daily, each card offers "Measure now" and "Warm in background", and an unmeasured card shows a placeholder instead of blocking. Every file-system walk is capped at 30,000 files or 500 ms.'],
                ['type' => 'added', 'text' => 'The Dependencies card carries a searchable package inventory under the totals: free-text search over names and descriptions debounced at 300 ms, a Composer/npm filter, and 15 rows a page, with no DataTables. composer.lock is about a megabyte of JSON, so the parsed rows and the totals are cached against the lock files\' modified-time and size fingerprint — editing a dependency invalidates the entry by comparison instead of by TTL, and a keystroke filters an array rather than re-reading the file.'],
                ['type' => 'added', 'text' => 'A Security Posture card states the verdicts a reviewer asks for before trusting an install: debug mode, the registration and email-verification switches, which second factors Fortify offers and how many privileged accounts actually confirm one, whether the runtime directories are writable, free disk space, whether every client is trusted as a proxy, a .env left inside public/, the Vite dev marker that silently breaks asset delivery when it goes stale, and whether the audit trail is recording.'],
                ['type' => 'added', 'text' => 'A redacted JSON export sits behind its own "export system overview" permission rather than reusing the view permission, because reading the dashboard and taking a copy of it off the server are different risks; it stays Super Admin only. The file names what it withheld, keeps only each line\'s label, value, status and hint, takes heavy sections from cache so an export never walks the file system, and records every download in the audit trail with its section, line and withheld counts.'],
                ['type' => 'security', 'text' => 'Lines a probe marks sensitive never travel in the export: the database endpoint and the trusted-proxy ranges are filtered out, and coverage asserts that a seeded database or SMTP password appears neither on the page nor in the file. Package homepages read from composer.lock are treated as a link only when they use http or https; any other scheme is dropped rather than handed to the view as a clickable URL.'],
                ['type' => 'changed', 'text' => 'The admin sidebar lists the page under its System group behind the new permission, and "Re-measure" drops every cached section and hands the heavy ones back to the queue.'],
            ],
        ],
        [
            'version' => '1.16.0',
            'summary' => 'System overview authorization: lines are revealed per reader, and the Database card works on sqlite',
            'released_at' => '2026-10-16',
            'notes' => [
                ['type' => 'security', 'text' => 'Sections are cached under one key with no user dimension, so a line measured for a reader entitled to it reached the next reader who was not. A line now declares the ability it needs and the section is filtered when it is read: the cache keeps the full measurement, and the database endpoint reaches the page only for a reader holding "manage environment". Probes no longer branch on who is asking, so a warm-up running with nobody authenticated cannot delete lines for readers entitled to them.'],
                ['type' => 'fixed', 'text' => 'The Database card failed on every sqlite run. PRAGMA page_size and page_count return a row keyed by the pragma name, so reading index zero of the cast row threw "Undefined array key 0" and the section degraded to "This section could not be measured". Development never noticed because the local connection is MariaDB and never takes that branch; a regression test now measures the section on sqlite.'],
                ['type' => 'security', 'text' => 'The heavy-section card refuses readers who lack the view permission, so a component id alone cannot drive "Measure now" or background warming through the Livewire update endpoint. The package inventory card already refused.'],
                ['type' => 'changed', 'text' => 'Hiding a line also removes its severity from the section verdict, so a finding a reader may not see no longer marks their card as needing attention or counts in the page summary.'],
            ],
        ],
        [
            'version' => '1.17.0',
            'summary' => 'Blog authorship, a publish gate, an archived state and a post list that edits without opening the editor',
            'released_at' => '2026-10-17',
            'notes' => [
                ['type' => 'added', 'text' => 'Posts now have a real author. A nullable user_id column (nullOnDelete) is stamped with the signed-in admin on create and backfilled to the earliest Super Admin on existing rows, so no content is left unattributed. The public post page and each listing card show the author with avatar initials, the publish date and a computed reading time.'],
                ['type' => 'added', 'text' => 'Archived is a third workflow state: the status column is now a draft, published, archived enum. An archived post keeps its content but leaves the public site, because the published scope only ever matches published. Restoring one re-runs the publish check, so an unfinished post cannot slip back online just by unarchiving it.'],
                ['type' => 'added', 'text' => 'The editor has a publish gate: a live "Ready to publish" checklist for content blocks, excerpt and category, a submit that would produce an unfinished post is stopped with the missing fields named, and "Save as draft" is always offered. The same three rules are enforced server-side, so the browser check is guidance rather than the only defence.'],
                ['type' => 'added', 'text' => 'Title (120) and excerpt (300) limits now live as constants on the Post model, driving the form counters, the validation rules and the error copy from one place so a counter can never disagree with its rule. Both columns were widened to hold the limits the form already enforced.'],
                ['type' => 'added', 'text' => "Status no longer requires opening the editor. A row's status pill, or a right-click anywhere on the row, opens a menu, and a bulk bar acts on the selected rows; both call JSON endpoints behind the existing update-blog permission, repaint the pills from the server's reply without a page reload, and report the outcome in a toast. The menu offers only the states the row is not already in, and updates immediately after a change."],
                ['type' => 'added', 'text' => 'A published post carrying a future date is recognised as scheduled, stays hidden from readers and shows a blue Scheduled pill. Because it is already published, the menu does not offer Publish for it again.'],
                ['type' => 'added', 'text' => 'Bulk publish refuses the whole selection with a count when any chosen post is unfinished, rather than publishing part of it and leaving the rest; moving a post back to draft is never blocked.'],
                ['type' => 'added', 'text' => 'Add category and add tag in the editor are no longer links that navigate away. They open a create dialog on the spot: saving posts the name, closes the dialog and ticks the new chip, so unsaved post content is never lost or reloaded. Required-name and duplicate-name errors render inside the dialog, and the taxonomy CRUD pages still redirect as before.'],
                ['type' => 'fixed', 'text' => 'Two posts, categories or tags whose names slugify onto an existing unique slug collided and failed to save. Slugs now disambiguate as name, name-2, name-3, ignoring the record being updated.'],
                ['type' => 'fixed', 'text' => 'Deselecting every category or tag on a post silently kept the old selection: an emptied checkbox group is absent from the request, and the relation sync only ran when the key was present.'],
                ['type' => 'changed', 'text' => 'The public listing was rebuilt: the newest post is promoted to a hero card that never repeats in the grid, is skipped when fewer than two posts exist or any filter is active, and is counted in the "Showing 1-N of M posts" line that previously undercounted it. Category tabs now carry each category\'s published post count.'],
                ['type' => 'changed', 'text' => 'The "Stay updated" intro copy and the newsletter sign-up band are gone from the blog index; nothing else on the site used that markup.'],
                ['type' => 'fixed', 'text' => "Every ->latest() call on posts ordered by created_at rather than published_at: Eloquent's concrete latest() method shadows the model's scopeLatest. The listing and the admin index now order explicitly by published_at with an id tiebreaker, so posts saved in the same second keep a stable order."],
                ['type' => 'changed', 'text' => 'The create and edit screens share basic-info, taxonomy and publish-settings partials instead of repeating that markup on both pages.'],
                ['type' => 'added', 'text' => 'BlogPostsTest, BlogTaxonomyTest and BlogListingTest cover authorship, the publish gate, archived transitions, the permission guards on the status endpoints, quick-add validation, slug collisions, hero promotion and filter scoping.'],
            ],
        ],
        [
            'version' => '1.18.0',
            'summary' => 'Optional fields stopped blocking creates: the service icon column is gone and a blank testimonial role saves',
            'released_at' => '2026-10-17',
            'notes' => [
                ['type' => 'fixed', 'text' => 'Admins could not create a service at all. The services.icon column was NOT NULL without a default while the Form Request treated it as nullable and no form ever posted it, so MySQL strict mode rejected the insert with error 1364. The column is now dropped: nothing above the database required it, no public view ever read it, and the admin list showed only a dead column printing the raw icon name.'],
                ['type' => 'fixed', 'text' => 'Saving a testimonial with the field its own form labels optional left blank failed the same way, with error 1048. The role column is now nullable, and pages render a testimonial that has no role.'],
                ['type' => 'changed', 'text' => 'The icon field is removed from the Service model, its Form Request, the admin services list and three translation files. The migration reverses by restoring the column as nullable, so the removal is undoable without re-introducing the constraint that caused the failure.'],
                ['type' => 'changed', 'text' => 'The same NOT NULL-without-a-default mismatch was swept across the admin-writable tables: gallery images, partners, staff, categories, tags and glass types are each either required by their request or written unconditionally by their service, so only these two columns needed changing.'],
                ['type' => 'added', 'text' => 'OptionalContentColumnTest creates a service through the admin form with no icon field, asserts the column is absent from the schema and the rendered list, and creates and renders a testimonial with a blank role.'],
            ],
        ],
        [
            'version' => '1.19.0',
            'summary' => 'Blog activity on the profile page and an author filter on the post list',
            'released_at' => '2026-10-17',
            'notes' => [
                ['type' => 'added', 'text' => 'The Activity card on the profile page gained a fourth "Blog posts" tile with the signed-in user\'s post count. It joins the existing loadCount call, so the stat row is still a single aggregate query rather than a lookup per stat, and the grid reads four tiles on wide screens, two on tablet and one on mobile.'],
                ['type' => 'added', 'text' => "Below Recent bookings the card lists the user's five newest posts — title linking to that post's edit screen, a status pill, and the publish date falling back to the creation date — with a 'No blog posts yet.' empty state in the same muted styling. The list is always scoped to the authenticated user, so another author's posts cannot appear."],
                ['type' => 'added', 'text' => 'View all opens the admin post list filtered to that author: the list accepts a user query parameter, shows a banner naming who is filtered with a clear-filter link, eager-loads author, categories and tags, and keeps the filter across pagination.'],
                ['type' => 'added', 'text' => 'ProfileDetailsTest covers the new tile, the recent-posts list with its empty state, the author filter on the post list, and that only the signed-in user\'s posts are counted or shown.'],
            ],
        ],
        [
            'version' => '1.20.0',
            'summary' => 'Honest session counts, and a cleanup that actually collects',
            'released_at' => '2026-10-17',
            'notes' => [
                ['type' => 'fixed', 'text' => 'The Sessions card counted every row in the sessions table as a live session, with no lifetime filter and no guest split, so one account could appear to hold 18 live sessions while the rows were in fact expired or belonged to visitors and bots that never signed in. Distinct users now counts signed-in rows only, and the card separates authenticated from guest traffic.'],
                ['type' => 'fixed', 'text' => 'The scheduled cleanup never deleted framework session rows: it only closed ledger entries and trimmed history. Removing an expired row was left to the probabilistic garbage collector in the session driver, which fires on roughly 2% of requests and so almost never on a quiet site, letting the backlog grow without limit. The sweep now deletes rows past the lifetime in batches of 500, each batch in its own transaction.'],
                ['type' => 'added', 'text' => 'A single reconciliation service owns session cleanup, so the scheduled command, the admin action and the overview card read the same definition of live. It measures valid, guest, stale and distinct-user counts, closes idle and expired sign-ins, drops history past the retention window, and records each run so the card can say when cleanup last happened and what it removed.'],
                ['type' => 'fixed', 'text' => 'A recorded sign-in only counts as active while the session behind it still exists and has not expired. An orphaned ledger row, left open because the framework row was destroyed with no foreign key between the tables, no longer shows as a device in use; those rows close with a new reason naming what happened.'],
                ['type' => 'changed', 'text' => 'The session sweep runs hourly instead of nightly, with the overlap lock expiring after 10 minutes rather than 24 hours and only on one server. A previous default meant a single run killed mid-sweep silently suppressed every later run for a full day, which is the likeliest explanation for sign-ins left open past the lifetime in production.'],
                ['type' => 'added', 'text' => 'A dry-run option on the command reports exactly what would be deleted and closed without changing anything, so the size of a backlog can be read before it is collected.'],
                ['type' => 'added', 'text' => 'A Prune now action on the Sessions card takes the same code path as the schedule behind the existing manage-user-sessions permission, records the counts in the audit trail, re-measures the card so the redirect shows fresh numbers, and always spares the session making the request so an administrator cannot sign themselves out mid-task.'],
                ['type' => 'added', 'text' => 'Company Settings, General tab, Account Security gained a session idle timeout in minutes. A sign-in inactive that long closes early, inside the framework lifetime, and its session row is deleted with it so the browser is genuinely signed out rather than merely recorded as finished. Zero leaves the lifetime in charge.'],
                ['type' => 'added', 'text' => 'The session ledger can now record two further end reasons, one for a lost session row and one for an idle timeout. Both fit the existing column, so no migration was needed.'],
                ['type' => 'changed', 'text' => 'Every number on the card comes from one measurement, so the rows cannot contradict each other. An amber dot and a remediation hint appear while anything waits to be collected, and the card turns green once the sweep has run.'],
            ],
        ],
        [
            'version' => '1.20.1',
            'summary' => 'The sidebar shows the uploaded profile picture',
            'released_at' => '2026-10-17',
            'notes' => [
                ['type' => 'fixed', 'text' => 'After uploading a profile picture the sidebar badge kept showing initials: both avatar slots rendered initials only and never read the avatar_url accessor, so no upload could change what appeared. The accessor, the public disk, the storage link and the saved URL were all already correct, which is why the profile page showed the image while the sidebar did not.'],
                ['type' => 'added', 'text' => 'A shared user-avatar component is now the single source of truth for a person’s picture. It draws the image over the initials inside the same round badge, so a missing or broken URL falls back to initials without any script, and it hides the initials from screen readers only while a picture is actually painted. Extra classes merge onto the badge, which preserves the centring the collapsed rail needs.'],
                ['type' => 'changed', 'text' => 'The upload path is untouched: the same validation, the same square webp written to the public disk, and the same deletion of a file that a replacement supersedes, with a rejected upload leaving the stored picture in place. No cache-busting parameter was added, because every upload stores a fresh random file name and the URL therefore changes by itself.'],
                ['type' => 'added', 'text' => 'Feature tests cover the initials-only state, the picture appearing in the sidebar markup after a real profile upload, the fallback staying in the markup behind it, the superseded file being removed on replacement, and a rejected upload preserving both the stored path and its file.'],
            ],
        ],
        [
            'version' => '1.21.0',
            'summary' => 'Sub-categories are built inside the glass type you are editing',
            'released_at' => '2026-10-17',
            'notes' => [
                ['type' => 'fixed', 'text' => 'On the Edit Glass Type page, "+ Add Sub-Category" was a link to a separate create screen, and saving there redirected to the sub-category index instead of back to the parent you came from. The glass-type form posts the whole page, so any unsaved rename or reordering was silently discarded the moment the link was clicked.'],
                ['type' => 'added', 'text' => 'A modal now opens on the same page, following the quick-add taxonomy pattern already used by the post editor. One component serves both jobs: the title and the save label switch between creating and editing, and the fields are name, a slug generated from the name that stays editable, sort order and an active flag.'],
                ['type' => 'changed', 'text' => 'The parent glass type is preset and shown as text rather than a select, so a sub-category created from a glass type cannot land under a different one by accident. It is still validated server-side against the glass types table.'],
                ['type' => 'changed', 'text' => 'Saved rows refresh in place — a new row is cloned from an inert template, an edited row is repainted — and nothing reloads, so the glass-type form keeps its unsaved values through the whole round trip. Validation problems appear inside the modal and success uses the shared toast stream.'],
                ['type' => 'added', 'text' => 'Existing rows can be edited and deleted from the same pattern. Deleting asks for confirmation, and the guard that refuses to remove a sub-category still referenced by a quote now surfaces as an error toast with the row left in place instead of a redirect flash.'],
                ['type' => 'added', 'text' => 'The create, update and delete endpoints answer JSON for the modal while keeping their redirect-and-flash behaviour for the standalone pages, so the sub-category index flow is unchanged. Every route stays behind the manage-services permission.'],
                ['type' => 'changed', 'text' => 'The management list now includes inactive sub-categories. It previously reused the public active-only query, so saving a new sub-category as inactive made the row look like it had failed to save until the next reload.'],
                ['type' => 'changed', 'text' => 'Slug uniqueness deliberately stays global, matching the unique index that already exists on the column, so no migration ships with this release. The modal reports a collision against any parent, and the slug it displays is the one actually stored, because the service still appends a suffix to a duplicate.'],
                ['type' => 'added', 'text' => 'Eleven feature tests cover the markup swap, inactive rows staying visible, JSON create and update with the generated slug, the missing-name and duplicate-slug error bags, deletion, the quote reference guard, permission denial, and that the standalone create page still redirects after a plain form save.'],
            ],
        ],
        [
            'version' => '1.22.0',
            'summary' => 'Services tell the full story on the public site',
            'released_at' => '2026-10-17',
            'notes' => [
                ['type' => 'fixed', 'text' => 'The Detailed Description on the Edit Service form was validated, mass-assignable and saved correctly for every service, yet the public services page only ever echoed the summary — so the field looked broken from the admin side while the write path had been fine all along.'],
                ['type' => 'added', 'text' => 'Each service row now keeps its summary on the card and adds an expandable Read more section holding the detailed description, using the collapse plugin already bundled for the site. The label flips to Show less, the chevron turns, and aria-expanded with aria-controls describe the panel to assistive technology.'],
                ['type' => 'changed', 'text' => 'Both alternating image-left and image-right layouts include one shared partial, so the summary and the expandable copy cannot drift apart between them as they had, duplicated as two identical lines of markup.'],
                ['type' => 'changed', 'text' => 'The field is a plain textarea, not the rich editor used by the block builder, so the copy is escaped and only line breaks become markup. Text saved through the admin can never become script on a public page, and no unescaped echo of stored content was introduced.'],
                ['type' => 'changed', 'text' => 'The section hides completely when the detailed description is empty, whitespace-only, or merely repeats the summary, leaving the short description as the fallback with no orphan toggle and no duplicated paragraph.'],
                ['type' => 'changed', 'text' => 'Search metadata for the services page is unchanged: it still resolves from the static-route override and then the site-wide defaults. Detailed per-service descriptions cannot reach metadata without a per-service route, which this release deliberately does not add.'],
                ['type' => 'added', 'text' => 'Eight feature tests pin the behaviour the page was missing: the detailed copy reaches the page, line breaks survive the escape, stored markup is escaped rather than executed, empty and duplicate copy suppresses the toggle, and both row layouts render the section.'],
            ],
        ],
        [
            'version' => '1.23.0',
            'summary' => 'The homepage only claims what it can prove',
            'released_at' => '2026-10-17',
            'notes' => [
                ['type' => 'fixed', 'text' => 'The counter strip published four invented figures — 0+ Happy Clients beside 4.8, 98% and 24h — so the page that needed proof led with numbers no record backed. Counters now appear only above explicit thresholds: Verified Reviews from five published reviews, Average Rating from five rated ones, and Recommend Us when 80 per cent of ratings reach four stars.'],
                ['type' => 'added', 'text' => 'Admin-editable fact tiles fill the row beneath the real counters, so a business without enough reviews yet still shows substantive claims instead of zeros. A row missing its label or value is dropped rather than rendered half-empty.'],
                ['type' => 'fixed', 'text' => 'The hero banner was a hardcoded Unsplash photograph, which no editor could change and which advertised a fleet of vehicles nobody in Gaborone had ever seen. It now resolves from an admin upload, then the newest public gallery shot, then the gradient backdrop it had before the image was ever added.'],
                ['type' => 'added', 'text' => 'One ContactDetails value object is the sole source of every telephone number, WhatsApp link, email, address, opening-hours string and quote-request prefill. The header, footer, both call-to-action bands, the urgency band, the mobile bar, the floating WhatsApp bubble, the contact page and the structured data all read it, verified across seven public routes.'],
                ['type' => 'added', 'text' => 'LocalBusiness JSON-LD with the postal address, telephone, email, opening hours, service area and social links is attached to any page that does not bring its own schema, and the FAQ block emits its FAQPage node from the same settings rows the accordion renders.'],
                ['type' => 'changed', 'text' => 'The featured services grid, the warranty band, the process steps, the service-area block and the FAQ accordion all read the database or settings instead of copy baked into the view, and every section with nothing to show hides itself rather than presenting an empty shell — including the review rail, which no longer renders a prompt to be the first reviewer.'],
                ['type' => 'changed', 'text' => 'The two competing taglines are unified on Precision Automotive Glass, and the homepage headline now follows the value saved in settings so the admin edit reaches the H1 rather than only the meta description.'],
                ['type' => 'changed', 'text' => 'Get a Quote is the single primary action in the navigation and hero, Book Appointment is the secondary, and every one of those targets, including the mobile sticky bar, clears 44 pixels with a visible focus ring.'],
                ['type' => 'security', 'text' => 'Structured data embedded in the page escapes the script delimiters explicitly, because the local PHP build ignores JSON_HEX_QUOT and raw stored copy could otherwise close the element it sits in.'],
                ['type' => 'added', 'text' => 'A Homepage tab in Settings holds the hero upload, tagline, years of experience, fact tiles, warranty copy and highlights, urgency toggle, both call-to-action bands, WhatsApp prefill, coverage areas, process steps and FAQs, with the eighteen new keys registered in both default sets so the admin screen and the public page cannot drift.'],
                ['type' => 'added', 'text' => 'Thirty-six feature tests across two files pin the credibility rules: empty states hide, counters stay below their thresholds until records exist, tiles come from settings, and no published contact link escapes the central source.'],
            ],
        ],
        [
            'version' => '1.24.0',
            'summary' => 'The pages around the homepage get the same treatment',
            'released_at' => '2026-10-18',
            'notes' => [
                ['type' => 'fixed', 'text' => 'The About Us page rendered nothing but its own heading. The editor wrote the first row while the public page read the first active row, both landing on a blank record created when the screen was opened, and the 226 characters of real copy sat unused behind it. One resolver now prefers an active row that actually holds copy, and both surfaces use it.'],
                ['type' => 'fixed', 'text' => 'The Name field on the quote form threw a JavaScript SyntaxError on every keystroke because its input filter was a regular-expression literal split across two lines, so nothing was ever cleaned and the console filled up. The replacement stays on one line and is unicode-aware, so apostrophes, hyphens and non-ASCII letters survive typing.'],
                ['type' => 'fixed', 'text' => 'Attaching a photo of the damage on the quote form sent it to the admin uploader, which sits behind authentication and an admin permission, so a guest could never complete the upload — and the path it returned was never read server-side anyway, because the request only accepts a real file. The AJAX layer is gone and the file ships with the multipart submit that already worked, now with a local preview and a visible validation error.'],
                ['type' => 'fixed', 'text' => 'Bookings could be accepted and then lost. The free-text columns are 191 characters wide while validation allowed 255 for vehicle details and 500 for the address, so a long value reached MySQL and failed; the same over-capacity ceilings sat on the quote and contact forms. All three requests now match the columns and say so in the error. No migration ships with this release.'],
                ['type' => 'fixed', 'text' => 'The gallery lightbox was dead on arrival. Its script block was pushed after the layout component closed, and the stack had already been flushed, so Quick View threw a ReferenceError on every card while the page itself looked healthy.'],
                ['type' => 'fixed', 'text' => 'Fact tiles without a suffix vanished from both the homepage strip and the services hero, because the reader required every listed field to be filled — which quietly deleted the Lifetime workmanship warranty tile. Required and optional fields are now distinct.'],
                ['type' => 'fixed', 'text' => 'An empty WhatsApp setting still produced a headline card with a blank number and a dead wa.me link on the contact page. Unconfigured numbers now yield no card at all.'],
                ['type' => 'changed', 'text' => 'The services hero replaced its invented tiles with the live count of active services followed by the same admin-editable facts the homepage uses, and the contact page dropped its invented telephone numbers, placeholder street, generic maps link and four competing opening-hours renderings in favour of the central source, with the map embed gated behind a configured API key and directions falling back to a search on the stored address.'],
                ['type' => 'changed', 'text' => 'The gallery dropped six hardcoded stock photographs and a placeholder empty state, and its empty state now converts with quote, WhatsApp and call actions instead of advertising a portfolio that is not there.'],
                ['type' => 'changed', 'text' => 'The booking page gained a call and WhatsApp path in its hero, and lost the dead copy claiming same-day appointments and weekend closures that the availability rules never enforced — closure comes from the working-hours settings.'],
                ['type' => 'security', 'text' => 'The signed booking confirmation is forced out of search indexes in code rather than in a database row, so an editor cannot re-expose a client name, telephone number and vehicle by deleting the record, and the malformed telephone link in the quote receipt email is gone.'],
                ['type' => 'added', 'text' => 'Twenty-eight feature tests cover the release: the populated row wins, filters stay on one line, over-length input is rejected before the database sees it, no public page can show an invented contact detail, and the confirmation page stays unindexed while the rest remain indexable.'],
            ],
        ],
        [
            'version' => '1.25.0',
            'summary' => 'Booking becomes a real appointment system',
            'released_at' => '2026-10-18',
            'notes' => [
                ['type' => 'added', 'text' => 'The booking page is now a four-step wizard — vehicle and service, date and time, your details, review and confirm — with per-step validation, inline errors, data preserved when stepping back, and a local draft that survives a refresh until the booking exists.'],
                ['type' => 'added', 'text' => 'Availability is computed, not asserted: slot interval, per-service durations and per-location capacity are settings, closure dates are an admin-managed table that overrides the weekly hours, and a job occupies its whole span so a 90-minute windscreen still holds a bay at the half hour after it starts. The last slot of a day always finishes before closing.'],
                ['type' => 'added', 'text' => 'The calendar dims past, closed and fully-booked days from one batched survey per month, announces how many days remain available, and offers Today, Tomorrow and This-week quick picks plus an earliest-available jump; the slot column refreshes instantly with a skeleton and an aria-live count.'],
                ['type' => 'added', 'text' => 'Bookings carry structured service type, damage type, make, model and year, a WhatsApp preference, an optional damage photo resized to WebP, and a human reference like HB-000004 shown on the confirmation, in admin and in every email.'],
                ['type' => 'added', 'text' => 'The confirmation page adds an Add to calendar download served from a signed route and a prefilled WhatsApp message carrying the reference, alongside the full summary and the booking event timeline.'],
                ['type' => 'added', 'text' => 'Admin gains the availability settings — interval, workshop and mobile capacity, per-service durations — and a closed-dates manager that refuses past and duplicate dates.'],
                ['type' => 'fixed', 'text' => 'Two submissions racing for the last slot can no longer both win: the day is re-checked inside a transaction against row locks, a deadlock retries once, and the loser returns to the step that failed with their other answers intact. A resubmitted idempotency token returns the original booking instead of creating a twin.'],
                ['type' => 'fixed', 'text' => 'The availability window is read in three queries per surveyed range instead of three per slot, and the relation behind rescheduled appointments is hydrated without the eager-load path the query detector misreads as an N+1, so the page no longer raises a false alarm while staying free of real ones.'],
                ['type' => 'changed', 'text' => 'The booking flow adopts the site identity — the red the rest of the public pages paint, with Cabinet Grotesk display headings — through scoped tokens, so the wizard reads as part of the same system while keeping its own dark panel surfaces.'],
                ['type' => 'security', 'text' => 'Client-side availability is treated as a hint only: every rule is re-validated server-side on submit, the damage photo is restricted to image types with a size ceiling, and the calendar download requires a valid signature.'],
                ['type' => 'added', 'text' => 'Eighty-four feature tests across six files pin the flow: slot generation honours closing time, Sunday and closures, capacity and double-booking races behave, each step validates and resumes where it failed, and the confirmation, calendar file and admin surfaces render what was booked.'],
            ],
        ],
        [
            'version' => '1.25.1',
            'summary' => 'The homepage hero is isolated from the public gallery',
            'released_at' => '2026-10-18',
            'notes' => [
                ['type' => 'fixed', 'text' => 'With no hero upload configured, the homepage hero silently fell back to the newest active public-gallery image, so an empty Company Settings > Homepage field advertised an arbitrary gallery photograph the customer never picked for it. SiteService::heroImage() now resolves the admin upload only; with nothing configured it returns null and the hero keeps its gradient backdrop, which is what the method\'s own docblock had always promised. The public gallery and the About-Us hero were never involved, and the structured-data image already read the setting alone.'],
                ['type' => 'added', 'text' => 'A HomepageContentTest case now seeds an active gallery image and asserts the hero ignores it, closing the gap where the previous test passed only because the sqlite fixture held no gallery rows for the fallback to borrow.'],
            ],
        ],
        [
            'version' => '1.25.2',
            'summary' => 'Image uploads reach the record they were chosen for',
            'released_at' => '2026-10-18',
            'notes' => [
                ['type' => 'fixed', 'text' => 'Picking a photo in the admin could save the old file, or nothing at all, because every image resolver read the form\'s image_path input before it looked for an uploaded file. Admin edit forms — and the services create form — keep the current path in a hidden input so a page reload does not lose it, so that field was never empty on those screens: the stored value, usually the placeholder sentinel, shadowed the fresh upload and the file was discarded. ServiceService, GalleryService and MediaLibraryService now resolve in the only sane order — an uploaded file wins, then a deliberately supplied stored path, then the placeholder on create or the existing file on update.'],
                ['type' => 'fixed', 'text' => 'The same mismatch explained why one page looked healthy and its neighbour did not: /admin/gallery/create carries no hidden image_path field, so its uploads landed, while the services forms and every edit screen did carry one, so theirs silently did not. The reference implementation was never the difference — the hidden field was.'],
                ['type' => 'added', 'text' => 'One shared admin image field, x-ui.image-field, now used by both services forms: it uploads through the admin.image-upload endpoint with a live preview, a progress bar, inline errors and optional removal, then submits the resulting path through a hidden input guarded by SafeStoredUploadPath. This is the pattern blog posts and About Us already used, so services no longer needs a second, multipart-only path through the same pipeline.'],
                ['type' => 'fixed', 'text' => 'placeholder.gif resolved through the public disk, which is driver-switchable, so with object storage configured the sentinel pointed at a file that had never been uploaded there. MediaUrl now recognises bundled public-root assets and serves them from the app origin, where git ships them on every deploy.'],
                ['type' => 'fixed', 'text' => 'A service update saved twice or not at all depending on the branch taken: removal ran its own save inside a helper while replacement fell through separately. Both paths now converge on one update, replacement deletes only the file the service actually owns (placeholder and anything outside services/ are left alone), and clearing remove_image no longer drops the other field changes with it.'],
                ['type' => 'fixed', 'text' => 'ServiceRequest read $validated[\'features\'] directly, which raises an undefined-index warning when the textarea is absent from the payload — a real case for JSON and partial submits — now covered by a filled() check before the line-split.'],
                ['type' => 'added', 'text' => 'Nine upload tests pin the behaviour end to end: a multipart create stores on the public disk, an AJAX upload followed by create saves the returned path, non-images and oversized files are refused on both routes, replacing an image deletes the old file, adopted paths outside services/ are never deleted, removing an image keeps the rest of the update, and saving without a new file keeps the existing one.'],
            ],
        ],
        [
            'version' => '1.26.0',
            'summary' => 'The blog can be searched from anywhere on it',
            'released_at' => '2026-10-18',
            'notes' => [
                ['type' => 'added', 'text' => 'Ctrl+K (Cmd+K on macOS) opens a command palette over the blog listing and every post: a dimmed blurred backdrop, a centred panel with a labelled search field, results grouped into Articles, Categories and Tags inside one listbox, and a footer spelling out the keys. / opens it whenever focus is not already in a field, Esc or a tap outside closes it, and the control that opened it gets focus back.'],
                ['type' => 'added', 'text' => 'A dedicated JSON endpoint answers the type-ahead instead of the Livewire listing. That component re-renders the hero, the category tabs, the tag cloud and a nine-item grid for every keystroke, which is far too much work for a search-as-you-box; the new path selects seven columns, returns at most six posts plus four categories and four tags, and is throttled to 60 requests a minute per visitor with an 80-character ceiling that answers 422 rather than quietly truncating the term.'],
                ['type' => 'added', 'text' => 'Results rank a title match above an excerpt match, then by publish date, and read only through Post::published() — drafts, archived posts and future-scheduled ones cannot reach the palette any more than they can reach the listing. Categories and tags surface as filter links, so a term that names a section is useful even when no article title contains it.'],
                ['type' => 'fixed', 'text' => 'A query containing % or _ was treated as a LIKE pattern, so searching for 100% matched every row. The palette escapes \\, % and _ before binding the term, so those characters now search for themselves; the listing’s own filter was left as it was.'],
                ['type' => 'added', 'text' => 'Typing waits 250ms and ignores anything shorter than two characters. Each request carries an AbortController and a monotonic id, so an answer that has been superseded is discarded rather than overwriting the fresher results on screen, and a 429 or any failed response falls back to a plain “search is unavailable” message instead of an empty list.'],
                ['type' => 'added', 'text' => 'Recent searches live in the browser only, under one storage key, capped at six, de-duplicated case-insensitively and newest first. A term is recorded when a result is opened or a query submitted, never on a keystroke; each row has its own ✕, Delete removes the highlighted one, and one button clears the lot. Every storage touch is wrapped, so a private-mode browser loses the history rather than losing search.'],
                ['type' => 'security', 'text' => 'Neither the typed term nor stored post content is ever parsed as markup: rows are filled with text binding and highlighting appends text nodes and <mark> elements, never innerHTML. A payload typed into the field renders as literal text with no element injected, and the endpoint stays on parameterised queries with no concatenated SQL.'],
                ['type' => 'added', 'text' => 'With nothing typed the palette is not an empty box: it offers recent searches, the newest posts and the busiest categories, and shrinks back to that state the moment the query drops below two characters.'],
                ['type' => 'changed', 'text' => 'The listing’s search field became the palette trigger — the same pill, now showing the placeholder or the active term beside a Ctrl K / ⌘K badge chosen from the platform — while the Livewire input it replaced stays in the DOM hidden by Alpine, so a page whose Alpine never boots still has a working field. Enter with nothing highlighted navigates to /blog?search=…, which is the same page a non-JS visitor could reach by hand.'],
                ['type' => 'changed', 'text' => 'The header trigger is icon-only and hidden from lg upwards. Desktop has the shortcut and the in-page field, so the label and badge were only ever spending nav width on the two largest breakpoints.'],
                ['type' => 'fixed', 'text' => 'Tapping the dimmed area outside the modal did nothing, because the backdrop sits underneath the full-screen scroll wrapper that actually receives the click. The wrapper and its centring div now close on a self-targeted click, which is the only way to dismiss the palette on a phone.'],
                ['type' => 'fixed', 'text' => 'Livewire runs its own Alpine instance, so anything registered in the site bundle is invisible to markup inside a Livewire component — the trigger failed there with its data object undefined until it carried an inline scope, and the open listener is bound in init() rather than through an x-on window directive that never fired across the two runtimes. x-cloak alone is not enough either: an element with no x-data ancestor is never processed, which had left the header button permanently hidden.'],
                ['type' => 'added', 'text' => 'The palette is a dialog in the accessible sense: role=dialog with aria-modal, a labelled combobox carrying aria-expanded and aria-activedescendant, options inside labelled groups, and a polite status region that announces the result count. The focus trap is hand-written because the focus plugin is not bundled here, body scroll locks while it is open, and the enter and leave transitions collapse under prefers-reduced-motion.'],
                ['type' => 'changed', 'text' => 'No index ships with this release. A leading-wildcard LIKE cannot use a B-tree index and the corpus is small enough that the scan stays sub-millisecond, so both a FULLTEXT index and a search package were deferred until the query shows up in a slow query log.'],
            ],
        ],
        [
            'version' => '1.27.0',
            'summary' => 'Public pages change without a browser reload',
            'released_at' => '2026-10-18',
            'notes' => [
                ['type' => 'added', 'text' => 'The public site swaps pages without a browser reload. Header, mobile drawer, footer, service tiles, blog cards, the gallery grid and its filters, the blog paginator and every in-page call to action carry wire:navigate, so home to services to a post to gallery moves content and the history entry only. Measured here: 820-890 ms a hop against 2.4 s for a full load, and each hop transfers one 15-19 kB document instead of the 2.2 MB a fresh page pulls.'],
                ['type' => 'added', 'text' => 'The primary navigation prefetches on hover and on keyboard focus, so a click after a deliberate hover is instant. Prefetch arms 60 ms after the pointer settles and cancels on leave, which means sweeping across the header asks the server for nothing; the hover-enabled links cost one request each, only when actually rested on.'],
                ['type' => 'added', 'text' => 'The progress bar drawn during a hop is brand red rather than the starter blue, and keeps the built-in 150 ms delay before it starts, so a fast local navigation shows no bar at all while a slow connection always gets one.'],
                ['type' => 'fixed', 'text' => 'wire:navigate was a no-op on five of the seven public pages, with no error anywhere to say so: Livewire injects its JavaScript only on a page that renders a component, and home, services, gallery, contact and quote render none. The public layout now loads that bundle explicitly, which is the one thing any future public page must not forget.'],
                ['type' => 'changed', 'text' => 'What a hop actually replaces turned out to matter more than any individual fix: the whole body is swapped, not just the main region, so the header, drawer and footer re-render from server HTML. Active-link highlighting, scroll restoration, scroll locks and any open mobile menu are correct without being written, and nothing needed persisting, because the only long-lived UI is the cookie banner and it re-derives from its own cookie.'],
                ['type' => 'fixed', 'text' => 'Returning to the gallery died with SyntaxError: Identifier galleryImages has already been declared, killing the lightbox outright. A pushed body script re-runs on every hop, and that script declared its dataset at top level. It is now an IIFE that republishes its globals per visit, so repeat visits work and each reads its own filtered set — skipping the re-run was rejected deliberately, because the dataset changes with the category filter.'],
                ['type' => 'fixed', 'text' => 'That same script bound a document-level keydown for Escape and the arrow keys and never removed it, so after one gallery visit every page on the site answered those keys and threw against the missing lightbox. It is bound once per document now and returns immediately unless a lightbox is actually open.'],
                ['type' => 'fixed', 'text' => 'The header drawer had the same leak plus a worse one: its pushed IIFE reset its own open flag while the scroll lock it had applied stayed on, and its Escape listener stacked another copy per hop. The bindings are delegated from the document, which is the only thing that survives a body swap, and a navigated hook clears the stale state so the first tap after a navigation still opens the drawer.'],
                ['type' => 'fixed', 'text' => 'The blog command palette registered its window keydown listener inside init with no teardown, so every hop between the listing and a post added another Ctrl+K handler that called preventDefault and toggled the dialog again. Alpine destroy now removes both listeners, and a copy left behind by an earlier view refuses to answer the shortcut at all.'],
                ['type' => 'changed', 'text' => 'The site bundle one-shot DOMContentLoaded block bound the mobile menu and modal controls directly to elements a hop then threw away. Everything is delegated at module scope now, and the scroll-parallax targets are re-queried per event instead of cached from the first page that loaded.'],
                ['type' => 'changed', 'text' => 'The homepage hero and sticky call bar re-ran on every visit with no way to unwind: the observer watching the hero was never disconnected, and cleanup hung off pagehide, which a client-side navigation never fires. Teardown now runs on the navigate hook too, one listener set per document, so a second hop to home leaves exactly one headline rather than two.'],
                ['type' => 'changed', 'text' => 'Public pages run one Alpine instead of two. The site bundle used to import and boot its own copy alongside Livewire’s, and the pair did not share component registrations; it now queues them onto Livewire’s alpine:init, the pattern the admin bundle has always used. The chunk went from 53.5 kB to 8.4 kB, 18.8 to 3.1 kB gzipped, and the multiple-instances console warning is gone.'],
                ['type' => 'fixed', 'text' => 'Two pages shipped no JavaScript at all because their script tags sat after the closing layout tag, where Blade discards them with a 200 response and nothing logged. The quote page now actually defines the sub-category loader its glass-type select calls, and its photo preview works; the media library picker and its uploader come back the same way, both verified by rendering the page and looking for the functions.'],
                ['type' => 'added', 'text' => 'The blog listing paginates through a dark view of its own. The framework paginator is light-themed and its links were plain hrefs, so a page turn reloaded in the middle of an otherwise instant listing. The new view is wired for navigation and is deliberately not registered as the global default, so the 21 admin tables keep the plain page links they have always had.'],
                ['type' => 'fixed', 'text' => 'Structured data moved out of the head. A script there is treated as an asset by the navigation layer and is never removed, so each hop kept the previous page’s schema and a visitor browsing four pages collected a stack of conflicting ones. It renders in the body now, which is replaced wholesale, while title, description, canonical, Open Graph and the favicon links all update correctly because they are not assets.'],
                ['type' => 'changed', 'text' => 'Links that must reload stay reloads: anything off-origin, tel, mailto and WhatsApp hrefs, new tabs, downloads, the logout form, and every crossing between the public site and the admin, which ship different Vite bundles. A shared link component applies the rule for database-authored hrefs, so a page-builder button pointing at wa.me cannot accidentally become a navigation link.'],
                ['type' => 'changed', 'text' => 'The admin area is deliberately untouched and still does full page loads. Its theme, sidebar-width and appearance bootstrap runs once in the head, its block editor and post table leak several window listeners per mount, and its 78 views were all built for reloads. Enabling navigation there is its own piece of work, not a side effect of this one.'],
                ['type' => 'changed', 'text' => 'No migration and no index. First paint is still complete server-rendered HTML and every link is a normal href, so a crawler, a reader with JavaScript switched off, and the sitemap all see exactly the pages they saw before.'],
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
