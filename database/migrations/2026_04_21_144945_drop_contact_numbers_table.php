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
        Schema::dropIfExists('contact_numbers');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('contact_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('phone_number');
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_whatsapp')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }
};
