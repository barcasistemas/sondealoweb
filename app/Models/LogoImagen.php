<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogoImagen extends Model
{
    use HasFactory;
    protected $table      = 'logoimagen';
    protected $primaryKey = 'id';
    public $timestamps    = false;
   
    protected $fillable = [
        "nombre", 
        "ruta", 
        "identificador", 
        "ruta2",
        "sucursal"
    ];
}