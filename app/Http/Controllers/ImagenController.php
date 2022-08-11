<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Validator;
use File;

class ImagenController extends Controller
{
  public function cambiarPromocion(Request $request)
  {
    /*2mb peso maximo*/
    $validator = Validator::make($request->all(), [
      'prom' => 'required|integer|min:1',
      'img'  => 'required|image|mimes:jpeg,png,jpg|max:2048' ]);

      if($validator->fails()){
        return response()->json(['status' => 422, 'msg' => 'No valido']);
      }
      if(!$request->img->isValid()){
        return response()->json(['status'=> 422, 'msg' => 'La imagen tiene errores, inténtelo más tarde']);
      }

      $img       = $request->file('img');
      $extension = $request->img->extension();

      $nombre = 'promo'.$request['prom'].'.'.$extension;
      $ruta = 'https://sondealo.com/sitio/images/promos/'.$nombre;

      if(File::exists(public_path() . '/images/promos/' . $nombre)) {
        File::delete(public_path() . '/images/promos/' . $nombre);
      }

      $img->move(public_path() . '/images/promos/', $nombre);

      $update = DB::table('promodia')->where('id', $request['prom'])->update([
        'ruta' => $ruta
      ]);

      return response()->json(['status' => 200, 'msg' => 'Imagen actualizada con éxito']);
  }

  public function cambiarCupon(Request $request)
  {
    $validator = Validator::make($request->all(), ['id' => 'required|integer|min:1', 'img' => 'required|image|mimes:jpg,png,jpeg|max:2048' ]);

    if($validator->fails()){
      return response()->json(['status' => 422, 'msg' => 'Información no valida']);
    }

    if(!$request->img->isValid()){
      return response()->json(['status'=> 422, 'msg' => 'La imagen tiene errores, inténtelo más tarde']);
    }

    $img = $request->file('img');
    $extension = $request->img->extension();

    $query_ruta = DB::table('promoimagen')->select('ruta')->where('id', $request['id'])->first();

    if($query_ruta->ruta != 'Promociones/promocupon.jpg')
    {
      $arr_actual_bd = array();
      $arr_actual_bd = explode('/', $query_ruta->ruta);
      $nombre_actual_bd = $arr_actual_bd[count($arr_actual_bd)-1];

      if(File::exists(public_path().'/images/cupones/'.$nombre_actual_bd)){
         File::delete(public_path().'/images/cupones/'.$nombre_actual_bd);
      }
    }

    $nombre_nuevo = 'promo'.$request['id'].'.'.$extension;
    $nueva_ruta_bd = 'https://sondealo.com/sitio/images/cupones/'.$nombre_nuevo;

    $img->move(public_path().'/images/cupones/', $nombre_nuevo);

    $update = DB::table('promoimagen')->where('id', $request['id'])->update([
      'ruta' => $nueva_ruta_bd
    ]);

    return response()->json(['status' => 200, 'msg' => 'Imagen actualizada con éxito']);

  }

  public function cambiarLogo(Request $request)
  {
    $validator = Validator::make($request->all(), ['id' => 'required|integer|min:1', 'img' => 'required|image|mimes:jpeg,png,jpg|max:1024']);
    if($validator->fails()){
      return response()->json(['status' => 422, 'msg' => 'No valido']);
    }

    $img = $request->file('img');
    $id  = $request['id'];
    $extension = $request->img->extension();

    $logo_actual_bd =  DB::table('logoimagen')->select('ruta')->where('id', $id)->first()->ruta;
    if($logo_actual_bd != 'logo/logos.png')
    {
      if(File::exists(public_path().'/images/'.$logo_actual_bd)){
        File::delete(public_path().'/images/'.$logo_actual_bd);
      }
    }

    $nombre_nuevo = "logo$id.$extension";

    $img->move(public_path().'/images/logo/', $nombre_nuevo);
    DB::table('logoimagen')->where('id', $id)->update(['ruta' => 'logo/'.$nombre_nuevo ]);

    return response()->json(['status'=>200, 'msg' => 'Logo actualizado con éxito']);
  }

  public function copiarPromociones(Request $request)
  {
    $validator = Validator::make($request->all(), ['desde' => 'required|string', 'actual'=> 'required|string']);
    if($validator->fails()){
      return response()->json(['status' => 422, 'msg' => 'No valido']);
    }

    /* promociones de la sucursal_fijada */
    $promos_actual = DB::table('promodia')->select('id','ruta')
    ->where(['sucursal' => $request->actual, 'identificador' => Session::get('identificador') ])
    ->orderBy('id', 'ASC')->take(5)->get();

    /*promociones de la sucursal a replicar imagenes*/
    $promos_to_reply = DB::table('promodia')->select('id','ruta')
    ->where(['sucursal' => $request->desde, 'identificador' => Session::get('identificador') ])
    ->orderBy('id', 'ASC')->take(5)->get();

    /* si cualquiera de las consultas devuelven nada retornamos mensaje */
    if(count($promos_actual) < 1 or count($promos_to_reply) < 1){
      return response()->json(['status' => 422, 'msg' => 'Información no valida']);
    }

    /*recorremos el arreglo de promociones de la sucursal actual*/
    for ($i=0; $i < count($promos_actual) ; $i++)
    {
      $promo_name ='';

      /*si no se ha cambiado nunca la imagen*/
      if($promos_actual[$i]->ruta != 'PromosKimono/promoejemplo.jpg')
      {
        $str_url_arr = explode('/',$promos_actual[$i]->ruta);
        $promo_name = $str_url_arr[count($str_url_arr)-1];
        /*si existe la imagen en el directorio*/
        if(File::exists(public_path().'/images/promos/'.$promo_name))
        {
          /* la eliminamos del directorio*/
          File::delete(public_path().'/images/promos/'.$promo_name);
        }
      }

      $nombre_promocion_nuevo = "promo".$promos_actual[$i]->id;

      $ruta_a_insertar = '';

      /* ----------------------------------------------------------- */
      /*verificamos si la sucursal a replicar tiene imagenes asignadas*/
      if($promos_to_reply[$i]->ruta != 'PromosKimono/promoejemplo.jpg')
      {
        /*obtenemos el nombre de la imagen en el directorio*/
        $p_to_reply_arr = explode('/', $promos_to_reply[$i]->ruta);
        $p_name_to_reply_str = $p_to_reply_arr[count($p_to_reply_arr)-1];
        $extension = explode('.',$p_name_to_reply_str)[1];
        /*si el archivo existe en el directorio */
        if(File::exists(public_path().'/images/promos/'.$p_name_to_reply_str))
        {
          /* copiamos la imagen en el mismo directorio cambiando el nombre con los ids del la sucursal actual*/
           File::copy(public_path().'/images/promos/'.$p_name_to_reply_str ,
           public_path().'/images/promos/'.$nombre_promocion_nuevo.'.'.$extension);
        }

        $ruta_a_insertar = 'https://sondealo.com/sitio/images/promos/'.$nombre_promocion_nuevo.'.'.$extension;
      }
      else{
        $ruta_a_insertar = 'PromosKimono/promoejemplo.jpg';
      }

      DB::table('promodia')->where(['id' => $promos_actual[$i]->id])->update([
        'ruta' => $ruta_a_insertar
      ]);
    }
    return response()->json(['status' => 200, 'msg' => 'Promociones replicadas con éxito']);
  }

  public function copiarCupones(Request $request)
  {
    $validator = Validator::make($request->all(), ['desde' => 'required|string', 'actual'=> 'required|string']);
    if($validator->fails()){
      return response()->json(['status' => 422, 'msg' => 'No valido']);
    }
    /*nos traemo los cupones de la sucursal a actualizar*/
    $cupones_actual =  DB::table('promoimagen')->select('id', 'nombre', 'ruta', 'valor2')
    ->where('sucursal', $request->actual)->orderBy('id', 'ASC')->limit(5)->get();
    /*traemos los cupones de la sucursal a replicar */
    $cupones_to_reply = DB::table('promoimagen')->select('id', 'nombre', 'ruta', 'valor2')
    ->where('sucursal', $request->desde)->orderBy('id', 'ASC')->limit(5)->get();

    if( count($cupones_actual) < 1 or count($cupones_to_reply) < 1 ){
      return response()->json(['status' => 422, 'msg' => 'Información no valida']);
    }


    for ($i=0; $i < count($cupones_actual) ; $i++)
    {
      /* si el cupon no es el original */
      if($cupones_actual[$i]->ruta != 'Promociones/promocupon.jpg')
      {
        $arr_url_cupon    = explode('/',$cupones_actual[$i]->ruta);
        $str_nombre_cupon = $arr_url_cupon[count($arr_url_cupon)-1];

        if(File::exists(public_path().'/images/cupones/'.$str_nombre_cupon))
        {
          File::delete(public_path().'/images/cupones/'.$str_nombre_cupon);
        }
      }

      $nuevo_nombre_cupon = "promo".$cupones_actual[$i]->id;
      $ruta_insertar = '';

      /* ---------------------------------------------------- */
      /* revisamos si la sucursal a replicar tiene imagenes  */
      if($cupones_to_reply[$i]->ruta != 'Promociones/promocupon.jpg')
      {
        $arr_url_cupon_reply = explode('/', $cupones_to_reply[$i]->ruta);
        $str_url_cupon_nombre = $arr_url_cupon_reply[count($arr_url_cupon_reply)-1];
        $extension = explode('.', $str_url_cupon_nombre)[1];

        if(File::exists(public_path().'/images/cupones/'.$str_url_cupon_nombre))
        {
          File::copy(public_path().'/images/cupones/'.$str_url_cupon_nombre,
          public_path().'/images/cupones/'.$nuevo_nombre_cupon.'.'.$extension );
        }

        $ruta_insertar = 'https://sondealo.com/sitio/images/cupones/'.$nuevo_nombre_cupon.'.'.$extension;
      }
      else{
        $ruta_insertar = 'Promociones/promocupon.jpg';
      }

      $valor_2_to_reply = ($cupones_to_reply[$i]->valor2 == '') ? 'N/A': $cupones_to_reply[$i]->valor2;

      DB::table('promoimagen')->where(['id' => $cupones_actual[$i]->id ])->update([
        'nombre'  => $cupones_to_reply[$i]->nombre,
        'ruta'    => $ruta_insertar,
        'valor2'  => $valor_2_to_reply
      ]);
    }
    return response()->json(['status' => 200, 'msg' => 'Operación completada con éxito']);
  }


  



}
