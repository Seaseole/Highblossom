<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->foreignId('glass_sub_category_id')->nullable()->after('glass_type_id')->constrained()->onDelete('set null');

            // Index for performance
            $table->index('glass_sub_category_id');
        });
    }

    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropForeign(['glass_sub_category_id']);
            $table->dropIndex(['glass_sub_category_id']);
            $table->dropColumn('glass_sub_category_id');
        });
    }
};
