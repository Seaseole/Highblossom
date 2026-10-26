<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\User;
use App\Models\UserSession;
use App\Services\ApplicationVersionService;
use Illuminate\Http\Request;

/**
 * What the admin top bar needs from the server, in one place.
 *
 * The bar sits in a `@persist` island like the rail, so anything it prints is frozen until the
 * person reloads the page. That rules out live counts and a live clock, and it is why the three
 * things here are the three that are honest about being a snapshot: a queue count already cached
 * for sixty seconds, a sign-in time that stopped moving the moment it happened, and a set of
 * destinations that only changes when someone's abilities do.
 *
 * Nothing in this class queries for a number the sidebar has not already paid for. The
 * notification list is `AdminMenu::badges()` read through the menu entries that own those
 * badges, so a count appears in the bell exactly when the row it links to is one this account
 * can open — one filter, not two that have to agree.
 */
final class AdminTopbar
{
    /**
     * The two words that name the page this bar is standing over.
     *
     * Asked of the menu rather than answered by the page: `AdminSidebar::clientData()` already
     * works out which row owns this path, by longest prefix, and that row is the one the rail
     * lights up. Reading the same answer is what keeps the bar and the rail from disagreeing
     * about where the person is — and the browser recomputes it after a `wire:navigate`, because
     * a persisted bar that printed the server's answer once would otherwise keep naming the page
     * that happened to be first.
     *
     * Nulls mean no menu row owns the path, which leaves the bar showing the business name
     * alone. That is the honest rendering; a invented label would be a claim about a page the
     * navigation does not know.
     *
     * @return array{group: string|null, label: string|null}
     */
    public static function context(Request $request): array
    {
        $data = AdminSidebar::clientData($request);
        $entry = $data['labels'][$data['active']] ?? null;

        return $entry ?? ['group' => null, 'label' => null];
    }

    /**
     * The counted queues this account can see, as the bell wants them.
     *
     * @return array<int, array{label: string, url: string, count: int}>
     */
    public static function notifications(): array
    {
        $counts = AdminMenu::badges();

        return collect(AdminMenu::visible())
            ->flatMap(fn (array $group) => $group['items'])
            ->filter(fn (array $item) => isset($item['badge']))
            ->map(fn (array $item) => [
                'label' => $item['label'],
                'url' => AdminSidebar::path($item['route']),
                'count' => $counts[$item['badge']] ?? 0,
            ])
            ->filter(fn (array $row) => $row['count'] > 0)
            ->values()
            ->all();
    }

    /**
     * The "new" actions, each paired with the ability its own route is already gated by.
     *
     * Mirrors the promise `AdminMenu::groups()` makes: an entry appears exactly when the page
     * behind it would answer with something other than a 403. Declaring the ability here rather
     * than reading the router's middleware keeps one obvious list to audit; the matching test
     * asserts these strings still equal what the routes enforce, so the pairing cannot rot
     * quietly. There is deliberately no "new booking": bookings are created by the public
     * flow, and an admin-facing create route does not exist to link to.
     *
     * @return array<int, array{label: string, description: string, route: string, icon: string, permission: string}>
     */
    public static function quickActions(): array
    {
        return [
            [
                'label' => 'Blog post',
                'description' => 'Write an article for the public blog.',
                'route' => 'admin.posts.create',
                'icon' => 'newspaper',
                'permission' => 'create blog',
            ],
            [
                'label' => 'Service',
                'description' => 'Add a service the business offers.',
                'route' => 'admin.services.create',
                'icon' => 'circle-stack',
                'permission' => 'manage services',
            ],
            [
                'label' => 'Gallery image',
                'description' => 'Upload a photo to the public gallery.',
                'route' => 'admin.gallery.create',
                'icon' => 'photo',
                'permission' => 'manage gallery',
            ],
            [
                'label' => 'Testimonial',
                'description' => 'Record what a customer said.',
                'route' => 'admin.testimonials.create',
                'icon' => 'document-text',
                'permission' => 'manage testimonials',
            ],
            [
                'label' => 'Staff member',
                'description' => 'Add someone to the team directory.',
                'route' => 'admin.staff.create',
                'icon' => 'users',
                'permission' => 'manage settings',
            ],
            [
                'label' => 'Partner',
                'description' => 'List a company you work with.',
                'route' => 'admin.partners.create',
                'icon' => 'globe-alt',
                'permission' => 'manage settings',
            ],
            [
                'label' => 'User account',
                'description' => 'Invite someone into the admin panel.',
                'route' => 'admin.users.create',
                'icon' => 'identification',
                'permission' => 'manage users',
            ],
        ];
    }

    /**
     * The quick actions this account is allowed to start, with their paths resolved.
     *
     * @return array<int, array{label: string, description: string, url: string, icon: string}>
     */
    public static function permittedQuickActions(): array
    {
        $user = auth()->user();

        if ($user === null) {
            return [];
        }

        return collect(self::quickActions())
            ->filter(fn (array $action) => $user->can($action['permission']))
            ->map(fn (array $action) => [
                'label' => $action['label'],
                'description' => $action['description'],
                'url' => AdminSidebar::path($action['route']),
                'icon' => $action['icon'],
            ])
            ->values()
            ->all();
    }

    /**
     * When this account last signed in, before the session now being served.
     *
     * The `users` table carries no login timestamp on purpose — `user_sessions` is the ledger —
     * so the honest answer to "last signed in" is the previous row. It is the figure that shows
     * whether someone else has been using the account, which is the only reason to print it at
     * all; this session's own start time is something the person already knows.
     *
     * Memoised rather than cached: the row changes on every sign-in, so a shared cache entry
     * would be wrong for the account that shares nothing with the last reader but happens to
     * land on the same id after a test-suite reset.
     */
    public static function lastSignIn(?User $user): ?\DateTimeInterface
    {
        if ($user === null) {
            return null;
        }

        $key = self::memoKey();

        if (array_key_exists('lastSignIn', self::$memo[$key] ?? [])) {
            return self::$memo[$key]['lastSignIn'];
        }

        $current = session()->getId();

        self::$memo[$key]['lastSignIn'] = UserSession::query()
            ->forUser($user)
            ->when($current !== '', fn ($query) => $query->where('session_id', '!=', $current))
            ->latest('login_at')
            ->value('login_at');

        return self::$memo[$key]['lastSignIn'];
    }

    /**
     * The per-request memos, keyed the same way AdminMenu keys its own.
     *
     * @var array<string, array<string, mixed>>
     */
    private static array $memo = [];

    /**
     * Which memo slot this call belongs to: the account and the request, never the account alone.
     */
    private static function memoKey(): string
    {
        return (auth()->id() ?? 'guest').':'.spl_object_id(app('request'));
    }

    /**
     * The environment word, or null in production, where printing it would only be noise.
     */
    public static function environment(): ?string
    {
        $environment = (string) config('app.env');

        return $environment === 'production' ? null : $environment;
    }

    /**
     * The running application version, for the same non-production footer.
     *
     * The version service caches its own lookup for an hour, so this is free on every admin
     * page — it is the same call the environment badge used to make inline.
     */
    public static function version(): ?string
    {
        return app(ApplicationVersionService::class)->current()?->version;
    }
}
