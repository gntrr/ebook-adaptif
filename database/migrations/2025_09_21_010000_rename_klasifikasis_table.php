<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('klasifikasis') && !Schema::hasTable('klasifikasi')) {
            Schema::rename('klasifikasis', 'klasifikasi');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('klasifikasi') && !Schema::hasTable('klasifikasis')) {
            Schema::rename('klasifikasi', 'klasifikasis');
        }
    }
};
