<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'bukus';

    protected $fillable = [
        'id_kategori',
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'tipe',
        'harga',
        'isbn',
        'deskripsi',
        'img',
    ];

    /**
     * Relasi: Buku milik satu Kategori
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function digital()
    {
        return $this->hasOne(Digital::class, 'id_buku');
    }

    public function fisik()
    {
        return $this->hasOne(Fisik::class, 'id_buku');
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_peminjaman');
    }

    public function detailbukus()
    {
        return $this->hasMany(Detailbuku::class, 'id_buku');
    }



}
