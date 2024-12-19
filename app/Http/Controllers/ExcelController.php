<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\CorreosExport;
use App\Exports\ReporteGeneralExport;
use App\Exports\UsuariosPlataforma;

class ExcelController extends Controller
{
    public function getCorreosClientes($sucursal = null)
    {
      $actual_fecha = date('d-m-Y-H-i-s');
      $nombre_reporte = "correos-clientes-$sucursal-$actual_fecha.xlsx";
      return \Excel::download(new CorreosExport($sucursal), $nombre_reporte);
    }

    public function getReporteExcel($sucursal, $desde, $hasta)
    {
      $fecha_actual = date('d-m-Y');
      $nombre_reporte = "reporte-$sucursal-generado-$fecha_actual.xlsx";
      return \Excel::download(new ReporteGeneralExport($sucursal, $desde, $hasta), $nombre_reporte);
    }

    public function getUsuariosPlataforma()
    {
      $fecha_actual = date('d-m-Y-H-i-s');
      $nombre_reporte = "reporte-usuarios-plataforma-$fecha_actual.xlsx";
      return \Excel::download(new UsuariosPlataforma(), $nombre_reporte);
    }











}
