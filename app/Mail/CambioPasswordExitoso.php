<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CambioPasswordExitoso extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;

    public function __construct($usuario)
    {
      $this->usuario = $usuario;
    }
    public function build()
    {
        return $this->view('mail.password_restablecido')
                    ->subject("Cambio de contraseña exitoso");
    }
}
