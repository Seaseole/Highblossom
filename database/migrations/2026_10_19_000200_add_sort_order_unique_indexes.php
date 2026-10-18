<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Let the database enforce what the admin forms already promise: one number per
 * record, per scope.
 *
 * Runs after the renumber migration; adding this on top of duplicates, nulls or a
 * crowd of zeroes would fail.
 */
return new class extends Migration
{
    /**
     * @var array<int, array{table: string, index: string, columns: array<int, string>}>
     */
    private const INDEXES = [
        ['table' => 'services', 'index' => 'services_sort_order_unique', 'columns' => ['sort_order']],
        ['table' => 'testimonials', 'index' => 'testimonials_sort_order_unique', 'columns' => ['sort_order']],
        ['table' => 'gallery_images', 'index' => 'gallery_images_source_sort_order_unique', 'columns' => ['source', 'sort_order']],
        ['table' => 'gallery_categories', 'index' => 'gallery_categories_sort_order_unique', 'columns' => ['sort_order']],
        ['table' => 'glass_types', 'index' => 'glass_types_sort_order_unique', 'columns' => ['sort_order']],
        ['table' => 'glass_sub_categories', 'index' => 'glass_sub_categories_glass_type_id_sort_order_unique', 'columns' => ['glass_type_id', 'sort_order']],
        ['table' => 'service_types', 'index' => 'service_types_sort_order_unique', 'columns' => ['sort_order']],
        ['table' => 'partners', 'index' => 'partners_order_unique', 'columns' => ['order']],
        ['table' => 'staff', 'index' => 'staff_order_unique', 'columns' => ['order']],
    ];

    /**
     * Add the unique index for every ordered surface.
     */
    public function up(): void
    {
        foreach (self::INDEXES as $definition) {
            if (! Schema::hasTable($definition['table'])) {
                continue;
            }

            if (Schema::hasIndex($definition['table'], $definition['index'])) {
                continue;
            }

            Schema::table($definition['table'], function (Blueprint $table) use ($definition): void {
                $table->unique($definition['columns'], $definition['index']);
            });
        }
    }

    /**
     * Drop them again, leaving the renumbered data in place.
     */
    public function down(): void
    {
        foreach (array_reverse(self::INDEXES) as $definition) {
            if (! Schema::hasTable($definition['table'])) {
                continue;
            }

            if (! Schema::hasIndex($definition['table'], $definition['index'])) {
                continue;
            }

            Schema::table($definition['table'], function (Blueprint $table) use ($definition): void {
                $table->dropUnique($definition['index']);
            });
        }
    }
};
