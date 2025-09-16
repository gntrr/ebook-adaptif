<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materi';
    protected $fillable = [
        'bab','track','step','tipe','judul','konten',
        // kalau kamu pakai level seperti di draf:
        // 'level',
    ];

    protected $casts = [
        'bab'  => 'integer',
        'step' => 'integer',
    ];

    /* ===== Relationships ===== */
    public function evaluasis()
    {
        return $this->hasMany(Evaluasi::class);
    }

    /* ===== Scopes ===== */
    public function scopeSlot($q, int $bab, ?string $track, int $step)
    {
        return $q->where('bab', $bab)
                 ->when($track, fn($x)=>$x->where('track',$track), fn($x)=>$x->whereNull('track'))
                 ->where('step', $step);
    }

    public function scopeOrdered($q)
    {
        return $q->orderBy('bab')->orderBy('track')->orderBy('step')
                 ->orderByRaw("CASE tipe WHEN 'materi' THEN 1 WHEN 'praktek' THEN 2 WHEN 'evaluasi' THEN 3 WHEN 'evaluasi_bab' THEN 4 ELSE 5 END");
    }
}
