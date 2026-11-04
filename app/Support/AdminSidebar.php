<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\CompanySetting;
use Highblossom\ContentBlocks\Support\MediaUrl;
use Illuminate\Http\Request;

/**
 * What the admin sidebar needs from the server, in one place.
 *
 * This exists so the Blade stays a renderer. The rail used to be a Livewire component that
 * mounted a logo, a company name, a theme and a user count on every admin page, then kept a
 * duplicate of the whole navigation because the drawer and the desktop rail each re-printed
 * it — which is how the two drifted into disagreeing about what the user is allowed to see.
 * These methods answer a question each, and the navigation is rendered once and placed twice.
 *
 * Nothing here filters by a search query, and nothing here can. A persisted sidebar is not
 * re-rendered when `wire:navigate` swaps the page, so filtering could not live on the server and
 * stay honest; the paths, their labels, and the counts beside them are handed to the browser
 * instead, in the shape described by AdminMenu::index(), and both the rail and the top bar answer
 * their questions from that one blob.
 */
final class AdminSidebar
{
    /**
     * The cookie that mirrors the browser's memory of open branches back to the server.
     */
    public const REMEMBER_COOKIE = 'adminSidebarGroups';

    /**
     * The path a menu entry lives at, host stripped.
     *
     * `route()` returns an absolute URL, and every comparison the rail makes — which row is
     * current, which row a query matched, which branch owns this pathname — is against
     * `window.location.pathname`. Normalising here means those comparisons are written once
     * and never have to remember which form they were handed.
     */
    public static function path(string $routeName): string
    {
        return AdminMenu::normalize(route($routeName));
    }

    /**
     * The navigation as the rail needs to draw it: permitted, resolved, and marked.
     *
     * This is the only place that turns a menu entry into a view model, so the Blade can be a
     * loop. Doing the arithmetic in the template is how the two columns of the old sidebar
     * ended up disagreeing — each one worked out visibility for itself, and only one of them
     * remembered to.
     *
     * `open` and `hasActive` travel together because they answer the same question twice: the
     * second is what lights the branch heading, the first is whether it is showing.
     *
     * @return array<int, array{label: string, icon: string, open: bool, hasActive: bool, items: array<int, array<string, mixed>>}>
     */
    public static function render(Request $request): array
    {
        $path = AdminMenu::normalize($request->url());
        $badges = AdminMenu::badges();
        $remembered = self::remembered($request);

        return collect(AdminMenu::visible())
            ->map(function (array $group) use ($path, $remembered) {
                $items = collect($group['items'])->map(function (array $item) use ($path) {
                    $target = self::path($item['route']);

                    return [
                        'label' => $item['label'],
                        'url' => $target,
                        'icon' => $item['icon'] ?? null,
                        'active' => $path === $target || str_starts_with($path, $target.'/'),
                        'badge' => isset($item['badge']) ? ($badges[$item['badge']] ?? null) : null,
                    ];
                })->all();

                $hasActive = collect($items)->contains(fn (array $item) => $item['active']);

                return [
                    'label' => $group['label'],
                    'icon' => $group['icon'],
                    'hasActive' => $hasActive,
                    // An unremembered branch opens when it holds the page and stays shut
                    // when it does not; a remembered branch obeys the person who set it,
                    // even after they navigated away from it.
                    'open' => $remembered[$group['label']] ?? $hasActive,
                    'items' => $items,
                ];
            })
            ->values()
            ->all();
    }

    /**
     * The rail's own facts, as JSON: the destinations it can reach, the words each row
     * answers to, the counts beside them, and which of them is the current page.
     *
     * `active` is resolved here rather than left to the browser because the first paint after
     * a full reload must not depend on JavaScript running to know what is lit.
     *
     * @return array{text: array<string, string>, labels: array<string, array{label: string, group: string}>, badges: array<string, int>, active: string|null, theme: string}
     */
    public static function clientData(Request $request): array
    {
        $index = AdminMenu::index();
        $path = AdminMenu::normalize($request->url());

        $active = null;

        foreach (array_keys($index) as $target) {
            if ($path === $target || str_starts_with($path, $target.'/')) {
                if ($active === null || strlen($target) > strlen($active)) {
                    $active = $target;
                }
            }
        }

        return [
            'text' => collect($index)->map(fn (array $entry) => $entry['search'])->all(),
            'labels' => collect($index)->map(fn (array $entry) => [
                'label' => $entry['label'],
                'group' => $entry['group'],
            ])->all(),
            'badges' => AdminMenu::badges(),
            'active' => $active === null ? $path : $active,
            'theme' => self::theme($request),
        ];
    }

    /**
     * Branches the person left open, read from the cookie the rail writes on every toggle.
     *
     * An unlisted branch is not closed, it is unremembered — which is why this hands back the
     * raw map and leaves the caller to fall through to the active-group default. The cookie
     * exists so the very first paint after a full reload matches what the person saw last
     * time; without it the rail would re-open everything and the memory would arrive a frame
     * late, which reads as a flicker rather than as a restore.
     *
     * @return array<string, bool>
     */
    public static function remembered(Request $request): array
    {
        // Read through the request, not $_COOKIE, because the cookie is on EncryptCookies'
        // exception list in bootstrap/app.php — it is written by the browser, so an attempt to
        // decrypt it would answer nothing and every branch would render open on the first frame.
        $decoded = json_decode((string) $request->cookie(self::REMEMBER_COOKIE), true);

        return is_array($decoded) ? $decoded : [];
    }

    /**
     * The theme label to print. This is the saved preference, not the effective appearance:
     * under `auto` the operating system decides, and only the browser can know that.
     */
    public static function theme(Request $request): string
    {
        return auth()->user()?->theme?->value ?? 'auto';
    }

    /**
     * The wordmark's partner: the company logo, resolved through the same disk-switchable
     * helper that serves the avatar, so a move to object storage cannot fix one surface and
     * strand the other.
     */
    public static function logoUrl(): ?string
    {
        return MediaUrl::asset(CompanySetting::get('business_logo'));
    }

    /**
     * The name beside the logo, falling back to the application name rather than printing an
     * empty wordmark when the setting has never been filled in.
     */
    public static function companyName(): string
    {
        return (string) CompanySetting::get('company_name', config('app.name'));
    }

    /**
     * The role line under the name, or null when the account carries none. A profile card
     * showing an empty role reads as a mistake, so the caller hides the line, not the card.
     */
    public static function roleLabel(): ?string
    {
        $role = auth()->user()?->roles?->first();

        return $role === null ? null : $role->name;
    }
}
