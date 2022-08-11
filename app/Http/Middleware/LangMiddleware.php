<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\App;

class LangMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $lang = 'es';
        if( ! Session::has('lang')){
          Session::put('lang' , $lang);
        }else{
          $lang = Session::get('lang');
        }

        App::setLocale($lang);      

        return $next($request);
    }
}

















//
