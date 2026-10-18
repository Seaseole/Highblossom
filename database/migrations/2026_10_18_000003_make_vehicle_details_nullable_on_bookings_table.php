<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Let the notes field be genuinely optional.
 *
 * `vehicle_details` was created as NOT NULL back when it was the only place any
 * vehicle information went. Make/model/year/damage now carry the structured half,
 * so the column is free text the customer may legitimately have nothing to say
 * in — and leaving it NOT NULL turned an empty notes box into a failed booking.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('vehicle_details')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('vehicle_details')->nullable(false)->change();
        });
    }
};
