<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('glass_sub_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('glass_type_id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            // Indexes for performance
            $table->index(['glass_type_id', 'is_active', 'sort_order']);
            $table->index('slug');
        });

        // Add foreign key constraint after both tables exist
        Schema::table('glass_sub_categories', function (Blueprint $table) {
            $table->foreign('glass_type_id')->references('id')->on('glass_types')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('glass_sub_categories');
    }
};
