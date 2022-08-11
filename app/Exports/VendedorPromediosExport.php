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

class VendedorPromediosExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize,WithStyles
{
    public $sucursal;
    public $desde;
    public $hasta;
    public $headings;
    public $preguntas;
    public static $abecedario = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');


    public function __construct($sucursal, $desde, $hasta)
    {
      $this->sucursal = $sucursal;
      $this->desde    = $desde;
      $this->hasta    = $hasta;

      $this->preguntas = DB::table('cuestionario')->select('id', 'pregunta', 'valor', 'valor2')
      ->whereRaw("sucursal='$this->sucursal' AND valor != 2")
      ->orderBy('id', 'ASC')->get();
      /*Columnas*/
      $this->headings = array('VENDEDOR');
      

      $arreglo_sucursales_cambiar_headings = array('cirugia', 'estudios', 'consulta');
      if(in_array($this->sucursal, $arreglo_sucursales_cambiar_headings)){
        $this->headings = array('COLABORADOR');
      }


      foreach ($this->preguntas as $p) {
        $this->headings[] = $p->pregunta;
      }
      $this->headings[] = 'CONTESTADAS';
      $this->headings[] = 'NO CONTESTADAS';
      $this->headings[] = 'PROMEDIO';
    }
    public function collection()
    {
      $contador = 0;
      $full_sql = '';

      for($i=0;$i<count($this->preguntas);$i++)
      {
        $str_sql = '';
        $multiplicador = 0;
        $multiplicador = ManipularCadenas::getMultiplicador($this->preguntas[$i]->valor, $this->preguntas[$i]->valor2);

        $str_sql = ($this->preguntas[$i]->id == 1) ? ", ROUND( (AVG(eval)*$multiplicador) , 2) AS 'prom1'" : ",ROUND( (AVG(eval".(string)$this->preguntas[$i]->id.")*$multiplicador) , 2 ) AS 'prom".(string)$this->preguntas[$i]->id."'";

        if($this->preguntas[$i]->valor == 0 or $this->preguntas[$i]->valor == 5 or $this->preguntas[$i]->valor == 8)
        {
          $contador++;
        }
        elseif($this->preguntas[$i]->valor == 4 and $this->preguntas[$i]->valor2 == 1){
          $contador++;
        }
        $full_sql .= $str_sql;
      }

      $arreglo_promedios = DB::table('calificaciones')
      ->selectRaw("mesero as 'vendedor' $full_sql , COUNT(*) as 'contestadas' ")
      ->whereRaw("sucursal='$this->sucursal' AND fec BETWEEN '$this->desde' AND '$this->hasta'")
      ->groupBy('mesero')->get();

      $arreglo_return = array();
      $sumador = 0;

      for($j=0;$j<count($arreglo_promedios);$j++)
      {
        $sumador = 0;

        for ($k=0; $k < count($this->preguntas); $k++)
        {
          if($this->preguntas[$k]->valor == 0 or $this->preguntas[$k]->valor == 5 or $this->preguntas[$k]->valor == 8)
          {
            $sumador += $arreglo_promedios[$j]->{'prom'.($k+1)};
          }
          elseif($this->preguntas[$k]->valor == 4 and $this->preguntas[$k]->valor2 == 1)
          {
            $sumador += $arreglo_promedios[$j]->{'prom'.($k+1)};
          }
        }

        $no_contestadas = DB::table('nocontestadas')
        ->selectRaw("count(meseros) as 'nocontestadas'")
        ->whereRaw("meseros='".$arreglo_promedios[$j]->vendedor."' AND sucursal='$this->sucursal' AND fec2 BETWEEN '$this->desde' AND '$this->hasta' ")
        ->first()->nocontestadas;

        $promedio_vnd = ($contador > 0) ? $sumador / $contador : 0;

        $arreglo_promedios[$j]->no_contestadas = $no_contestadas;
        $arreglo_promedios[$j]->promedio_final = number_format($promedio_vnd , 2, '.', '');
      }
      return $arreglo_promedios;
    }

    public function headings() : array
    {
      return $this->headings;
    }
    public function styles(Worksheet $sheet)
    {
      $sheet->getStyle('A1:'.self::$abecedario[count($this->preguntas)+3].'1')->getFill()
     ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('0658c9');

      $sheet->getStyle('A1:'.self::$abecedario[count($this->preguntas)+3].'1')->getFont()
      ->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
    }
    public function title():string
    {
      return 'Vendedores';
    }
}
