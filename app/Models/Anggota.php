<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Anggota extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

        protected $fillable = [
        'id_akun',
        'nama',
        'alamat',
        'notlp',
        'status',
        'img',
        'akses',
    ];

    protected $table = 'anggotas';

    public function akun()
    {
        return $this->belongsTo(Akun::class, 'id_akun');
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    // Anggota.php
    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class, 'id_anggota');
    }


}
