<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content="encuestas,sondealo"/>
    <meta content="{{ csrf_token() }}" name="csrf-token"/>
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('icons/sondealo.ico')}}" />
    <title>Cambiar contraseña</title>
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet"/>

    <link href="{{ asset('css/aplicar_restablecer_pass.css')}}" rel="stylesheet"/>
  </head>

  <body class="text-center">
    <header class="sondealo-color">
      <div class="container">
        <img style="margin-top: 10px;height:40px;" src="{{asset('images/sondealogo.png')}}" class="brand-img"/>
      </div>
    </header>

    <form class="form-signin"  action="{{route('restablecer_password_enlace')}}" id="form-aplicar_cambio_pass"  onsubmit="return false;" autocomplete="off" method="post" >
      @csrf
      <input type="hidden" name="key" value="{{$token}}"/>
      <h3 class="h3 mb-3 font-weight-normal">
        Cambiar contraseña
      </h3>
      <small>Nueva contraseña</small>
      <input type="password" id="password_1" name="password_1" maxlength="30" class="form-control" maxlength="30" required autofocus/>
      <small>Confirma la nueva contraseña</small>
      <input type="password" id="password_2" name="password_2" maxlength="30" class="form-control" maxlength="30" required autofocus/>

      @if($errors->any())
        <div class="alert alert-warning" role="alert">
          {{ $errors->first() }}
        </div>
      @endif
      
      <button class="btn btn-primary btn-block" id="btn-aplicar-cambio-pass" style="margin-top:10px;">Enviar</button>
    </form>
    <script src="{{asset('js/sweetalert29.js')}}"></script>
    <script type="text/javascript">
    document.getElementById('btn-aplicar-cambio-pass').addEventListener('click', savePassReset);
    function savePassReset()
    {
      let txt_password_1 = document.getElementById('password_1');
      let txt_password_2 = document.getElementById('password_2');

      let password_1 = txt_password_1.value.trim();
      let password_2 = txt_password_2.value.trim();

      if(password_1 == '' || password_2 == ''){
        return Swal.fire({icon:'info', text:'Llene ambos campos'});
      }

      if(password_1 != password_2){
        return Swal.fire({icon:'info', text:'Las contraseñas no coinciden'});
      }

      if(password_1.length < 6 || password_2.length < 6){
        return Swal.fire({icon:'info', text:'La contraseña debe de contener al menos 6 caracteres'});
      }

      document.getElementById('form-aplicar_cambio_pass').submit();
    }
    </script>
  </body>
</html>
