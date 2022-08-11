<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RestablecerPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $enlace;
    public $usuario;

    public function __construct($enlace, $usuario)
    {
      $this->enlace  = $enlace;
      $this->usuario = $usuario;
    }
    public function build()
    {
        return $this->view('mail.restablecer_correo')
                    ->subject("Restablecer Contraseña SONDEALO");
    }
}
