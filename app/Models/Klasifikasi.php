<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Klasifikasi extends Model
{
    use HasFactory;

    protected $table = 'klasifikasi';
    protected $fillable = ['user_id','kategori']; // 'A' | 'B' | 'C'

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
