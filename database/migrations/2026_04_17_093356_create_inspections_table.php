<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('staff_id')->constrained('users');
            $table->dateTime('scheduled_at')->index();
            $table->dateTime('ended_at')->index();
            $table->string('location');
            $table->enum('type', ['mobile', 'workshop'])->index();
            $table->timestamps();
            
            $table->index(['scheduled_at', 'ended_at', 'staff_id']); // Critical for availability checks
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
