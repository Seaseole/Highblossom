<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Give a booking a structured description of the job.
 *
 * `vehicle_details` was the only place any of this lived, so the shop could not
 * filter, price, or schedule from it. The old column stays and stays required:
 * it now carries the registration and anything free-form, which no structured
 * field covers.
 *
 * `duration_minutes` is a snapshot rather than a lookup. Re-deriving it from the
 * service settings would silently rewrite the length of a historical job the
 * moment someone adjusted the setting, which would move past bookings in the
 * occupancy maths.
 *
 * `reference` is stored so it is unique and searchable. The value keeps the
 * `HB-000123` shape the confirmation page and every booking email already show
 * customers, so nothing a customer has already seen changes.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('service_type', 32)->nullable()->after('vehicle_details');
            $table->string('damage_type', 32)->nullable()->after('service_type');
            $table->string('vehicle_make', 191)->nullable()->after('damage_type');
            $table->string('vehicle_model', 191)->nullable()->after('vehicle_make');
            $table->unsignedSmallInteger('vehicle_year')->nullable()->after('vehicle_model');
            $table->string('damage_photo_path', 191)->nullable()->after('vehicle_year');
            $table->unsignedSmallInteger('duration_minutes')->default(60)->after('scheduled_at');
            $table->string('reference', 20)->nullable()->after('id');
        });

        DB::table('bookings')->whereNull('reference')->orderBy('id')->chunkById(500, function ($bookings): void {
            foreach ($bookings as $booking) {
                DB::table('bookings')
                    ->where('id', $booking->id)
                    ->update(['reference' => 'HB-'.str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT)]);
            }
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->unique('reference', 'bookings_reference_unique');
            $table->index('service_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropUnique('bookings_reference_unique');
            $table->dropIndex(['service_type']);
            $table->dropColumn([
                'reference', 'service_type', 'damage_type',
                'vehicle_make', 'vehicle_model', 'vehicle_year',
                'damage_photo_path', 'duration_minutes',
            ]);
        });
    }
};
