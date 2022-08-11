<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Sucursal;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class UsuariosPlataforma implements FromView
{
  public function view(): View
  {
    $usuarios_db = User::select('usuario', 'nombre','correo', 'fec', 'identificador')->where('poder', 1)->orderBy('id','DESC')->get();
    $usuarios_return = array();
    for ($i=0; $i < count($usuarios_db); $i++)
    {
      $usuario       = $usuarios_db[$i]->usuario;
      $nombre        = $usuarios_db[$i]->nombre;
      $correo        = $usuarios_db[$i]->correo;
      $registro      = $usuarios_db[$i]->fec;
      $identificador = $usuarios_db[$i]->identificador;

      $sucursales = Sucursal::select('sucursal')->where('identificador', $identificador)->get();

      $usuarios_return[$i] = array(
        'usuario'            => $usuario,
        'nombre'             => $nombre,
        'correo'             => $correo,
        'sucursales'         => $sucursales,
        'fecha'              => $registro
      );

    }
      return view('excel_exports.usuariosplataforma', [ 'usuarios_return' => $usuarios_return]);
  }

}
