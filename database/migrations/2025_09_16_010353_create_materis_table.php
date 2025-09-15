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
        Schema::create('materi', function (Blueprint $t) {
            $t->id();

            $t->unsignedTinyInteger('bab');                  // 1..6
            $t->string('track', 1)->nullable();              // null | 'A' | 'B'
            $t->unsignedTinyInteger('step');                 // 1..5
            $t->string('tipe', 20);                          // materi|praktek|evaluasi|evaluasi_bab
            $t->string('judul', 120);
            $t->text('konten')->nullable();                  // HTML/Markdown bebas

            $t->timestamps();

            // Akselerasi query navigasi
            $t->index(['bab', 'track', 'step']);
            $t->unique(['bab', 'track', 'step', 'tipe'], 'materi_unique_slot');
        });

        // CHECK constraints (Postgres-friendly)
        DB::statement("ALTER TABLE materi
          ADD CONSTRAINT check_track_ab CHECK (track IN ('A','B') OR track IS NULL),
          ADD CONSTRAINT check_step_range CHECK (step BETWEEN 1 AND 5),
          ADD CONSTRAINT check_tipe CHECK (tipe IN ('materi','praktek','evaluasi','evaluasi_bab'));");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materi');
    }
};
