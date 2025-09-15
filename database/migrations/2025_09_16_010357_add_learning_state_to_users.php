<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->unsignedTinyInteger('current_bab')->default(1)->after('id');
            $t->string('current_track', 1)->nullable()->after('current_bab'); // null|A|B
            $t->unsignedTinyInteger('current_step')->default(1)->after('current_track');
            $t->float('progress')->default(0)->after('current_step');

            $t->index(['current_bab','current_track','current_step'], 'users_learning_idx');
        });

        // CHECK constraints
        DB::statement("ALTER TABLE users
          ADD CONSTRAINT users_check_track_ab CHECK (current_track IN ('A','B') OR current_track IS NULL),
          ADD CONSTRAINT users_check_step_range CHECK (current_step BETWEEN 1 AND 5);");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->dropIndex('users_learning_idx');
            $t->dropColumn(['current_bab','current_track','current_step','progress']);
        });
    }
};
