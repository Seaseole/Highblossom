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
        Schema::create('nudges', function (Blueprint $table) {
            $table->id();
            $table->string('key', 64)->unique();
            $table->string('fingerprint', 64);
            $table->string('severity', 16)->default('info');
            $table->string('message', 500);
            $table->string('action_url', 500);
            $table->string('action_label', 100);
            $table->string('permission', 100)->nullable();
            $table->string('status', 16)->default('open')->index();
            $table->timestamp('snoozed_until')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nudges');
    }
};
