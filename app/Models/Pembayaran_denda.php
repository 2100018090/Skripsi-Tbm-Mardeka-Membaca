<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran_denda extends Model
{
     use HasFactory;

    protected $table = 'pembayaran_dendas'; // pastikan nama tabel benar

    protected $fillable = [
        'id_peminjaman',
        'jumlah_denda',
        'status_pembayaran',
        'tanggal_pembayaran',
        'metode_pembayaran',
        'bukti_pembayaran',
    ];

    // Relasi ke peminjaman
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman');
    }
}
