<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materi', function (Blueprint $table) {
            $table->string('konten_type', 20)->default('html')->after('konten');
            $table->string('konten_image_path')->nullable()->after('konten_type');
        });
    }

    public function down(): void
    {
        Schema::table('materi', function (Blueprint $table) {
            $table->dropColumn(['konten_type', 'konten_image_path']);
        });
    }
};
