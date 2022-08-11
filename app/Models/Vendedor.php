<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    use HasFactory;

    protected $table      = 'meseros1';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    protected $fillable = [
        'nombre',
        'clave',
        'identificador',
        'correo',
        'activado',
        'sucursal'
    ];

}
