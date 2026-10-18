<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\GalleryCategory;
use App\Models\GalleryImage;
use App\Models\GlassSubCategory;
use App\Models\GlassType;
use App\Models\Partner;
use App\Models\Service;
use App\Models\ServiceType;
use App\Models\Staff;
use App\Models\Testimonial;

/**
 * The admin modules whose records carry a sort order.
 *
 * Keyed by the name the Blade field and the availability endpoint use, so a
 * browser never supplies a table or model name of its own.
 */
final class SortOrderRegistry
{
    /**
     * @var array<string, array{model: class-string, ability: string}>
     */
    private const MODULES = [
        'services' => ['model' => Service::class, 'ability' => 'manage services'],
        'testimonials' => ['model' => Testimonial::class, 'ability' => 'manage testimonials'],
        'gallery' => ['model' => GalleryImage::class, 'ability' => 'manage gallery'],
        'gallery-categories' => ['model' => GalleryCategory::class, 'ability' => 'manage gallery'],
        'glass-types' => ['model' => GlassType::class, 'ability' => 'manage services'],
        'glass-sub-categories' => ['model' => GlassSubCategory::class, 'ability' => 'manage services'],
        'service-types' => ['model' => ServiceType::class, 'ability' => 'manage services'],
        'partners' => ['model' => Partner::class, 'ability' => 'manage settings'],
        'staff' => ['model' => Staff::class, 'ability' => 'manage settings'],
    ];

    /**
     * The model class behind a module key, or null when the key is unknown.
     *
     * @return class-string|null
     */
    public static function model(?string $module): ?string
    {
        return $module === null ? null : (self::MODULES[$module]['model'] ?? null);
    }

    /**
     * The ability required to edit the module's records.
     */
    public static function ability(?string $module): ?string
    {
        return $module === null ? null : (self::MODULES[$module]['ability'] ?? null);
    }

    /**
     * Every registered module key.
     *
     * @return array<int, string>
     */
    public static function keys(): array
    {
        return array_keys(self::MODULES);
    }
}
