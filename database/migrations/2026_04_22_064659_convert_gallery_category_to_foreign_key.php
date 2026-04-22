<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('gallery_images', function (Blueprint $table) {
            // Add new foreign key column
            $table->unsignedBigInteger('gallery_category_id')->nullable()->after('sort_order');
        });

        // Migrate existing data from category enum to category_id
        DB::statement("
            UPDATE gallery_images gi
            SET gallery_category_id = (
                SELECT id FROM gallery_categories WHERE slug = gi.category
                LIMIT 1
            )
        ");

        // Drop the old enum column
        Schema::table('gallery_images', function (Blueprint $table) {
            $table->dropColumn('category');
        });

        // Add foreign key constraint
        Schema::table('gallery_images', function (Blueprint $table) {
            $table->foreign('gallery_category_id')
                  ->references('id')
                  ->on('gallery_categories')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gallery_images', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['gallery_category_id']);
        });

        // Add back the enum column
        Schema::table('gallery_images', function (Blueprint $table) {
            $table->enum('category', ['automotive', 'heavy_machinery', 'fleet', 'other'])->default('other')->after('sort_order');
        });

        // Migrate data back from category_id to category enum
        DB::statement("
            UPDATE gallery_images gi
            SET category = (
                SELECT slug FROM gallery_categories WHERE id = gi.gallery_category_id
                LIMIT 1
            )
        ");

        // Drop the foreign key column
        Schema::table('gallery_images', function (Blueprint $table) {
            $table->dropColumn('gallery_category_id');
        });
    }
};
