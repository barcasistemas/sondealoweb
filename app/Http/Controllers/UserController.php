<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Models\User;
use App\Models\Sucursal;
use Session;
use File;

class UserController extends Controller
{
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), ['user' => 'required|string|max:30',
    'password' => 'required|string|max:30']);

    if($validator->fails()){
      return back()->withInput()->withErrors(['error_msg' => 'Campos no validos']);
    }

    if($request['email'] != null){
      $validator_email = Validator::make($request->only('email'), ['email' => 'email:rfc,dns|max:100']);

      if($validator_email->fails()){
        return back()->withInput()->withErrors(['error_msg' => 'Correo electrónico no valido']);
      }
      $check_email = DB::table('registros')->select('id')->where('correo' , $request['email'])->first();
      if($check_email){
        return back()->withInput()->withErrors(['error_msg' => 'El correo electrónico {'.$request['email'].'} ya está en uso']);
      }
    }

    $check_user = DB::table('registros')->select('id')->where('usuario', $request['user'])->first();
    if($check_user){
      return back()->withInput()->withErrors(['error_msg' => 'El usuario {'.$request['user'].'} ya está en uso']);
    }
    /*tabla registros*/
    $usuario_obj = new User();
    $usuario_obj->usuario       = mb_strtolower($request['user'], 'UTF-8');
    $usuario_obj->contra        = sha1($request['password']);
    $usuario_obj->nombre        = 'usuario';
    $usuario_obj->correo        = mb_strtolower($request['email'], 'UTF-8');
    $usuario_obj->identificador = Session::get('identificador');
    $usuario_obj->tipoplan      = 2;
    $usuario_obj->telefono      = '';
    $usuario_obj->cookie        = '';
    $usuario_obj->activado      = 1;
    $usuario_obj->empresa       = '';
    $usuario_obj->codigo        = '';
    $usuario_obj->poder         = 2;
    $usuario_obj->facultad1     = 0;
    $usuario_obj->facultad2     = 0;
    $usuario_obj->facultad3     = 0;
    $usuario_obj->facultad4     = 0;
    $usuario_obj->facultad5     = 0;
    $usuario_obj->facultad6     = 0;
    $usuario_obj->facultad7     = 0;
    $usuario_obj->facultad8     = 0;
    $usuario_obj->sucs          = '';
    $usuario_obj->facultad9     = 0;
    $usuario_obj->customer_id   = '';
    $usuario_obj->estado        = '';
    $usuario_obj->ciudad        = '';
    $usuario_obj->pais          = '';
    $usuario_obj->cp            = '';
    $usuario_obj->direccion     = '';
    $usuario_obj->inicia        = 0;
    $usuario_obj->facultad10    = 0;
    $usuario_obj->cuenta        = '';

    /*tabla usuarios*/
    DB::table('usuarios')->insert([
      'folio'         => '',
      'usuario'       => mb_strtolower($request['user'], 'UTF-8'),
      'nombre'        => 'usuario',
      'password1'      => $request['password'],
      'tipousr'       => 1,
      'identificador' => Session::get('identificador'),
      'sucursal'      => 'sucursal',
      'activado'      => 1,
      'facultad1'     => 1,
      'facultad2'     => 1,
      'facultad3'     => 1,
      'facultad4'     => 1,
      'facultad5'     => 1,
      'facultad6'     => 1,
      'facultad7'     => 1,
      'facultad8'     => 1,
      'facultad9'     => 1,
      'facultad10'    => 1,
      'sucs'          => '',
      'poder'         => 2,
      'correo'        => ''
    ]);

    $insert = $usuario_obj->save();
    if(!$insert){
      return back()->withInput()->withErrors(['error_msg' => 'No se pudo agregar el usuario, inténtalo más tarde']);
    }
    Session::flash('msg_success', 'El usuario '.$request['user'].' fue agregado con éxito');
    return back();
  }

  public function update(Request $request){
    $validator = Validator::make($request->only('id'), ['id' => 'required|integer|min:1']);
    if($validator->fails()){
      return response()->json(['status' => 422, 'msg' => 'No valido']);
    }

    $info_user = DB::table('registros')->selectRaw("correo, sucs, facultad1 as 'f1', facultad2 as 'f2', facultad3 as 'f3',
    facultad4 as 'f4', facultad5 as 'f5', facultad6 as 'f6', facultad7 as 'f7', facultad8 as 'f8', facultad9 as 'f9',
    facultad10 as 'f10'")->where('id', $request['id'])->first();

    if(!$info_user){
      return response()->json(['status' => 204, 'msg' => 'No existe el usuario']);
    }
    return response()->json(['status' => 200, 'msg' => 'success', 'info' => $info_user]);
  }

  public function updateFacultades(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'usuario'    => 'required|integer|min:1',
      'sucursales' => 'present|array|min:0',
      'f1'         => 'required|integer|min:0|max:1',
      'f2'         => 'required|integer|min:0|max:1',
      'f3'         => 'required|integer|min:0|max:1',
      'f4'         => 'required|integer|min:0|max:1',
      'f5'         => 'required|integer|min:0|max:1',
      'f6'         => 'required|integer|min:0|max:1',
      'f7'         => 'required|integer|min:0|max:1',
      'f8'         => 'required|integer|min:0|max:1',
      'f9'         => 'required|integer|min:0|max:1',
      'f10'        => 'required|integer|min:0|max:1' ]);

      if($validator->fails()){
        return response()->json(['status' => 422, 'msg' => 'No valido']);
      }

      $string_sucursales = '';
      $arreglo_sucursales = (Array)$request['sucursales'];
      if(count($arreglo_sucursales) > 0){
        $string_sucursales = implode(',', $arreglo_sucursales);
      }

      $update = DB::table('registros')->where('id', $request['usuario'])->update([
        'facultad1'  => $request['f1'],
        'facultad2'  => $request['f2'],
        'facultad3'  => $request['f3'],
        'facultad4'  => $request['f4'],
        'facultad5'  => $request['f5'],
        'facultad6'  => $request['f6'],
        'facultad7'  => $request['f7'],
        'facultad8'  => $request['f8'],
        'facultad9'  => $request['f9'],
        'facultad10' => $request['f10'],
        'sucs'       => $string_sucursales,
      ]);

      if(!$update){
        return response()->json(['status' => 204, 'msg'=>'No se pudo actualizar la infomación del usuario intente más tarde']);
      }
      return response()->json(['status' => 200, 'msg'=>'Información actualizada con éxito']);
  }

  public function delete(Request $request)
  {
    $validator = Validator::make($request->only('usuario'), ['usuario' => 'required|integer|min:1']);
    if($validator->fails()){
      return response()->json(['status'=>422, 'msg'=>'No valido']);
    }

    $delete = DB::table('registros')->where(['id' => $request['usuario'], 'identificador'=> Session::get('identificador')])->delete();
    if(!$delete){
      return response()->json(['status' => 204, 'msg' => 'No se pudo eliminar, intente más tarde']);
    }
    return response()->json(['status' => 200, 'msg' => 'Usuario eliminado con éxito']);
  }

  public function updatePassword(Request $request)
  {
    $validator = Validator::make($request->all(), ['password_actual' => 'required',
    'password_1' => 'required|string|min:6', 'password_2' => 'required|string|min:6']);
    if($validator->fails()){
      return back()->withErrors(['error_msg' => 'No valido']);
    }
    if($request['password_1'] != $request['password_2']){
      return back()->withInput()->withErrors(['error_msg' => 'La contraseñas no coinciden']);
    }
    $check_old_password_id =DB::table('registros')->select('id')->where(['usuario' => Session::get('user'), 'contra' => sha1($request['password_actual']) ])
    ->first();

    if(!$check_old_password_id){
      return back()->withInput()->withErrors(['error_msg' => 'La contraseña antigua no coincide']);
    }

    DB::table('registros')->where('id', $check_old_password_id->id)->update([
      'contra' => sha1($request['password_1'])
    ]);
    $table_usuarios_id = DB::table('usuarios')->select('idusr')->where('usuario', Session::get('user'))->first();

    if($table_usuarios_id){
      DB::table('usuarios')->where('idusr', $table_usuarios_id->idusr)->update([
        'password1' => $request['password_1']
      ]);
    }
    Session::flash('msg_success', 'Contraseña actualizada con éxito');
    return back();
  }

  public function updateUserInfo(Request $request)
  {
    $validator = Validator::make($request->all(), ['email' => 'required|email|max:50']);
    if($validator->fails()){
      return response()->json(['status'=> 422, 'msg' => 'El correo electrónico no es valido']);
    }

    if(!is_null($request['phone'])){
      $validator_phone = Validator::make($request->only('phone'), ['phone' => 'digits:10']);
      if($validator_phone->fails()){
        return response()->json(['status' => '422', 'msg' => 'El teléfono no es valido']);
      }
    }

    User::where('id', Session::get('id'))->update([
      'correo'    => $request['email'],
      'telefono'  => $request['phone']
    ]);

    Session::put([
      'correo'        => $request['email'],
      'telefono'      => $request['phone']
    ]);
    return response()->json(['status' => 200, 'msg' => 'Infomación actualizada con éxito']);
  }

  public function resetPasswordByAdmin(Request $request)
  {
    $validator = Validator::make($request->all(), ['usuario' => 'required|integer|min:1', 'pass' => 'required|string|min:4']);
    if($validator->fails()){
      return response()->json(['status' => 422, 'msg' => 'No valido']);
    }

    $check_username_registros = User::select('usuario')
    ->where(['id' => $request->usuario, 'identificador' => Session::get('identificador')])->first();

    if(!$check_username_registros){
      return response()->json(['status' => 204, 'msg' => 'No existe el usuario']);
    }

    $user_id_usuarios = DB::table('usuarios')->select('idusr')
    ->where('usuario', $check_username_registros->usuario)->first()->idusr;

    User::where('id', $request->usuario)->update([
      'contra' => sha1($request->pass)
      ]);

    DB::table('usuarios')->where('idusr',$user_id_usuarios)->update([
      'password1' => $request->pass
    ]);

    return response()->json(['status' => 200, 'msg' => 'Operación completada con éxito']);

  }


  public function deleteCuenta(Request $request)
  {
    $validator = Validator::make($request->all(), ['user_delete' => 'required|integer|min:1', 'pass_comprobacion' => 'required|string|min:1']);
    if($validator->fails()){
      return response()->json(['status' => 422, 'msg' => 'No valido']);
    }

    $id_usuario_delete = $request->user_delete; /*id del usuario a eliminar*/
    $pass_comprobacion = $request->pass_comprobacion; /*la contraseña de la cuenta como validacion para poder eliminar*/

    /*comprobamos que la contraseña de sondealo sea correcta*/
    $pass = User::where(['id' => Session::get('id'), 'contra' => sha1($pass_comprobacion)])->first();
    if(!$pass){
      return response()->json(['status' => 422, 'msg' => 'La contraseña es erronea']);
    }
    /*buscamos por id el usuario a eliminar*/
    $user_table_registros = User::select('identificador')->where('id', $id_usuario_delete)->first();
    /*si no hay registros retornamos lo siguiente*/
    if(!$user_table_registros){
      return response()->json(['status' => 404, 'msg' => 'El usuario no existe']);
    }
    /*IDENTIFICADOR DE LA CUENTA*/
    $identificador_delete = $user_table_registros->identificador; /*almacenamos el identificador de usuario a eliminar*/

    $users_table_registros = User::select('id')->where('identificador', $identificador_delete)->get();
    /*eliminamos los usuarios de la tabla registros*/
    for($j=0;$j<count($users_table_registros);$j++)
    {
      User::where('id', $users_table_registros[$j]->id)->delete();
    }

    /*traemos todos los registro de la tabla usuarios con el mismo identificador*/
    $users_table_usuarios = DB::table('usuarios')->select('idusr')->where('identificador', $identificador_delete)->get();

    /*si existen registro en la tabla usuarios los eliminamos*/
    if(count($users_table_usuarios) > 0)
    {
      for ($i=0; $i < count($users_table_usuarios) ; $i++)
      {
        DB::table('usuarios')->where('idusr', $users_table_usuarios[$i]->idusr)->delete();
      }
    }

    /* ==================== TABLA SUCURSALES ========================*/
    $sucursales_delete = Sucursal::select('id')->where('identificador', $identificador_delete)->get();
    if(count($sucursales_delete) > 0)
    {
      for ($k=0; $k <count($sucursales_delete) ; $k++)
      {
         Sucursal::where('id', $sucursales_delete[$k]->id)->delete();
      }
    }


    /* =================== TABLA PROMODIA(PROMOCIONES)===============================*/
    $promociones = DB::table('promodia')->select('id', 'ruta')->where('identificador', $identificador_delete)->get();

    if(count($promociones) > 0)
    {
      for ($x=0; $x <count($promociones) ; $x++)
      {
           /*obtenemos el nombre de la imagen */
        $arr_nombre_promo = explode('/', $promociones[$x]->ruta);
        $nombre_promo = $arr_nombre_promo[count($arr_nombre_promo)-1];
        /*si la imagen ya ha sido cambiada la eliminamos*/
        if($nombre_promo != 'promoejemplo.jpg')
        {
          if(File::exists(public_path().'/images/promos/'.$nombre_promo))
          {
            File::delete(public_path().'/images/promos/'.$nombre_promo);
          }
        }
        DB::table('promodia')->where('id', $promociones[$x]->id)->delete();
      }
    }


    /* ================= TABLA PROMOIMAGEN(CUPONES) =========================*/
    $cupones = DB::table('promoimagen')->select('id', 'ruta')->where('identificador', $identificador_delete)->get();
    if(count($cupones) > 0)
    {
      for ($y=0; $y < count($cupones) ; $y++)
      {
        $arr_nombre_cupon = explode('/', $cupones[$y]->ruta);
        $nombre_cupon     = $arr_nombre_cupon[count($arr_nombre_cupon)-1];

        if($nombre_cupon != 'promocupon.jpg')
        {
          if(File::exists(public_path().'/images/cupones/'.$nombre_cupon))
          {
            File::delete(public_path().'/images/cupones/'.$nombre_cupon);
          }
        }
        /*eliminamos el registro*/
        DB::table('promoimagen')->where('id', $cupones[$y]->id)->delete();
      }
    }


    /* ======================= TABLA CUESTONARIO ===============================*/
    /*se elimina por identificador la tabla no tiene llave primaria unica*/
    DB::table('cuestionario')->where('identificador', $identificador_delete)->delete();


    /* ======================= TABLA LOGOIMAGEN ================================*/
    $logos = DB::table('logoimagen')->select('id', 'ruta')->where('identificador', $identificador_delete)->get();

    if(count($logos) > 0)
    {
      for ($z=0; $z < count($logos) ; $z++)
      {
        $arr_nombre_logo = explode('/', $logos[$z]->ruta);
        $nombre_logo     = $arr_nombre_logo[count($arr_nombre_logo)-1];

        if($nombre_logo != 'logos.png')
        {
          if(File::exists(public_path().'/images/logo/'.$nombre_logo))
          {
            File::delete(public_path().'/images/logo/'.$nombre_logo);
          }
        }
        DB::table('logoimagen')->where('id', $logos[$z]->id)->delete();
      }

    }

    /* ======================= TABLA VALORES ================================*/
    /* se elimina por identificador, la tabla no cuenta con llave primaria unica*/
    DB::table('valores')->where('identificador', $identificador_delete)->delete();

    /* ========================== TABLA MESEROS1 ============================*/
    $meseros = DB::table('meseros1')->select('id')->where('identificador', $identificador_delete)->get();
    if(count($meseros) > 0)
    {
      for ($ite=0; $ite < count($meseros) ; $ite++)
      {
        DB::table('meseros1')->where('id', $meseros[$ite]->id)->delete();
      }
    }
    return response()->json(['status' => 200, 'msg' => 'Cuenta eliminada con éxito, espera ...']);
  }

  public function storeTitular(Request $request)
  {
     $validator = Validator::make($request->all(), [
          'name'     => 'required|string|max:50',
          'username' => 'required|string|max:30',
          'email'    => 'required|email|max:100',
          'phone'    => 'required|digits:10',
          'password' => 'required|string',
      ]);

      if ($validator->fails()) {
          return back()->withInput()->withErrors(['error_msg' => 'No valido']);
      }
      /*revisamos que no exista el nombre de usuario*/
      $check_nombre_user = User::select('id')->where('usuario', $request->username)->first();
      if ($check_nombre_user) {
          return back()->withInput()->withErrors(['error_msg' => "El usuario '".$request->username."' ya existe"]);
      }
      /*revisamos que no exista el correo*/
      $check_email = User::select('id')->where('correo', $request->email)->first();
      if($check_email){
        return back()->withInput()->withErrors(['error_msg' => "El correo electrónico '".$request->email."' ya está asociado a otra cuenta"]);
      }
      /*obtenemos el identificador nuevo a utilizar*/
      $identificador = DB::table('registros')->select('identificador')->orderBy('identificador', 'DESC')->first()->identificador + 1;
      /*insertamos el registro en la tabla registros*/
      $registro = new User();
      $registro->usuario       = mb_strtolower($request->username, 'UTF-8');
      $registro->contra        = sha1($request->password);
      $registro->nombre        = $request->name;
      $registro->correo        = mb_strtolower($request->email, 'UTF-8');
      $registro->telefono      = $request->phone;
      $registro->activado      = 1;
      $registro->poder         = 1;
      $registro->identificador = $identificador;
      $registro->customer_id   = '';
      $registro->facultad1     = 1;
      $registro->facultad2     = 1;
      $registro->facultad3     = 1;
      $registro->facultad4     = 1;
      $registro->facultad5     = 1;
      $registro->facultad6     = 1;
      $registro->facultad7     = 1;
      $registro->facultad8     = 1;
      $registro->facultad9     = 1;
      $registro->inicia        = 1;

      $registro->save();

      /*insetamos en la tabla usuarios*/
      DB::table('usuarios')->insert([
        'folio'         => '',
        'usuario'       => mb_strtolower($request->username, 'UTF-8'),
        'nombre'        => 'usuario',
        'password1'      => $request->password,
        'tipousr'       => 1,
        'identificador' => $identificador,
        'sucursal'      => 'sucursal',
        'activado'      => 1,
        'facultad1'     => 1,
        'facultad2'     => 1,
        'facultad3'     => 1,
        'facultad4'     => 1,
        'facultad5'     => 1,
        'facultad6'     => 1,
        'facultad7'     => 1,
        'facultad8'     => 1,
        'facultad9'     => 1,
        'facultad10'    => 1,
        'sucs'          => '',
        'poder'         => 1,
        'correo'        => mb_strtolower($request->email, 'UTF-8')
      ]);

      //obtenemos el ultimo id de la tabla registros
      $registroLast = User::where('identificador', $identificador)->orderBy('id', 'DESC')->first();

      $planes_id = ($request->has('plan_id')) ? $request->plan_id : 56 ;

      /*se inserta el registro del plan*/
      DB::table('registros_planes')->insert([
        'registros_id' => $registroLast->id,
        'planes_id'    => $planes_id ,
        'fecha_inicio' => date('Y-m-d H:i:s'),
        'estatus'      => 1
      ]);

     //Aqui enviamos el correo de registro
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, 'https://sondealo.com/mail/send/'.$registroLast->usuario);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_exec($ch);
      curl_close($ch);

      //asignamos las variables de sesion
      Session::put([
        'id'            => $registroLast->id,
        'user'          => $registroLast->usuario,
        'nombre'        => $registroLast->nombre,
        'correo'        => $registroLast->correo,
        'telefono'      => $registroLast->telefono,
        'identificador' => $registroLast->identificador,
        'poder'         => $registroLast->poder,
        'plan'          => $planes_id,
        'f1'            => $registroLast->facultad1,
        'f2'            => $registroLast->facultad2,
        'f3'            => $registroLast->facultad3,
        'f4'            => $registroLast->facultad4,
        'f5'            => $registroLast->facultad5,
        'f6'            => $registroLast->facultad6,
        'f7'            => $registroLast->facultad7,
        'f8'            => $registroLast->facultad8,
        'f9'            => $registroLast->facultad9,
        'int_tour'      => $registroLast->inicia
      ]);

      session_start();
      $_SESSION['session'] = sha1($registroLast->usuario);
      /*redirigimos al tour*/
      return redirect()->route('mostrar_sucursales');
  }


  public function authMovil($user, $password)
  {
    $validator = Validator::make(['user' => $user, 'password' => $password ], [
      'user' => 'required|string',
      'password' => 'required|string'
    ]);
    if($validator->fails()){
      return response()->json(['error' => 'Infomación no valida'],422);
    }

    $info = User::select('poder', 'identificador', 'activado', 'facultad1', 'facultad2',
    'facultad3', 'facultad4', 'facultad5', 'facultad6', 'facultad7', 'facultad8', 'facultad9', 'facultad10' )
    ->where(['usuario' => $user, 'contra' => sha1($password)])->first();

    if( ! $info){
      return response()->json(['error' => 'Usuario o contraseña erronea'], 404);
    }
    return response()->json($info, 200);

  }

  public function guardarFirebaseToken(Request $request)
  {
    $validator = Validator::make($request->only('usuario', 'token'),[
      'usuario' => 'required|string',
      'token'   => 'required|string'
    ]);

    if($validator->fails()){
      return response()->json(['errors' => ['msg' => 'Información no valida']], 422);
    }

    $user = User::select('poder', 'identificador', 'sucs')->where('usuario', $request->usuario)->first();
    if(!$user){
      return response()->json(['errors' => ['msg' => 'El usuario no existe']], 404);
    }

    $sucursales = [];

    switch($user->poder)
    {
      case 1:
        $sucursales = Sucursal::select('sucursal')->where('identificador', $user->identificador)->get();
        break;
        default:
        $strSucursales = $user->sucs;
        if($user->sucs != null and $user->sucs != ''){
          $sucs_arreglo = explode(',', $user->sucs);
          for($i=0;$i<count($sucs_arreglo);$i++)
          {
            $sucursales[$i] = new \stdClass();
            $sucursales[$i]->sucursal = $sucs_arreglo[$i];
          }
        }
        break;
    }
    
    for ($j=0; $j < count($sucursales) ; $j++) 
    { 
      $currRegistro = DB::table('notificaciones')->select('id')
      ->where('sucursal', $sucursales[$j]->sucursal)->where('token', $request->token)->first();

      if( ! $currRegistro)
      {
        DB::table('notificaciones')->insert([
          'token'         => $request->token,
          'identificador' => $user->identificador,
          'sucursal'      => $sucursales[$j]->sucursal,
          'enviar'        => 1,
          'badge'         => 0,
          'usuario'       => $request->usuario
        ]);
      }  
      else{
          DB::table('notificaciones')->where('id', $currRegistro->id)->update([
            'enviar' => 1,
            'badge' => 0
          ]);
      }  
    }

    return response()->json([
      'errors' => ['msg' => '' ],
      'success' => ['msg' => 'Operación completada con éxito']
    ], 200);

  }


  public function storeMovil(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name'     => 'required|string|max:50',
      'username' => 'required|string|max:30',
      'email'    => 'required|email|max:100',
      'phone'    => 'required|digits:10',
      'password' => 'required|string',
    ]);

    if ($validator->fails()) 
    {
        return response()->json([
          'errors' => ['msg' => 'Información no valida' ]
        ], 422);
    }
    /*revisamos que no exista el nombre de usuario*/
    $check_nombre_user = User::select('id')->where('usuario', $request->username)->first();
    
    if ($check_nombre_user) 
    {
        return response()->json([
          'errors' => ['msg' => "El usuario $request->username ya existe"]
        ], 422);
    }
    /*revisamos que no exista el correo*/
    $check_email = User::select('id')->where('correo', $request->email)->first();
    
    if($check_email)
    {
      return response()->json([
        'errors' => ['msg' => "El correo electrónico '".$request->email."' ya está asociado a otra cuenta"]
      ], 422);      
    }

    /*obtenemos el identificador nuevo a utilizar*/
    $identificador = DB::table('registros')->select('identificador')->orderBy('identificador', 'DESC')->first()->identificador + 1;
   
    /*insertamos el registro en la tabla registros*/
    $registro = new User();
    $registro->usuario       = mb_strtolower($request->username, 'UTF-8');
    $registro->contra        = sha1($request->password);
    $registro->nombre        = $request->name;
    $registro->correo        = mb_strtolower($request->email, 'UTF-8');
    $registro->telefono      = $request->phone;
    $registro->activado      = 1;
    $registro->poder         = 1;
    $registro->identificador = $identificador;
    $registro->customer_id   = '';
    $registro->facultad1     = 1;
    $registro->facultad2     = 1;
    $registro->facultad3     = 1;
    $registro->facultad4     = 1;
    $registro->facultad5     = 1;
    $registro->facultad6     = 1;
    $registro->facultad7     = 1;
    $registro->facultad8     = 1;
    $registro->facultad9     = 1;
    $registro->inicia        = 1;

    $registro->save();

    /*insertamos en la tabla usuarios*/
    DB::table('usuarios')->insert([
      'folio'         => '',
      'usuario'       => mb_strtolower($request->username, 'UTF-8'),
      'nombre'        => 'usuario',
      'password1'      => $request->password,
      'tipousr'       => 1,
      'identificador' => $identificador,
      'sucursal'      => 'sucursal',
      'activado'      => 1,
      'facultad1'     => 1,
      'facultad2'     => 1,
      'facultad3'     => 1,
      'facultad4'     => 1,
      'facultad5'     => 1,
      'facultad6'     => 1,
      'facultad7'     => 1,
      'facultad8'     => 1,
      'facultad9'     => 1,
      'facultad10'    => 1,
      'sucs'          => '',
      'poder'         => 1,
      'correo'        => mb_strtolower($request->email, 'UTF-8')
    ]);

    //obtenemos el ultimo id de la tabla registros
    $registroLast = User::where('identificador', $identificador)->orderBy('id', 'DESC')->first();

    $planes_id = ($request->has('plan_id')) ? $request->plan_id : 56 ;

    /*se inserta el registro del plan*/
    DB::table('registros_planes')->insert([
      'registros_id' => $registroLast->id,
      'planes_id'    => $planes_id ,
      'fecha_inicio' => date('Y-m-d H:i:s'),
      'estatus'      => 1
    ]);

    //Aqui enviamos el correo de registro
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://sondealo.com/mail/send/'.$registroLast->usuario);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_exec($ch);
    curl_close($ch);


    return response()->json([
      'errors' => ['msg' => '' ],
      'success' => ['msg' => 'Registro completado con éxito']
    ], 200);   
  }

}
