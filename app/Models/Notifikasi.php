<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;
    protected $fillable = ['id_anggota', 'judul', 'pesan', 'dibaca'];

    protected $table = 'notifikasis';

    // Notifikasi.php
    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota');
    }

}
