<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add an explicit start marker to inspections.
 *
 * `Inspection::getStatusAttribute()` currently infers status from scheduled_at
 * and ended_at, so there is no way to tell "underway" from "not yet begun",
 * which is the milestone customers ask about most.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->timestamp('started_at')->nullable()->after('scheduled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->dropColumn('started_at');
        });
    }
};
