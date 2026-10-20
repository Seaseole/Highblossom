<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * The column is added nullable and stays that way. The backfill is an artisan command
     * (users:backfill-usernames), not a step inside this file, so a deploy that runs the
     * migration cannot fail on data it has not seen — the pattern that broke the last
     * production migration here was a renumber step baked into the schema change.
     *
     * Nullability is also what makes the unique index safe to create now: MySQL and sqlite
     * both allow many NULL values in a unique index, so existing accounts keep signing in
     * with their email while the names are filled in, and a required username only applies
     * to accounts created from here on.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 64)->nullable()->after('name');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unique('username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn('username');
        });
    }
};
