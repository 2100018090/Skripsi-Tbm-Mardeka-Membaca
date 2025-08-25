<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detailbuku extends Model
{
    use HasFactory;

    protected $table = 'detailbukus';

    protected $fillable = [
        'id_buku',
        'id_fisik',
        'kode',
        'dipinjam',
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku');
    }

    public function fisik()
    {
        return $this->belongsTo(Fisik::class, 'id_fisik');
    }

    public function peminjaman()
    {
        return $this->hasOne(Peminjaman::class, 'id_detail_buku');
    }
}
