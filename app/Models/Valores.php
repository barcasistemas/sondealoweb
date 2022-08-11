<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Valores extends Model
{
    use HasFactory;
    protected $table      = 'valores';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    protected $fillable = [
        'id',
        'valor',
        'nombre',
        'identificador',
        'sucursal'
    ];
}
