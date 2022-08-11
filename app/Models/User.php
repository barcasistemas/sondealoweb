<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table      = 'registros';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    protected $fillable = [
      'usuario',
      'contra',
      'nombre',
      'correo',
      'identificador',
      'tipoplan',
      'telefono',
      'cookie',
      'activado',
      'fec',
      'empresa',
      'codigo',
      'poder',
      'facultad1',
      'facultad2',
      'facultad3',
      'facultad4',
      'facultad5',
      'facultad6',
      'facultad7',
      'facultad8',
      'sucs',
      'facultad9',
      'customer_id',
      'estado',
      'ciudad',
      'pais',
      'cp',
      'direccion',
      'inicia',
      'facultad10',
      'cuenta'
    ];

}
