<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    use HasFactory;

    protected $table      = 'cuestionario';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    protected $fillable = [
        'id',
        'pregunta',
        'calificacion',
        'valor',
        'valor2',
        'identificador',
        'sucursal',
        'textos'
    ];
}
