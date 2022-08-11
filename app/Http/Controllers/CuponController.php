<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use Session;

class CuponController extends Controller
{
    public function validarCupon(Request $request)
    {
      $validator = Validator::make($request->all(), ['s' => 'required|string|max:30', 'cupon' => 'required|regex:/^[A-Za-z0-9]+$/']);

      if($validator->fails()){
        return back()->withErrors(['error_msg' => 'Petición no valida']);
      }

      $check_exist_estatus = DB::table('promocion')->select('id', 'estado')
      ->where(['folio' => $request['cupon'], 'sucursal' => $request['s']])
      ->first();

      if(!$check_exist_estatus){
        return back()->withErrors(['error_msg' => 'No existe el cupón']);
      }

      if($check_exist_estatus->estado != 'activo'){
        return back()->withErrors(['error_msg' => 'El cupón esta '.$check_exist_estatus->estado]);
      }

      $update = DB::table('promocion')->where('id', $check_exist_estatus->id)->update([
        'estado' => 'inactivo'
      ]);

      if(!$update){
        return back()->withErrors(['error_msg' => 'No se pudo canjear el cupón, intenta de nuevo']);
      }

      Session::flash('msg_success', 'Cupón canjeado con éxito');
      return back();
    }



    public function actualizarInformacion(Request $request)
    {
       $validator = Validator::make($request->all(), ['id' => 'required|integer|min:1',
       'descripcion' => 'required|string|max:90', 'vigencia' => 'required' ]);

       if($validator->fails()){
         return response()->json(['status' => 422, 'msg' => 'No valido']);
       }

       $id          = $request['id'];
       $descripcion = $request['descripcion'];
       $vigencia    = $request['vigencia'];

       $update = DB::table('promoimagen')->where('id', $id)->update([
         'nombre' => $descripcion,
         'valor2' => $vigencia
       ]);

       return response()->json(['status' => 200, 'msg' => 'Información guardada con éxito']);
    }


}
