<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Give posts a real author and widen title/excerpt so the columns can hold the
 * limits the admin form enforces.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->string('title', 255)->change();
            $table->text('excerpt')->nullable()->change();
        });

        $this->backfillAuthors();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->string('title', 191)->change();
            $table->string('excerpt', 191)->nullable()->change();
        });
    }

    /**
     * Attribute existing posts to the earliest super admin so the author surface
     * is not blank for content written before this column existed.
     */
    private function backfillAuthors(): void
    {
        $authorId = DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('roles.name', 'Super Admin')
            ->where('model_has_roles.model_type', User::class)
            ->orderBy('model_has_roles.model_id')
            ->value('model_has_roles.model_id');

        if ($authorId === null) {
            $authorId = DB::table('users')->orderBy('id')->value('id');
        }

        if ($authorId === null) {
            return;
        }

        DB::table('posts')->whereNull('user_id')->update(['user_id' => $authorId]);
    }
};
