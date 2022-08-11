<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Validator;
use App\Models\PasswordReset;
use Session;
use Mail;
use App\Mail\RestablecerPassword;
use App\Mail\CambioPasswordExitoso;

class PasswordResetController extends Controller
{
    public function enviarPeticion(Request $request)
    {
      $validator = Validator::make($request->only('user'), ['user' => 'required|string|max:30']);
      if($validator->fails()){
        return back()->withErrors(['error_msg' => 'Usuario no valido']);
      }

      $check_existencia_usuario = User::where('usuario', $request['user'])->first();

      if(!$check_existencia_usuario){
        return back()->withErrors(['error_msg' => 'El usuario no existe']);
      }

      if($check_existencia_usuario->poder != 1){
        return back()->withErrors(['error_msg' => 'El usuario de esta cuenta puede restablecer tu contraseña']);
      }

      if($check_existencia_usuario->correo == ''){
        return back()->withErrors(['error_msg' => 'No hay un correo electrónico asociado a esta cuenta, ponte en contacto con nosotros para más información']);
      }

      $fecha = date('Y-m-d H:i:s');
      $random_int = random_int(-1000, 1000);

      $random_string = sha1($request['user'].$fecha.$random_int);

      $correo_original   = $check_existencia_usuario->correo;
      $longitud_correo   = strlen($correo_original);
      $correo_asteriscos = '';
      $contador = 0;

      for($i=0;$i<$longitud_correo;$i++)
      {
        if($correo_original[$i] == '@')
        {
          $contador++;
        }

        if($i <= 2 or $contador > 0)
        {
          $correo_asteriscos .= $correo_original[$i];
        }
        else
        {
          $correo_asteriscos .= '*';
        }
      }

      $password_reset          = new PasswordReset();
      $password_reset->usuario = $request['user'];
      $password_reset->token   = $random_string;

      $password_reset->save();

      $enlace = 'https://sondealo.com/sitio/restablecer/aplicar/'.$random_string;

      Mail::to($correo_original)->send(new RestablecerPassword($enlace, $request['user']) );

      Session::flash('msg_success', "Ingresa a \"$correo_asteriscos\",
      se ha enviado un correo electrónico con las instrucciones para restablecer tu contraseña");
      return back();
    }

    public function restablecimientoPassword(Request $request)
    {
      $validator = Validator::make($request->all(), ['key' => 'required|string',
      'password_1' => 'required|string|max:30',  'password_2' => 'required|string|max:30']);

      if($validator->fails()){
        return back()->withErrors(['error_msg' => 'No valido']);
      }

      $token      = $request['key'];
      $password_1 = $request['password_1'];
      $password_2 = $request['password_2'];

      if($password_1 != $password_2){
        return back()->withErrors(['error_msg' => 'Las contraseñas no coinciden']);
      }

      $check_token = PasswordReset::where('token', $token)->first();
      if(!$check_token){
        return back()->withErrors(['error_msg' => 'No existe']);
      }

      if($check_token->usado == 1){
        return back();
      }

      $user_info_registros = User::where('usuario', $check_token->usuario)->first();

      User::where('id', $user_info_registros->id)->update([
        'contra' => sha1($password_1)
      ]);

      $user_info_usuarios = DB::table('usuarios')->select('idusr')->where('usuario', $check_token->usuario)->first();

      DB::table('usuarios')->where('idusr', $user_info_usuarios->idusr)->update([
        'password1' => $password_1
      ]);

      PasswordReset::where('id', $check_token->id)->update(['usado' => 1]);

      Mail::to($user_info_registros->correo)->send(new CambioPasswordExitoso($user_info_registros->usuario));

      $usuario = $user_info_registros->usuario;

      return view('mail.password_restablecido', compact('usuario'));
    }



}
