<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Which hero the homepage opens with.
 *
 * `static` is the hero the site has always drawn: one background photograph, the animated
 * headline, and the trust copy under it. `cinematic` replaces that block with the layered
 * card — a display word painted behind the subject, one or more slides, stat counters and a
 * slide preview. Neither one owns the sections below the fold; the switch swaps the contents
 * of `<header id="page-hero">` and nothing else.
 *
 * The default is `static` so that turning the new hero on is a deliberate act in the admin,
 * never a side effect of a deploy. A cinematic configuration with no usable slide resolves
 * back to the static hero rather than rendering an empty card.
 */
enum HeroStyle: string
{
    case Static = 'static';
    case Cinematic = 'cinematic';

    /**
     * The word a person reads on the control that chooses this hero.
     */
    public function label(): string
    {
        return match ($this) {
            self::Static => 'Classic',
            self::Cinematic => 'Cinematic',
        };
    }

    /**
     * One line under that word, written for someone who has never seen the other hero.
     */
    public function description(): string
    {
        return match ($this) {
            self::Static => 'One photograph, the animated headline and the trust copy. Nothing to maintain.',
            self::Cinematic => 'Full-bleed card with a display word behind the subject, auto-playing slides and stat counters.',
        };
    }

    /**
     * The homepage opens here until someone chooses otherwise, and again if a stored value
     * is not one of ours.
     */
    public static function default(): self
    {
        return self::Static;
    }

    /**
     * Both heroes, in the order a person should be offered them.
     *
     * @return list<self>
     */
    public static function options(): array
    {
        return [self::Static, self::Cinematic];
    }

    /**
     * Read a stored setting without throwing: anything unrecognised is the default hero, so a
     * stray row in `company_settings` cannot take the homepage down.
     */
    public static function normalize(mixed $value): self
    {
        if (! is_string($value)) {
            return self::default();
        }

        return self::tryFrom($value) ?? self::default();
    }
}
