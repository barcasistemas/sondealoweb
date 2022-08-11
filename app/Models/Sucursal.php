<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory;

    protected $table      = 'sucursales';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    protected $fillable = [
        'sucursal',
        'identificador',
        'empresa',
        'usuario',
        'pass',
        'activo',
        'tipousr',
        'url_menu',
        'url_qr',
        'notificacion_detallada',
        'sucursal_domicilios',
        'pedir_folio',
        'notificacion_comentario',
        'sin_preguntas_obligatorias'
    ];
}
