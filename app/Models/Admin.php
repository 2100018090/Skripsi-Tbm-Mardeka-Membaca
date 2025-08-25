<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

        protected $fillable = [
        'id_akun',
        'nama',
        'alamat',
        'notlp',
        'img',
    ];

    protected $table = 'admins';

    public function akun()
    {
        return $this->belongsTo(Akun::class, 'id_akun');
    }

}
