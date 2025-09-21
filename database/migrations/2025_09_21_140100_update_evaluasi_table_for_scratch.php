<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE evaluasi DROP CONSTRAINT IF EXISTS check_jawaban_abcd;");
        DB::statement("ALTER TABLE evaluasi ALTER COLUMN jawaban_benar TYPE varchar(50);");
        DB::statement("ALTER TABLE evaluasi ALTER COLUMN jawaban_benar SET DEFAULT 'scratch';");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE evaluasi ALTER COLUMN jawaban_benar TYPE varchar(5);");
        DB::statement("ALTER TABLE evaluasi ALTER COLUMN jawaban_benar DROP DEFAULT;");
        DB::statement("ALTER TABLE evaluasi ADD CONSTRAINT check_jawaban_abcd CHECK (jawaban_benar IN ('A','B','C','D'));");
    }
};
