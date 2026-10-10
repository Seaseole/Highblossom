<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Separate the media library from the curated public gallery.
 *
 * Both admin surfaces write to `gallery_images`, and the public gallery filtered
 * only on `is_active`, so every image a content block's library picker uploaded
 * was published straight onto /gallery and into sitemap.xml. `source` records
 * which surface owns a row, letting public reads exclude library assets.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('gallery_images', function (Blueprint $table) {
            $table->string('source')->default('gallery')->after('image_path');
        });

        // Library rows are the ones the admin Gallery form never saved: that form
        // always registers the file and always requires a category, while the block
        // picker leaves `gallery_category_id` null unless a category shares its slug.
        DB::table('gallery_images')
            ->whereNull('gallery_category_id')
            ->whereNotExists(fn ($query) => $query->select(DB::raw(1))
                ->from('media_registries')
                ->whereColumn('media_registries.path', 'gallery_images.image_path'))
            ->update(['source' => 'library', 'is_active' => false]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gallery_images', function (Blueprint $table) {
            $table->dropColumn('source');
        });
    }
};
