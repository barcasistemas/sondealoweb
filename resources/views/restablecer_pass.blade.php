<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content="encuestas,sondealo"/>
    <meta content="{{ csrf_token() }}" name="csrf-token"/>
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('icons/sondealo.ico')}}" />
    <title>Restablecer contraseña</title>
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet"/>

    <link href="{{ asset('css/restablecerpass.css')}}" rel="stylesheet"/>
  </head>

  <body class="text-center">
    <header class="sondealo-color">
      <div class="container">
        <img style="margin-top: 10px;height:40px;" src="{{asset('images/sondealogo.png')}}" class="brand-img"/>
      </div>
    </header>

    <form class="form-signin" id="form-restablecer" autocomplete="off" action="{{route('generar_peticion_restablecer')}}" method="post" >
      @csrf()
      <h3 class="h3 mb-3 font-weight-normal">
        Ingresa tu nombre de usuario
      </h3>
      <label for="user" class="sr-only">Nombre de Usuario</label>
      <input type="text" id="user" name="user" class="form-control" maxlength="30" placeholder="Usuario" required autofocus/>
      @if($errors->any())
        <div class="alert alert-warning" role="alert">
          {{ $errors->first() }}
        </div>
      @endif

      @if(Session::has('msg_success'))
        <div class="alert alert-success" role="alert" style="margin-top:1rem;">
          {{ Session::get('msg_success')}}
        </div>
      @endif
      <button class="btn btn-primary btn-block" style="margin-top:10px;">Enviar</button>
    </form>


  </body>
</html>
