<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Vendedor;
use Session;
use Validator;
use App\Utilidades\ManipularCadenas;

class VendedorController extends Controller
{
    public function store(Request $request)
    {
      $validator = Validator::make($request->all(), ['name' => 'required|string|max:90',
      'password' => 'required|string|max:30', 's' => 'required|string|max:50']);

      if($validator->fails()){
        return back()->withErrors(['msg_error' => 'No valido']);
      }

      $query_check = DB::table('meseros1')->select('id')
      ->where(['sucursal'=> $request['s'], 'clave' => $request['password']])->first();
      if($query_check){
        return back()->withInput()->withErrors(['msg_error' => 'La contraseña '.$request['password'].' ya está en uso']);
      }

      $name = trim($request['name']);

     $vendedor_obj = new Vendedor();
     $vendedor_obj->nombre        = mb_strtolower($name,'UTF-8');
     $vendedor_obj->clave         = $request['password'];
     $vendedor_obj->identificador = Session::get('identificador');
     $vendedor_obj->correo        = '';
     $vendedor_obj->activado      = 1;
     $vendedor_obj->sucursal      = $request['s'];

     $save = $vendedor_obj->save();
     if(!$save){
       return back()->withErrors(['error_msg' => 'No se pudo guardar al vendedor, intenta mas tarde']);
     }
     Session::flash('msg_success', $request['name'].' agregado con éxito');
     return back();
    }
    /*ajax*/
    public function getInfoVendedor(Request $request)
    {
      $validator = Validator::make($request->only('vendedor'), ['vendedor' => 'required|integer|min:1']);
      if($validator->fails()){
        return response()->json(['status' => 422, 'msg' => 'No valido']);
      }
      $vendedor_info = DB::table('meseros1')->selectRaw("nombre, clave as 'password'")->where('id', $request['vendedor'])->first();
      if(!$vendedor_info){
        return response()->json(['status' => 205,'msg' => 'El vendedor no existe']);
      }
      return response()->json(['status' => 200, 'msg' => 'success', 'info' => $vendedor_info]);
    }
    /*ajax*/
    public function updateVendedor(Request $request)
    {
      $validator = Validator::make($request->all(), ['vendedor'=>'required|integer|min:1',
      'name'=>'required|string|max:90', 'password' => 'required|string|max:30']);
      if($validator->fails()){
        return response()->json(['status'=> 422, 'msg' => 'No valido']);
      }

      $sucursal_user = DB::table('meseros1')->select('sucursal')->where('id', $request['vendedor'])->first()->sucursal;

      $check_password_user = DB::table('meseros1')->select('id')
      ->whereRaw("sucursal = '$sucursal_user' AND clave = '".$request['password']."' AND id != ".$request['vendedor'])->first();

      if($check_password_user){
        return response()->json(['status' => 204, 'msg' => "La contraseña ".$request['password'].' ya está en uso']);
      }

      $update = DB::table('meseros1')->where('id', $request['vendedor'])
      ->update(['nombre' => mb_strtolower($request['name'], 'UTF-8'), 'clave' => $request['password'] ]);
      if(!$update){
        return response()->json(['status'=> 204, 'msg'=>'Envíaste la misma información']);
      }
      return response()->json(['status'=> 200, 'msg'=>'Actualizado con éxito']);
    }
    /*ajax*/
    public function removeVendedor(Request $request)
    {
      $validator = Validator::make($request->only('vendedor'), ['vendedor'=>'required|integer|min:1']);
      if($validator->fails()){
        return response()->json(['status' => 422, 'msg' => 'No valido']);
      }
      $delete = DB::table('meseros1')->where('id', $request['vendedor'])->delete();

      if(!$delete){
        return response()->json(['status'=> 204, 'msg'=>'El usuario no se pudo eliminar, intente más tarde']);
      }
      return response()->json(['status'=> 200, 'msg'=>'Usuario eliminado con éxito']);
    }

    public function getPromedios(Request $request)
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

      $arreglo_return = array();

      $sumador = 0;

      for($j=0;$j<count($arreglo_promedios);$j++)
      {
        $sumador = 0;
        $arreglo_index = array();
        $arreglo_index['Vendedor'] = $arreglo_promedios[$j]->vendedor;

        for ($k=0; $k < count($preguntas); $k++)
        {
          $arreglo_index[$preguntas[$k]->pregunta] = $arreglo_promedios[$j]->{'prom'.($k+1)};

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

        $promedio_vnd = ($contador > 0) ? $sumador / $contador : 0;

        $arreglo_index['Contestadas']    = $arreglo_promedios[$j]->contestadas;
        $arreglo_index['No contestadas'] = $no_contestadas;
        $arreglo_index['Promedio']       = number_format($promedio_vnd, 2, '.', '');

        array_push($arreglo_return, $arreglo_index);
      }

      return response()->json(['status' => 200, 'msg' => 'success', 'info' => $arreglo_return]);

    }



}
