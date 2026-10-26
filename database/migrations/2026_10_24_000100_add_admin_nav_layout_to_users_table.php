<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Remember which admin navigation each account chose.
 *
 * Nullable on purpose, and that is the whole design: "this person has never chosen" has to be a
 * different answer from "this person chose the sidebar", because only the first one lets the
 * site-wide default in `company_settings.admin_nav_layout` apply. A non-null column with a
 * database default — the shape `users.theme` got — cannot express that, and would have frozen
 * every account onto whatever the default was at the moment they were created.
 *
 * A string rather than a native enum for the same reason in miniature: adding a third layout
 * would be a data change instead of a schema change, and the cast to App\Enums\AdminNavLayout
 * plus `Rule::enum()` on both write paths are what actually keep the column honest.
 *
 * Reversible: `down()` drops the column, which puts every account back on the site-wide default
 * and leaves nothing behind to clean up.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('admin_nav_layout', 16)->nullable()->after('theme');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('admin_nav_layout');
        });
    }
};
