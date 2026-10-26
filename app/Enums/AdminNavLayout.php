<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Which navigation the admin panel is drawn with on a wide screen.
 *
 * `sidebar` is the original shell: a persisted column down the left edge holding the whole
 * menu. `top` moves those same rows into the bar above the content. Neither one owns a copy of
 * the navigation — both read `AdminMenu::visible()` — so the choice changes the shape of the
 * bar, never the list of destinations an account is allowed to reach.
 *
 * The name says it out loud in every control that offers it: this preference only describes the
 * desktop. Below the `lg` breakpoint both layouts hand the menu to the same drawer, because a
 * horizontal bar with thirty labels in it is not a thing a phone can hold.
 */
enum AdminNavLayout: string
{
    case Sidebar = 'sidebar';
    case Top = 'top';

    /**
     * The word a person reads on the control that chooses this layout.
     */
    public function label(): string
    {
        return match ($this) {
            self::Sidebar => 'Sidebar',
            self::Top => 'Top bar',
        };
    }

    /**
     * One line under that word, written for someone who has never seen the other mode.
     */
    public function description(): string
    {
        return match ($this) {
            self::Sidebar => 'Menu in a column down the left edge, collapsible to an icon rail.',
            self::Top => 'Menu across the top bar, with each group opening as a dropdown.',
        };
    }

    /**
     * Every account starts here, and so does every account that has never chosen.
     */
    public static function default(): self
    {
        return self::Sidebar;
    }

    /**
     * Both layouts, in the order a person should be offered them.
     *
     * @return list<self>
     */
    public static function options(): array
    {
        return [self::Sidebar, self::Top];
    }
}
