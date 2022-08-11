<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;
    protected $table      = 'restablecer_password_s';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    protected $fillable = [
        'usuario',
        'token',
        'usado',
        'creado'
    ];
}
