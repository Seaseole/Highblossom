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
        Schema::table('poll_votes', function (Blueprint $table) {
            $table->unique(['poll_id', 'ip_address'], 'unique_poll_ip');
            // Unique constraint on poll_id and user_id where user_id is not null
            // Since standard Laravel migrations don't support partial unique indexes easily
            // on all drivers, this is the standard approach.
            $table->unique(['poll_id', 'user_id'], 'unique_poll_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('poll_votes', function (Blueprint $table) {
            $table->dropUnique('unique_poll_ip');
            $table->dropUnique('unique_poll_user');
        });
    }
};
