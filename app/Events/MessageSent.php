<?php

namespace App\Events;

use App\Models\Pesan;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

     public $pesan;

    public function __construct(Pesan $pesan)
    {
        // Load relasi pengirim supaya bisa kirim nama pengirim ke frontend
        $this->pesan = $pesan->load('pengirim');
    }

    /**
     * Tentukan channel broadcast-nya.
     * Jika ada penerima, kirim ke private channel penerima.
     * Jika tidak ada penerima, kirim ke public channel volunteer.
     */
    public function broadcastOn()
    {
        if ($this->pesan->penerima_id) {
            return new PrivateChannel('chat.' . $this->pesan->penerima_id);
        }

        return new Channel('chat.volunteer');
    }

    /**
     * Nama event yang dipakai di frontend (opsional tapi bagus untuk kejelasan)
     */
    public function broadcastAs()
    {
        return 'message.sent';
    }

    /**
     * Data yang dikirim ke frontend lewat broadcast.
     */
    public function broadcastWith()
    {
        return [
            'id'           => $this->pesan->id,
            'pengirim_id'  => $this->pesan->pengirim_id,
            'pengirim_nama'=> $this->pesan->pengirim->nama ?? 'Anonymous',
            'penerima_id'  => $this->pesan->penerima_id,
            'isi'          => $this->pesan->isi,
            'created_at'   => $this->pesan->created_at->toDateTimeString(),
        ];
    }
}
