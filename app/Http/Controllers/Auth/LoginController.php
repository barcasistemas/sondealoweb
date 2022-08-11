<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Validator;

class LoginController extends Controller
{
  public function loginW(Request $request)
  {
    //validamos los valores recibidos
    $validator = Validator::make($request->all(), ['user' => 'required|string|max:30', 'password' => 'required|string']);

    if($validator->fails())
    {
      return back()->withErrors(['error_msg'=>'Valores no validos']);
    }
    //consultamos si existe el usuario
    $user_query = DB::table('registros')
    ->select("id", "nombre", "correo", "telefono", "identificador", "activado", "poder", "facultad1", "facultad2",
    "facultad3", "facultad4", "facultad5", "facultad6", "facultad7", "facultad8", "facultad9", "inicia")
    ->where(['usuario' => $request['user'], 'contra' => sha1($request['password']) ])->first();

    /*en caso de no existir regresamos al login*/
    if(!$user_query){
      return back()->withInput()->withErrors(['error_msg' => 'Usuario o contraseña incorrecta']);
    }
    // en caso de no estar activado regresamos al login
    if($user_query->activado != 1){
      return back()->withInput()->withErrors(['error_msg' => 'Usuario desactivado']);
    }

    /*revisamos la tabla registros_planes*/
    $check_plan = DB::table('registros_planes')->select('estatus', 'planes_id')
    ->where('registros_id', $user_query->id)->orderBy('id', 'DESC')->first();

    $plan=56;

    if($check_plan){
      if($check_plan->estatus == 0){
        return back()->withInput()->withErrors(['error_msg' => 'Usuario inactivo por falta de pago']);
      }
      $plan =  $check_plan->planes_id;
    }
    //asignamos las variables de sesion
    Session::put([
      'id'            => $user_query->id,
      'user'          => $request['user'],
      'nombre'        => $user_query->nombre,
      'correo'        => $user_query->correo,
      'telefono'      => $user_query->telefono,
      'identificador' => $user_query->identificador,
      'plan'          => $plan,
      'poder'         => $user_query->poder,
      'f1'            => $user_query->facultad1,
      'f2'            => $user_query->facultad2,
      'f3'            => $user_query->facultad3,
      'f4'            => $user_query->facultad4,
      'f5'            => $user_query->facultad5,
      'f6'            => $user_query->facultad6,
      'f7'            => $user_query->facultad7,
      'f8'            => $user_query->facultad8,
      'f9'            => $user_query->facultad9,
      'int_tour'      => $user_query->inicia
    ]);
    //iniciamos session
    session_start();
    $_SESSION['session'] = sha1($request['user']);
    //redirigimos al home
    if($user_query->poder == 3){
      return redirect()->route('adm_mostrar_home');
    }
    return redirect()->route('mostrar_home');
  }
}
