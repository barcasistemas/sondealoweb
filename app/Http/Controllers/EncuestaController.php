<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Models\Sucursal;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\ImagenController;
use App\Utilidades\ManipularCadenas;

use Session;


class EncuestaController extends Controller
{

  public function store(Request $request)
  {
     $validator = Validator::make($request->all(), ['s'=> 'required', 'preguntas'=> 'required' ]);
     if($validator->fails()){
       return response()->json(['status' => 422, 'msg' => 'No valido']);
     }

     $sucursal            = $request->s;
     $preguntas           = json_decode($request->preguntas);
     $comentario_original = $request->comentario;
     $comentario          = $request->comentario;
     $correo              = $request->correo;
     $mesa                = $request->mesa;


     $mesero = ($mesa == 'Compartido-Whatsapp') ? 'Whatsapp': 'QR';
	


     /*--------------------------------*/
     if(mb_strtolower($mesa, 'UTF-8') == 'movilstand')
     {
       $mesero = 'Stand';
       $mesa = 0;
     }
     /*--------------------------------*/


    
     if($request->has('vendedor'))
     {
       $mesero = $request->vendedor;
     }

     $folio = ($request->has('folio')) ? $request->folio : '0000';


     $identificador = 0;
     $query_identificador = Sucursal::where('sucursal', $sucursal)->first();
     if( ! $query_identificador){
       return abort(404);
     }

     $identificador = $query_identificador->identificador;

     /*se ordena el arreglo por id*/
     for ($i = 1; $i < count($preguntas); $i++)
     {
        for ($j = 0; $j < count($preguntas) - $i; $j++)
      	{
            if ($preguntas[$j]->id > $preguntas[$j + 1]->id)
      	    {
                $temp_arr_row      = $preguntas[$j + 1];
                $preguntas[$j + 1] = $preguntas[$j];
                $preguntas[$j]     = $temp_arr_row;
            }
        }
     }

    /*columnas de la base de datos*/
    $columnas_px   = array('p1', 'p2', 'p3', 'p4', 'p5', 'p6', 'p7', 'recomen', 'p9', 'p10', 'p11', 'p12', 'p13');
    $columnas_eval = array('eval', 'eval2', 'eval3', 'eval4', 'eval5', 'eval6', 'eval7', 'eval8', 'eval9', 'eval10', 'eval11', 'eval12', 'eval13');

    /*columnas en las cuales se insertara informacion*/
    $arreglo_columnas_px_insertar   = array_slice($columnas_px, 0, count($preguntas));
    $arreglo_columnas_eval_insertar = array_slice($columnas_eval, 0, count($preguntas));

    /*de arreglo a cadena*/
//    $str_columnas_px_insertar   = implode(',', $arreglo_columnas_px_insertar);
//    $str_columnas_eval_insertar = implode(',', $arreglo_columnas_eval_insertar);

    $str_columnas_px_insertar   = (count($arreglo_columnas_px_insertar)  >0) ? implode(',', $arreglo_columnas_px_insertar) : 'p1';
    $str_columnas_eval_insertar = (count($arreglo_columnas_eval_insertar)>0) ? implode(',', $arreglo_columnas_eval_insertar):'eval';


    $fecha  = date('Y-m-d h:i a');
    $fecha2 = date('Y-m-d');
    $fec    = date('Y-m-d H:i:s');

    $arreglo_valores_px   = array();
    $arreglo_valores_eval = array();

    $promedio_general = 0;
    $contador         = 0;
    $sumador          = 0;

    /*recorremos las preguntas*/
    for ($i = 0; $i < count($preguntas); $i++)
    {
        $value = $preguntas[$i]->value;
        $str   = $preguntas[$i]->str;

        /*vamos almacenando los valores a insertar en un arreglo*/
        $arreglo_valores_px[]   = "'$str'";
        $arreglo_valores_eval[] = $value;

        $valor2 = $preguntas[$i]->v2;
        $tipo   = $preguntas[$i]->tipo;

        if ($tipo == 0 or $tipo == 5 or ($tipo == 4 and $valor2 == 1)) {

            $multiplicador = ($tipo == 0 or $tipo == 4) ? 10 : 1;

            $sumador += ($multiplicador * $value);
            $contador++;
        }
    }

    /*si el contador es mas de 0 calculamos el promedio*/
    $promedio_general = ($contador > 0) ? $sumador / $contador : 0;

    /*los valores que vamos a insertar los pasamos a str*/
//    $str_valores_px   = implode(',', $arreglo_valores_px);
//    $str_valores_eval = implode(',', $arreglo_valores_eval);

    $str_valores_px   = (count($arreglo_valores_px) > 0)   ? implode(',', $arreglo_valores_px) : 'null';
    $str_valores_eval = (count($arreglo_valores_eval) > 0) ? implode(',', $arreglo_valores_eval) : 0;




    if($comentario != '')
    {
      $comentario = json_encode($comentario);
      $cadena2    = '';
      /*codificamos los comentarios*/
      for ($j = 0; $j < strlen($comentario); $j++) {
        $cadena2 .= ($comentario[$j] == '\\') ? '\\' . $comentario[$j] : $comentario[$j];
      }

      $comentario = $cadena2;
    }else{
      $comentario = "\"\"";
    }

    $insert = DB::insert("INSERT INTO calificaciones (folio, mesa, mesero, cliente, repartidor, $str_columnas_px_insertar, comentarios, fecha, fecha2, correo, $str_columnas_eval_insertar, fec, identificador, sucursal)
    VALUES ('$folio', '$mesa', '$mesero', 'null', 'null', $str_valores_px, '$comentario', '$fecha', '$fecha2', '$correo', $str_valores_eval, '$fec', $identificador, '$sucursal')");

    if ( ! $insert) {
      return response()->json(['status' => 500, 'msg' => 'No se pudo guardar la encuesta intenta más tarde']);
    }

    DB::table('promedios_encuestas')->insert([
      'sucursal_prom' =>  $sucursal,
      'encuesta_prom' => $promedio_general
    ]);


    /* ---------------------- verificamos si el request tiene imagenes ------------------- */

    $tiene_evidencia = false;   
  
    $id_encuesta_reciente = DB::table('calificaciones')->select('id')
      ->where(['sucursal' => $sucursal])->orderBy('id', 'DESC')->first()->id;

    if($request->hasFile('file1') or $request->hasFile('file2'))
    { 

      $tiene_evidencia = true;   
      $nombre_ev1 = '';
      $nombre_ev2 = '';
	
      $url_ev1 = '';
      $url_ev2 = '';

      if($request->hasFile('file1'))
      {
        $img1 = $request->file('file1');
        $arr_extension = explode('.',$request->file1->getClientOriginalName());
        $extension = $arr_extension[count($arr_extension)-1];
        $nombre_ev1 = $id_encuesta_reciente.'_e1_'.random_int(100, 500).'.'.$extension;
        $url_ev1 =  'https://sondealo.com/sitio/images/evidencia/'.$nombre_ev1;
        $img1->move(public_path().'/images/evidencia/', $nombre_ev1);
      }

      if($request->hasFile('file2'))
      {
        $img2 = $request->file('file2');
        $arr_extension2 = explode('.',$request->file2->getClientOriginalName());
        $extension2 = $arr_extension2[count($arr_extension2)-1];
        $nombre_ev2 = $id_encuesta_reciente.'_e2_'.random_int(501, 999).'.'.$extension2;
        $url_ev2 = 'https://sondealo.com/sitio/images/evidencia/'.$nombre_ev2;
        $img2->move(public_path().'/images/evidencia/', $nombre_ev2);
      }

      DB::table('evidencia')->insert([
        'id_encuesta'      => $id_encuesta_reciente,
        'ruta_evidencia_1' => $url_ev1,
        'ruta_evidencia_2' => $url_ev2
      ]);

    }


    /* -----------------------se inicia proceso para enviar notificacion ----------------------- */
   $tokens = array();
   //en caso de tener en 1 las notificacion_comentario cada vez que alguien contesta una encuesta se manda notificacion en automatico
    if($query_identificador->notificacion_comentario == 1)
    {
        /*traemos los token del la BD*/
        $arreglo_tokens_bd = DB::table('notificaciones')->select('token', 'badge')->whereRaw(" token != '' AND sucursal='$sucursal' AND enviar=1")->get();

        if(count($arreglo_tokens_bd) > 0)
        {
          for($k = 0; $k < count($arreglo_tokens_bd); $k++)
          {
              $t      = $arreglo_tokens_bd[$k]->token;
              $numero = $arreglo_tokens_bd[$k]->badge;
              $numero += 1;

              DB::table('notificaciones')->where(['token' => $t, 'sucursal' => $sucursal])->update([
                'badge' => $numero
              ]);

              $tokens[] = $t;
          }

          $registrationIds = array_values(array_unique($tokens));
          NotificacionController::send('Alguien contesto una encuesta', $sucursal, $registrationIds);
        }
    }
    if(trim($comentario) != '""' and trim($comentario) != '')
    {
      /*buscamos la alerta*/
      $alertaBoolean = false;
      $alertaBoolean = ManipularCadenas::buscarAlerta( json_encode($comentario_original) );


      if($alertaBoolean) /*si se detecta una alerta se buscan los  tokens*/
      {
          /*traemos los token del la BD*/
          $arreglo_tokens_bd = DB::table('notificaciones')->select('token', 'badge')->whereRaw(" token != '' AND sucursal='$sucursal' AND enviar=1")->get();

          if(count($arreglo_tokens_bd) > 0)
          {
            for($k = 0; $k < count($arreglo_tokens_bd); $k++)
            {
                $t      = $arreglo_tokens_bd[$k]->token;
                $numero = $arreglo_tokens_bd[$k]->badge;
                $numero += 1;

                DB::table('notificaciones')->where(['token' => $t, 'sucursal' => $sucursal])->update([
                  'badge' => $numero
                ]);

                $tokens[] = $t;
            }

            $registrationIds = array_values(array_unique($tokens));

            if($tiene_evidencia){
              $comentario_original .= ' - (Imagen adjunta disponible)';
            }
            NotificacionController::send($comentario_original, $sucursal, $registrationIds);
          }
       }
    }


    /* ------------------------ fin de if comentario != '' ------------------------------- */



    
    /* ------------------------Enviar cupon si proporciona correo ---------------------------- */

    if(trim($correo) != '')
    {
      //Aqui enviamos el correo de cupones
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,"https://sondealo.com/mail/cupon-send/$correo/$sucursal/$id_encuesta_reciente");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_exec($ch);
      curl_close($ch);
    }

    return response()->json(['status' => 200, 'msg' => 'Encuesta guardada con éxito, será redirigido']);

  }


    public function cambiarEstado(Request $request)
  {
    $validator = Validator::make($request->only('key'), ['key' => 'required|integer|min:1']);
    if($validator->fails()){
      return response()->json(['status' => 422, 'msg' => 'no valido']);
    }

    $sucursal = DB::table('estadoencuestaPrueba')->where([ 'id' => $request->key ])->first()->sucursal;

    $arreglo_sucursales_no_expira_sucursal = ['datossocios'];

    if(! in_array( mb_strtolower( $sucursal, 'UTF-8' ), $arreglo_sucursales_no_expira_sucursal) ){
      DB::table('estadoencuestaPrueba')->where('id', $request->key)->update(['estado' => 'inactivo']);
    }    


    return response()->json(['status' => 200, 'msg' => 'success']);

  }

  
  
  public function personalizar(Request $request)
  {

    $validator = Validator::make($request->only('header_color', 'header_text_color'), [
      'header_color' => 'required',
      'header_text_color' => 'required'
    ]);

    if($validator->fails()){
      return response()->json(['status' => 422, 'msg' => 'No valido' ]);
    }

    $check = DB::table('personalizacion_encuesta')
    ->where('sucursal', Session::get('sucursal_fijada'))->first();

    if( ! $check)
    {
      DB::table('personalizacion_encuesta')->insert([
        'sucursal'        => Session::get('sucursal_fijada') ,
        'color_head'      => $request->header_color,
        'color_head_text' => $request->header_text_color ,
      ]);
    }
    else
    {
      DB::table('personalizacion_encuesta')->where('id', $check->id)->update([
        'color_head'      => $request->header_color,
        'color_head_text' => $request->header_text_color ,
      ]);
    }
    return response()->json(['status' => 200, 'msg' => 'Cambios realizados con éxito']);
  }





}
