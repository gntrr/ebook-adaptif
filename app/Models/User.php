<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'current_bab',
        'current_track',
        'current_step',
        'progress',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'current_bab' => 'integer',
            'current_step' => 'integer',
            'progress' => 'integer',
        ];
    }

    /* ===== Relationships ===== */
    public function hasilEvaluasi()
    {
        return $this->hasMany(HasilEvaluasi::class);
    }

    public function klasifikasi()
    {
        return $this->hasOne(Klasifikasi::class);
    }

    /* ===== Helpers ===== */
    public function learningUrl(): string
    {
        return route('materi.show', [
            $this->current_bab,
            $this->current_track ?: null,
            $this->current_step,
        ]);
    }
}
