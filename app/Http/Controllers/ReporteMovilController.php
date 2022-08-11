<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\PreguntaController;
use DB;
use Validator;
use App\Utilidades\ManipularCadenas;

class ReporteMovilController extends Controller
{
  public static $columnas_calificaciones_eval = array('eval', 'eval2', 'eval3', 'eval4', 'eval5', 'eval6', 'eval7',
         'eval8', 'eval9', 'eval10', 'eval11','eval12', 'eval13');

  public static $columnas_calificaciones_p = array(
    'p1', 'p2', 'p3', 'p4', 'p5', 'p6', 'p7', 'recomen', 'p9', 'p10', 'p11', 'p12', 'p13' );


    public function general(Request $request)
    {
      $contestadas = DB::table('calificaciones')->selectRaw("COUNT(id) as 'contestadas'")
      ->whereRaw("fec BETWEEN '$request->desde_h' AND '$request->hasta_h' AND sucursal='$request->sucursal'")->first()->contestadas;

      if($contestadas < 1){
        return response()->json(['status' => 204, 'msg' => '<div class="alert alert-info" role="alert">Sin información</div>']);
      }

      $promedios_preguntas = PreguntaController::preguntasPromedios($request->sucursal, $request->desde_h, $request->hasta_h);

      $info_charts = PreguntaController::getInfoCharts($request->sucursal, $request->desde_h, $request->hasta_h);

      $no_contestadas = DB::table('nocontestadas')->selectRaw("COUNT(id) as 'nocontestadas'")
      ->whereRaw("fec2 BETWEEN '$request->desde_h' AND '$request->hasta_h' AND sucursal='$request->sucursal'")->first()->nocontestadas;


      $logo_url = DB::table('logoimagen')->select('ruta')->where('sucursal', $request->sucursal)->first()->ruta;
      $logo_url_arr = explode('/', $logo_url);
      $logo = $logo_url_arr[count($logo_url_arr)-1];


      $promedio_tabla = (count($promedios_preguntas)>0) ? $promedios_preguntas[0]->promedios : 0 ;


      $html_general =
     '<div class="row">
      <div class="col col-12 text-center">
      <img class="logo" src="https://sondealo.com/sitio/images/logo/'.$logo.'"/>
      </div>
      <div class="col col-12">
      <table class="table table-sm text-center">
      <tr>
      <td>Sucursal</td>
      <td>'.$request->sucursal.'</td>
      </tr>
      <tr>
      <td>Período</td>
      <td>'.$request->desde. ' - '.$request->hasta.'</td>
      </tr>
      <tr>
      <td>Encuestas NO contestadas</td>
      <td>'.$no_contestadas.'</td>
      </tr>
      <tr>
      <td>Encuestas Contestadas</td>
      <td>'.$contestadas.'</td>
      </tr>
      <tr>
      <td colspan="2" class="h3 text-center">Calificación: '.$promedio_tabla.'</td>
      </tr>
      </table>
      </div>
      </div>
      <div class="row">';

      foreach ($promedios_preguntas as $prom)
      {

        $html_general .= '<div class="col-10">
                          <span>'.$prom->pregunta.'</span>
                          <div class="progress">';

        if ($prom->valor != 9)
        {
  				$mult = 10;
  				if($prom->valor == 1){
            $mult = 1;
          }

          $html_general .= '<div class="progress-bar bg-success" role="progressbar" style="width:'.($prom->promedio)*$mult.'%;background-color:'.$prom->css.'!important;" aria-valuenow="'.($prom->promedio)*$mult.'" aria-valuemin="0" aria-valuemax="10"></div>
                          </div>
                          </div>
                          <div class="col-2" style="position: relative;">
                          <span style="position: absolute;bottom:0;">'.$prom->promedio.$prom->signo.'</span>
                          </div>';
  			}
      }

      $html_general .= '</div>';
      $html_graphs = '';

      for ($i=0;$i<count($info_charts);$i++)
      {
        $html_graphs .= '<div class="col-12"><p class="bg-primary text-white pl-1 pr-1">'.$info_charts[$i]->pregunta.'</p><canvas id="chart_'.($i+1).'"></canvas><div id="label_'.($i+1).'" style="margin-top:1rem;display:flex;flex-wrap:wrap;justify-content:space-around;"></div></div>';
      }

      $html_general .= '<div class="row mt-4">'.$html_graphs.'</div>';

      return response()->json(['status' => 200, 'msg' => 'success', 'general' => $html_general, 'general_info_charts' => $info_charts]);

    }


    public function comentarios(Request $request)
    {
      $validator = Validator::make($request->all(), ['sucursal' => 'required|string|max:30',
      'desde' => 'required|date_format:"Y-m-d H:i:s"', 'hasta' => 'required|date_format:"Y-m-d H:i:s"', 'limite_i' => 'required|integer' ]);

      if($validator->fails()){
        return response()->json(['status' => 422, 'msg' => 'No valido']);
      }

      $sucursal = $request['sucursal'];
      $desde    = $request['desde'];
      $hasta    = $request['hasta'];

      $limite_inferior = $request['limite_i'];
      $limite_superior = 20;

      $comentarios = DB::table('calificaciones')->select('id','folio','fecha', 'comentarios')
      ->whereRaw("fec BETWEEN '$desde' AND '$hasta' AND sucursal='$sucursal' AND comentarios !='' AND comentarios != '\"\"'")
      ->orderBy('id', 'DESC')->offset($limite_inferior)->limit($limite_superior)->get();


      if(count($comentarios) < 1 ){
        return response()->json(['status' => 204, 'msg'=> '<p class="col-12 text-info text-center">Todos los comentarios</p>']);
      }
      $html = '';
      $arreglo_comentarios = array();

      for($i=0;$i<count($comentarios);$i++)
      {
        if(substr($comentarios[$i]->comentarios, 0, 1) == '"')
        {
          $comentarios[$i]->comentarios = str_replace(['<', '>'],' ', $comentarios[$i]->comentarios);

          $comentario = ManipularCadenas::decodeEmoticons($comentarios[$i]->comentarios);
          $comentarios[$i]->comentarios = substr($comentario, 1, -1);
        }

        $bool_alerta = false;
        $css = '';
        $bool_alerta = ManipularCadenas::buscarAlerta($comentarios[$i]->comentarios);

        if($bool_alerta)
        {
          $css = 'alerta';
        }
        $html .= '<div class="bg-secondary text-white" style="width:70%;float:left;padding:2px;">
        Fecha:'.$comentarios[$i]->fecha.'
        </div>
        <div class="bg-secondary text-white" style="width:30%;float:right;padding:2px;" data-encuesta="'.$comentarios[$i]->id.'">
        Folio:'.$comentarios[$i]->folio.'
        </div>
        <div class="mb-3 '.$css.'" style="width:100%;padding:2px;">
        '.$comentarios[$i]->comentarios.'
        </div>';
      }

      $html .= '<button class="btn btn-primary btn-sm btn-block" id="btn-mas-comentarios">Cargar mas comentarios</button>';

      return response()->json(['status' => 200, 'msg' => 'success', 'info' => $html ]);

    }

    public function encuestas(Request $request)
    {
      $validator = Validator::make($request->all(), ['sucursal' => 'required|string|max:30',
      'desde' => 'required|date_format:"Y-m-d H:i:s"', 'hasta' => 'required|date_format:"Y-m-d H:i:s"', 'limite_i' => 'required|integer' ]);

      if($validator->fails()){
        return response()->json(['status' => 422, 'msg' => 'No valido']);
      }

      $sucursal = $request['sucursal'];
      $desde    = $request['desde'];
      $hasta    = $request['hasta'];

      $limite_inferior = $request['limite_i'];
      $limite_superior = 10;

      $preguntas = DB::table('cuestionario')->select('pregunta')
      ->whereRaw("sucursal = '$sucursal' AND valor != 2")->orderBy('id', 'ASC')->get();
      $size_preguntas = count($preguntas);

      $arr_campos_calificaciones = array_slice(self::$columnas_calificaciones_p , 0, $size_preguntas);
      $str_campos_calificaciones = implode(',' , $arr_campos_calificaciones);

      $select_raw = "id, fecha, folio, mesa, mesero, $str_campos_calificaciones , correo, comentarios";

      $encuestas = DB::table('calificaciones')->selectRaw($select_raw)
      ->whereRaw("fec BETWEEN '$desde' AND '$hasta' AND  sucursal='$sucursal'")->orderBy('id', 'DESC')
      ->offset($limite_inferior)->limit($limite_superior)->get();

      if(count($encuestas) < 1){
        return response()->json(['status' => 204, 'msg' => 'Sin información']);
      }

      $html = '<div class="row"><div class="col-12">';

      for($i=0;$i<count($encuestas);$i++)
      {

          $html .='<table class="table table-sm table-striped">
          <tbody>
          <tr><td>Fecha</td><td>'.$encuestas[$i]->fecha.'</td></tr>
          <tr><td>Folio</td><td>'.$encuestas[$i]->folio.'</td></tr>
          <tr><td>Mesa</td><td>'.$encuestas[$i]->mesa.'</td></tr>
          <tr><td>Mesero</td><td>'.$encuestas[$i]->mesero.'</td></tr>';



          /*si la sucursal es bernini agregamos la infomacion de la tabla formulario_vendedor*/


          if($sucursal == 'bernini')
          {
            $info_form_vendedor = DB::table('formulario_vendedor')
            ->where('id_encuesta', $encuestas[$i]->id)->first();

            if( ! $info_form_vendedor){
              $html .= '<tr><td colspan="2" style="background-color:rgba(255,0,0,0.1);">Sin información capturada por mesero</td></tr>';
            }
            else
            {
              $html .= '<tr><td>Mayoría por mesa</td><td>'.$info_form_vendedor->p1.'</td></tr>'
              .'<tr><td>Tipo</td><td>'.$info_form_vendedor->p2.'</td></tr>'
              .'<tr><td>Momento en la semana</td><td>'.$info_form_vendedor->p3.'</td></tr>'
              .'<tr><td>Rango de edades</td><td>'.$info_form_vendedor->p4.'</td></tr>';
            }
          }
          /*---------------------------------------------------------------------------------*/




          for($j=0;$j<count($preguntas);$j++)
          {
            $html .='<tr><td>'.$preguntas[$j]->pregunta.'</td><td class="">'.$encuestas[$i]->{$arr_campos_calificaciones[$j]}.'</td></tr>';
          }

          $html .= '<tr><td>Correo</td><td>'.$encuestas[$i]->correo.'</td></tr>';

          if($encuestas[$i]->comentarios == '""' or trim($encuestas[$i]->comentarios) == '')
          {
              $encuestas[$i]->comentarios = '';
          }
          $comentario = $encuestas[$i]->comentarios;

          if(substr($comentario, 0, 1) == '"'){
            $comentario = ManipularCadenas::decodeEmoticons($encuestas[$i]->comentarios);
            $comentario = substr($comentario, 1, -1);
          }

          $html .= '<tr><td>Comentario</td><td>'.$comentario.'</td></tr>';

          $check_evidencia = DB::table('evidencia')->select('id', 'ruta_evidencia_1', 'ruta_evidencia_2')
          ->where('id_encuesta', $encuestas[$i]->id)->first();

          if($check_evidencia)
          {
            $html .= '<tr><td colspan="2"><button data-url1="'. $check_evidencia->ruta_evidencia_1.'"
              data-url2="'. $check_evidencia->ruta_evidencia_2.'" class="btn btn-danger btn-block" data-btnevidencia="true">Evidencia</button></td></tr>';
          }


          $html .= '</tbody></table>';
      }
      $html .= '<button class="btn btn-primary btn-sm btn-block" id="btn-mas-encuestas">Mas encuestas</button></div></div>';

      return response()->json(['status'=> 200, 'msg' => 'success', 'html'=> $html]);
    }

    public function vendedores(Request $request)
    {
      $validator = Validator::make($request->all(),['sucursal' => 'required|string|max:30',
        'desde' => 'required|date_format:"Y-m-d H:i:s"','hasta' => 'required|date_format:"Y-m-d H:i:s"' ]);

      if($validator->fails()){
        return response()->json(['status' => 422, 'msg' => 'No valido']);
      }

      $sucursal = $request['sucursal'];
      $desde    = $request['desde'];
      $hasta    = $request['hasta'];

      $preguntas = DB::table('cuestionario')->select('id', 'pregunta', 'valor', 'valor2')
      ->whereRaw("sucursal='$sucursal' AND valor != 2")
      ->orderBy('id', 'ASC')->get();

      $contador = 0;
      $full_sql = '';

      for($i=0;$i<count($preguntas);$i++)
      {
        $str_sql = '';
        $multiplicador = 0;
        $multiplicador = ManipularCadenas::getMultiplicador($preguntas[$i]->valor, $preguntas[$i]->valor2);

        $str_sql = ($preguntas[$i]->id == 1) ? ", ROUND( (AVG(eval)*$multiplicador) , 2) AS 'prom1'" : ",ROUND( (AVG(eval".(string)$preguntas[$i]->id.")*$multiplicador) , 2 ) AS 'prom".(string)$preguntas[$i]->id."'";

        if($preguntas[$i]->valor == 0 or $preguntas[$i]->valor == 5 or $preguntas[$i]->valor == 8)
        {
          $contador++;
        }
        elseif($preguntas[$i]->valor == 4 and $preguntas[$i]->valor2 == 1){
          $contador++;
        }
        $full_sql .= $str_sql;
      }

      $arreglo_promedios = DB::table('calificaciones')
      ->selectRaw("mesero as 'vendedor' $full_sql , COUNT(*) as 'contestadas' ")
      ->whereRaw("sucursal='$sucursal' AND fec BETWEEN '$desde' AND '$hasta'")
      ->groupBy('mesero')->offset(0)->limit(20)->get();

      if(count($arreglo_promedios) < 1){
        return response()->json(['status' => 204, 'msg' => 'Sin información']);
      }

      $html ='';

      for($j=0;$j<count($arreglo_promedios);$j++)
      {
        $sumador = 0;
        $arreglo_index = array();

        $html .= '<table class="table table-sm table-striped"><tbody><tr><td>Vendedor</td><td>'.$arreglo_promedios[$j]->vendedor.'</td></tr>';

        for ($k=0; $k < count($preguntas); $k++)
        {
          $html .= '<tr><td>'.$preguntas[$k]->pregunta.'</td><td>'.$arreglo_promedios[$j]->{'prom'.($k+1)}.'</td></tr>';

          if($preguntas[$k]->valor == 0 or $preguntas[$k]->valor == 5 or $preguntas[$k]->valor == 8)
          {
            $sumador += $arreglo_promedios[$j]->{'prom'.($k+1)};
          }
          elseif($preguntas[$k]->valor == 4 and $preguntas[$k]->valor2 == 1){
            $sumador += $arreglo_promedios[$j]->{'prom'.($k+1)};
          }
        }

        $no_contestadas = DB::table('nocontestadas')
        ->selectRaw("count(meseros) as 'nocontestadas'")
        ->whereRaw("meseros='".$arreglo_promedios[$j]->vendedor."' AND sucursal='$sucursal' AND fec2 BETWEEN '$desde' AND '$hasta' ")
        ->first()->nocontestadas;

        $promedio_vendedor = ($contador > 0) ? $sumador / $contador : 0;

        $html .= '<tr><td>Contestadas</td><td>'.$arreglo_promedios[$j]->contestadas.'</td></tr>
                 <tr><td>No contestadas</td><td>'.$no_contestadas.'</td></tr>
                 <tr><td>Promedio</td><td>'.number_format($promedio_vendedor, 2, '.', '').'</td></tr></tbody></table>';
      }
      return response()->json(['status' => 200, 'msg' => 'success', 'html' => $html]);
    }

    public function encuestaDetalle(Request $request)
    {
      $validator = Validator::make($request->all(), ['encuesta' => 'required|integer|min:1', 'sucursal' => 'required|string|max:30']);
      if($validator->fails()){
        return response()->json(['status' => 422, 'msg' => 'No valido']);
      }

      $sucursal    = $request['sucursal'];
      $encuesta_id = $request['encuesta'];

      $preguntas = DB::table('cuestionario')->select('pregunta')->whereRaw("sucursal='$sucursal' AND valor != 2")
      ->orderBy('id', 'ASC')->get();

      $size_preguntas = count($preguntas);

      $arr_campos_calificaciones =  array_slice(self::$columnas_calificaciones_p , 0, $size_preguntas);
      $str_campos_calificaciones = implode(',' , $arr_campos_calificaciones);

      $select_raw = "id, fecha, folio, mesa, mesero, $str_campos_calificaciones , correo, comentarios";

      $encuesta = DB::table('calificaciones')->selectRaw($select_raw)->where('id', $encuesta_id)->first();
      if(!$encuesta){
        return response()->json(['status' => 204, 'msg' => 'La encuesta no existe']);
      }

      $html ='<table class="table table-sm table-striped">
      <tbody>
      <tr><td>Fecha</td><td>'.$encuesta->fecha.'</td></tr>
      <tr><td>Folio</td><td>'.$encuesta->folio.'</td></tr>
      <tr><td>Mesa</td><td>'.$encuesta->mesa.'</td></tr>
      <tr><td>Mesero</td><td>'.$encuesta->mesero.'</td></tr>';


      /*----------------------------------------------------------*/
      if($sucursal == 'bernini')
      {
        $info_form_vendedor = DB::table('formulario_vendedor')
        ->where('id_encuesta', $encuesta_id)->first();

        if( ! $info_form_vendedor){
          $html .= '<tr><td colspan="2" style="background-color:rgba(255,0,0,0.1);">Sin información capturada</td></tr>';
        }
        else
        {
          $html .= '<tr><td>Mayoría por mesa</td><td>'.$info_form_vendedor->p1.'</td></tr>'
          .'<tr><td>Tipo</td><td>'.$info_form_vendedor->p2.'</td></tr>'
          .'<tr><td>Momento en la semana</td><td>'.$info_form_vendedor->p3.'</td></tr>'
          .'<tr><td>Rango de edades</td><td>'.$info_form_vendedor->p4.'</td></tr>';
        }
      }
      /*----------------------------------------------------------*/




      for ($i=0; $i < count($arr_campos_calificaciones) ; $i++)
      {
        $html .= '<tr><td>'.$preguntas[$i]->pregunta.'</td><td>'.$encuesta->{$arr_campos_calificaciones[$i]}.'</td></tr>';
      }
      $html .= '<tr><td>Correo</td><td>'.$encuesta->correo.'</td></tr>';

      $comentario = $encuesta->comentarios;

      if(substr($comentario, 0, 1) == '"'){
        $comentario = ManipularCadenas::decodeEmoticons($encuesta->comentarios);
        $comentario = substr($comentario, 1, -1);
      }
      $html .= '<tr><td>Comentario</td><td>'.$comentario.'</td></tr>';

      $check_evidencia = DB::table('evidencia')->select('id', 'ruta_evidencia_1', 'ruta_evidencia_2')
      ->where('id_encuesta', $encuesta->id)->first();

      if($check_evidencia)
      {
        $html .= '<tr><td colspan="2"><button data-url1="'. $check_evidencia->ruta_evidencia_1.'"
          data-url2="'. $check_evidencia->ruta_evidencia_2.'" class="btn btn-danger btn-block" data-btnevidencia="true">Evidencia</button></td></tr>';
      }

      $html .= '</tbody></table>';

      return response()->json(['status' => 200, 'msg' => 'success', 'html' => $html]);

    }

    public function preguntas(Request $request )
    {
      $validator = Validator::make($request->all(),['sucursal' => 'required|string|max:30',
        'desde' => 'required|date_format:"Y-m-d H:i:s"','hasta' => 'required|date_format:"Y-m-d H:i:s"' ]);

      if($validator->fails()){
        return response()->json(['status' => 422, 'msg' => 'No valido']);
      }

      $sucursal = $request['sucursal'];
      $desde    = $request['desde'];
      $hasta    = $request['hasta'];

      $preguntas = DB::table('cuestionario')->select('id', 'pregunta')
      ->whereRaw("sucursal='$sucursal' AND valor = 3")
      ->orderBy('id', 'ASC')->get();

      if(count($preguntas) < 1){
        return response()->json(['status' => 204, 'msg' => 'Sin preguntas abiertas en la encuesta']);
      }

      $html = '';
      $contador=0;

      for ($i=0; $i < count($preguntas) ; $i++)
      {
        $campo = self::$columnas_calificaciones_p[ ($preguntas[$i]->id - 1) ];

        $query_calificaciones_preguntas = DB::table('calificaciones')->selectRaw("$campo, fecha")
        ->whereRaw("fec BETWEEN '$desde' AND '$hasta' AND sucursal='$sucursal' AND $campo != '' AND $campo != 'null'")
        ->orderBy('fec', 'DESC')->limit(30)->get();

        if(count($query_calificaciones_preguntas) > 0)
        {
          $contador++;

          for ($j=0; $j < count($query_calificaciones_preguntas); $j++)
          {
            $html .= '<div class="bg-secondary text-white" style="width:70%;float:left;padding:2px;">
            '.$preguntas[$i]->pregunta.'
            </div>
            <div class="bg-secondary text-white" style="width:30%;float:right;padding:2px;">
            Fecha:'.$query_calificaciones_preguntas[$j]->fecha.'
            </div>
            <div class="mb-3" style="width:100%;padding:2px;">
            '.$query_calificaciones_preguntas[$j]->$campo.'
            </div>';
          }
        }
      }
      if($contador == 0){
        return response()->json(['status' => 200, 'msg' => 'success', 'html' => 'Sin información']);
      }
      return response()->json(['status' => 200, 'msg' => 'success', 'html' => $html]);
    }

    public function promedioSucursales($usuario, $token)
    {

      $info_usuario = DB::table('registros')->select('poder', 'identificador', 'sucs')->where('usuario', $usuario)->first();

      if( ! $info_usuario){
        return response()->json('No existe el usuario', 404);
      }

      $sucursales= array();

      switch ($info_usuario->poder)
      {
        case 1:
          $sucursales_db = DB::table('sucursales')->selectRaw('DISTINCT sucursal')->where('identificador', $info_usuario->identificador)->get();
          foreach ($sucursales_db as $s) {
            $sucursales[] = $s->sucursal;
          }
          break;
        default:
          $sucursales = explode(',' , $info_usuario->sucs);
          break;
      }

      if(count($sucursales) < 1){
        return response()->json('sin sucursales', 204);
      }

      $arreglo_final = array();

      for ($i=0; $i < count($sucursales); $i++)
      {
        $sucursal = $sucursales[$i];
        $arreglo_index = array();

        $fecha_desde = date('Y-m-d');
        $fecha_hasta = date("Y-m-d", strtotime($fecha_desde . "+ 1 days"));
        $fecha_desde .= ' 05:00:00';
        $fecha_hasta .= ' 04:59:59';

        $info_promedio = DB::select(DB::raw("SELECT AVG(encuesta_prom) as 'promedio' FROM promedios_encuestas
        WHERE sucursal_prom ='$sucursal' AND fecha_reg BETWEEN '$fecha_desde' AND '$fecha_hasta'
        UNION ALL (SELECT badge from notificaciones where sucursal='$sucursal' AND token ='$token')"));

        if( count($info_promedio) == 1 ){
          return response()->json('El token no existe', 422);
        }

        $ruta_logo = DB::table('logoimagen')->select('ruta')->where('sucursal', $sucursal)->first()->ruta;
        $arr_ruta_logo = explode('/', $ruta_logo);
        $logo_ruta = 'https://sondealo.com/sitio/images/logo/'.$arr_ruta_logo[count($arr_ruta_logo)-1];


        $badge = ($info_promedio[1]->promedio < 1) ? 0 : number_format($info_promedio[1]->promedio, 0, '.', '');

        $arreglo_index['sucursal'] = $sucursal;
        $arreglo_index['calif'] = (float)number_format($info_promedio[0]->promedio, 2, '.', '');
        $arreglo_index['badge'] = (int)$badge;
        $arreglo_index['logo'] = $logo_ruta;

        array_push($arreglo_final, $arreglo_index);
      }
      return response()->json($arreglo_final, 200);
    }

    public function getInfoChartsFormularioVendedor(Request $request)
    {
      $arreglo_campos = ['p1', 'p2', 'p3', 'p4', 'p5'];

      $arreglo_final = [];

      for($i=0;$i<count($arreglo_campos);$i++)
      {
        $info = DB::table(DB::raw("formulario_vendedor, calificaciones"))
        ->selectRaw("formulario_vendedor.".$arreglo_campos[$i].", count(*) as 'conteo'")
        ->whereRaw("calificaciones.sucursal ='$request->sucursal' AND  calificaciones.fec between '$request->desde' AND '$request->hasta'
        AND formulario_vendedor.p1 != '' AND formulario_vendedor.id_encuesta = calificaciones.id GROUP BY ".$arreglo_campos[$i])->get();

        if(count($info) > 0)
        {
          if($info[0]->{$arreglo_campos[$i]} != '')
          {
            $arreglo_labels = [];
            $arreglo_valores = [];

            for ($j=0; $j < count($info); $j++)
            {
              $arreglo_valores[] = $info[$j]->conteo;
              $arreglo_labels[] = $info[$j]->{$arreglo_campos[$i]};
            }

            $suma = array_sum($arreglo_valores);
            $multiplicador = 100/$suma;
            for($k=0;$k<count($arreglo_valores);$k++)
            {
              $porcentaje = number_format($multiplicador * $arreglo_valores[$k], 2, '.', '');

              $arreglo_labels[$k] = $arreglo_labels[$k].", $porcentaje%";
            }
            $arreglo_final[] = ['pregunta' => ($i+1) ,'labels' => $arreglo_labels, 'valores' => $arreglo_valores];

          }
        }
      }
      return response()->json($arreglo_final);
    }



    public function getHtmlAlertas(Request $request)
    {
      $html = '<div class="col-12 text-center header pt-2">';

      $rutaImg = DB::table('logoimagen')->select('ruta')->where('sucursal', $request->sucursal)->first();


      $arrlogo = explode('/', $rutaImg->ruta);
			$srcImgLogo = $arrlogo[count($arrlogo)-1];

      $html .=  '<img class="shadow-sm bg-white rounded-pill" src="https://sondealo.com/sitio/images/logo/'.$srcImgLogo.'" alt="logo sucursal">'
                .'<p class="h6 pt-2">Alertas de la sucursal '.$request->sucursal.'</p>'
                .'</div>';

      $preguntas = DB::table('cuestionario')->select('id','pregunta')
      ->whereRaw("sucursal = '$request->sucursal' AND valor != 2")->orderBy('id', 'ASC')->get();

      $sizeArrPreguntas = count($preguntas);
      $arrCamposDinamicos = array_slice(self::$columnas_calificaciones_p , 0, $sizeArrPreguntas);
      $strCamposDinamicos = implode(',' , $arrCamposDinamicos);

      $strCamposFijos      = "id, fec, folio, mesa, mesero, comentarios, correo,";

			$alertas = DB::select(DB::raw("SELECT $strCamposFijos $strCamposDinamicos FROM calificaciones
      WHERE sucursal='$request->sucursal'  AND comentarios !='' AND comentarios!= '\"\"' ORDER BY id DESC LIMIT 50"));

      if(count($alertas) < 1){

        $html .= '<div class="col-12 mt-3"><p class="text-center">Sin registros</p></div>';
        return response()->json(['html' => $html]);
      }

      $html .= '<div class="col-12">';

      for ($i=0; $i < count($alertas); $i++)
      {
        $comentario  = $alertas[$i]->comentarios;

        if(ManipularCadenas::buscarAlerta($comentario))
        {
          $fecha = ManipularCadenas::obtenerFechaFormateada($alertas[$i]->fec);

          $html .= '<table class="table table-sm table-striped mt-3">'
                  .'<tr><td>Fecha</td><td>'.$fecha.'</td></tr>'
                  .'<tr><td>Folio</td><td>'.$alertas[$i]->folio.'</td></tr>'
                  .'<tr><td>Ubicación</td><td>'.$alertas[$i]->mesa.'</td></tr>'
                  .'<tr><td>Colaborador</td><td>'.$alertas[$i]->mesero.'</td></tr>';

          for ($j = 0; $j < $sizeArrPreguntas; $j++)
          {
            $pregunta = $preguntas[$j]->pregunta;

            $campo = $arrCamposDinamicos[$j];

            $respuestaPreg = $alertas[$i]->{$campo};

            $html .= "<tr><td>$pregunta</td><td>$respuestaPreg</td></tr>";
          }

          $html .= '<tr><td>Correo</td><td>'.$alertas[$i]->correo.'</td></tr>'
                  . '<tr><td>Comentarios</td><td class="alerta">'.ManipularCadenas::decodeEmoticons($comentario).'</td></tr>';

          $check_evidencia = DB::table('evidencia')->where('id_encuesta', $alertas[$i]->id)->first();

          if($check_evidencia){

            $ev1 = ($check_evidencia->ruta_evidencia_1 != '') ? 'https://sondealo.com/sitio/images/evidencia/' . substr($check_evidencia->ruta_evidencia_1, 44) : '';
            $ev2 = ($check_evidencia->ruta_evidencia_2 != '') ? 'https://sondealo.com/sitio/images/evidencia/' . substr($check_evidencia->ruta_evidencia_2, 44) : '';

            $html .= '<tr>'
                    .'<td colspan="2">'
                    .'<button data-url1="'.$ev1.'" data-url2="'.$ev2.'" data-btnevidencia="true" class="btn btn-sm btn-danger col-12 evidencia">Evidencia</button>'
                    .'</td>'
                    .'</tr>';
          }
          $html .= '</table>';
        }
      }
      $html .= '</div>';
      return response()->json(['html'=>$html]);
    }
}
