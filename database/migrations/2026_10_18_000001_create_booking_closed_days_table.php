<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Shop-wide closures: public holidays, stock takes, training days.
 *
 * `staff_absences` could not stand in for this. It is per person, and it only
 * blocks a slot because capacity happens to be one; the moment the shop runs two
 * bays, a single absence stops closing anything. A closure has to be a statement
 * about the day, not about who is off.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('booking_closed_days', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->string('reason', 191)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_closed_days');
    }
};
