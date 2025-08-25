<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Digital extends Model
{
    use HasFactory;

    protected $table = 'digitals';

    protected $fillable = [
        'id_buku',
        'file_url',
        'jumlahHalaman',
    ];

    // Relasi ke model Buku
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku');
    }
}
