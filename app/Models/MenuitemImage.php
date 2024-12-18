<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuitemImage extends Model
{
    use HasFactory;
    protected $table      = 'menu_imagenes_items';
    protected $primaryKey = 'id';
    public $timestamps    = false;
   
    protected $fillable = [
        "id_item", 
        "ruta_servidor"
    ];
}