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
        Schema::table('testimonials', function (Blueprint $table) {
            $table->renameColumn('company', 'role');
            $table->renameColumn('comment', 'content');
            $table->renameColumn('is_active', 'is_published');
            $table->boolean('is_featured')->default(false)->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->renameColumn('role', 'company');
            $table->renameColumn('content', 'comment');
            $table->renameColumn('is_published', 'is_active');
            $table->dropColumn('is_featured');
        });
    }
};
