<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materi', function (Blueprint $t) {
            $t->string('konten_type', 10)->default('html');
            $t->string('konten_image_path')->nullable();
        });

        // Jika ada baris yang awalnya konten NULL tapi seharusnya image, admin bisa edit manual nanti.
        // Tambahkan check constraint (Postgres) opsional
        try {
            DB::statement("ALTER TABLE materi ADD CONSTRAINT check_konten_type CHECK (konten_type IN ('html','image'));");
        } catch (Throwable $e) {
            // abaikan jika DB bukan Postgres atau constraint sudah ada
        }
    }

    public function down(): void
    {
        Schema::table('materi', function (Blueprint $t) {
            $t->dropColumn(['konten_type','konten_image_path']);
        });
    }
};
