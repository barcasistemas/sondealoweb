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

class EncuestasExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize,WithStyles
{
    public $sucursal;
    public $desde;
    public $hasta;
    public static $columnas = array('p1', 'p2','p3','p4','p5','p6','p7','recomen', 'p9','p10','p11','p12', 'p13');
    public static $abecedario = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
    public $arreglo_headings;
    public $preguntas_db;


    public function __construct($sucursal, $desde, $hasta)
    {
      $this->sucursal = $sucursal;
      $this->desde = $desde;
      $this->hasta = $hasta;

      $this->preguntas_db = DB::table('cuestionario')->select('pregunta')
      ->whereRaw("sucursal = '$this->sucursal'  AND valor != 2")
      ->orderBy('id','ASC')->get();


      //se llena el arreglo de headings
      $this->arreglo_headings = array('ID','FOLIO', 'MESA', 'VENDEDOR');

      //en caso de que la sucursa coincida se cambian los headings
      $arreglo_sucursales_cambiar_headings = array('cirugia', 'estudios', 'consulta');
      if(in_array($this->sucursal, $arreglo_sucursales_cambiar_headings)){
        $this->arreglo_headings = array('ID','FOLIO', 'UBICACION', 'COLABORADOR');
      }


      foreach ($this->preguntas_db as $pregunta) {
        $this->arreglo_headings[] = mb_strtoupper($pregunta->pregunta,'UTF-8');
      }

      $this->arreglo_headings[] = 'CORREO';
      $this->arreglo_headings[] = 'COMENTARIOS';
      $this->arreglo_headings[] = 'FECHA';
    }

    public function collection()
    {
        $size_preguntas = count($this->preguntas_db);
        $columns = array_slice(self::$columnas, 0, $size_preguntas);
        $str_columnas = 'id, folio, mesa, mesero,'.implode(',', $columns).', correo, comentarios, fec';

        $encuestas = DB::table('calificaciones')->selectRaw($str_columnas)
        ->whereRaw("sucursal='$this->sucursal' AND fec BETWEEN '$this->desde' AND '$this->hasta'")
        ->orderBy('id', 'DESC')->get();

        for($i=0;$i<count($encuestas);$i++)
        {
          $comentario = $encuestas[$i]->comentarios;
          if(substr($comentario, 0, 1) == '"'){
            $comentario = substr($comentario, 1,-1);
          }
          $encuestas[$i]->comentarios = ManipularCadenas::decodeEmoticons($comentario);


          if($this->sucursal == 'bernini')
          {
            $info_form_vendedor = DB::table('formulario_vendedor')
            ->where('id_encuesta', $encuestas[$i]->id)->first();

            if($info_form_vendedor)
            {
              $encuestas[$i]->form_vendedor_p1 = $info_form_vendedor->p1;
              $encuestas[$i]->form_vendedor_p2 = $info_form_vendedor->p2;
              $encuestas[$i]->form_vendedor_p3 = $info_form_vendedor->p3;
              $encuestas[$i]->form_vendedor_p4 = $info_form_vendedor->p4;
            }
            else{
              $encuestas[$i]->form_vendedor_p1 = 'sin información';
              $encuestas[$i]->form_vendedor_p2 = 'sin información';
              $encuestas[$i]->form_vendedor_p3 = 'sin información';
              $encuestas[$i]->form_vendedor_p4 = 'sin información';
            }

          }





        }
        return $encuestas;
    }

    public function styles(Worksheet $sheet)
    {
       $sheet->getStyle('A1:'.self::$abecedario[count($this->arreglo_headings)-1].'1')->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
       ->getStartColor()->setARGB('0658c9');

       $sheet->getStyle('A1:'.self::$abecedario[count($this->arreglo_headings)-1].'1')->getFont()
       ->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
    }

    public function headings() : array
    {
      return $this->arreglo_headings;
    }
    public function title():string
    {

      if($this->sucursal == 'bernini')
      {
        $this->arreglo_headings[] = 'Mayoría por mesa';
        $this->arreglo_headings[] = 'Tipo';
        $this->arreglo_headings[] = 'Momento en la semana';
        $this->arreglo_headings[] = 'Rango de Edades';
      }

      return 'Encuestas';
    }
}
