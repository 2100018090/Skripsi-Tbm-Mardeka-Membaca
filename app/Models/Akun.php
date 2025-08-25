<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Akun extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
        protected $fillable = [
            'email',
            'username',
            'password',
            'role',
            'session_token',
            'email_verification_token',
            'email_verified_at',
            'reset_password_token',           // ✅ Tambahan
            'reset_password_expires_at',      // ✅ Tambahan
            'img_identitas',                  // ✅ Tambahan untuk gambar identitas
        ];

    protected $table = 'akuns';

    public function admin()
    {
        return $this->hasOne(Admin::class, 'id_akun');
    }

    public function anggota()
    {
        return $this->hasOne(Anggota::class, 'id_akun');
    }

    public function voluntter()
    {
        return $this->hasOne(Voluntter::class, 'id_akun');
    }

}
