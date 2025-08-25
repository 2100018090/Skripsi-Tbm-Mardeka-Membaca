<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Voluntter extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

        protected $fillable = [
        'id_akun',
        'nama',
        'jabatan',
        'status',
        'img',
    ];

    protected $table = 'voluntters';

    public function akun()
    {
        return $this->belongsTo(Akun::class, 'id_akun');
    }

    public function peminjamanDiberikan()
    {
        return $this->hasMany(Peminjaman::class, 'id_voluntter_pinjam');
    }

    public function peminjamanDikembalikan()
    {
        return $this->hasMany(Peminjaman::class, 'id_voluntter_kembali');
    }


}
