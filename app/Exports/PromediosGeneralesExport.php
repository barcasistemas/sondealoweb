<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Utilidades\ManipularCadenas;

class PromediosGeneralesExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    public $sucursal;
    public $desde;
    public $hasta;
    public static $columnas = array('eval', 'eval2','eval3','eval4','eval5','eval6','eval7','eval8', 'eval9','eval10','eval11','eval12', 'eval13');
    public static $abecedario = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
    public $preguntas;
    public $headings = [];
    public $promedios;



    public function __construct($sucursal, $desde, $hasta)
    {
      $this->sucursal = $sucursal;
      $this->desde    = $desde;
      $this->hasta    = $hasta;

      $this->preguntas =  DB::select(DB::raw(
        "SELECT id, pregunta, valor, valor2 FROM cuestionario WHERE sucursal='$this->sucursal'
        AND valor != 2 AND valor != 3 AND valor != 4 AND valor != 8 AND valor != 9
        UNION ALL
        SELECT id, pregunta, valor, valor2 FROM cuestionario WHERE sucursal='this->sucursal' AND valor = 4 AND valor2=1
        ORDER BY id ASC"));
      /*Columnas*/
      foreach ($this->preguntas as $pregunta) {
        $this->headings[] = $pregunta->pregunta;
      }
    }

    public function collection()
    {
      $select_arr =array();

      foreach ($this->preguntas as $p) {
        $multiplicador = ManipularCadenas::getMultiplicador($p->valor, $p->valor2);
        $select_arr[] =   "ROUND( ( AVG(".self::$columnas[ $p->id -1 ].") )*".$multiplicador.",1) ";
      }

      $this->promedios  = DB::table('calificaciones')->selectRaw( implode(',',$select_arr) )
      ->whereRaw("sucursal='$this->sucursal' AND fec BETWEEN '$this->desde' AND '$this->hasta'")
      ->orderBy('fec', 'DESC')->get();

      return $this->promedios;

    }

    public function styles(Worksheet $sheet)
    {
      $hasta_ = date("Y-m-d",strtotime($this->hasta."- 1 days"));

      $sheet->insertNewRowBefore(1, 1);
      $sheet->mergeCells('A1:'.self::$abecedario[count($this->preguntas)-1].'1');
      $sheet->setCellValue('A1', 'Reporte '.$this->sucursal.' ['.substr($this->desde, 0, 10).' - '.substr($hasta_,0,10).']'   );
      $sheet->getStyle('A1')->getFont()->setSize(20);

      $estilos = array(
         'font' => [
             'bold' => true,
             'color' => ['rgb' => 'ffffff' ],
         ],
         'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,],
         'fill' => [
             'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
             'startColor' => ['argb' => '0658c9'],
         ],
     );
     $sheet->getStyle('A1')->applyFromArray($estilos);


      $sheet->getStyle('A2:'.self::$abecedario[count($this->preguntas)-1].'2')->getFill()
     ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('0658c9');

      $sheet->getStyle('A2:'.self::$abecedario[count($this->preguntas)-1].'2')->getFont()
      ->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);

    }
    public function headings() : array
    {
      return $this->headings;
    }
    public function title() : string
    {
      return 'Promedio General';
    }
}
