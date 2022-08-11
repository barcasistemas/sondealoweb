<?php

// namespace App\Exports;

// use Maatwebsite\Excel\Concerns\WithMultipleSheets;
// use App\Exports\PromediosPorCuentaExport;

// use DB;

// class ReporteExcelPorCuentaExport implements WithMultipleSheets
// {
//     public $identificador;
//     public $desde;
//     public $hasta;

//     public function __construct($identificador, $desde, $hasta)
//     {
//       $this->identificador = $identificador;
//       $this->desde         = $desde;
//       $this->hasta         = $hasta;
//     }
//     public function sheets() : array
//     {       
//         return [
//             new PromediosPorCuentaExport($this->identificador, $this->desde, $this->hasta),
//             // new EncuestasExport($this->identificador, $this->desde, $this->hasta),
//             // new AlertasExport($this->identificador, $this->desde, $this->hasta)
//         ];
        

//     }
// }