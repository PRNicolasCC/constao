<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActivateAccountMail extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $token;

    public function __construct($usuario, $token)
    {
        $this->usuario = $usuario;
        $this->token = $token;
    }

    public function build()
    {
        return $this->subject('Activa tu cuenta')
                    ->markdown('emails.activar-cuenta');
    }
}
