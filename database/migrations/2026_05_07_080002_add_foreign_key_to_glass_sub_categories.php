<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add foreign key constraint after both tables exist
        Schema::table('glass_sub_categories', function (Blueprint $table) {
            $table->foreign('glass_type_id')->references('id')->on('glass_types')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('glass_sub_categories', function (Blueprint $table) {
            $table->dropForeign(['glass_sub_categories_glass_type_id_foreign']);
        });
    }
};
