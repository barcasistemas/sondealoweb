<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuCategoria extends Model
{
    use HasFactory;
    protected $table      = 'categorias_menu';
    protected $primaryKey = 'id';
    public $timestamps    = false;
   
    protected $fillable = [
        "id_interno", 
        "indice_orden", 
        "id_menu", 
        "sucursal",
        "nombre",
        "nombre_en",
        "imagen_url",
        "id_video",
        "ruta_promo",
        "video_switch",
        "state"
    ];
}