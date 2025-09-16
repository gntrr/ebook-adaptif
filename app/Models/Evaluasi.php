<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Evaluasi extends Model
{
    use HasFactory;

    protected $table = 'evaluasi';
    protected $fillable = [
        'materi_id',
        'pertanyaan',
        'jawaban_benar',
        // MODE JSON
        'opsi',
        // MODE A–D (kalau kamu pakai kolom ini)
        'opsi_a','opsi_b','opsi_c','opsi_d',
        'bobot',
    ];

    protected $casts = [
        'opsi'  => 'array',
        'bobot' => 'integer',
    ];

    /* ===== Relationships ===== */
    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }

    public function hasil()
    {
        return $this->hasMany(HasilEvaluasi::class);
    }
}
