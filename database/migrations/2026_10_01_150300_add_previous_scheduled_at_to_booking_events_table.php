<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Remember the slot a rescheduled booking was moved off.
 *
 * A reschedule notice is worthless without "moved from", and storing it as a
 * typed timestamp keeps it out of `summary`, which is customer-authored free text.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('booking_events', function (Blueprint $table) {
            $table->timestamp('previous_scheduled_at')->nullable()->after('summary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_events', function (Blueprint $table) {
            $table->dropColumn('previous_scheduled_at');
        });
    }
};
