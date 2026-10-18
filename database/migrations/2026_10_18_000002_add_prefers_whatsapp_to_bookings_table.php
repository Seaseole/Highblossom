<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Record whether the customer would rather be chased on WhatsApp than by call.
 *
 * Nearly every customer here gives a mobile number, and the shop follows up on
 * WhatsApp, so a bare phone column says who to reach but not how. The number
 * itself stays in `client_phone`; this only steers the follow-up channel.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->boolean('prefers_whatsapp')->default(false)->after('client_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('prefers_whatsapp');
        });
    }
};
