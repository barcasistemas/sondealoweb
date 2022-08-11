<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Session;

class UserAdministracion
{
    public function handle(Request $request, Closure $next)
    {
        session_start();
    		if (isset($_SESSION['session']))
    		{
    			 if(!Session::has('user') ){
    				 return redirect()->route('mostrar_login')->withErrors(['error_msg' => 'La sesión ha expirado']);
    			 }
           $nombre_ruta = $request->route()->getName();

          if(Session::get('poder') == 3)
          {
              return $next($request);
          }
          abort(404);
       }
       return redirect()->route('mostrar_login');
    }

}
