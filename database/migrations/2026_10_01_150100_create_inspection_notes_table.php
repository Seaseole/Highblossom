<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Internal, timestamped admin notes on an inspection.
 *
 * Never emailed and never rendered on the customer status page. Rows created
 * before this table existed are what the old single `notes` textarea pretended
 * to persist — that column was never added, so those notes were discarded.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inspection_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('body');
            $table->boolean('is_action')->default(false);
            $table->timestamp('done_at')->nullable();
            $table->timestamps();

            $table->index(['inspection_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_notes');
    }
};
