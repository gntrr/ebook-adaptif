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
        Schema::create('evaluasi', function (Blueprint $t) {
            $t->id();

            $t->foreignId('materi_id')->constrained('materi')->cascadeOnDelete();
            $t->text('pertanyaan');
            $t->json('opsi');                 // ["A","B","C","D"] atau bebas
            $t->string('jawaban_benar', 5);   // "A" | "B" | "C" | "D" | dsb
            $t->unsignedSmallInteger('bobot')->default(100); // opsional
            $t->timestamps();

            $t->index('materi_id');
        });

        // Opsional validasi jawaban (kalau mau strict A-D)
        DB::statement("ALTER TABLE evaluasi ADD CONSTRAINT check_jawaban_abcd CHECK (jawaban_benar IN ('A','B','C','D'));");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasi');
    }
};
