<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('vehicle_type');
            $table->string('make_model')->nullable();
            $table->string('reg_number')->nullable();
            $table->integer('year')->nullable();
            $table->foreignId('glass_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_type_id')->constrained()->onDelete('cascade');
            $table->text('message')->nullable();
            $table->boolean('mobile_service')->default(false);
            $table->string('status')->default('pending'); // pending, contacted, completed, cancelled
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
