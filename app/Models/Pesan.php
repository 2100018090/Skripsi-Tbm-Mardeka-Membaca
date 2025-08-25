<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    use HasFactory;

    protected $table = 'pesans'; // override nama tabel default

    protected $fillable = [
        'pengirim_id',
        'penerima_id',
        'isi',
        'dibaca',
        'created_at',
    ];

    // Relasi ke pengirim (akun)
    public function pengirim()
    {
        return $this->belongsTo(Akun::class, 'pengirim_id');
    }

    // Relasi ke penerima (akun)
    public function penerima()
    {
        return $this->belongsTo(Akun::class, 'penerima_id');
    }
}
