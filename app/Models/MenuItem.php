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
        "ingredientes", 
        "precio"
    ];
}
