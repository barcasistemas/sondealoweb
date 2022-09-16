<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Validator;
use App\Utilidades\ManipularCadenas;
use App\Utilidades\MenuHTML;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\PreguntaController;
use App\Utilidades\PreviewEncuesta;
use App\Models\PasswordReset;
use App\Models\User;
use App\Models\Pregunta;
use App\Models\Valores;
use App\Models\Sucursal;
use App\Utilidades\encuestaHTML;

class ViewController extends Controller
{
  public function __construct()
  {
    $menu_html = (Session::get('int_tour') == 1) ? MenuHTML::getMenuTour() : MenuHTML::getMenu();

    view()->share(['menu' => $menu_html]);
  }

  public static function getSucursales()
  {
    $sucursales = array();
    if(Session::get('poder') == 1)
    {
      $sucursales = DB::table('sucursales')->select('sucursal')->where('identificador', Session::get('identificador'))->distinct()->get();
    }
    else
    {
      $query = DB::table('registros')->select('sucs')->where('id', Session::get('id'))->first();
      if($query)
      {
        $sucs = explode(',', $query->sucs);

        for($i=0;$i<count($sucs);$i++)
        {
          array_push($sucursales, (object)[ 'sucursal'=>$sucs[$i] ] );
        }
      }
    }
    return $sucursales;
  }

  public function login()
  {
    if( Session::has('user') ){
      return redirect()->route('mostrar_home');
    }
    return view('login');
  }

  public function home($sucursal_url = null)
  {
    $sucursal_url = SessionController::setSesionSucursal($sucursal_url);
    $sucursales_html =  MenuHTML::getSelectSucursales(self::getSucursales());
    return view('home', compact('sucursales_html', 'sucursal_url'));
  }

  public function vendedores($sucursal_url = null)
  {
    $sucursal_url = SessionController::setSesionSucursal($sucursal_url);
    $sucursales_html =  MenuHTML::getSelectSucursales(self::getSucursales());

    $boolean_agregar_vendedores = true;
    $arr_vendedores = array();

    if($sucursal_url != null and Session::has('sucursal_fijada') )
    {
      $tipo_sucursal = DB::table('sucursales')->select('tipousr')->where('sucursal', $sucursal_url)->first();
      if($tipo_sucursal)
      {
        if($tipo_sucursal->tipousr == 1)
        {
          $boolean_agregar_vendedores = false;
        }
        else{
          $arr_vendedores = DB::table('meseros1')->select('id', 'nombre', 'clave')->where(['sucursal' => Session::get('sucursal_fijada'), 'activado' => 1])->orderBy('nombre', 'ASC')->get();
        }
      }

    }

    return view('vendedores', compact('sucursal_url', 'arr_vendedores', 'sucursales_html', 'boolean_agregar_vendedores'));
  }
  public function sucursales($sucursal_url =  null)
  {
    $sucursal_url = SessionController::setSesionSucursal($sucursal_url);
    $sucursales_arreglo = self::getSucursales();
    $sucursales = array();

    $boolean_show_formulario = false;

    $plan_id = DB::table('registros_planes')->select('planes_id')->where('registros_id', Session::get('id'))->orderBy('id', 'DESC')->first()->planes_id;

    $limite = DB::select(DB::raw("SELECT planes.limit as 'limite' FROM planes WHERE id =$plan_id"));
    $limite_integer = $limite[0]->limite;

    if(count($sucursales_arreglo) < $limite_integer)
    {
      $boolean_show_formulario = true;
    }

    if(count($sucursales_arreglo) > 0)
    {
      for($i=0;$i<count($sucursales_arreglo);$i++)
      {
        $sucursal_info = DB::table('sucursales')->select('id', 'sucursal','empresa', 'pass', 'tipousr')->where('sucursal', $sucursales_arreglo[$i]->sucursal)->first();
        array_push($sucursales, $sucursal_info);
      }
    }

    /*si esta el tour activado buscamos si ya dieron una sucursal de alta*/
    if(Session::get('int_tour') == 1)
    {
      if(count($sucursales) > 0)
      {
        $s_t = DB::table('sucursales')->select('sucursal')->where('identificador', Session::get('identificador'))
        ->orderBy('id', 'DESC')->first()->sucursal;
         Session::put(['sucursal_fijada' => $s_t]);
      }
    }

    if(count($sucursales) < 1)
    {
      User::where('id', Session::get('id'))->update(['inicia' => 1]);
      Session::put(['int_tour' => 1]);
    }
    /*---------------------------------------------------------------------------------------------------------------*/

    return view('sucursales', compact('sucursales', 'sucursal_url', 'boolean_show_formulario','limite_integer'));
  }

  public function alertas($sucursal_url = null)
  {
    $sucursal_url = SessionController::setSesionSucursal($sucursal_url);
    $sucursales_html =  MenuHTML::getSelectSucursales(self::getSucursales());
    $arreglo_alertas = array();
    $preguntas_sucursal = array();


    if($sucursal_url != null and Session::has('sucursal_fijada'))
    {
      $preguntas_sucursal = DB::table('cuestionario')->select('id', 'pregunta')
      ->whereRaw("sucursal = '$sucursal_url' and valor != 2")->orderBy('id', 'ASC')->get();

      $calificaciones = DB::table('calificaciones')->selectRaw("id, folio, mesa, mesero, p1,p2,p3,p4,p5,p6,p7,comentarios,recomen as 'p8',
      fecha,correo,p9, p10, p11, p12, p13")
      ->whereRaw("sucursal = '$sucursal_url' AND comentarios != '' AND comentarios != '\"\"' ")
      ->orderBy('id', 'DESC')->limit(70)->get();

      if(sizeof($calificaciones) > 0)
      {
        for($i=0;$i<count($calificaciones);$i++)
        {
          $bool_alerta = false;
          $bool_alerta = ManipularCadenas::buscarAlerta($calificaciones[$i]->comentarios);

          if($bool_alerta)
          {
            if(substr($calificaciones[$i]->comentarios,0,1) == '"')
            {
                $comentario = ManipularCadenas::decodeEmoticons($calificaciones[$i]->comentarios);
                $calificaciones[$i]->comentarios = substr($comentario, 1, -1);
            }
            $evidencia = DB::table('evidencia')->where('id_encuesta', $calificaciones[$i]->id)->first();
            $calificaciones[$i]->evidencia = $evidencia;
            array_push($arreglo_alertas, $calificaciones[$i]);
          }
        }
      }
    }
    return view('alertas', compact('sucursal_url', 'sucursales_html', 'preguntas_sucursal','arreglo_alertas'));
  }
  public function promociones($sucursal_url = null)
  {
    $sucursal_url = SessionController::setSesionSucursal($sucursal_url);
    $sucursales_fn = self::getSucursales();
    $sucursales_html = MenuHTML::getSelectSucursales($sucursales_fn);
    $arreglo_promociones = array();

    if($sucursal_url != null and Session::has('sucursal_fijada'))
    {
      $arreglo_promociones = DB::table('promodia')->select('id', 'ruta')->where('sucursal', $sucursal_url)
      ->orderBy('id', 'ASC')->take(5)->get();
    }
    return view('promociones', compact('sucursal_url', 'sucursales_html', 'arreglo_promociones', 'sucursales_fn'));
  }

  public function encuesta($sucursal_url = null)
  {
    $arr_sucursales = self::getSucursales();
    $sucursal_url = SessionController::setSesionSucursal($sucursal_url);
    $sucursales_html = MenuHTML::getSelectSucursales($arr_sucursales);
    $arr_preguntas = array();
    $arr_valores = array();
    $preguntas_recomendadas = array();
    $logo_info = array();
    $keys_plantillas = array();

    $colorHeader = '#0658c9';
    $colorHeaderText = '#ffffff';

    $arreglo_personalizacion_extra = array();

    if($sucursal_url != null and Session::has('sucursal_fijada'))
    {
      $arr_preguntas = DB::table('cuestionario')->selectRaw("id, pregunta, valor as 'tipo', valor2, sucursal as 's', textos")
      ->whereRaw("sucursal='$sucursal_url' and valor != 2")->orderBy('id', 'ASC')->get();

      $arr_valores = DB::table('valores')->select('valor')->where('sucursal', $sucursal_url)->orderBy('id', 'ASC')->get();

      $preguntas_recomendadas = PreguntaController::$PREGUNTAS_RECOMENDADAS_SONDEALO;

      $logo_info = DB::table('logoimagen')->select('id','ruta')->where('sucursal', $sucursal_url)->first();
      $ruta = explode('/', $logo_info->ruta);
      $logo_info->ruta = $ruta[count($ruta)-1];

      //$keys_plantillas = array_keys(PreguntaController::$preguntasPlantillas);


      $keys_plantillas = PreguntaController::$preguntasPlantillas;


      $check_colors = DB::table('personalizacion_encuesta')
      ->where('sucursal', $sucursal_url)->first();

      if($check_colors){
        $colorHeader      = $check_colors->color_head;
        $colorHeaderText = $check_colors->color_head_text;
      }


      if(Session::get('plan') == 2){
        $arr_preguntas = $arr_preguntas->slice(0,5);
      }


      $arreglo_personalizacion_extra = DB::table('sucursales')
      ->selectRaw("notificacion_comentario as 'siempre_notificacion', emailcomentarios_top as 'mover_top'")
      ->where('sucursal', $sucursal_url)->first();
    }
    return view('encuestas', compact('sucursal_url', 'sucursales_html', 'arr_preguntas',
     'arr_valores', 'arr_sucursales', 'preguntas_recomendadas', 'logo_info', 'keys_plantillas',
     'colorHeader', 'colorHeaderText', 'arreglo_personalizacion_extra'));
  }

  public function usuarios($sucursal_url = null)
  {
    $arr_sucursales  = self::getSucursales();
    $sucursal_url    = SessionController::setSesionSucursal($sucursal_url);
    $sucursales_html = MenuHTML::getSelectSucursales($arr_sucursales);

    $arr_usuarios  = array();
    $identificador = Session::get('identificador');
    $usuario       = Session::get('user');
    $arr_usuarios  = DB::table('registros')->select('id', 'usuario', 'correo')
    ->whereRaw("identificador = $identificador AND usuario != '$usuario' AND poder != 1 AND poder != 3")->orderBy('usuario', 'ASC')->paginate(25);

    return view('usuarios', compact('sucursal_url', 'sucursales_html', 'arr_sucursales','arr_usuarios'));
  }

  public function ajustes()
  {
    $sucursal_url='';
    return view('ajustes', compact('sucursal_url'));
  }

  public function correos($sucursal_url = null)
  {
    $sucursal_url     = SessionController::setSesionSucursal($sucursal_url);
    $sucursales_html  = MenuHTML::getSelectSucursales(self::getSucursales());
    $correos_clientes = array();

    if($sucursal_url != null and Session::has('sucursal_fijada'))
    {
      $correos_clientes = DB::table('calificaciones')->select('correo','fecha')
      ->whereRaw("sucursal = '$sucursal_url' AND correo != '' AND CHAR_LENGTH(correo) > 5")->orderBy('id','DESC')->paginate(25);
    }
    return view('correos_clientes', compact('sucursal_url', 'sucursales_html', 'correos_clientes'));
  }

  public function reportes($sucursal_url = null, $desde = null, $hasta = null)
  {
    $sucursal_url     = SessionController::setSesionSucursal($sucursal_url);
    $sucursales_html  = MenuHTML::getSelectSucursales(self::getSucursales());
    $promedios_preguntas = array();
    $info_charts = array();
    $boolean_show = false;
    $contestadas = 0;
    $no_contestadas = 0;
    $chart1=array(); $chart2=array(); $chart3=array(); $chart4=array(); $chart5=array(); $chart6=array();
    $chart7=array();$chart8=array(); $chart9=array();$chart10=array();$chart11=array(); $chart12=array();
    $chart13=array();

    $hasta_h = '';

    if($sucursal_url != null and Session::has('sucursal_fijada'))
    {
      $boolean_show = true;

      $validateDate = Validator::make(['desde'=>$desde, 'hasta'=> $hasta],
      ['desde' => 'required|date_format:"Y-m-d"', 'required|hasta'=> 'date_format:"Y-m-d"']);

      if(!$validateDate->fails() && $hasta >= $desde)
      {
        $desde = $desde.' 05:00:00';
        $hasta_h = date("Y-m-d", strtotime($hasta."+ 1 days"));
        $hasta_h = $hasta_h.' 04:59:59';

        $promedios_preguntas = PreguntaController::preguntasPromedios($sucursal_url, $desde, $hasta_h);

        $info_charts = PreguntaController::getInfoCharts($sucursal_url, $desde, $hasta_h);

        $contestadas = DB::table('calificaciones')->selectRaw("COUNT(id) as 'contestadas'")
        ->whereRaw("fec BETWEEN '$desde' AND '$hasta_h' AND sucursal='$sucursal_url'")->first()->contestadas;

        $no_contestadas = DB::table('nocontestadas')->selectRaw("COUNT(id) as 'nocontestadas'")
        ->whereRaw("fec2 BETWEEN '$desde' AND '$hasta_h' AND sucursal='$sucursal_url'")->first()->nocontestadas;


        if(count($info_charts) > 0)
        {
          for($i=0;$i<count($info_charts);$i++)
          {
            if(count($info_charts[$i]->valores) > 0)
            {
              ${'chart'.($i+1)} = app()->chartjs
              ->name('chart'.($i+1))
              ->type('pie')
              ->size(['width' => 420, 'height' => 220])
              ->labels( $info_charts[$i]->labels )
              ->datasets([
                [
                  'backgroundColor' =>  $info_charts[$i]->colores,
                  'data' =>  $info_charts[$i]->valores,
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

          }    /*fin ciclo*/
        } /*fin if charts > 0*/

      }/* fin validacion fechas*/
    } /* fin suc_url != null */

    return view('reportes', compact('sucursal_url', 'desde', 'hasta','hasta_h','boolean_show', 'sucursales_html',
    'promedios_preguntas', 'contestadas', 'no_contestadas', 'info_charts', 'chart1', 'chart2', 'chart3', 'chart4', 'chart5', 'chart6',
    'chart7', 'chart8', 'chart9', 'chart10', 'chart11', 'chart12', 'chart13'));
  }


  public function cuponesHistorial($sucursal_url = null, $tipo_reporte = null)
  {
    $sucursal_url     = SessionController::setSesionSucursal($sucursal_url);
    $sucursales_html  = MenuHTML::getSelectSucursales(self::getSucursales());

    $tipo_reporte_aux = 'activo';
    $tipo_reporte_aux = ($tipo_reporte == 'activo' or $tipo_reporte == 'inactivo' or $tipo_reporte == 'todos') ? $tipo_reporte : 'activo';
    $tipo_reporte = $tipo_reporte_aux;

    $arr_cupones = array();
    $boolean_show = false;
    if($sucursal_url != null && Session::has('sucursal_fijada'))
    {
      $boolean_show = true;
      if($tipo_reporte == 'todos')
      {
        $arr_cupones = DB::table('promocion')->select('folio', 'promocion', 'meserogenera', 'generado', 'canjeado')
        ->whereRaw("sucursal='$sucursal_url' AND promocion != '' AND promocion != 'null' AND estado != 'expirado'")->orderBy('generado', 'DESC')
        ->paginate(30);
      }
      else
      {
        $arr_cupones = DB::table('promocion')->select('folio', 'promocion', 'meserogenera', 'generado', 'canjeado')
        ->whereRaw("sucursal='$sucursal_url' AND estado='$tipo_reporte' AND promocion != '' AND promocion != 'null'")
        ->orderBy('generado', 'DESC')->paginate(30);
      }
    }
    return view('historial_cupones', compact('sucursal_url', 'tipo_reporte', 'sucursales_html', 'arr_cupones', 'boolean_show'));
  }

  public function cuponesValidar($sucursal_url = null)
  {
    $sucursal_url     = SessionController::setSesionSucursal($sucursal_url);
    $sucursales_html  = MenuHTML::getSelectSucursales(self::getSucursales());

    $boolean_show = false;
    if($sucursal_url != null && Session::has('sucursal_fijada'))
    {
      $boolean_show = true;
    }
    return view('cupones_validar', compact('sucursal_url', 'sucursales_html', 'boolean_show'));
  }

  public function cupones($sucursal_url = null)
  {
    $sucursal_url     = SessionController::setSesionSucursal($sucursal_url);
    $sucursales_fn    = self::getSucursales();
    $sucursales_html  = MenuHTML::getSelectSucursales($sucursales_fn);
    $cupones = array();
    $boolean_show = false;
    if($sucursal_url != null && Session::has('sucursal_fijada'))
    {
      $cupones = DB::table('promoimagen')->select('id', 'nombre', 'ruta', 'valor2')
      ->where('sucursal', $sucursal_url)->orderBy('id', 'ASC')->limit(5)->get();
      $boolean_show = true;
    }
    return view('cupones', compact('sucursal_url', 'sucursales_html', 'boolean_show', 'cupones', 'sucursales_fn'));
  }

  public function menuQr($sucursal_url = null)
  {
    $sucursal_url        = SessionController::setSesionSucursal($sucursal_url);
    $sucursales_html     = MenuHTML::getSelectSucursales(self::getSucursales());
    $boolean_show        = false;
    $menu_url            = '';
    $id_sucursal         = 0;

    if($sucursal_url != null && Session::has('sucursal_fijada'))
    {
      $boolean_show = true;
      $check_menu_sucursal = DB::table('sucursales')->select('id', 'url_menu')->where('sucursal', $sucursal_url)->first();
      $id_sucursal = $check_menu_sucursal->id;
      $menu_url = $check_menu_sucursal->url_menu;
    }
    return view('menuqr', compact('sucursal_url', 'sucursales_html', 'menu_url', 'boolean_show', 'id_sucursal'));
  }

  public function preview($sucursal)
  {
    $preguntas = DB::table('cuestionario')->select('pregunta', 'valor', 'textos')
    ->whereRaw("sucursal='$sucursal' AND valor != 2")->orderBy('id', 'ASC')->get();

    if(count($preguntas) < 1){
      return 'Sin información';
    }

    $logo_url = DB::table('logoimagen')->select('ruta')->where('sucursal', $sucursal)->first()->ruta;

    $html = "";



    $mostrar_top_binary = DB::table('sucursales')->select('emailcomentarios_top')->where('sucursal', $sucursal)->first()->emailcomentarios_top;

    if($mostrar_top_binary != 1){
       $html .= PreviewEncuesta::logo($logo_url);
    }

    if($mostrar_top_binary == 1)
    {
      $valores_std = DB::table('valores')->select('id','valor')->where('sucursal', $sucursal)->orderBy('id','ASC')->get();

      if($valores_std[2]->valor == 1 and $valores_std[5]->valor == 1){
        $html .= PreviewEncuesta::mostrarInputCorreo(2);
      }elseif($valores_std[2]->valor == 1 and $valores_std[5]->valor == 0){
        $html .= PreviewEncuesta::mostrarInputCorreo(1);
      }elseif($valores_std[2]->valor == 0 and $valores_std[5]->valor == 1){
        $html .= PreviewEncuesta::mostrarInputCorreo(3);
      }else{
        $html .= '';
      }

      ($valores_std[3]->valor == 1) ? $html .= PreviewEncuesta::mostrarInputComentarios() : '';


      for ($i=0; $i < count($preguntas) ; $i++)
      {
        $pregunta = $preguntas[$i]->pregunta;
        $textos = $preguntas[$i]->textos;

        PreviewEncuesta::$pregunta = $pregunta;
        PreviewEncuesta::$textos = $textos;

        switch ($preguntas[$i]->valor) {
          case 0:
            $html .= PreviewEncuesta::tipo0();
            break;
          case 1:
            $html .= PreviewEncuesta::tipo1();
            break;
          case 3:
            $html .= PreviewEncuesta::tipo3();
            break;
          case 4:
            $html .= PreviewEncuesta::tipo4();
            break;
          case 5:
            $html .= PreviewEncuesta::tipo5();
            break;
          case 6:
            $html .= PreviewEncuesta::tipo6();
            break;
            case 8:
            $html .= PreviewEncuesta::tipo8();
            break;
          case 9:
            $html .= PreviewEncuesta::tipo9();
            break;
          case 10:
            $html .= PreviewEncuesta::tipo10();
            break;
        }
      }

    }
    else
    {
      for ($i=0; $i < count($preguntas) ; $i++)
      {
        $pregunta = $preguntas[$i]->pregunta;
        $textos = $preguntas[$i]->textos;

        PreviewEncuesta::$pregunta = $pregunta;
        PreviewEncuesta::$textos = $textos;

        switch ($preguntas[$i]->valor) {
          case 0:
            $html .= PreviewEncuesta::tipo0();
            break;
          case 1:
            $html .= PreviewEncuesta::tipo1();
            break;
          case 3:
            $html .= PreviewEncuesta::tipo3();
            break;
          case 4:
            $html .= PreviewEncuesta::tipo4();
            break;
          case 5:
            $html .= PreviewEncuesta::tipo5();
            break;
          case 6:
            $html .= PreviewEncuesta::tipo6();
            break;
            case 8:
            $html .= PreviewEncuesta::tipo8();
            break;
          case 9:
            $html .= PreviewEncuesta::tipo9();
            break;
          case 10:
            $html .= PreviewEncuesta::tipo10();
            break;
        }
      }

      $valores_std = DB::table('valores')->select('id','valor')->where('sucursal', $sucursal)->orderBy('id','ASC')->get();


      if($valores_std[2]->valor == 1 and $valores_std[5]->valor == 1){
        $html .= PreviewEncuesta::mostrarInputCorreo(2);
      }elseif($valores_std[2]->valor == 1 and $valores_std[5]->valor == 0){
        $html .= PreviewEncuesta::mostrarInputCorreo(1);
      }elseif($valores_std[2]->valor == 0 and $valores_std[5]->valor == 1){
        $html .= PreviewEncuesta::mostrarInputCorreo(3);
      }else{
        $html .= '';
      }

      ($valores_std[3]->valor == 1) ? $html .= PreviewEncuesta::mostrarInputComentarios() : '';

    }



    ($valores_std[1]->valor == 1) ? $html .= PreviewEncuesta::mostrarBotonesFinal(true) : $html .= PreviewEncuesta::mostrarBotonesFinal(false);


    $check_colors = DB::table('personalizacion_encuesta')
    ->where('sucursal', $sucursal)->first();

    $colorHeader = '#0658c9';
    $colorHeaderText = '#ffffff';

    if($check_colors){
      $colorHeader      = $check_colors->color_head;
      $colorHeaderText = $check_colors->color_head_text;
    }

    return view('previewencuesta', compact('html', 'colorHeader', 'colorHeaderText'));
  }

  public function mostrarRestablecerCuenta()
  {
    return view('restablecer_pass');
  }

  public function aplicarResetPassword($token)
  {
    $info_token = PasswordReset::where('token', $token)->first();

    if(!$info_token){
      abort(403, 'El enlace no existe');
    }
    if($info_token->usado == 1){
      abort(403, 'Este enlace ya fue usado');
    }

    $hora_creado     = date($info_token->creado);
    $hora_expiracion = strtotime ( '+6 hour' , strtotime ($hora_creado) );
    $hora_expiracion = date('Y-m-d H:i:s', $hora_expiracion);

    $hora_actual = date('Y-m-d H:i:s');

    if($hora_expiracion < $hora_actual){
      abort(403, 'El enlace caducó');
    }

    return view('aplicar_reset_pass', compact('token'));
  }

  public function metodoPago($sucursal_url = null)
  {
    $sucursal_url  = SessionController::setSesionSucursal($sucursal_url);

    $plan_id = DB::table('registros_planes')->select('planes_id')
    ->where('registros_id', Session::get('id'))->orderBy('id', 'DESC')->first()->planes_id;

    return view('metodopago', compact('sucursal_url', 'plan_id'));
  }

  public function finTour()
  {
    User::where('id', Session::get('id'))->update(['inicia' => 0]);
    Session::put(['int_tour' => 0]);

    return view('finalizaciontour');
  }

  public function getQrEncuestaMesa($sucursal_url = null)
  {
    $sucursal_url     = SessionController::setSesionSucursal($sucursal_url);
    $sucursales_html  = MenuHTML::getSelectSucursales(self::getSucursales());
    $boolean_show_input = false;
    $url_qr_default = '';

   if($sucursal_url != null and Session::has('sucursal_fijada'))
   {
     $url_qr_default = "https://sondealo.com/sitio/qr-encuesta/$sucursal_url";
     $boolean_show_input = true;
   }
    return view('encuesta_generar_qr_mesas', compact('boolean_show_input','sucursal_url', 'sucursales_html', 'url_qr_default'));
  }



  public function encuestaWebLibreQr($sucursal, $mesa = 'qr-libre')
  {
    $preguntas = Pregunta::select('id', 'pregunta', 'valor', 'valor2', 'textos', 'identificador', 'pregunta_en', 'textos_en')->where('sucursal', $sucursal)->where('valor', '!=', 2)->orderBy('id', 'ASC')->get();

    if (count($preguntas) < 1) {
        return abort(404);
    }

    $identificador = $preguntas[0]->identificador ;


    $info_plan = DB::table(DB::raw("registros, registros_planes"))
    ->selectRaw("registros.id, registros_planes.planes_id, registros.activado")
    ->whereRaw("registros.identificador = $identificador and registros.id = registros_planes.registros_id")
    ->orderByRaw("registros_planes.id DESC")->first();



    if($info_plan->activado != 1)
    {
      return abort(403, "\"$sucursal\" desactivada");
    }



    if($info_plan->planes_id == 2)
    {

      $curr_date = date('Y-m-d');
      $limite_inferior  = $curr_date." 00:00:00";
      $limite_superior = $curr_date." 23:59:59";

      $encuestas_realizadas = DB::table('calificaciones')->selectRaw("id")
      ->whereRaw("fec BETWEEN '$limite_inferior' AND '$limite_superior' AND sucursal='$sucursal'")->get();

      if(count($encuestas_realizadas) >= 10){
        abort(403, 'Plan gratis excedido');
      }
    }

    $query_logo = DB::table('logoimagen')->select('ruta')->where('sucursal', $sucursal)->first();
    $arr_img_logo = explode('/', $query_logo->ruta);
    $nombre_logo = $arr_img_logo[count($arr_img_logo)-1];

    $html ='';

    $no_obligatorias = 0;
    $obligatorias    = 0;

    $mostrar_top_binary = DB::table('sucursales')->select('emailcomentarios_top')->where('sucursal', $sucursal)->first()->emailcomentarios_top;




    
    if($mostrar_top_binary == 1)
    {

      $valores_arr = Valores::select('id', 'valor')->where('sucursal', $sucursal)->orderBy('id', 'ASC')->get();

      if($valores_arr[2]->valor == 1 and $valores_arr[5]->valor == 1){
        $html .= encuestaHTML::mostrarInputCorreo(2);
      }elseif($valores_arr[2]->valor == 1 and $valores_arr[5]->valor == 0){
        $html .= encuestaHTML::mostrarInputCorreo(1);
      }elseif($valores_arr[2]->valor == 0 and $valores_arr[5]->valor == 1){
        $html .= encuestaHTML::mostrarInputCorreo(3);
      }else{
        $html .= '';
      }

      ($valores_arr[3]->valor == 1) ? $html .= encuestaHTML::mostrarInputComentarios() : '';


      for ($i = 0; $i < count($preguntas); $i++)
  		{
          $pregunta = $preguntas[$i]->pregunta;
          $textos   = $preguntas[$i]->textos;
          $id       = $preguntas[$i]->id;


          encuestaHTML::$pregunta = $pregunta;
          encuestaHTML::$textos   = $textos;
          encuestaHTML::$id       = $id;

          encuestaHTML::$pregunta_en = $preguntas[$i]->pregunta_en;
          encuestaHTML::$textos_en = $preguntas[$i]->textos_en;


          switch ($preguntas[$i]->valor) {
              case 0:
                  $html .= encuestaHTML::tipo0();
                  break;
              case 1:
                  $html .= encuestaHTML::tipo1();
                  break;
              case 3:
                  $html .= encuestaHTML::tipo3();
                  $no_obligatorias++;
                  break;
              case 4:
                  $valor2 = ($preguntas[$i]->valor2 == 1) ? 1 : 0;
                  $html .= encuestaHTML::tipo4($valor2);
                  break;
              case 5:
                  $html .= encuestaHTML::tipo5();
                  break;
              case 6:
                  $html .= encuestaHTML::tipo6();
                  $no_obligatorias++;
                  break;
              case 8:
                  $html .= encuestaHTML::tipo8();
                  break;
              case 9:
                  $html .= encuestaHTML::tipo9();
                  break;
              case 10:
                  $html .= encuestaHTML::tipo10();
                  break;
              }
          }

    }
    else
    {
      for ($i = 0; $i < count($preguntas); $i++)
  		{
          $pregunta = $preguntas[$i]->pregunta;
          $textos   = $preguntas[$i]->textos;
          $id       = $preguntas[$i]->id;


          encuestaHTML::$pregunta = $pregunta;
          encuestaHTML::$textos   = $textos;
          encuestaHTML::$id       = $id;


          encuestaHTML::$pregunta_en = $preguntas[$i]->pregunta_en;
          encuestaHTML::$textos_en = $preguntas[$i]->textos_en;


          switch ($preguntas[$i]->valor) {
              case 0:
                  $html .= encuestaHTML::tipo0();
                  break;
              case 1:
                  $html .= encuestaHTML::tipo1();
                  break;
              case 3:
                  $html .= encuestaHTML::tipo3();
                  $no_obligatorias++;
                  break;
              case 4:
                  $valor2 = ($preguntas[$i]->valor2 == 1) ? 1 : 0;
                  $html .= encuestaHTML::tipo4($valor2);
                  break;
              case 5:
                  $html .= encuestaHTML::tipo5();
                  break;
              case 6:
                  $html .= encuestaHTML::tipo6();
                  $no_obligatorias++;
                  break;
              case 8:
                  $html .= encuestaHTML::tipo8();
                  break;
              case 9:
                  $html .= encuestaHTML::tipo9();
                  break;
              case 10:
                  $html .= encuestaHTML::tipo10();
                  break;
              }
          }

          $valores_arr = Valores::select('id', 'valor')->where('sucursal', $sucursal)->orderBy('id', 'ASC')->get();

          if($valores_arr[2]->valor == 1 and $valores_arr[5]->valor == 1){
            $html .= encuestaHTML::mostrarInputCorreo(2);
          }elseif($valores_arr[2]->valor == 1 and $valores_arr[5]->valor == 0){
            $html .= encuestaHTML::mostrarInputCorreo(1);
          }elseif($valores_arr[2]->valor == 0 and $valores_arr[5]->valor == 1){
            $html .= encuestaHTML::mostrarInputCorreo(3);
          }else{
            $html .= '';
          }

          ($valores_arr[3]->valor == 1) ? $html .= encuestaHTML::mostrarInputComentarios() : '';

    }


        $html .= encuestaHTML::mostrarBotonesFinal();


        $domicilios_str = DB::table('sucursales')->select('sucursal_domicilios', 'pedir_folio')->where('sucursal', $sucursal)->first();
        if(trim($domicilios_str->sucursal_domicilios))
        {
          $domicilios_arr = explode(',' , $domicilios_str->sucursal_domicilios);
          $html .= encuestaHTML::sucursalDomicilioAsVendedor($domicilios_arr);
        }


        if($domicilios_str->pedir_folio == 1){

            $html .= encuestaHTML::pedirFolioTexto();
        }


        $check_colors = DB::table('personalizacion_encuesta')
        ->where('sucursal', $sucursal)->first();

        $colorHeader = '#0658c9';
        $colorHeaderText = '#ffffff';

        if($check_colors){
          $colorHeader      = $check_colors->color_head;
          $colorHeaderText = $check_colors->color_head_text;
        }


        /*modificado*/
        
        $consultaMandLang = Sucursal::select('sin_preguntas_obligatorias', 'lang_en')->where('sucursal', $sucursal)->first();
       
       
        $obligatorias = ($consultaMandLang->sin_preguntas_obligatorias == 1) ? 0 : count($preguntas) - $no_obligatorias;


        $lang_en_bool = ($consultaMandLang->lang_en == 1) ? true : false;

        /*modificado*/
        return view('encuesta_libre_web_qr', compact('lang_en_bool' , 'html', 'obligatorias', 'sucursal', 'mesa', 'colorHeader', 'colorHeaderText', 'nombre_logo'));
  }

  public function encuestaWhatsapp($key)
  {
    $check_key = DB::table('estadoencuestaPrueba')->select('id', 'sucursal', 'estado', 'identificador')->where(['clave' => $key])->first();
    if( ! $check_key){
      return abort(404);
    }
    if($check_key->estado != 'activo'){
      return abort(403, 'Este enlace ya ha sido usado');
    }


    $infoUser = User::select('activado')->where(['identificador' => $check_key->identificador, 'poder' => 1])->first();

    if($infoUser->activado != 1){
      return abort(403, "\"$check_key->sucursal\" desactivada");
    }

    $sucursal  = $check_key->sucursal;
    $mesa      = 'Compartido-Whatsapp';

    $javascript = 'fetch("'.route("cambiar_estado_encuesta_wa").'", {
      method:"post",
      body:JSON.stringify({"key" : '.$check_key->id.' }),
      headers:{
        "Content-Type":"application/json",
        "X-CSRF-TOKEN": "'.csrf_token().'" } }).then(res=>res.json()).then(function(response){console.log(response.msg);})';

    $preguntas = Pregunta::select('id', 'pregunta', 'valor', 'valor2', 'textos', 'pregunta_en', 'textos_en')->where('sucursal', $sucursal)->where('valor', '!=', 2)->orderBy('id', 'ASC')->get();

    if (count($preguntas) < 1) {
        return abort(404);
    }

    $query_logo = DB::table('logoimagen')->select('ruta')->where('sucursal', $sucursal)->first();
    $arr_img_logo = explode('/', $query_logo->ruta);
    $nombre_logo = $arr_img_logo[count($arr_img_logo)-1];

    $html = '';

    $no_obligatorias = 0;
    $obligatorias    = 0;

    $mostrar_top_binary = DB::table('sucursales')->select('emailcomentarios_top')->where('sucursal', $sucursal)->first()->emailcomentarios_top;

     if($mostrar_top_binary == 1)
     {
        $valores_arr = Valores::select('id', 'valor')->where('sucursal', $sucursal)->orderBy('id', 'ASC')->get();

        if($valores_arr[2]->valor == 1 and $valores_arr[5]->valor == 1){
          $html .= encuestaHTML::mostrarInputCorreo(2);
        }elseif($valores_arr[2]->valor == 1 and $valores_arr[5]->valor == 0){
          $html .= encuestaHTML::mostrarInputCorreo(1);
        }elseif($valores_arr[2]->valor == 0 and $valores_arr[5]->valor == 1){
          $html .= encuestaHTML::mostrarInputCorreo(3);
        }else{
          $html .= '';
        }

        ($valores_arr[3]->valor == 1) ? $html .= encuestaHTML::mostrarInputComentarios() : '';

        for ($i = 0; $i < count($preguntas); $i++)
    		{
            $pregunta = $preguntas[$i]->pregunta;
            $textos   = $preguntas[$i]->textos;
            $id       = $preguntas[$i]->id;

            encuestaHTML::$pregunta = $pregunta;
            encuestaHTML::$textos   = $textos;
            encuestaHTML::$id       = $id;

            encuestaHTML::$pregunta_en = $preguntas[$i]->pregunta_en;
            encuestaHTML::$textos_en = $preguntas[$i]->textos_en;


            switch ($preguntas[$i]->valor) {
                case 0:
                    $html .= encuestaHTML::tipo0();
                    break;
                case 1:
                    $html .= encuestaHTML::tipo1();
                    break;
                case 3:
                    $html .= encuestaHTML::tipo3();
                    $no_obligatorias++;
                    break;
                case 4:
                    $valor2 = ($preguntas[$i]->valor2 == 1) ? 1 : 0;
                    $html .= encuestaHTML::tipo4($valor2);
                    break;
                case 5:
                    $html .= encuestaHTML::tipo5();
                    break;
                case 6:
                    $html .= encuestaHTML::tipo6();
                    $no_obligatorias++;
                    break;
                case 8:
                    $html .= encuestaHTML::tipo8();
                    break;
                case 9:
                    $html .= encuestaHTML::tipo9();
                    break;
                case 10:
                    $html .= encuestaHTML::tipo10();
                    break;
                }
            }

    }
    else
    {
      for ($i = 0; $i < count($preguntas); $i++)
  		{
          $pregunta = $preguntas[$i]->pregunta;
          $textos   = $preguntas[$i]->textos;
          $id       = $preguntas[$i]->id;

          encuestaHTML::$pregunta = $pregunta;
          encuestaHTML::$textos   = $textos;
          encuestaHTML::$id       = $id;

          encuestaHTML::$pregunta_en = $preguntas[$i]->pregunta_en;
          encuestaHTML::$textos_en = $preguntas[$i]->textos_en;

          switch ($preguntas[$i]->valor) {
              case 0:
                  $html .= encuestaHTML::tipo0();
                  break;
              case 1:
                  $html .= encuestaHTML::tipo1();
                  break;
              case 3:
                  $html .= encuestaHTML::tipo3();
                  $no_obligatorias++;
                  break;
              case 4:
                  $valor2 = ($preguntas[$i]->valor2 == 1) ? 1 : 0;
                  $html .= encuestaHTML::tipo4($valor2);
                  break;
              case 5:
                  $html .= encuestaHTML::tipo5();
                  break;
              case 6:
                  $html .= encuestaHTML::tipo6();
                  $no_obligatorias++;
                  break;
              case 8:
                  $html .= encuestaHTML::tipo8();
                  break;
              case 9:
                  $html .= encuestaHTML::tipo9();
                  break;
              case 10:
                  $html .= encuestaHTML::tipo10();
                  break;
              }
          }


          $valores_arr = Valores::select('id', 'valor')->where('sucursal', $sucursal)->orderBy('id', 'ASC')->get();

          if($valores_arr[2]->valor == 1 and $valores_arr[5]->valor == 1){
            $html .= encuestaHTML::mostrarInputCorreo(2);
          }elseif($valores_arr[2]->valor == 1 and $valores_arr[5]->valor == 0){
            $html .= encuestaHTML::mostrarInputCorreo(1);
          }elseif($valores_arr[2]->valor == 0 and $valores_arr[5]->valor == 1){
            $html .= encuestaHTML::mostrarInputCorreo(3);
          }else{
            $html .= '';
          }

          ($valores_arr[3]->valor == 1) ? $html .= encuestaHTML::mostrarInputComentarios() : '';

    }


        $html .= encuestaHTML::mostrarBotonesFinal();

        $domicilios_str = DB::table('sucursales')->select('sucursal_domicilios', 'pedir_folio')->where('sucursal', $sucursal)->first();
        if(trim($domicilios_str->sucursal_domicilios))
        {
          $domicilios_arr = explode(',' , $domicilios_str->sucursal_domicilios);
          $html .= encuestaHTML::sucursalDomicilioAsVendedor($domicilios_arr);
        }

        if($domicilios_str->pedir_folio == 1){
            $html .= encuestaHTML::pedirFolioTexto();
        }

        $check_colors = DB::table('personalizacion_encuesta')
        ->where('sucursal', $sucursal)->first();

        $colorHeader = '#0658c9';
        $colorHeaderText = '#ffffff';

        if($check_colors){
          $colorHeader      = $check_colors->color_head;
          $colorHeaderText = $check_colors->color_head_text;
        }

        $consultaMandLang = Sucursal::select('sin_preguntas_obligatorias', 'lang_en')->where('sucursal', $sucursal)->first();
        $obligatorias = ($consultaMandLang->sin_preguntas_obligatorias == 1) ? 0 : count($preguntas) - $no_obligatorias;
        $lang_en_bool = ($consultaMandLang->lang_en == 1) ? true : false;

        return view('encuesta_libre_web_qr', compact('lang_en_bool', 'html', 'obligatorias', 'sucursal', 'mesa', 'javascript', 'colorHeader', 'colorHeaderText', 'nombre_logo'));
  }

  public function reporteMovil($sucursal, $desde, $hasta)
  {
    $desde_h = $desde.' 05:00:00';
    $hasta_h = date("Y-m-d", strtotime($hasta."+ 1 days"));
    $hasta_h = $hasta_h.' 04:59:59';

    if($desde_h > $hasta_h){
      return 'La fecha de inicio es superior a la final';
    }
    return view('reportes_movil', compact('sucursal', 'desde', 'hasta', 'desde_h', 'hasta_h'));
  }

  public function menuTab($sucursal = null)
  {
     $info_sucursal = DB::table('sucursales')->where('sucursal', $sucursal)->first();
     if(! $info_sucursal){
       return 'La sucursal no existe';
     }

     $url_bebidas = '';

     $label_alimentos = 'Menú Alimentos';
     $label_bebidas   = 'Menú Bebidas';

     $url_alimentos   = $info_sucursal->url_menu;

     switch($sucursal)
     {
       case 'morictrc':
          $url_bebidas     = 'https://sondealo.com/sitio/menu_qr/m_morictrc_bebidas.pdf';
          break;
        case 'hltrc':
            $url_bebidas     = 'https://sondealo.com/sitio/menu_qr/m_hltrc_comidas.pdf';
            $label_alimentos = 'Menú desayunos ';
            $label_bebidas   = 'Menú comidas';
            break;
     }

    return view('menu_tabs', compact('url_bebidas', 'url_alimentos', 'label_alimentos', 'label_bebidas'));

  }

  public function menuMultipleButtons($sucursal = null)
  {
    $arreglo_enlaces = [];

    if($sucursal == 'morictrc')
    {
      $arreglo_enlaces = [
        'menú de alimentos' => 'https://sondealo.com/sitio/menu_qr/M_MORIC_ALIMENTOS_LINKED.pdf',
        'menú de bebidas'   => 'https://sondealo.com/sitio/menu_qr/M_MORIC_BEBIDAS_LINKED.pdf'
      ];
    }
    else
    {
      $query = DB::table('sucursales')->select('url_menu')->where('sucursal', $sucursal)->first();
      if($query)
      {
        $arreglo_enlaces = [
          'alimentos' => $query->url_menu
        ];
      }     
    }
    return view ('menumultiplebuttons', compact('arreglo_enlaces', 'sucursal'));
  }

  public function selectLang($sucursal)
  {
    $logo = DB::table('logoimagen')->select('ruta')->where('sucursal', $sucursal)->first();
        if($logo){
          $image_url = $logo->ruta;
        }

    $nombre = DB::table('menus')->select('name_comercial')->where('sucursal', $sucursal)->first();
       if($nombre){
          $name_comercial = $nombre->name_comercial;
       } 
       
  
    $switches = DB::table('menus')
    ->select('encuesta_switch', 'logo_switch','url_switch', 'insta_switch', 'tiktok_switch', 'facebook_switch', 'whatsapp_switch', 'esp_switch', 'eng_switch')
    ->where('sucursal', $sucursal)->first();
    if($switches){
      $encuesta_switch = $switches->encuesta_switch;
      $logo_switch = $switches->logo_switch;
      $url_switch = $switches->url_switch;
      $insta_switch = $switches->insta_switch;
      $tiktok_switch = $switches->tiktok_switch;
      $facebook_switch = $switches->facebook_switch;
      $whatsapp_switch = $switches->whatsapp_switch;
      $esp_switch = $switches->esp_switch;
      $eng_switch = $switches->eng_switch;
    }
    
    $urls = DB::table('menus')
    ->select('insta_url','tiktok_url', 'facebook_url', 'whatsapp_url', 'page_url')
    ->where('sucursal', $sucursal)->first();
    if($urls){
      $insta_url = $urls->insta_url;
      $tiktok_url = $urls->tiktok_url;
      $facebook_url = $urls->facebook_url;
      $whatsapp_url = $urls->whatsapp_url;
      $page_url = $urls->page_url;
    }

    return view ('menulang', compact('sucursal','image_url', 'name_comercial', 'encuesta_switch', 'logo_switch', 'url_switch', 'insta_switch', 'tiktok_switch', 'facebook_switch', 'whatsapp_switch', 'esp_switch', 'eng_switch', 'insta_url', 'tiktok_url', 'facebook_url', 'whatsapp_url', 'page_url'));
  }

  public function menuSelectCategorias($sucursal)
  {

    $array_categorias = array();
    $array_categorias = DB::table('categorias_menu')
    ->select('id', 'nombre', 'nombre_en', 'imagen_url', 'id_video' , 'ruta_promo')
    ->where(['sucursal' => $sucursal, 'state' => 1])
    ->orderBy('indice_orden', 'ASC')
    ->get();

    return view ('menu_categorias', compact('sucursal', 'array_categorias'));
  }

  public function menuSecciones($sucursal, $id)
  {

    $array_items = array();
    $array_items = DB::table(DB::raw("menu_items"))
    ->selectRaw("id, nombre, nombre_en, ingredientes, ingredientes_en, precio, recomen, recomen_en, recom_catid")
    ->whereRaw("id_categoria = $id")
    ->get();

    
    foreach($array_items as $item_url)
    {
      $item_url->imagenes_url = DB::table('menu_imagenes_items')
      ->select('ruta_servidor')
      ->where('id_item', $item_url->id)
      ->get();
    }


    $array_categorias = array();
    $array_categorias = DB::table('categorias_menu')
    ->select('id', 'nombre', 'nombre_en', 'imagen_url', 'id_video' , 'ruta_promo' , 'video_switch')
    ->where(['sucursal' => $sucursal, 'state' => 1])
    ->orderBy('indice_orden', 'ASC')
    ->get();
    
    return view ('menu_item', compact('sucursal','array_items','id', 'array_categorias'));
  }

  public function menuPreferences()
  {

  }


  public function mostrarGaleriaImagenesMenu($sucursal_url = null, $id_menu = null)
  {
    $sucursal_url     = SessionController::setSesionSucursal($sucursal_url);
    $sucursales_html  = MenuHTML::getSelectSucursales(self::getSucursales());
    $boolean_show_form = false;
    $categorias =[];
    $menus = [];
    $platillos = [];

    if($sucursal_url != null and Session::has('sucursal_fijada'))
    {   
      $menus = DB::table('menus')->select('id', 'nombre')->where('sucursal', Session::get('sucursal_fijada'))->get();
      
      if($id_menu != null and is_numeric($id_menu))
      {
        $check_menu = DB::table('menus')->select('nombre')->where('id', $id_menu)
        ->where('sucursal', Session::get('sucursal_fijada'))->first();

        if($check_menu)
        {
          $boolean_show_form = true;

          $categorias = DB::table('categorias_menu')->select('id', 'nombre')
          ->where('id_menu', $id_menu)->get();

          if($categorias->count())
          {
            for($i=0;$i<$categorias->count();$i++)
            {
              $items = []; 
              
              $items_index= DB::table('menu_items')->select('id','nombre','ingredientes', 'precio')->where('id_categoria', $categorias[$i]->id)->get();

              if($items_index->count())
              {
                for($j=0;$j<$items_index->count();$j++)
                {
                  $items_index[$j]->imagenes = DB::table('menu_imagenes_items')->select('id','ruta_servidor')->where('id_item', $items_index[$j]->id)->get();
              
                }
                $items[] = $items_index;
              }  
              $categorias[$i]->items = $items;              
            }
          }
        }
      }

    }

    return view('galeria_menu', compact('categorias', 'sucursal_url','sucursales_html', 'boolean_show_form', 'menus', 'id_menu'));
  }

  public function creaPerfilMenu($sucursal_url = null, $id_menu = null){
    $sucursal_url     = SessionController::setSesionSucursal($sucursal_url);
    $sucursales_html  = MenuHTML::getSelectSucursales(self::getSucursales());
    $boolean_show_form = false;
    $categorias =[];
    $menus = [];
    $platillos = [];
    $name_comerial = "";
    $whatsappurl = "";
    $facebookurl = "";
    $tiktokurl = "";
    $urlweb = "";
    $instaurl = "";
    $youtubeurl = "";

    $nombre = DB::table('menus')->select('name_comercial', 'facebook_url', 'whatsapp_url', 'tiktok_url', 'page_url', 'youtube_url', 'insta_url', 'logo_switch',
    'encuesta_switch', 'url_switch', 'insta_switch', 'tiktok_switch', 'facebook_switch', 'whatsapp_switch', 'esp_switch', 'eng_switch', 'youtube_switch')->where('sucursal', $sucursal_url)->first();
       if($nombre){
          $name_comercial = $nombre->name_comercial;
          $whatsappurl = $nombre->whatsapp_url;
          $facebookurl = $nombre->facebook_url;
          $tiktokurl = $nombre->tiktok_url;
          $urlweb = $nombre->page_url;
          $instaurl = $nombre->insta_url;
          $youtubeurl = $nombre->youtube_url;
          $logo_switch = $nombre->logo_switch;
          $encuesta_switch = $nombre->encuesta_switch;
          $url_switch = $nombre->url_switch;
          $insta_switch = $nombre->insta_switch;
          $tiktok_switch = $nombre->tiktok_switch;
          $facebook_switch = $nombre->facebook_switch;
          $whatsapp_switch = $nombre->whatsapp_switch;
          $esp_switch = $nombre->esp_switch;
          $eng_switch = $nombre->eng_switch;
          $youtube_switch = $nombre->youtube_switch;
       } 

    if($sucursal_url != null and Session::has('sucursal_fijada'))
    {   
      $menus = DB::table('menus')->select('id', 'nombre')->where('sucursal', Session::get('sucursal_fijada'))->get();
      
      if($id_menu != null and is_numeric($id_menu))
      {
        $check_menu = DB::table('menus')->select('nombre')->where('id', $id_menu)
        ->where('sucursal', Session::get('sucursal_fijada'))->first();

        if($check_menu)
        {
          $boolean_show_form = true;

          $categorias = DB::table('categorias_menu')->select('id', 'nombre')
          ->where('id_menu', $id_menu)->get();

          if($categorias->count())
          {
            for($i=0;$i<$categorias->count();$i++)
            {
              $items = []; 
              
              $items_index= DB::table('menu_items')->select('id','nombre','ingredientes', 'precio')->where('id_categoria', $categorias[$i]->id)->get();

              if($items_index->count())
              {
                for($j=0;$j<$items_index->count();$j++)
                {
                  $items_index[$j]->imagenes = DB::table('menu_imagenes_items')->select('id','ruta_servidor')->where('id_item', $items_index[$j]->id)->get();
              
                }
                $items[] = $items_index;
              }  
              $categorias[$i]->items = $items;              
            }
          }
        }
      }

    }

    return view('menuconfig_perfil', compact('sucursal_url', 'id_menu','sucursales_html', 'boolean_show_form', 'menus', 'categorias', 'name_comercial', 'whatsappurl', 'facebookurl', 'tiktokurl', 'urlweb', 'instaurl', 'youtubeurl', 'logo_switch' ,'encuesta_switch', 'url_switch', 'insta_switch', 'tiktok_switch', 'facebook_switch', 'whatsapp_switch', 'esp_switch', 'eng_switch', 'youtube_switch'));
  }

  public function creaCategoriasMenu($sucursal_url = null, $id_menu = null){
    $sucursal_url     = SessionController::setSesionSucursal($sucursal_url);
    $sucursales_html  = MenuHTML::getSelectSucursales(self::getSucursales());
    $boolean_show_form = false;
    $categorias =[];
    $menus = [];
    $platillos = [];
    $name_cat = [];
    $state_cat = [];

    for($itc = 1; $itc <= 12; $itc++){
      $catego_name = DB::table('categorias_menu')->select('nombre','state', 'id_video', 'video_switch')->where('id_interno',$itc)->first();
       if($catego_name){
         $name_cat[$itc-1] = $catego_name->nombre;
         $state_cat[$itc-1] = $catego_name->state;
         $id_video[$itc-1] = $catego_name->id_video;
         $video_switch[$itc-1] = $catego_name->video_switch;
       }
    }
    

    if($sucursal_url != null and Session::has('sucursal_fijada'))
    {   
      $menus = DB::table('menus')->select('id', 'nombre')->where('sucursal', Session::get('sucursal_fijada'))->get();
      
      if($id_menu != null and is_numeric($id_menu))
      {
        $check_menu = DB::table('menus')->select('nombre')->where('id', $id_menu)
        ->where('sucursal', Session::get('sucursal_fijada'))->first();

        if($check_menu)
        {
          $boolean_show_form = true;

          $categorias = DB::table('categorias_menu')->select('id', 'nombre')
          ->where('id_menu', $id_menu)->get();

          if($categorias->count())
          {
            for($i=0;$i<$categorias->count();$i++)
            {
              $items = []; 
              
              $items_index= DB::table('menu_items')->select('id','nombre','ingredientes', 'precio')->where('id_categoria', $categorias[$i]->id)->get();

              if($items_index->count())
              {
                for($j=0;$j<$items_index->count();$j++)
                {
                  $items_index[$j]->imagenes = DB::table('menu_imagenes_items')->select('id','ruta_servidor')->where('id_item', $items_index[$j]->id)->get();
              
                }
                $items[] = $items_index;
              }  
              $categorias[$i]->items = $items;              
            }
          }
        }
      }

    }

    return view('menuconfig_categorias', compact('sucursal_url', 'id_menu','sucursales_html', 'boolean_show_form', 'menus', 'categorias','name_cat','state_cat', 'id_video', 'video_switch'));
  }

  public function creaDisenoMenu($sucursal_url = null, $id_menu = null){
    $sucursal_url     = SessionController::setSesionSucursal($sucursal_url);
    $sucursales_html  = MenuHTML::getSelectSucursales(self::getSucursales());
    $boolean_show_form = false;
    $categorias =[];
    $menus = [];
    $platillos = [];
    $colorHeader = '';

    if($sucursal_url != null and Session::has('sucursal_fijada'))
    {   
      $menus = DB::table('menus')->select('id', 'nombre')->where('sucursal', Session::get('sucursal_fijada'))->get();
      
      if($id_menu != null and is_numeric($id_menu))
      {
        $check_menu = DB::table('menus')->select('nombre')->where('id', $id_menu)
        ->where('sucursal', Session::get('sucursal_fijada'))->first();

        if($check_menu)
        {
          $boolean_show_form = true;

          $categorias = DB::table('categorias_menu')->select('id', 'nombre')
          ->where('id_menu', $id_menu)->get();

          if($categorias->count())
          {
            for($i=0;$i<$categorias->count();$i++)
            {
              $items = []; 
              
              $items_index= DB::table('menu_items')->select('id','nombre','ingredientes', 'precio')->where('id_categoria', $categorias[$i]->id)->get();

              if($items_index->count())
              {
                for($j=0;$j<$items_index->count();$j++)
                {
                  $items_index[$j]->imagenes = DB::table('menu_imagenes_items')->select('id','ruta_servidor')->where('id_item', $items_index[$j]->id)->get();
              
                }
                $items[] = $items_index;
              }  
              $categorias[$i]->items = $items;              
            }
          }
        }
      }

    }

    return view('menuconfig_diseno', compact('sucursal_url', 'id_menu','sucursales_html', 'boolean_show_form', 'menus', 'categorias', 'colorHeader'));
  }

  public function creaCodigoQRMenu($sucursal_url = null, $id_menu = null){
    $sucursal_url     = SessionController::setSesionSucursal($sucursal_url);
    $sucursales_html  = MenuHTML::getSelectSucursales(self::getSucursales());
    $boolean_show_form = false;
    $categorias =[];
    $menus = [];
    $platillos = [];

    if($sucursal_url != null and Session::has('sucursal_fijada'))
    {   
      $menus = DB::table('menus')->select('id', 'nombre')->where('sucursal', Session::get('sucursal_fijada'))->get();
      
      if($id_menu != null and is_numeric($id_menu))
      {
        $check_menu = DB::table('menus')->select('nombre')->where('id', $id_menu)
        ->where('sucursal', Session::get('sucursal_fijada'))->first();

        if($check_menu)
        {
          $boolean_show_form = true;

          $categorias = DB::table('categorias_menu')->select('id', 'nombre')
          ->where('id_menu', $id_menu)->get();

          if($categorias->count())
          {
            for($i=0;$i<$categorias->count();$i++)
            {
              $items = []; 
              
              $items_index= DB::table('menu_items')->select('id','nombre','ingredientes', 'precio')->where('id_categoria', $categorias[$i]->id)->get();

              if($items_index->count())
              {
                for($j=0;$j<$items_index->count();$j++)
                {
                  $items_index[$j]->imagenes = DB::table('menu_imagenes_items')->select('id','ruta_servidor')->where('id_item', $items_index[$j]->id)->get();
              
                }
                $items[] = $items_index;
              }  
              $categorias[$i]->items = $items;              
            }
          }
        }
      }

    }

    return view('menuconfig_codigoqr', compact('sucursal_url', 'id_menu','sucursales_html', 'boolean_show_form', 'menus', 'categorias'));
  }

  public function creaPlatilloMenu($sucursal_url = null, $id_menu = null, $id_categoria = null){
    $sucursal_url     = SessionController::setSesionSucursal($sucursal_url);
    $sucursales_html  = MenuHTML::getSelectSucursales(self::getSucursales());
    $boolean_show_form = false;
    $categorias =[];
    $menus = [];
    $platillos = [];

    $array_items = array();
    $array_items = DB::table(DB::raw("menu_items"))
    ->selectRaw("id, id_categoria ,nombre, nombre_en, ingredientes, ingredientes_en, precio, recomen, recomen_en, recom_catid")
    ->whereRaw("id_categoria = $id_categoria")
    ->get();

    
    foreach($array_items as $item_url)
    {
      $item_url->imagenes_url = DB::table('menu_imagenes_items')
      ->select('ruta_servidor')
      ->where('id_item', $item_url->id)
      ->get();
    }

    if($sucursal_url != null and Session::has('sucursal_fijada'))
    {   
      $menus = DB::table('menus')->select('id', 'nombre')->where('sucursal', Session::get('sucursal_fijada'))->get();
      
      if($id_menu != null and is_numeric($id_menu))
      {
        $check_menu = DB::table('menus')->select('nombre')->where('id', $id_menu)
        ->where('sucursal', Session::get('sucursal_fijada'))->first();

        if($check_menu)
        {
          $boolean_show_form = true;

          $categorias = DB::table('categorias_menu')->select('id', 'nombre')
          ->where('id_menu', $id_menu)->get();

          if($categorias->count())
          {
            for($i=0;$i<$categorias->count();$i++)
            {
              $items = []; 
              
              $items_index= DB::table('menu_items')->select('id','nombre','ingredientes', 'precio')->where('id_categoria', $categorias[$i]->id)->get();

              if($items_index->count())
              {
                for($j=0;$j<$items_index->count();$j++)
                {
                  $items_index[$j]->imagenes = DB::table('menu_imagenes_items')->select('id','ruta_servidor')->where('id_item', $items_index[$j]->id)->get();
              
                }
                $items[] = $items_index;
              }  
              $categorias[$i]->items = $items;              
            }
          }
        }
      }

    }

    return view('menuconfig_platillos', compact('sucursal_url', 'id_menu', 'id_categoria', 'sucursales_html', 'boolean_show_form', 'menus', 'categorias', 'array_items'));
  }
}