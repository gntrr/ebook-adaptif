<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hasil_evaluasi', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('evaluasi_id')->constrained('evaluasi')->cascadeOnDelete();
            $t->unsignedSmallInteger('skor');     // 0..100
            $t->boolean('lulus');                  // default rule: skor >= 60
            $t->timestamps();

            $t->index(['user_id', 'evaluasi_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_evaluasi');
    }
};
