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

            <nav class="navbar sondealo-color navbar-fixed-top" role="navigation">
                <div class="navbar-header">
                    <a class="navbar-brand" href="{{route('mostrar_home')}}">
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

                  {{-- <li class="dropdown">
                    @php
                    $lang = App::getLocale();
                    $arrLangs = [
                      'es' => ['prefix' => 'es' , 'string' => 'Español', 'image' => 'mexicoflag.png'],
                      'en' => ['prefix' => 'en' , 'string' => 'English', 'image' => 'britishflag.png'],
                    ];
                    @endphp

                      <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                          <i class="fa-fw">
                            <img style="height:15px;" src="{{asset('images')}}/{{$arrLangs[$lang]['image']}}"/>
                          </i>
                      </a>
                      <ul class="dropdown-menu">

                        @foreach ($arrLangs as $lng)
                          @if($lang != $lng['prefix'])
                           <li>
                             <a href="{{route('set_lenguaje',['lang' => $lng['prefix']])}}">
                               <i class="fa-fw">
                                 <img style="height:15px;" src="{{asset('images')}}/{{$lng['image']}}"/>
                                 &nbsp;{{$lng['string']}}
                               </i>
                             </a>
                           </li>
                         @endif
                        @endforeach

                      </ul>
                 </li> --}}



                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-user fa-fw"></i><span  id="text-username">{{Session::get('user')}}</span> <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="{{route('mostrar_ajustes')}}"><i class="fa fa-gear fa-fw"></i>@lang('master.ajustes-cuenta')</a>
                            </li>
                            <li class="divider"></li>
                            <li><a href="{{route('cerrar_sesion')}}"><i class="fa fa-sign-out fa-fw"></i>@lang('master.cerrar-session')</a>
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('master.btn-modal-cerrar')</button>
                <button type="button" id="btn-save-edit" class="btn btn-primary">@lang('master.btn-modal-guardar')</button>
              </div>
            </div>
          </div>
        </div>

        <script src="{{asset('js/jquery.min.js')}}"></script>

        <script src="{{asset('js/jquery-ui.min.js')}}"></script>
        <script src="{{asset('js/jquery.ui.touch-punch.min.js')}}"></script>

        <script src="{{asset('js/bootstrap.min.js')}}"></script>
        <script src="{{asset('js/metisMenu.min.js')}}"></script>
        <script src="{{asset('js/master_logged.js')}}?{{rand(10,200)}}"></script>
        <script src="{{asset('js/sweetalert29.js')}}"></script>

      <script type="text/javascript">

        const CSRF_TOKEN            = "{{csrf_token()}}";
        const URL_FIJAR_SUCURSAL    = "{{route('fijar_sesion_sucursal')}}";
        const PATRON_LETRAS_NUMEROS = new RegExp('^[A-Za-z0-9\\s]*$');
        const PATRON_USUARIO        = new RegExp('^[A-Za-z0-9]*$');
        const PATRON_NUMEROS        = new RegExp('^[0-9]+$');
        const PATRON_TEXTO          = new RegExp('^[A-Za-z0-9áÁéÉíÍóÓúÚñÑüÜ\\s]*$');
        const PATRON_EMAIL          = new RegExp(/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/);

        var selectSucursales= document.getElementById('sucursal');
        if(document.body.contains(selectSucursales)){
          selectSucursales.addEventListener('change', getSucursal);
        }

        var extras_url = '';
        if(document.body.contains(document.getElementById('extras_url'))){
          extras_url = document.getElementById('extras_url').value;
        }

        function getSucursal()
        {
          if(this.value.trim() != '' && this.value != -1)
          {
            if(this.value != "{{$sucursal_url}}")
            {
              showLoader();

              let sucursal = this.value;

              fetch(URL_FIJAR_SUCURSAL, {
                method:'post',
                body:JSON.stringify({'sucursal':sucursal}),
                headers:{
                  'Content-Type':'application/json',
                  'X-CSRF-TOKEN':CSRF_TOKEN
                }
              }).then(res => res.json())
              .catch(error => console.log(error))
              .then(function(response){
                if(response.status == 200){
                  window.location = "{{route(Request::route()->getName())}}/"+sucursal+extras_url;
                }
              });
            }
          }
        }

        function showLoader()
        {
          document.getElementById('loader').style.display = 'inherit';
        }
        function hideLoader()
        {
          document.getElementById('loader').style.display = 'none';
        }
        function showModal()
        {
          $('#modal-edit').modal('show');
        }
        function hideModal()
        {
          $('#modal-edit').modal('hide');
        }
        function validarTexto(ev)
        {
          if(!PATRON_TEXTO.test(ev.key)){
            ev.preventDefault();
            return false;
          }
        }

        </script>
        @yield('js')
    </body>
</html>
