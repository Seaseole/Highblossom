<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_static_routes', function (Blueprint $table) {
            $table->id();
            $table->string('route_name')->unique();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('og_type')->default('website');
            $table->string('twitter_card')->default('summary_large_image');
            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('robots')->nullable();
            $table->boolean('no_index')->default(false);
            $table->decimal('priority', 2, 1)->default(0.5);
            $table->string('changefreq')->default('monthly');
            $table->json('schema_json_ld')->nullable();
            $table->timestamps();

            $table->index('route_name');
            $table->index('no_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_static_routes');
    }
};
