<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content="encuestas,sondealo"/>
    <meta content="{{ csrf_token() }}" name="csrf-token"/>
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('icons/sondealo.ico')}}" />
    <title>Regístrate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link href="{{ asset('css/registro.css')}}" rel="stylesheet"/>
</head>

  <body>

    <div class="container-fluid">
      <div class="row">
        <div class="col-xxl-4 col-xl-3 col-lg-4 col-md-5 col-sm-6 col-12 p-4 position-relative" id="container-form">

          <form class="row" style="margin:auto;" id="form-registro"  autocomplete="off" action="{{ route('registrar_usuario')}}" method="post" onsubmit="return false;">
            @csrf()
            <div class="col-12 mb-2">
              <img src="https://sondealo.com/assets/images/logos/logo_h_1900_800_color.png" style="max-height:4rem;"/>
            </div>
            <div class="col-12 mb-4">
              <p class="h3 text-muted fw-bold">Regístrate</p>
              <p class="lh-1 small text-muted" >
                ¿Ya estas registrado?
                <a href="https://sondealo.com/sitio/entrar" class="sondealo-text-color">Ingresa desde aquí</a>
              </p>
            </div>

            @if($errors->any())
            <div class="alert alert-warning" role="alert">
              {{ $errors->first() }}
            </div>
            @endif

            <input type="hidden" value="{{$plan_id}}" name="plan_id" required/>

            <div class="col-12 mb-2 text-center text-light">
                @if($plan_id == 2)
                  <span class="small bg-success p-1 rounded">Plan gratuito</span>
                @else
                  {{-- <span class="small sondealo-bg-color p-1 rounded">Plan premium</span> --}}
                @endif
            </div>

            <div class="form-group col-md-12 mb-3">
              <small class="text-muted"></small>
              <input type="text" placeholder="Nombre completo" id="name" name="name" value="{{old('name')}}" class="form-control form-control-sm" maxlength="50" required/>
            </div>

            <div class="form-group col-md-12 mb-3">
              <small class="text-muted"></small>
              <input type="text" placeholder="Nombre de usuario" id="username" name="username" value="{{old('username')}}" class="form-control form-control-sm" maxlength="30" required/>
            </div>

            <div class="form-group col-md-12 mb-3">
              <small class="text-muted"></small>
              <input type="text" placeholder="Correo Electrónico" id="email" name="email" value="{{old('email')}}" class="form-control form-control-sm"  maxlength="100" required/>
            </div>

            <div class="form-group col-md-6 mb-3">
              <small class="text-muted"></small>
              <input type="phone" placeholder="Teléfono" id="phone" name="phone" value="{{old('phone')}}" class="form-control form-control-sm" maxlength="10" required/>
            </div>

            <div class="form-group col-md-6 mb-3">
              <small class="text-muted"></small>
              <input type="password" placeholder="Contraseña" id="password" name="password" value="{{old('password')}}" class="form-control form-control-sm" maxlength="20" required/>
            </div>

            <div class="form-group col-md-12">
              <input type="checkbox" name="terms" id="terms" />
              <a href="https://sondealo.com/terminos-y-condiciones" class="small text-muted " target="_blank">Acepto los términos de uso y la declaración de privacidad</a>
            </div>

            <div class="form-group col-md-6" style="margin-top:20px;">
              <button id="btn-registrar" class="btn btn-success btn-block sondealo-bg-color">Regístrarme</button>
            </div>

            {{-- <div class="form-group col-md-6" style="margin-top:20px;">
                 <button class="btn btn-danger btn-block" id="btn-registro-google"><i class="fab fa-google">  Ingresar con Google</i></button>
            </div> --}}

            <div class="form-group col-md-12" style="margin-top:30px;">
              <p class="text-muted text-center">&copy; Powered by CBAPP S.A de C.V | {{date('Y')}}</p>
            </div>
        </form>

        </div>
        <div class="col-xxl-8 col-xl-9 col-lg-8 col-md-7 col-sm-6 col-12 bg-light border back-img" id="container-main-img"
        style="background-image:url({{asset('images/login-main-banner.jpg')}})">
        </div>
      </div>
    </div>





    {{-- <button class="btn btn-primary" id="btn">Press</button> --}}













    <script src="{{asset('js/sweetalert29.js')}}"></script>
    <script type="text/javascript">

      const PATRON_LETRAS  = new RegExp('^[A-Za-zÁáÉéÍíÓóÚúÜüÑñ.\\s]*$');
      const PATRON_USUARIO = new RegExp('^[A-Za-z0-9]*$');
      const PATRON_NUMEROS = new RegExp('^[0-9]+$');
      const PATRON_EMAIL   = new RegExp(/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/);

      // document.getElementById('name').addEventListener('keypress', function(ev){
      //   if(!PATRON_LETRAS.test(ev.key)){
      //     ev.preventDefault();
      //     return false;
      //   }
      // });
      // document.getElementById('username').addEventListener('keypress', function(ev){
      //   if(!PATRON_USUARIO.test(ev.key)){
      //     ev.preventDefault();
      //     return false;
      //   }
      // });
      // document.getElementById('phone').addEventListener('keypress', function(ev){
      //   if(!PATRON_NUMEROS.test(ev.key)){
      //     ev.preventDefault();
      //     return false;
      //   }
      // });

      let inp = document.querySelectorAll('input');
      inp.forEach((item, i) => {
        item.addEventListener('keypress', function(ev){
          switch (item.getAttribute('id')) {
            case 'name':
              if(!PATRON_LETRAS.test(ev.key)){
                ev.preventDefault();
                return false;
              }
              break;
            case 'username':
              if(!PATRON_USUARIO.test(ev.key)){
                ev.preventDefault();
                return false;
              }
              break;
            case 'phone':
              if(!PATRON_NUMEROS.test(ev.key)){
                ev.preventDefault();
                return false;
              }
              break;
          }
        });
      });








      // (function(window, document){
      //
      //   var fn = function(){
      //     let elem,
      //         js = {
      //           byId:function(id){
      //             elem = document.getElementById(id);
      //             return this;
      //           },
      //           css:function(cssText){
      //             elem.style.cssText = cssText;
      //           }
      //         };
      //         return js;
      //    };
      //
      //    if(typeof window.lib === 'undefined'){
      //      window.lib  = __ = fn();
      //    }
      //
      // })(window, document);
      //
      // __.byId('btn').css('color:red;background-color:#00ffab;');









      // document.getElementById('btn-registro-google').addEventListener('click',function(){alert("registro google");});

      document.getElementById('btn-registrar').addEventListener('click', saveRegistro);

      function saveRegistro()
      {
        let name        = document.getElementById('name').value.trim();
        let username    = document.getElementById('username').value.trim();
        let email       = document.getElementById('email').value.trim();
        let phone       = document.getElementById('phone').value.trim();
        let password    = document.getElementById('password').value.trim();
        let check_terms = document.getElementById('terms');

        if(name == '' || username == '' || email == '' || password == ''){
          return Swal.fire({icon:'info', text:'Todos los campos son obligatorios'});
        }
        if( ! PATRON_LETRAS.test(name)){
          return Swal.fire({icon:'info', text:'El nombre no es valido'});
        }
        if( ! PATRON_USUARIO.test(username) || username.length < 5){
          return Swal.fire({icon:'info', text:'El nombre de usuario no es valido, (letras y números sin espacios mínimo 5 caracteres)'});
        }
        if( ! PATRON_EMAIL.test(email)){
          return Swal.fire({icon:'info', text:'El correo electrónico no es valido'});
        }
        if( ! PATRON_NUMEROS.test(phone) || phone.length != 10){
          return Swal.fire({icon:'info', text:'El teléfono no es valido'});
        }
        if( ! check_terms.checked){
          return Swal.fire({icon:'info', text:'¡Acepta los términos de uso!'});
        }
        document.getElementById('form-registro').submit();
      }

    </script>
  </body>
</html>
