<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'subtitle', 'body', 'hero_image', 'mission', 'vision', 'is_active'])]
/**
 * About Us page content with mission and vision.
 * Maps to the `about_us_contents` database table.
 */
final class AboutUsContent extends Model
{
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the active About Us content entry.
     *
     * The table accumulates rows: the admin editor creates one on first visit and
     * the seeder adds another, so a plain `first()` can surface a blank row while
     * the real copy sits behind it. Prefer an active row that has content, then
     * the most recently edited.
     */
    public static function active(): ?self
    {
        $rows = self::where('is_active', true)
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->get();

        return $rows->first(fn (self $row): bool => $row->hasContent()) ?? $rows->first();
    }

    /**
     * Determine whether the row carries renderable copy.
     *
     * The title is excluded because the auto-created placeholder row is given a
     * default title, so treating it as content would keep blank rows reachable.
     */
    public function hasContent(): bool
    {
        foreach ([$this->subtitle, $this->body, $this->mission, $this->vision] as $field) {
            if (trim((string) $field) !== '') {
                return true;
            }
        }

        return false;
    }
}
