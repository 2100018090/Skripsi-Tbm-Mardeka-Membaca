<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'sender_type',
        'message',
    ];

    // Relasi ke pengirim (anggota atau volunteer, asumsi tabelnya sesuai)
    public function sender()
    {
        return $this->morphTo(null, 'sender_type', 'sender_id');
    }

}
