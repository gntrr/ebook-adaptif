<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'goal_track')) {
                $table->string('goal_track', 10)->nullable()->after('current_step');
            }
            if (!Schema::hasColumn('users', 'learning_goal')) {
                $table->string('learning_goal', 255)->nullable()->after('goal_track');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'learning_goal')) {
                $table->dropColumn('learning_goal');
            }
            if (Schema::hasColumn('users', 'goal_track')) {
                $table->dropColumn('goal_track');
            }
        });
    }
};
