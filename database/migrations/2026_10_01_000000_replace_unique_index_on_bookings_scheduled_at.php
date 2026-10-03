<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Replace the unique scheduled_at constraint with a plain index.
 *
 * The unique index made a cancelled booking permanently block its own time slot:
 * the availability check ignores cancelled rows, but the insert still collided with
 * them and surfaced as "just booked by someone else". Double-booking is now prevented
 * by a SELECT ... FOR UPDATE gap lock inside the booking transaction instead.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropUnique('bookings_scheduled_at_unique');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->index('scheduled_at', 'bookings_scheduled_at_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex('bookings_scheduled_at_index');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->unique('scheduled_at', 'bookings_scheduled_at_unique');
        });
    }
};
