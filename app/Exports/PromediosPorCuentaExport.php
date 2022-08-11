<?php

// namespace App\Exports;

// use Maatwebsite\Excel\Concerns\FromCollection;
// use Illuminate\Support\Facades\DB;
// use Maatwebsite\Excel\Concerns\WithHeadings;
// use Maatwebsite\Excel\Concerns\WithTitle;
// use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// use Maatwebsite\Excel\Concerns\WithStyles;
// use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
// use App\Utilidades\ManipularCadenas;

// use \Illuminate\Support\Collection;

// class PromediosPorCuentaExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
// {
//     public $sucursales;
//     public $identificador;
//     public $desde;
//     public $hasta;
//     public static $columnas = array('eval', 'eval2','eval3','eval4','eval5','eval6','eval7','eval8', 'eval9','eval10','eval11','eval12', 'eval13');
//     public static $abecedario = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
//     public $headings = [];
//     public $promedios;

//     public function __construct($identificador, $desde, $hasta)
//     {
//       $this->identificador = $identificador;
//       $this->desde    = $desde;
//       $this->hasta    = $hasta;

//       $this->sucursales  = DB::table('sucursales')->select('sucursal')->where('identificador', $this->identificador)->get();

//       for($i=0;$i<count($this->sucursales);$i++)
//       {
//         $sucursal = $this->sucursales[$i]->sucursal;

//         $preguntas =  DB::select(DB::raw(
//             "SELECT id, pregunta, valor, valor2 FROM cuestionario WHERE sucursal='$sucursal'
//             AND valor != 2 AND valor != 3 AND valor != 4 AND valor != 8 AND valor != 9
//             UNION ALL
//             SELECT id, pregunta, valor, valor2 FROM cuestionario WHERE sucursal='$sucursal' AND valor = 4 AND valor2=1
//             ORDER BY id ASC"));
//         /*Columnas*/
//         foreach ($preguntas as $pregunta) 
//         {
//             $this->headings[$sucursal] = $pregunta->pregunta;
//         }
//       }


//     }


//     public function collection()
//     {
        

//         for($i=0;$i<count($this->sucursales);$i++)
//         {
//             $sucursal = $this->sucursales[$i]->sucursal;

//             $preguntas =  DB::select(DB::raw(
//                 "SELECT id, pregunta, valor, valor2 FROM cuestionario WHERE sucursal='$sucursal'
//                 AND valor != 2 AND valor != 3 AND valor != 4 AND valor != 8 AND valor != 9
//                 UNION ALL
//                 SELECT id, pregunta, valor, valor2 FROM cuestionario WHERE sucursal='$sucursal' AND valor = 4 AND valor2=1
//                 ORDER BY id ASC"));

//             $select_arr =array();
            
//             foreach ($preguntas as $p) {
//                 $multiplicador = ManipularCadenas::getMultiplicador($p->valor, $p->valor2);
//                 $select_arr[] =   "ROUND( ( AVG(".self::$columnas[ $p->id -1 ].") )*".$multiplicador.",1) ";
//             }

//             $this->promedios[$sucursal]  = DB::table('calificaciones')->selectRaw( implode(',',$select_arr) )
//             ->whereRaw("sucursal='$sucursal' AND fec BETWEEN '$this->desde' AND '$this->hasta'")
//             ->orderBy('fec', 'DESC')->get();
//         }

//         return Collection::make($this->promedios);
//     }
    
//     public function styles(Worksheet $sheet)
//     {

//     }

//     public function headings() : array
//     {
//       return $this->headings;
//     }

//     public function title() : string
//     {
//       return 'Promedio General';
//     }

// }