<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Add an `archived` workflow state to posts, for retiring a post without deleting it.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('posts')->where('status', 'archived')->update(['status' => 'draft']);

        Schema::table('posts', function (Blueprint $table) {
            $table->enum('status', ['draft', 'published'])->default('draft')->change();
        });
    }
};
