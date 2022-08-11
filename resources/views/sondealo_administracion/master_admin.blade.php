<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
        <meta content="{{ csrf_token() }}" name="csrf-token"/>
        <link rel="shortcut icon" type="image/x-icon" href="{{asset('icons/sondealo.ico')}}" />
        <title>@yield('title')</title>
        <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
        <link href="{{asset('css/metisMenu.min.css')}}" rel="stylesheet">
        <link href="{{asset('css/master_logged.css')}}?{{rand(10,200)}}" rel="stylesheet">
        <link href="{{asset('css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
        @yield('css')
    </head>
    <body>
        <div id="wrapper">
            <nav class="navbar sondealo-color navbar-fixed-top" style="box-shadow:0px -2px 8px 1px #000000;"role="navigation">
                <div class="navbar-header">
                    <a class="navbar-brand" href="{{route('adm_mostrar_home')}}">
                      <img src="{{asset('images/sondealogo.png')}}"/>
                    </a>
                </div>

                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                   <span class="sr-only">Toggle navigation</span>
                   <span class="icon-bar"></span>
                   <span class="icon-bar"></span>
                   <span class="icon-bar"></span>
               </button>

                <ul class="nav navbar-right navbar-top-links">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-user fa-fw"></i><span  id="text-username">{{Session::get('user')}}</span> <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li>
                              <a href="{{route('cerrar_sesion')}}"><i class="fa fa-sign-out fa-fw"></i>Cerrar Sesión</a>
                            </li>

                        </ul>
                    </li>
                </ul>

                <!-- menu lateral -->
                <div class="navbar-default sidebar" role="navigation">
                    <div class="sidebar-nav navbar-collapse">
                        <ul class="nav" id="side-menu">
                            {!! $menu !!}
                        </ul>
                    </div>
                </div>
            </nav>

            <div id="page-wrapper">
                <div class="container-fluid">
                  @if($errors->any())
                    <div class="alert alert-warning" role="alert" style="margin-top:1rem;">{{ $errors->first()}}</div>
                  @endif

                  @if(Session::has('msg_success'))
                    <div class="alert alert-success" role="alert" style="margin-top:1rem;">
                      {{ Session::get('msg_success')}}
                    </div>
                  @endif

                  @yield('content')
                </div>
            </div>
        </div>

        <div id="loader">
          <img src="{{asset('images/loader.gif')}}"/>
        </div>


        <div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">@yield('modal-title')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                @yield('modal-body')
              </div>
              <div class="modal-footer">
                {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button> --}}
                {{-- <button type="button" id="btn-save-edit" class="btn btn-primary">Guardar</button> --}}
              </div>
            </div>
          </div>
        </div>

        <script src="{{asset('js/jquery.min.js')}}"></script>
        <script src="{{asset('js/bootstrap.min.js')}}"></script>
        <script src="{{asset('js/metisMenu.min.js')}}"></script>
        <script src="{{asset('js/master_logged.js')}}?{{rand(10,200)}}"></script>
        <script src="{{asset('js/sweetalert29.js')}}"></script>
        <script type="text/javascript">

        const CSRF_TOKEN = "{{csrf_token()}}";
        const URL_FIJAR_SUCURSAL = "{{route('fijar_sesion_sucursal')}}";
        const PATRON_LETRAS_NUMEROS = new RegExp('^[A-Za-z0-9\\s]*$');
        const PATRON_USUARIO = new RegExp('^[A-Za-z0-9]*$');
        const PATRON_NUMEROS = new RegExp('^[0-9]+$');
        const PATRON_EMAIL = new RegExp(/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/);

        function showLoader(){
          document.getElementById('loader').style.display = 'inherit';
        }
        function hideLoader(){
          document.getElementById('loader').style.display = 'none';
        }

        function showModal(){
          $('#modal-edit').modal('show');
        }
        function hideModal(){
          $('#modal-edit').modal('hide');
        }
        </script>
        @yield('js')
    </body>
</html>
