<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Customer-facing milestone history for a booking.
 *
 * Deliberately separate from `inspection_notes`: this table backs the public
 * status timeline, so the only free text allowed here is `summary`, which is
 * written with the explicit intention of showing it to the customer.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('booking_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('type', 32);
            $table->text('summary')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('happened_at');
            $table->timestamps();

            $table->index(['booking_id', 'happened_at']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_events');
    }
};
