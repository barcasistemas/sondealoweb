<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\AlertasExport;
use App\Exports\EncuestasExport;
use App\Exports\ConciliacionTicketsExport;
use App\Exports\VendedorPromediosExport;
use App\Exports\PromediosGeneralesExport;
use App\Exports\GraficasExport;

use DB;

class ReporteGeneralExport implements WithMultipleSheets
{
    public $sucursal;
    public $desde;
    public $hasta;

    public function __construct($sucursal, $desde, $hasta)
    {
      $this->sucursal = $sucursal;
      $this->desde    = $desde;
      $this->hasta    = $hasta;
    }
    public function sheets() : array
    {
      $preguntas =  DB::select(DB::raw(
        "SELECT id, pregunta, valor, valor2 FROM cuestionario WHERE sucursal='$this->sucursal'
        AND valor != 2 AND valor != 3 AND valor != 4 AND valor != 8 AND valor != 9
        UNION ALL
        SELECT id, pregunta, valor, valor2 FROM cuestionario WHERE sucursal='this->sucursal' AND valor = 4 AND valor2=1
        ORDER BY id ASC"));

        if(count($preguntas) == 0)
        {
          return [
            new GraficasExport($this->sucursal, $this->desde, $this->hasta),
            new EncuestasExport($this->sucursal, $this->desde, $this->hasta),
            new AlertasExport($this->sucursal, $this->desde, $this->hasta)
          ];
        }
        return [
          new PromediosGeneralesExport($this->sucursal, $this->desde, $this->hasta),
          new GraficasExport($this->sucursal, $this->desde, $this->hasta),
          new EncuestasExport($this->sucursal, $this->desde, $this->hasta),
          new VendedorPromediosExport($this->sucursal, $this->desde, $this->hasta),
          new ConciliacionTicketsExport($this->sucursal, $this->desde, $this->hasta),
          new AlertasExport($this->sucursal, $this->desde, $this->hasta)
        ];

    }
}
