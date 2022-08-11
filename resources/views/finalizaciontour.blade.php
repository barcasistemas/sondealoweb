<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
        <meta content="{{ csrf_token() }}" name="csrf-token"/>
        <link rel="shortcut icon" type="image/x-icon" href="{{asset('icons/sondealo.ico')}}" />
        <title>Haz finalizado la configuración</title>
        <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
        <link href="{{asset('css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('css/finalizaciontour.css')}}" rel="stylesheet" type="text/css">
    </head>
    <body>
      <div class="wrapper">
        <nav class="navbar sondealo-color navbar-fixed-top" style="box-shadow:0px -2px 8px 1px #000000;"role="navigation">
              <div class="navbar-header">
                  <a class="navbar-brand" href="{{route('mostrar_home')}}">
                    <img src="{{asset('images/sondealogo.png')}}"/>
                  </a>
              </div>
        </nav>

        <div class="container" style="margin-top:60px;">
         <div class="jumbotron">
            <p class="lead">
              Haz terminado con éxito la configuración de tu cuenta
            </p>
           <span class="badge badge-warning">* En cualquier momento se podrá regresar a la configuración de cada apartado desde el portal para realizar modificaciones.</span>
            <hr class="my-4">
            <h3>Siguientes pasos:</h3>
            <p>
              1) Descarga desde Play Store la aplicación “SONDEALO ENCUESTADOR”  en tus Tablet(s) Android que utilizaras para encuestar a tus comensales e ingresa con el nombre de la sucursal y su contraseña que asignaste anteriormente para acceder a la encuesta de la sucursal.
              Sondealo te permite tener las Tablets de encuestas que desees por sucursal sin costo adicional. Todas ingresarían a la aplicación “Sondealo Encuestador” con el mismo nombre de sucursal y contraseña. Se recomienda una Tablet por cada 5 mesas, esto con el fin que no hagan falta a la hora de entregar la cuenta.
              <br/><br/>
              2) El usuario Titular y los usuarios adicionales podrán ingresar con su usuario y contraseña desde la página web sondealo.com en iniciar sesión o desde su dispositivo móvil (Android o IPhone) en la aplicación “SONDEALO” “logo” disponible en Play Store o App Store para tener acceso a la administración, reportes, resultados y alertas de las encuestas contestadas por los comensales en tiempo real.
              <br/><br/>
              • Los usuarios adicionales únicamente tendrán acceso a las facultadas y sucursales asignadas por el usuario titular.

            </p>
            <a class="btn btn-primary " href="{{route('mostrar_home')}}" role="button">Ir a la página principal</a>
            <a class="btn btn-danger" href="{{route('cerrar_sesion')}}" role="button">Cerrar sesión</a>
          </div>
       </div>

      </div>
    </body>
</html>
