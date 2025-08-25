<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PengumumanBaru
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

public $judul, $isi, $tanggal;

    public function __construct($judul, $isi, $tanggal)
    {
        $this->judul = $judul;
        $this->isi = $isi;
        $this->tanggal = $tanggal;
    }

    public function broadcastOn()
    {
        return new Channel('pengumuman-channel');
    }

    public function broadcastAs()
    {
        return 'pengumuman-baru';
    }
}
