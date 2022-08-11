<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class PlanController extends Controller
{
  public function detallePlanUsuario(Request $request)
  {
    $validator = Validator::make($request->only('user'), ['user' => 'required|integer|min:1']);
    if($validator->fails()){
      return response()->json(['status' => 422, 'msg' => 'No valido']);
    }

    $user = DB::table('registros')->select('usuario', 'nombre', 'identificador', 'correo', 'fec', 'inicia')->where('id', $request->user)->first();
    if(!$user){
      return response()->json(['status' => 204, 'msg' => 'El usuario no existe']);
    }

    $fecha_semana_atras = date("Y-m-d", strtotime(date('Y-m-d') . "-1 week"));

    $user->semana_atras = $fecha_semana_atras;

    $sucursales = DB::table('sucursales')->selectRaw("DISTINCT sucursal")
    ->where('identificador',$user->identificador)->get();

    if(count($sucursales) > 0)
    {
      for($i=0; $i <count($sucursales) ; $i++)
      {
        $s = $sucursales[$i]->sucursal;

        $conteo = DB::table('calificaciones')->selectRaw("count(*) as 'conteo'")
        ->whereRaw("sucursal='$s' AND fec BETWEEN '$fecha_semana_atras' AND now()")->first()->conteo;

        $sucursales[$i]->conteo = $conteo;
      }
    }
    $user->sucursales = $sucursales;

    $plan_id   = 56;
    $plan_info = DB::table('registros_planes')->select('planes_id')->where('registros_id', $request->user)->first();

    if($plan_info){
      $plan_id = $plan_info->planes_id;
    }

    $obj_info_plan= DB::table('planes')->select('limit')->where('id', $plan_id)->first();
    $user->plan_limite = $obj_info_plan->limit;

    return response()->json(['status' => 200, 'msg' => 'success', 'info' => $user]);
  }

}
