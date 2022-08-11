<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Utilidades\MenuHTML;
use Illuminate\Support\Facades\DB;
use Validator;


class ViewAdminController extends Controller
{
  public function __construct()
  {
    $menu_html =  MenuHTML::getMenuAdministracionSondealo();

    view()->share(['menu' => $menu_html]);
  }

  public function homeAdministracion()
  {
    $inicio = date('Y-m-d');
    $inicio .= ' 05:00:00';

    $final =  date("Y-m-d", strtotime(date('Y-m-d') . "+1 day"));
    $final .= ' 04:59:59';

    $conteo = DB::table('calificaciones')->selectRaw("count(*) as 'conteo'")
    ->whereRaw(" fec BETWEEN '$inicio' AND '$final' ")->first()->conteo;

    $colores_chart = array('1' => '#2c6903', '2' => '#337b04', '3' => '#63b503', '4' => '#6fca05', '5' => '#feae02',
    '6' => '#fec002', '7' => '#fede02', '8' => '#fa2323', '9' => '#e31010', '10' => '#ca0805');

    $chart_1 = '';

    if($conteo > 0)
    {
      $info_chart = DB::table('calificaciones')->selectRaw("sucursal, count(*) as 'conteo'")
      ->whereRaw("fec between '$inicio' and '$final'")->groupBy('sucursal')->orderBy('conteo', 'DESC')->limit(7)->get();

      $colores = array();
      $valores = array();
      $labels = array();

      $i=1;
      foreach ($info_chart as $info) {
        $valores[] = $info->conteo;
        $labels[]  = $info->sucursal;
        $colores[] = $colores_chart[$i];
        $i++;
      }

      $chart_1 = app()->chartjs
      ->name('chart_1')
      ->type('pie')
      ->size(['width' => 300, 'height' => 90])
      ->labels( $labels)
      ->datasets([
          [
              'backgroundColor' =>  $colores,
              'data' =>  $valores,
          ],
      ])->optionsRaw("{
        legend: {
          display: true,
          position: 'bottom'
        },
        responsive:true,

        scales: {
          yAxes: [{
            display: false,
            ticks: {
              suggestedMin: 0,
              beginAtZero: true,
              max: 10
            }
          }]
        }
      }");
    }

    return view('sondealo_administracion.homeadmin', compact('conteo', 'chart_1'));
  }

  public function usuariosTotales()
  {
    $usuarios = DB::table('registros')->select('id','usuario','nombre', 'telefono')
    ->where('poder', 1)->orderBy('id', 'DESC')->paginate(25);

    for($i=0;$i<count($usuarios);$i++)
    {
      $registros_plan_asc = DB::table('registros_planes')->select('fecha_inicio')
      ->where('registros_id', $usuarios[$i]->id)->orderBy('id', 'ASC')->first();

      $usuarios[$i]->inicio  = $registros_plan_asc->fecha_inicio  ?? 'A empezar';

      $registros_plan_desc = DB::table('registros_planes')->select('fecha_termino', 'estatus', 'planes_id')
      ->where('registros_id', $usuarios[$i]->id)->orderBy('id', 'DESC')->first();

      $termino ='';
      $plan = 56;
      if($registros_plan_desc)
      {
        $termino = $registros_plan_desc->fecha_termino;
        $plan    = $registros_plan_desc->planes_id;
      }

      $plan_info = DB::table('planes')->select('amount')->where('id', $plan)->first();

      $usuarios[$i]->termino    = $termino;
      $usuarios[$i]->plan       = $plan;
      $usuarios[$i]->total_pago = $plan_info->amount/100;
    }
      return view('sondealo_administracion.usuarios', compact('usuarios'));

  }


  public function encuestasRecientes($desde = null, $hasta = null)
  {
    $sucursalesEncuestas = array();
    $desde_inicial = '';
    $hasta_inicial = '';
    if($desde != null and $hasta != null)
    {
      $validator = Validator::make(['desde' => $desde, 'hasta' => $hasta], [
        'desde' => 'required|before_or_equal:hasta|date_format:Y-m-d',
        'hasta' => 'required|after_or_equal:desde|date_format:Y-m-d'
      ]);

      if( ! $validator->fails()){

        $desde_inicial = $desde;
        $hasta_inicial = $hasta;

        $desde = $desde.' 05:00:00';

        $hasta = date("Y-m-d", strtotime( $hasta . "+1 day"));
        $hasta = $hasta.' 04:59:59';

        $sucursalesEncuestas = DB::table('calificaciones')->selectRaw("DISTINCT sucursal , count(*) AS 'conteo'")
        ->whereRaw("fec BETWEEN '$desde' AND '$hasta'")->groupBy('sucursal')->orderBy('conteo', 'desc')->paginate(25);

      }
    }

    return view('sondealo_administracion.encuestas_recientes', compact('sucursalesEncuestas', 'desde_inicial', 'hasta_inicial'));

  }

}
