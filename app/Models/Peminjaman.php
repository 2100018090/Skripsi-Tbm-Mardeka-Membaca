<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';

    protected $fillable = [
        'id_anggota',
        'id_buku',
        'id_detail_buku',
        'id_voluntter_pinjam',   
        'id_voluntter_kembali',  
        'tanggal_pinjam',
        'tanggal_ambil',
        'tanggal_pengembalian',
        'status_pengembalian',
        'status_kondisi',
        'rating',
        'ulasan',
        'denda',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_ambil' => 'date',
        'tanggal_pengembalian' => 'date',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku');
    }

    public function pembayaranDenda()
    {
        return $this->hasOne(Pembayaran_denda::class, 'id_peminjaman');
    }

    public function detailbuku()
    {
        return $this->belongsTo(Detailbuku::class, 'id_detail_buku');
    }

    public function voluntterPinjam()
    {
        return $this->belongsTo(Voluntter::class, 'id_voluntter_pinjam');
    }

    public function voluntterKembali()
    {
        return $this->belongsTo(Voluntter::class, 'id_voluntter_kembali');
    }

}
