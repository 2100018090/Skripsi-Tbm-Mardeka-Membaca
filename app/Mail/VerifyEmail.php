<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $akun;

    public function __construct($akun)
    {
        $this->akun = $akun;
    }

    public function build()
    {
        return $this->subject('Verifikasi Email Anda')
            ->view('emails.verify')
            ->with([
                'url' => url('/verifikasi-email?token=' . $this->akun->email_verification_token),
                'username' => $this->akun->username,
            ]);
    }
}
