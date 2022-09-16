<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;
    protected $table      = 'menu_items';
    protected $primaryKey = 'id';
    public $timestamps    = false;
   
    protected $fillable = [
        "id_categoria", 
        "nombre", 
        "nombre_en",
        "ingredientes", 
        "ingredientes_en",
        "precio",
        "recomen",
        "recomen_en",
        "recom_catid",
        "recom_platid",
        "url_video"
    ];
}
