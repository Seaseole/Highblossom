<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Booking;
use App\Models\ContactMessage;
use App\Models\Inspection;
use Illuminate\Support\Facades\Cache;

/**
 * The admin sidebar's navigation, described as data rather than as markup.
 *
 * Every entry mirrors the ability its own route is already gated by in
 * `routes/admin.php`, which is what makes the menu honest: an item is shown exactly when
 * the page behind it would answer with something other than a 403. The previous sidebar
 * declared two of thirty permissions and filtered them on the mobile drawer only, so the
 * desktop rail offered links that its own route middleware then refused, and the two
 * columns disagreed with each other.
 *
 * Each item stores only the route it links to; the path is derived from the router, so a
 * prefix change cannot leave the menu pointing somewhere the page no longer is. Most admin
 * screens have no index route of their own, which is why a destination is named rather than
 * guessed from a convention this route file does not follow.
 *
 * Badges are the only database work this class does, and the only place the sidebar pays
 * for it. Counts are cached by their own query rather than per user: both figures are
 * global queues, so a per-viewer key would multiply the same COUNT across every admin in
 * the building while answering identically.
 */
final class AdminMenu
{
    /**
     * The navigation as written. `keywords` is search vocabulary the label alone would
     * not carry: diary finds Bookings, inbox finds Messages.
     *
     * @return array<string, array{label: string, icon: string, items: array<int, array<string, string|null>>}>
     */
    public static function groups(): array
    {
        return [
            'overview' => [
                'label' => 'Overview',
                'icon' => 'home',
                'items' => [
                    [
                        'label' => 'Dashboard',
                        'route' => 'dashboard',
                        'permission' => null,
                        'keywords' => 'home statistics metrics',
                    ],
                ],
            ],
            'bookings' => [
                'label' => 'Bookings',
                'icon' => 'calendar',
                'items' => [
                    [
                        'label' => 'Bookings',
                        'route' => 'admin.bookings.index',
                        'permission' => 'view bookings',
                        'badge' => 'pending_bookings',
                        'keywords' => 'appointments diary scheduling customers',
                    ],
                    [
                        'label' => 'Booking Closures',
                        'route' => 'admin.bookings.closures.index',
                        'permission' => 'update bookings',
                        'keywords' => 'holidays blocked dates unavailable closure',
                    ],
                    [
                        'label' => 'Appointments',
                        'route' => 'admin.inspections.index',
                        'permission' => 'view inspections',
                        'badge' => 'open_inspections',
                        'keywords' => 'inspections visits jobs field staff',
                    ],
                    [
                        'label' => 'Absences',
                        'route' => 'admin.absences.index',
                        'permission' => 'manage absences',
                        'keywords' => 'leave holiday time off staff unavailable',
                    ],
                    [
                        'label' => 'Quotes',
                        'route' => 'admin.quotes.index',
                        'permission' => 'view bookings',
                        'keywords' => 'estimates pricing proposals',
                    ],
                ],
            ],
            'content' => [
                'label' => 'Content',
                'icon' => 'document-text',
                'items' => [
                    [
                        'label' => 'About Us',
                        'route' => 'admin.about-us.edit',
                        'permission' => 'manage pages',
                        'keywords' => 'hero story team page',
                    ],
                    [
                        'label' => 'Testimonials',
                        'route' => 'admin.testimonials.index',
                        'permission' => 'manage testimonials',
                        'keywords' => 'reviews feedback clients',
                    ],
                    [
                        'label' => 'Services',
                        'route' => 'admin.services.index',
                        'permission' => 'view services',
                        'keywords' => 'offerings pricing catalogue',
                    ],
                    [
                        'label' => 'Service Types',
                        'route' => 'admin.service-types.index',
                        'permission' => 'view services',
                        'keywords' => 'categories types windscreen chip repair',
                    ],
                    [
                        'label' => 'Glass Types',
                        'route' => 'admin.glass-types.index',
                        'permission' => 'view services',
                        'keywords' => 'glass laminated tempered tinted',
                    ],
                    [
                        'label' => 'Glass Sub-Categories',
                        'route' => 'admin.glass-sub-categories.index',
                        'permission' => 'manage services',
                        'keywords' => 'glass sub categories trims edges',
                    ],
                    [
                        'label' => 'Gallery',
                        'route' => 'admin.gallery.index',
                        'permission' => 'view gallery',
                        'keywords' => 'images photos portfolio work',
                    ],
                    [
                        'label' => 'Gallery Categories',
                        'route' => 'admin.gallery-categories.index',
                        'permission' => 'view gallery',
                        'keywords' => 'albums collections categories',
                    ],
                    [
                        'label' => 'Gallery Settings',
                        'route' => 'admin.gallery-settings.index',
                        'permission' => 'manage gallery',
                        'keywords' => 'album settings gallery options',
                    ],
                    [
                        'label' => 'Partners',
                        'route' => 'admin.partners.index',
                        'permission' => 'manage settings',
                        'keywords' => 'logos sponsors insurers brands',
                    ],
                    [
                        'label' => 'Staff',
                        'route' => 'admin.staff.index',
                        'permission' => 'manage settings',
                        'keywords' => 'technicians team members installers',
                    ],
                    [
                        'label' => 'Messages',
                        'route' => 'admin.contact-messages.index',
                        'permission' => 'view contact messages',
                        'badge' => 'unread_messages',
                        'keywords' => 'inbox enquiries contact unread',
                    ],
                ],
            ],
            'blog' => [
                'label' => 'Blog',
                'icon' => 'newspaper',
                'items' => [
                    [
                        'label' => 'Posts',
                        'route' => 'admin.posts.index',
                        'permission' => 'view blog',
                        'keywords' => 'articles writing news',
                    ],
                    [
                        'label' => 'Categories',
                        'route' => 'admin.categories.index',
                        'permission' => 'view blog',
                        'keywords' => 'topics sections blog groups',
                    ],
                    [
                        'label' => 'Tags',
                        'route' => 'admin.tags.index',
                        'permission' => 'view blog',
                        'keywords' => 'labels keywords blog tags',
                    ],
                ],
            ],
            'access' => [
                'label' => 'Team & Access',
                'icon' => 'users',
                'items' => [
                    [
                        'label' => 'Users',
                        'route' => 'admin.users.index',
                        'permission' => 'manage users',
                        'keywords' => 'accounts people members admins',
                    ],
                    [
                        'label' => 'Roles',
                        'route' => 'admin.roles.index',
                        'permission' => 'manage roles',
                        'keywords' => 'permissions groups abilities access',
                    ],
                    [
                        'label' => 'Sessions',
                        'route' => 'admin.sessions.index',
                        'permission' => 'manage user sessions',
                        'keywords' => 'devices logins revoke sign out active',
                    ],
                ],
            ],
            'media' => [
                'label' => 'Media',
                'icon' => 'photo',
                'items' => [
                    [
                        'label' => 'Media Library',
                        'route' => 'admin.media-library.index',
                        'permission' => null,
                        'keywords' => 'files images uploads assets library',
                    ],
                ],
            ],
            'system' => [
                'label' => 'System',
                'icon' => 'cog-6-tooth',
                'items' => [
                    [
                        'label' => 'Settings',
                        'route' => 'admin.settings.index',
                        'permission' => 'view settings',
                        'keywords' => 'company business details branding config',
                    ],
                    [
                        'label' => 'Security',
                        'route' => 'admin.password-policy.index',
                        'permission' => 'view settings',
                        'keywords' => 'password rules strength policy 2fa',
                    ],
                    [
                        'label' => 'SEO',
                        'route' => 'admin.seo.settings',
                        'permission' => 'manage seo',
                        'keywords' => 'meta sitemap robots redirects search',
                    ],
                    [
                        'label' => 'Cache',
                        'route' => 'admin.cache.index',
                        'permission' => 'manage cache',
                        'keywords' => 'clear optimize warm performance storage',
                    ],
                    [
                        'label' => 'System Overview',
                        'route' => 'admin.system-overview.index',
                        'permission' => 'view system overview',
                        'keywords' => 'health diagnostics probes queues workers postures',
                    ],
                    [
                        'label' => 'Environment',
                        'route' => 'admin.decompose',
                        'permission' => 'manage environment',
                        'keywords' => 'env variables config editor decomposer',
                    ],
                    [
                        'label' => 'Versions',
                        'route' => 'admin.versions.index',
                        'permission' => 'manage versions',
                        'keywords' => 'releases changelog semver history',
                    ],
                ],
            ],
        ];
    }

    /**
     * The per-render memos, keyed by memoKey(). One store rather than three statics, so they
     * cannot disagree about when they are stale.
     *
     * @var array<string, array<string, mixed>>
     */
    private static array $memo = [];

    /**
     * Which memo slot this call belongs to.
     *
     * The account id alone is not enough: a long-lived process — a queue worker, Octane, or this
     * app's own test suite, whose RefreshDatabase restarts auto-increment so a later user reuses
     * an earlier id — would hand one account's menu to whoever landed on the same number.
     * Including the request instance scopes each memo to exactly the render it describes, which is
     * also its real lifetime under PHP-FPM.
     */
    private static function memoKey(): string
    {
        return (auth()->id() ?? 'guest').':'.spl_object_id(app('request'));
    }

    /**
     * The navigation as this user is allowed to see it, with groups that filtered down to
     * nothing removed rather than left as a heading over an empty band.
     *
     * Super Admin passes every check through the application's `Gate::before`, so this costs
     * nothing on the account that uses the panel most.
     *
     * @return array<string, array{label: string, icon: string, items: array<int, array<string, string|null>>}>
     */
    public static function visible(): array
    {
        $key = self::memoKey();

        if (isset(self::$memo[$key]['visible'])) {
            return self::$memo[$key]['visible'];
        }

        $user = auth()->user();

        self::$memo[$key]['visible'] = collect(self::groups())
            ->map(fn (array $group) => [
                ...$group,
                'items' => array_values(array_filter(
                    $group['items'],
                    fn (array $item) => $user === null
                        || $item['permission'] === null
                        || $user->can($item['permission']),
                )),
            ])
            ->filter(fn (array $group) => $group['items'] !== [])
            ->all();

        return self::$memo[$key]['visible'];
    }

    /**
     * Every destination the rail can reach, keyed by the path it lives at.
     *
     * This is the single source of truth for two things the browser has to answer on its own
     * once the sidebar is persisted: which item is this page (the keys, longest prefix wins),
     * and does this query match this row (the `search` field). Keeping both in one map is why
     * no matching rule ever exists twice — there is no second implementation in PHP to drift
     * away from the Alpine one, and a new menu entry cannot be made unfindable by search.
     *
     * Paths come from `route()` rather than being hand-written, so a prefix change in the
     * route file cannot leave the sidebar highlighting nothing on the pages it moved.
     *
     * @return array<string, array{search: string, badge: string|null}>
     */
    public static function index(): array
    {
        $key = self::memoKey();

        if (isset(self::$memo[$key]['index'])) {
            return self::$memo[$key]['index'];
        }

        $built = [];

        foreach (self::visible() as $group) {
            foreach ($group['items'] as $item) {
                $built[self::normalize(route($item['route']))] = [
                    'search' => mb_strtolower($item['label'].' '.($item['keywords'] ?? '').' '.$group['label']),
                    'badge' => $item['badge'] ?? null,
                ];
            }
        }

        self::$memo[$key]['index'] = $built;

        return $built;
    }

    /**
     * Strip the host and any trailing slash, so a generated URL compares to a pathname.
     *
     * Public because the sidebar asks the same question of the current request, and the two
     * answers only agree if they are cut by the same knife.
     */
    public static function normalize(string $url): string
    {
        $path = '/'.ltrim((string) parse_url($url, PHP_URL_PATH), '/');

        return rtrim($path, '/') ?: '/';
    }

    /**
     * Counted queues, keyed by badge name, for the items that ask for one.
     *
     * Memoised per account like visible(): the set of badges a person can even see is a
     * function of their abilities, so one global slot would hand the previous admin's subset to
     * the next. The 60-second cache underneath is shared, because the numbers themselves are.
     *
     * @return array<string, int>
     */
    public static function badges(): array
    {
        $key = self::memoKey();

        if (isset(self::$memo[$key]['badges'])) {
            return self::$memo[$key]['badges'];
        }

        $needed = collect(self::visible())
            ->flatMap(fn (array $group) => $group['items'])
            ->filter(fn (array $item) => isset($item['badge']))
            ->map(fn (array $item) => $item['badge'])
            ->unique()
            ->values()
            ->all();

        // Cached under one key per queue rather than one key per subset. A single shared map was
        // the wrong shape: the set of queues an account can even see depends on its abilities, so
        // whoever asked first wrote a partial map, and a later admin whose subset included a key
        // that map omitted would have seen no badge at all until it expired.
        self::$memo[$key]['badges'] = collect($needed)
            ->mapWithKeys(fn (string $badge) => [
                $badge => Cache::remember("admin:sidebar:badge:{$badge}", 60, fn (): int => self::count($badge)),
            ])
            ->all();

        return self::$memo[$key]['badges'];
    }

    /**
     * Run one badge query.
     */
    private static function count(string $badge): int
    {
        return match ($badge) {
            // The four booking statuses are the enum's own, and `pending` is what a new
            // booking is created with — so this is the queue, not merely what is unconfirmed.
            'pending_bookings' => Booking::query()->where('status', 'pending')->count(),
            // Inspection::getStatusAttribute is computed in PHP, so the equivalent of
            // "not finished" has to be read from the columns that produce it.
            'open_inspections' => Inspection::query()->whereNull('ended_at')->count(),
            'unread_messages' => ContactMessage::query()->unread()->count(),
            default => 0,
        };
    }
}
