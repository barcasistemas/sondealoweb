<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;

use App\Utilidades\ManipularCadenas;

class AlertasExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithTitle
{
    public $sucursal;
    public $desde;
    public $hasta;

    public function __construct($sucursal, $desde, $hasta)
    {
      $this->sucursal = $sucursal;
      $this->desde = $desde;
      $this->hasta = $hasta;
    }
    public function collection()
    {
      $collection = DB::table('calificaciones')->select('folio', 'comentarios', 'fec')
      ->whereRaw("sucursal='$this->sucursal' AND comentarios != '' AND comentarios !='\"\"' AND fec BETWEEN '$this->desde' AND '$this->hasta'")
      ->orderBy('fec', 'DESC')->get();

      for($i=0;$i<count($collection);$i++)
      {
        $comentario = $collection[$i]->comentarios;
        // if(substr($comentario, 0, 1) == '"'){
        //   $comentario = substr($comentario, 1,-1);
        // }
        $collection[$i]->comentarios = ManipularCadenas::decodeEmoticons($comentario);

        if(!ManipularCadenas::buscarAlerta($collection[$i]->comentarios)){
          unset($collection[$i]->folio);
          unset($collection[$i]->comentarios);
          unset($collection[$i]->fec);
        }
      }
      return $collection;
    }

    public function headings() : array
    {
      return ['FOLIO', 'COMENTARIO', 'FECHA'];
    }
    public function styles(Worksheet $sheet)
    {
       $sheet->getStyle('A1:C1')->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
       ->getStartColor()->setARGB('0658c9');

       $sheet->getStyle('A1:C1')->getFont()
       ->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
    }
    public function title(): string
   {
       return 'Alertas';
   }
}
