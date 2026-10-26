<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\AdminNavLayout;
use App\Models\CompanySetting;
use App\Models\User;

/**
 * Which navigation layout this request should draw.
 *
 * Two answers, in priority order: the account's own choice, then the site-wide default kept in
 * `company_settings`. Nothing here is cached separately, because the default is read through
 * CompanySetting's single aggregate cache — the same warm array the logo comes from — so
 * deciding a layout costs a page nothing it was not already paying, and the panel gains no
 * second round trip for a preference that changes about once per admin.
 *
 * The layout is decided on the server and published as one class on `<html>` (see
 * `resources/views/layouts/admin.blade.php`), which is what keeps this out of the two failure
 * modes a client-side switch would have: a flash of the wrong navigation on every first paint,
 * and a shell that disagrees with the page under it after a `wire:navigate`.
 */
final class AdminNav
{
    /**
     * The company setting that holds the site-wide default for accounts that have not chosen.
     */
    public const DEFAULT_KEY = 'admin_nav_layout';

    /**
     * The layout for whoever is signed in, falling through to the site-wide default.
     */
    public static function layout(): AdminNavLayout
    {
        return self::for(auth()->user());
    }

    /**
     * The same answer for one named account, which is what the profile screen needs: it has the
     * user in hand and must not read it back out of the session guard.
     *
     * A null choice is not a third state here — it is the site default, and saying so in one
     * place is why the Appearance tab can show a tick against the layout a person is actually
     * looking at rather than the one their row happens to hold.
     */
    public static function for(?User $user): AdminNavLayout
    {
        return $user?->admin_nav_layout ?? self::siteDefault();
    }

    /**
     * The layout an account gets when it has never chosen.
     *
     * Anything unrecognised in the setting resolves to the enum's own default rather than
     * throwing: this value is editable text in a settings table, and a typo there should mean a
     * panel that looks like it always did, not a 500 on every admin page.
     */
    public static function siteDefault(): AdminNavLayout
    {
        return self::normalize(CompanySetting::get(self::DEFAULT_KEY, AdminNavLayout::default()->value));
    }

    /**
     * Coerce whatever was stored — enum, string, null — to a layout.
     */
    public static function normalize(mixed $value): AdminNavLayout
    {
        if ($value instanceof AdminNavLayout) {
            return $value;
        }

        return AdminNavLayout::tryFrom((string) $value) ?? AdminNavLayout::default();
    }
}
