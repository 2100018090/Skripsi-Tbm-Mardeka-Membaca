<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fisik extends Model
{
    use HasFactory;

    protected $table = 'fisiks';

    protected $fillable = [
        'id_buku',
        'stok',
    ];

    // Relasi ke model Buku
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku');
    }

    public function detailbukus()
    {
        return $this->hasMany(Detailbuku::class, 'id_fisik');
    }
}
