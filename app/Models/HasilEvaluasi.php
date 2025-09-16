<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HasilEvaluasi extends Model
{
    use HasFactory;

    protected $table = 'hasil_evaluasi';

    protected $fillable = [
        'user_id','evaluasi_id','skor','lulus',
    ];

    protected $casts = [
        'user_id'    => 'integer',
        'evaluasi_id'=> 'integer',
        'skor'       => 'integer',
        'lulus'      => 'boolean',
    ];

    /* ===== Relationships ===== */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function evaluasi()
    {
        return $this->belongsTo(Evaluasi::class);
    }
}
