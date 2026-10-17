<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * `services.icon` was never required by anything above the database: no admin
 * create form posts it, no site view reads it, and ServiceRequest validated it
 * as nullable. Under MySQL strict mode the NOT NULL constraint turned the
 * omitted column into error 1364, so admins could not create services at all.
 * The same class of mismatch left `testimonials.role` rejecting the blank value
 * its form labels "Optional", surfacing as error 1048.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('icon');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->string('role')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('icon')->nullable()->after('slug');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->string('role')->nullable(false)->change();
        });
    }
};
