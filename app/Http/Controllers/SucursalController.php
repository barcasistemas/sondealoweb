<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Validator;
use App\Models\Sucursal;
use App\Models\Pregunta;
use App\Models\User;
use File;

class SucursalController extends Controller
{
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name'       => 'required|max:50',
      'password'   => 'required|max:30',
      'comercial'  => 'required|max:60',
      'giro'       => 'required|integer'
    ]);

    if($validator->fails()){
      return back()->withErrors(['msg_error' => 'No valido']);
    }

    $name      = $request['name'];
    $password  = $request['password'];
    $comercial = $request['comercial'];
    $giro      = $request['giro'];


    $check_suc = Sucursal::where('sucursal','=', $name)->first();
    if($check_suc){
      return back()->withInput()->withErrors(['msg_error' => "Ya existe un sucursal llamada \"$name\" proporciona otro nombre"]);
    }

    $sucursal = new Sucursal();
    $sucursal->sucursal      = mb_strtolower($name, 'UTF-8');
    $sucursal->identificador = Session::get('identificador');
    $sucursal->empresa       = $comercial;
    $sucursal->usuario       = $name;
    $sucursal->pass          = $password;
    $sucursal->activo        = 1;
    $sucursal->tipousr       = $giro;
    $sucursal->url_menu      = '';
    $sucursal->url_qr        = '';

    $sucursal->save();


    $limite_superior_for = (Session::get('plan') == 2) ? 5 : 9 ;


    for($i=1;$i<=12;$i++)
    {
      $valor = ($i > $limite_superior_for ) ? 2 : 0;

      $preg = new Pregunta();
      $preg->id            = $i;
      $preg->pregunta      = "Pregunta $i";
      $preg->calificacion  = 10;
      $preg->valor         = $valor;
      $preg->valor2        = 0;
      $preg->identificador = Session::get('identificador');
      $preg->sucursal      = $name;
      $preg->textos        = '';

      $preg->save();
    }

    for($j=1;$j<=5;$j++)
    {
      DB::table('promoimagen')->insert([
        'nombre'        => '¡Gracias por su visita!',
        'ruta'          => 'Promociones/promocupon.jpg',
        'porcentaje'    => '10% de probabilidad',
        'identificador' => Session::get('identificador'),
        'valor1'        => $j,
        'valor2'        => 'N/A',
        'sucursal'      => $name
      ]);

      DB::table('promodia')->insert([
        'nombre'        => 'Publicidad '.$j,
        'ruta'          => 'PromosKimono/promoejemplo.jpg',
        'identificador' => Session::get('identificador'),
        'valor1'        => $j,
        'sucursal'      => $name
      ]);
    }

    DB::table('logoimagen')->insert([
      'nombre'        => 'logo',
      'ruta'          => 'logo/logos.png',
      'identificador' => Session::get('identificador'),
      'ruta2'         => 'logo/logot.png',
      'sucursal'      => $name
    ]);

    $arreglo_valores =  array(9,1,1,1,2,0);

    $arreglo_labels_valores = array(
      'Cantidad de preguntas',
      'Boton no contestar',
      'Enviar Correo',
      'Enviar Comentarios',
      'No contestadas',
      'Adjuntar Evidencia' );

    for($k=1;$k<=6;$k++)
    {
      DB::table('valores')->insert([
        'id'            => $k,
        'valor'         => $arreglo_valores[$k-1],
        'nombre'        => $arreglo_labels_valores[$k-1],
        'identificador' => Session::get('identificador'),
        'sucursal'      => $name,
      ]);
    }

    Session::flash('msg_success', 'Sucursal dada de alta con éxito');
    return back();
  }


  public function delete(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'id' => 'required|integer|min:1',
      's'  => 'required|string'
    ]);

    if($validator->fails()){
      return response()->json(['status' => 422, 'msg' => 'No valido' ]);
    }

    $info_sucursal = Sucursal::where(['id' => $request['id'], 'sucursal' => $request['s'], 'identificador' => Session::get('identificador')])->first();

    if(!$info_sucursal){
      return response()->json(['status' => 204, 'msg' => 'No existe la sucursal']);
    }

    if(Session::get('sucursal_fijada') == $request['s']){
      Session::put(['sucursal_fijada' => '']);
    }

    /*eliminar de la tabla sucursales*/
    DB::table('sucursales')->where('id', $request['id'])->delete();

    /*eliminar de la tabla cuestionario*/
    DB::table('cuestionario')->where(['sucursal' => $request['s']])->delete();

    /*Nos traemos los regitros de la table cupones*/
    $cupones = DB::table('promoimagen')->select('id','ruta')->where('sucursal' , $request['s'])->get();

    if(count($cupones) > 0)
    {
      for($i=0;$i<count($cupones);$i++)
      {
        if($cupones[$i]->ruta != 'Promociones/promocupon.jpg')
        {
          /*eliminar los archivos de la carpeta public/images/cupones*/
          $arr_cupones_url = explode('/', $cupones[$i]->ruta);
          $nombre_archivo = $arr_cupones_url[count($arr_cupones_url)-1];
          if(File::exists(public_path().'/images/cupones/'.$nombre_archivo))
          {
             File::delete(public_path().'/images/cupones/'.$nombre_archivo);
          }
        }
        /*eliminar de la tabla promoimagen*/
        DB::table('promoimagen')->where('id', $cupones[$i]->id)->delete();
      }
    }

    /*nos traemos los registros promodia(promociones)*/
    $promociones = DB::table('promodia')->select('id', 'ruta')->where('sucursal', $request['s'])->get();

    if(count($promociones) > 0)
    {
      for($j=0;$j<count($promociones);$j++)
      {
        if($promociones[$j]->ruta != 'PromosKimono/promoejemplo.jpg')
        {
            /*eliminar el archivo de la carpeta public/images/promos*/
            $arreglo_promo_url = explode('/', $promociones[$j]->ruta);
            $nombre_prom_file = $arreglo_promo_url[count($arreglo_promo_url)-1];

            if(File::exists(public_path().'/images/promos/'.$nombre_prom_file))
            {
               File::delete(public_path().'/images/promos/'.$nombre_prom_file);
            }
        }
        /*eliminar registro de la tabla promodia*/
        DB::table('promodia')->where('id', $promociones[$j]->id)->delete();
      }
    }

    /*nos traemos la infomacion de tabla logos*/
    $logo_info = DB::table('logoimagen')->select('id', 'ruta')->where('sucursal', $request['s'])->first();

    if($logo_info)
    {
      if($logo_info->ruta != 'logo/logos.png')
      {
        /*eliminamos de la carpeta public/images/logo*/
        $arr_logo_url = explode('/', $logo_info->ruta);
        $nombre_logo_file = $arr_logo_url[count($arr_logo_url)-1];

        if(File::exists(public_path().'/images/logo/'.$nombre_logo_file))
        {
           File::delete(public_path().'/images/logo/'.$nombre_logo_file);
        }
      }
      DB::table('logoimagen')->where('id', $logo_info->id)->delete();
    }

    /*eliminamos registros de la tabla valores*/
     DB::table('valores')->where('sucursal', $request['s'])->delete();

    /*eliminamos la asignacion de sucursal en la tabla registros*/
    $usuarios_identificador = DB::table('registros')->select('id', 'sucs')
    ->whereRaw("identificador = ".Session::get('identificador')." AND poder != 1")->orderBy('id', 'DESC')->get();

    if(count($usuarios_identificador) > 0)
    {
      /*recorremos el arreglo de usuarios dados de alta*/
      for($k=0;$k<count($usuarios_identificador);$k++)
      {
        if($usuarios_identificador[$k]->sucs != '')
        {
          /*convertimos en arreglo si tienen sucursales asignadas*/
           $arreglo_sucursales = explode(',',$usuarios_identificador[$k]->sucs);
           $nuevo_arreglo_sucursales = array();
           /*recorremos las sucursales asignadas*/
           for($x=0;$x<count($arreglo_sucursales);$x++)
           {
             if($arreglo_sucursales[$x] != $request['s'])
             {
               /*insertamos en un arreglo nuevo las sucursales mientras esta no sea la sucursal a eliminar */
               $nuevo_arreglo_sucursales[] = $arreglo_sucursales[$x];

             }
           }
           /*se actualiza la nueva cadena la asignacion*/
           DB::table('registros')->where('id', $usuarios_identificador[$k]->id)->update([
             'sucs' => implode(',',$nuevo_arreglo_sucursales)
           ]);
        }
      }
    }

    /*eliminamos los meseros en caso de ser tipo 2(restaurante comensales) la sucursal eliminada*/
    if($info_sucursal->tipousr == 2)
    {
      $meseros = DB::table('meseros1')->select('id')->where('sucursal', $request['s'])->get();
      if(count($meseros) > 0)
      {
        for($y=0;$y<count($meseros);$y++)
        {
          DB::table('meseros1')->where('id', $meseros[$y]->id)->delete();
        }
      }
    }

    return response()->json(['status' => 200, 'msg' => 'Operación completada con éxito']);
  }


  public function getSucursalInfo(Request $request){
    $validator = Validator:: make($request->only('id'),['id' => 'required|integer|min:1']);
    if($validator->fails()){
      return response()->json(['status' => 422, 'msg' => 'No valido']);
    }

    $info_sucursal = Sucursal::select('id', 'sucursal', 'empresa', 'pass', 'tipousr')->where('id', $request['id'])->first();
    return response()->json(['status' => 200, 'msg' => 'success', 'info' => $info_sucursal ]);
  }

  public function update(Request $request)
  {
    $validator = Validator::make($request->all(), ['id' => 'required|integer|min:1',
    'comercial' => 'required|string|max:60',  'password' => 'required|string|max:30', 'giro' => 'required|integer|min:1']);

    if($validator->fails()){
      return response()->json(['status' => 422, 'msg' => 'no valido']);
    }

    $id        = $request['id'];
    $comercial = $request['comercial'];
    $password  = $request['password'];
    $giro      = $request['giro'];

    Sucursal::where('id','=',$id)->update(['empresa'=> $comercial, 'pass' => $password, 'tipousr' => $giro]);
    return response()->json(['status' => 200, 'msg' => 'Información actualizada con éxito']);
  }

  /*api*/
  public function autenticarSucursal(Request $request)
  {
    $validator = Validator::make($request->all(),['sucursal' => 'required|string|max:50', 'password' => 'required|string|max:30' ]);
    if($validator->fails()){
      return response()->json(['status' => 422, 'msg' => 'Información no valida'], 422);
    }

    $info_sucursal = Sucursal::select('id','sucursal','identificador','tipousr', 'activo')->where(['sucursal' => $request->sucursal, 'pass' => $request->password])->first();
    if( ! $info_sucursal){
      return response()->json(['status' => 401, 'msg' => 'Usuario o contraseña incorrecta'], 401);
    }

    /*se consulta si el usuario esta activo en la tabla sucursal*/
    if($info_sucursal->activo != 1){
      return response()->json(['status' => 403, 'msg' => 'Usuario no activo'], 403);
    }

    $check_usuario = User::select('activado')->where(["identificador" => $info_sucursal->identificador, 'poder' => 1])->orderBy('id', 'ASC')->first();

    if($check_usuario->activado != 1){
      return response()->json(['status' => 403, 'msg' => 'Usuario no activo'], 403);
    }

    /*traemos las preguntas*/
    $preguntas = Pregunta::select('id', 'pregunta','valor', 'valor2','textos')
    ->whereRaw("sucursal ='".$request->sucursal."' AND valor != 2")->orderBy('id', 'ASC')->get();
    $info_sucursal->preguntas = $preguntas;

    /*traemos las promociones*/
    $promociones = DB::table('promodia')->select('ruta')->where('sucursal' , $request->sucursal)->orderBy('id', 'ASC')->limit(5)->get();
    $info_sucursal->promociones = $promociones;

    $vendedores = array();
    /*si la sucursal no es de tipo para llevar consultamos los vendedores*/
    if($info_sucursal->tipousr != 1){
      $vendedores = DB::table('meseros1')->select('id','nombre', 'clave')->where('sucursal', $request->sucursal)->get();
    }

    $info_sucursal->vendedores = $vendedores;

    return response()->json(['status' => 200, 'msg' => 'success', 'info' => [$info_sucursal]], 200);
  }

  /*api*/
  public function promedioDiario(Request $request)
  {
    $validator = Validator::make($request->only('usuario'), ['usuario' => 'required|string|max:30']);
    if($validator->fails())
    {
      return response()->json(['status' => 422, 'msg' => 'Valores no validos']);
    }
    $registros_query = DB::table('registros')->select('poder', 'sucs', 'identificador')->where('usuario', $request['usuario'])->first();

    if( ! $registros_query){
      return response(['status' => 422 ,'msg' => 'No existe el usuario']);
    }

    $sucursales = array();

    if($registros_query->poder == 1)
    {
      $sucursales = DB::table('sucursales')->select('sucursal')
      ->where('identificador', $registros_query->identificador)->distinct()->get();
    }
    else
    {
      $sucs_arreglo = explode(",", $registros_query->sucs);
      for($i=0;$i<count($sucs_arreglo);$i++)
      {
        $sucursales[$i] = new \stdClass();
        $sucursales[$i]->sucursal = $sucs_arreglo[$i];
      }
    }

    if(count($sucursales) == 0){
      return response()->json(['status' => 204, 'msg' => 'Sin sucursales']);
    }

    $promedios_arreglo = array();
    for($j=0;$j<count($sucursales);$j++)
    {
      $query_promedio = DB::table('promedios_encuestas')->selectRaw("avg(encuesta_prom) as 'promedio'")
        ->whereRaw("sucursal_prom = '".$sucursales[$j]->sucursal."' AND fecha_reg BETWEEN concat(date(now()), ' 05:00:00')
       AND concat(date_add(date(now()), interval 1 day )  , ' 04:59:00')")->first();

       $query_logo = DB::table('logoimagen')->select('ruta')->where(['sucursal'=> $sucursales[$j]->sucursal ])->first();
       $url_logo = (!$query_logo) ? 'logo/logot.png' : $query_logo->ruta;

       $promedio_formateado = ($query_promedio->promedio == null) ? 0 : number_format($query_promedio->promedio, 2, '.', '');

       $promedios_arreglo[$j]['sucursal'] = $sucursales[$j]->sucursal;
       $promedios_arreglo[$j]['promedio'] = $promedio_formateado;
       $promedios_arreglo[$j]['rutalogo'] = $url_logo;
    }
    return response()->json(['status' => 200, 'msg' => 'success', 'info' => $promedios_arreglo]);
  }



  /*
    promedios en movil
  */
  public function promediosSucursalesMovil(Request $request)
  {
    $validator = Validator::make($request->only('usuario', 'token'),[
      'usuario' => 'required|string',
      'token'   => 'required|string'
    ]);

    if($validator->fails()){
      return response()->json(['error' =>['msg' => 'Valores no validos']], 422);
    }

    $infoUser = DB::table('registros')->select('poder', 'identificador', 'sucs')->where('usuario', $request->usuario)->first();

    if(!$infoUser){
      return response()->json(['error' => ['msg' => 'El usuario no existe']], 404);
    }

    $sucursales = array();

    if($infoUser->poder == 1)
    {
      $sucursales = DB::table('sucursales')->select('sucursal')
      ->where('identificador', $infoUser->identificador)->distinct()->get();
    }
    else
    {
      $sucs_arreglo = explode(",", $infoUser->sucs);
      for($i=0;$i<count($sucs_arreglo);$i++)
      {
        $sucursales[$i] = new \stdClass();
        $sucursales[$i]->sucursal = $sucs_arreglo[$i];
      }
    }

    if(count($sucursales) < 1){
      return response()->json(['error' => ['msg' => 'Sin sucursales']], 404);
    }

    $arrayToJSON = array();
    /*obtenemos las fechas*/
    $fecha_desde = date('Y-m-d');
    $fecha_hasta = date("Y-m-d", strtotime($fecha_desde . "+ 1 days"));
    $fecha_desde .= ' 05:00:00';
    $fecha_hasta .= ' 04:59:59';

    for ($i=0; $i < count($sucursales); $i++)
    {
        $prom  = 0;
        $badge = 0;
        //creamos el array vacio
        $promediosArr = array();
        //asignamos la sucursal dependiendo del indice en el ciclo for
        $sucursal = $sucursales[$i]->sucursal;

        $promediosArr = DB::select(DB::raw(
          "SELECT AVG(encuesta_prom) as 'promedio' FROM promedios_encuestas
          WHERE sucursal_prom ='$sucursal' AND fecha_reg BETWEEN '$fecha_desde' AND '$fecha_hasta'
          UNION ALL (SELECT badge from notificaciones where sucursal='$sucursal' AND token ='$request->token')"
        ));

        $prom  = ($promediosArr[0]->promedio == null || $promediosArr[0]->promedio == "") ? 0 : $promediosArr[0]->promedio;

        $badge = (!isset($promediosArr[1]->promedio)) ? 0 : $promediosArr[1]->promedio;

        // solo 2 decimal separados por un punto
        $prom  = number_format($prom, 2, '.', '');
        $badge = number_format($badge, 0, '.', '');

        $aP = explode(".", (string) $prom);

        if (count($aP) > 1) {
            if ($aP[1] == '0') {
                $prom = number_format($prom, 0, '.', '');
            }
        }

        $prom = (string) $prom;

        $ruta = 'https://sondealo.com/sitio/images/logo/logot.png';
        $logo = DB::table('logoimagen')->select('ruta')->where('sucursal', $sucursal)->first();
        if($logo){
          $ruta = "https://sondealo.com/sitio/images/$logo->ruta";
        }

        $arrayToJSON[] = array("sucursal" => $sucursal, "calif" => $prom, "badge" => $badge, "logo" => $ruta);

    }

    return response()->json(['error' => ['msg' => ''], 'success' => ['msg' => 'success', 'promedios' => $arrayToJSON]], 200);

  }


  public function storeMovil(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'username'  => 'required|string',
      'name'      => 'required|max:50',
      'password'  => 'required|max:30',
      'comercial' => 'required|max:60',
      'giro'      => 'required|integer'
    ]);

    if ($validator->fails()) 
    {
        return response()->json([
          'errors' => ['msg' => 'Información no valida' ]
        ], 422);
    }

    // asignamos en variables los valores del request
    $username   = $request->username;
    $name       = $request->name;
    $password   = $request->password;
    $comercial  = $request->comercial;
    $giro       = $request->giro;


    //revisamos que no exista una sucursal con el mismo nombre
    $check_suc = Sucursal::where('sucursal','=', $name)->first();

    if($check_suc){
      return response()->json([
        'errors' => ['msg' => "Ya existe un sucursal llamada \"$name\" proporciona otro nombre"]
      ], 422);
    }

    $check_usuario = User::where('usuario', $username)->first();

    if(!$check_usuario){
      return response()->json([
        'errors' => ['msg' => "El usuario '$username' no existe" ]
      ], 404);
    }

    $identificador = $check_usuario->identificador;

    $sucursal = new Sucursal();
    $sucursal->sucursal      = mb_strtolower($name, 'UTF-8');
    $sucursal->identificador = $identificador;
    $sucursal->empresa       = $comercial;
    $sucursal->usuario       = $name;
    $sucursal->pass          = $password;
    $sucursal->activo        = 1;
    $sucursal->tipousr       = $giro;
    $sucursal->url_menu      = '';
    $sucursal->url_qr        = '';

    $sucursal->save();

    $check_plan = DB::table('registros_planes')->select('planes_id')->where('registros_id', $check_usuario->id)->first();


    $limite_superior_for = ($check_plan->planes_id == 2)  ? 5 : 9;

    for($i=1;$i<=12;$i++)
    {
      $valor = ($i > $limite_superior_for ) ? 2 : 0;

      $preg = new Pregunta();
      $preg->id            = $i;
      $preg->pregunta      = "Pregunta $i";
      $preg->calificacion  = 10;
      $preg->valor         = $valor;
      $preg->valor2        = 0;
      $preg->identificador = $identificador;
      $preg->sucursal      = $name;
      $preg->textos        = '';

      $preg->save();
    }

    for($j=1;$j<=5;$j++)
    {
      DB::table('promoimagen')->insert([
        'nombre'        => '¡Gracias por su visita!',
        'ruta'          => 'Promociones/promocupon.jpg',
        'porcentaje'    => '10% de probabilidad',
        'identificador' => $identificador,
        'valor1'        => $j,
        'valor2'        => 'N/A',
        'sucursal'      => $name
      ]);

      DB::table('promodia')->insert([
        'nombre'        => 'Publicidad '.$j,
        'ruta'          => 'PromosKimono/promoejemplo.jpg',
        'identificador' => $identificador,
        'valor1'        => $j,
        'sucursal'      => $name
      ]);
    }

    DB::table('logoimagen')->insert([
      'nombre'        => 'logo',
      'ruta'          => 'logo/logos.png',
      'identificador' =>  $identificador,
      'ruta2'         => 'logo/logot.png',
      'sucursal'      => $name
    ]);

    $arreglo_valores =  array(9,1,1,1,2,0);

    $arreglo_labels_valores = array(
      'Cantidad de preguntas',
      'Boton no contestar',
      'Enviar Correo',
      'Enviar Comentarios',
      'No contestadas',
      'Adjuntar Evidencia' );

    for($k=1;$k<=6;$k++)
    {
      DB::table('valores')->insert([
        'id'            => $k,
        'valor'         => $arreglo_valores[$k-1],
        'nombre'        => $arreglo_labels_valores[$k-1],
        'identificador' => $identificador,
        'sucursal'      => $name,
      ]);
    }

    return response()->json([
      'errors' => ['msg' => '' ],
      'success' => ['msg' => 'Sucursal registrada con éxito']
    ], 200);   

  }


  public function getAppMenuImages($sucursal)
  {    
    $items_menu = DB::table(DB::raw("categorias_menu, menu_items"))
    ->selectRaw("categorias_menu.nombre as 'categoria', menu_items.id as 'id_item', menu_items.nombre, menu_items.nombre_en, menu_items.ingredientes, menu_items.ingredientes_en, menu_items.precio")
    ->whereRaw("categorias_menu.sucursal = '$sucursal' and categorias_menu.id = menu_items.id_categoria")
    ->orderByRaw("categorias_menu.indice_orden ASC")
    ->get();
   

    if(!$items_menu->count()){
      return response()->json([
        'errors' => ['msg' => "Sin registros para la sucursal $sucursal o no existe"]
      ], 404);
    } 

    foreach($items_menu as $item)
    {
      $item->imagenes_url = DB::table('menu_imagenes_items')->select('ruta_servidor')
      ->where('id_item', $item->id_item)
      ->get();      
    }

    return response()->json([
      'errors' => ['msg' => '' ],
      'success' => ['msg' => 'success', 'items' => $items_menu]
    ], 200); 
  }

  public function getAppMenuCatego($sucursal)
  {
    $catego_menu = DB::table(DB::raw("categorias_menu"))
    ->selectRaw("categorias_menu.id as 'categoria_id', categorias_menu.nombre as 'categoria', categorias_menu.nombre_en as 'categoria_en', categorias_menu.imagen_url as 'imagen_url', categorias_menu.id_video as 'id_video', categorias_menu.ruta_promo as 'promocion'")
    ->whereRaw("categorias_menu.sucursal = '$sucursal'")
    ->orderByRaw("categorias_menu.indice_orden ASC")
    ->get();

    if(!$catego_menu->count())
    {
      return response()->json([
        'errors' => ['msg' => "Sin registros para la sucursal $sucursal o no existe"]
      ], 404);
    }

    return response()->json([
      'errors' => ['msg' => '' ],
      'success' => ['msg' => 'success', 'items' => $catego_menu]
    ], 200);
  }

  public function getLogoMenu($sucursal)
  {
    $logo_menu = DB::table(DB::raw("logoimagen"))
    ->selectRaw("logoimagen.ruta as 'logo'")
    ->whereRaw("sucursal = '$sucursal'")
    ->get();

    if(!$logo_menu->count())
    {
      return response()->json([
        'errors' => ['msg' => "Sin registro para la sucursal $sucursal"]
      ], 404);
    }

    return response()->json([
      'errors' => ['msg' => '' ],
      'success' => ['msg' => 'success', 'item' => $logo_menu]
    ], 200);
  }

}
