<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'aksi', 'deskripsi', 'ip_address'
    ];

    public function akun()
    {
        return $this->belongsTo(Akun::class, 'user_id');
    }
}
