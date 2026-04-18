<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('content_blocks', function (Blueprint $table) {
            $table->id();
            $table->morphs('blockable'); // blockable_id, blockable_type (index included)
            $table->string('type'); // Hero, Teaser, TrustBar, Services, Gallery, etc.
            $table->json('content');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['blockable_id', 'blockable_type', 'sort_order']); // Optimization for loading blocks
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_blocks');
    }
};
