<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="html">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content="encuestas,sondealo"/>
    <meta content="{{ csrf_token() }}" name="csrf-token"/>
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('icons/sondealo.ico')}}" />
    <title>Ingresa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="{{ asset('css/login.css')}}" rel="stylesheet"/>
  </head>

  <body>
    <div class="container-fluid">
      <div class="row">

        <div class="col-xxl-4 col-xl-3 col-lg-4 col-md-5 col-sm-6 col-12 p-5 position-relative" id="container-form">
          <form class="row" id="form-login" autocomplete="off" action="{{ route('autenticar_web')}}" method="post" onsubmit="return false;">
            @csrf()

            <div class="col-12 mb-5">
              <img src="https://sondealo.com/assets/images/logos/logo_h_1900_800_color.png" style="max-height:5rem;"/>
            </div>
            <div class="col-12 mb-4">
              <p class="h3 text-muted fw-bold">Ingresar</p>
              <p class="lh-1 small text-muted" >
                ¿Aún no tienes cuenta? <br/>
                <a style="text-decoration:none;" href="{{route('mostrar_registro')}}" class="sondealo-text-color">¡Creala desde aquí y haz tu prueba gratis!</a> <br/>
                15 días sin cargo al registrarte
              </p>
            </div>

            <div class="col-12 mb-3">
              <label for="user" class="sr-only small fw-bold text-muted">Nombre de Usuario</label>
              <input type="text" id="user" name="user" class="form-control form-control-sm sondealo-text-color" value="{{old('user')}}" maxlength="30" placeholder="Usuario" required autofocus>
            </div>

            <div class="col-12 mb-4">
              <label for="password" class="sr-only small fw-bold text-muted">Contraseña</label>
              <input type="password" id="password" name="password" class="form-control form-control-sm sondealo-text-color"  maxlength="50" placeholder="Contraseña" required>
            </div>

            @if($errors->any())
              <div class="small alert alert-warning" role="alert">
                {{ $errors->first() }}
              </div>
            @endif

            <div class="col-12">
                <button class="btn btn-success sondealo-success-bg-color" id="btn-login" type="submit">Ingresar</button>
            </div>

            <div class="col-12 position-absolute" style="bottom:0;left:0;">
              <p class="text-muted" style="font-size:0.7rem;">Al utilizar una cuenta en <a href="https://sondealo.com" class="text-decoration-underline sondealo-text-color">Sondealo.com</a>, estás
                aceptando los <a href="https://sondealo.com/terminos-y-condiciones" class="text-decoration-underline text-secondary">Terminos y Condiciones</a> y la
                Política de Privacidad.</p>
            </div>

          </form>

        </div>

        <div class="col-xxl-8 col-xl-9 col-lg-8 col-md-7 col-sm-6 col-12 bg-light border back-img" id="container-main-img"
        style="background-image:url({{asset('images/login-main-banner.jpg')}})">
        </div>

      </div>
    </div>

    <script type="text/javascript">

    document.getElementById('btn-login').addEventListener('click', validateSubmit);

    function validateSubmit()
    {
      let txtUser     = document.getElementById('user');
      let txtPassword = document.getElementById('password');
      if(txtUser.value.trim() != '' && txtPassword.value.trim() != '')
      {
        document.getElementById('form-login').submit();
      }
    }
    function is_IE() {
      return (window.navigator.userAgent.match(/MSIE|Trident/) !== null);
    }
    if(is_IE()){
      document.getElementById('html').innerHTML = 'Navegador Obsoleto! <br/> Te recomendamos Google Chrome <a href="https://www.google.com/intl/es/chrome/?brand=UUXU&gclid=EAIaIQobChMI27nAxdjx7gIVUvDACh2GjAWgEAAYASAAEgI57fD_BwE&gclsrc=aw.ds">descargar</a>';
    }

  </script>

  </body>
</html>
