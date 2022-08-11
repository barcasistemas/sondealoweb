<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Validator;

class SessionController extends Controller
{

  public function cerrarSesion(Request $request)
  {
    session_start();
    session_destroy();
    $request->session()->flush();
    return redirect()->route('mostrar_login');
  }

  public function setSesionSucursalAjax(Request $request)
  {
    $validator = Validator::make($request->only('sucursal'), ['sucursal' => 'required|string|max:50']);
    if($validator->fails()){
      return response()->json(['status' => 422, 'msg' => 'Sucursal no valida']);
    }
    $request->session()->put(['sucursal_fijada' => $request['sucursal']]);
    return response()->json(['status' => 200, 'msg' => 'success']);
  }


  public static function setSesionSucursal($sucursal_url)
  {
    if($sucursal_url == null)
    {
      $sucursal_url = (Session::has('sucursal_fijada')) ? Session::get('sucursal_fijada')  : '';
    }
    if($sucursal_url != null )
    {
      $boolean_show_info_sucursal = false;
      if(Session::get('poder') == 1)
      {
        $query_check_sucursal = DB::table('sucursales')->select('id')->where(['sucursal'=> $sucursal_url, 'identificador' => Session::get('identificador')])->first();
        $boolean_show_info_sucursal = ($query_check_sucursal) ? true : false;
      }
      else
      {
        $query_check_user = DB::table('registros')->select('sucs')->where('usuario', Session::get('user'))->first();
        if($query_check_user)
        {
          $arr_sucs = explode(',',$query_check_user->sucs);

          for($i=0;$i<count($arr_sucs);$i++)
          {
            if(mb_strtolower($arr_sucs[$i], 'UTF-8') == mb_strtolower($sucursal_url, 'UTF-8'))
            {
              $boolean_show_info_sucursal = true;
            }
          }
        }
      }
      if($boolean_show_info_sucursal){
        Session::put(['sucursal_fijada'=> $sucursal_url]);
      }
      else
      {
        $sucursal_url = '';
        return abort(403);
      }
    }
    return $sucursal_url;
  }

}
