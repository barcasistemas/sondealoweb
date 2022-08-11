<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class ConciliacionTicketsExport implements FromCollection,WithHeadings, ShouldAutoSize, WithStyles, WithTitle
{
    public $sucursal;
    public $desde;
    public $hasta;
    public $conciliacion_collection;

    public function __construct($sucursal, $desde, $hasta)
    {
      $this->sucursal = $sucursal;
      $this->desde = $desde;
      $this->hasta = $hasta;

      $this->conciliacion_collection =  DB::table('tickets')->select('fecha','ticket','mesero','mesa','estado',)
      ->whereRaw("sucursal='$this->sucursal' AND fecha BETWEEN '$this->desde' AND '$this->hasta'")
      ->orderBy('id', 'DESC')->get();
    }

    public function collection()
    {
      return $this->conciliacion_collection;
    }

    public function headings() : array
    {
      $arreglo_sucursales_cambiar_headings = array('cirugia', 'estudios', 'consulta');
      if(in_array($this->sucursal, $arreglo_sucursales_cambiar_headings)){
        return ['FECHA', 'FOLIO', 'COLABORADOR', 'UBICACION', 'ESTADO'];
      }
      return ['FECHA','FOLIO','VENDEDOR','MESA','ESTADO'];
    }

    public function styles(Worksheet $sheet)
    {
       $sheet->getStyle('A1:E1')->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
       ->getStartColor()->setARGB('0658c9');

       $sheet->getStyle('A1:E1')->getFont()
       ->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);

       $renglon = 2;
       for($i=0;$i<count($this->conciliacion_collection);$i++)
       {
         if($this->conciliacion_collection[$i]->estado == 'NO contestada')
         {
           $sheet->getStyle('E'.$renglon )->getFill()
           ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
           ->getStartColor()->setARGB('ffff00');
         }
         $renglon++;
       }
    }

    public function title() : string
    {
      return 'Conciliación de Folios';
    }
}
