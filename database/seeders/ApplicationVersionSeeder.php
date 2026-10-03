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
