<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Durable ledger of session lifecycles.
 *
 * The framework `sessions` table only describes sessions that are alive right now:
 * rows are destroyed on logout and garbage-collected once idle past the session
 * lifetime. Device history therefore needs its own append-then-close table.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Matches `sessions.id`. Nullable because a revoked row is deliberately
            // kept after the framework session row is deleted.
            $table->string('session_id', 191)->nullable();

            $table->string('ip_address', 45)->nullable();
            $table->string('last_ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            // Parsed once, at write time, so the list can be filtered and indexed
            // without re-parsing every user agent on render.
            $table->string('device_type', 16)->nullable();
            $table->string('platform', 64)->nullable();
            $table->string('browser', 64)->nullable();
            $table->string('browser_version', 32)->nullable();

            $table->string('login_method', 16)->default('unknown');
            $table->boolean('remembered')->default(false);

            $table->timestamp('login_at');
            $table->timestamp('last_seen_at')->index();

            $table->timestamp('ended_at')->nullable();
            $table->string('end_reason', 24)->nullable();
            $table->foreignId('revoked_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->unique('session_id');
            $table->index(['user_id', 'ended_at']);
            $table->index(['user_id', 'last_seen_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_sessions');
    }
};
