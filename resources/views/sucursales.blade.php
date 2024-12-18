@extends('master.logged')

@section('title')
  Sucursales
@endsection

@section('css')
  <link rel="stylesheet" href="{{asset('css/sucursales.css')}}"/>
  <style type="text/css">

  .modal {
    text-align: center;
  }

  @media screen and (min-width: 768px) {
    .modal:before {
      display: inline-block;
      vertical-align: middle;
      content: " ";
      height: 100%;
    }
  }

  .modal-dialog {
    display: inline-block;
    text-align: left;
    vertical-align: middle;
  }
  .modal-body{
    padding: 1rem;
    position: relative;
  }

   .slider{
     height: 350px;
   }

  .slider div{
    position:relative;
    width: 100%;
    height: 100%;
    overflow: hidden;
  }

  .slider div img{
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    max-width: 99%;
    max-height: 99%;
  }

  #btn-close-modal-tour:hover .sp{
    opacity: 1;
  }

  </style>
@endsection

@section('content')

  {{-- MOSTRAR SOLO CUANDO Session::get('int_tour') == 1
  aparecera solo cuando la variable de sesion de tour este habilitada (cuenta nueva) --}}
  {{-- @if(Session::get('int_tour') == 1)
    <div class="alert alert-success" role="alert">
      <span class="tour-span">Paso 1.</span>
      Configura tu(s) sucursal(es) asignándole su nombre, contraseña y nombre comercial.
      <label class="tour-links-container">
        @if(count($sucursales) > 0)
          <a class="fa fa-arrow-right tour-link" href="{{route('mostrar_encuesta')}}"></a>
        @else
          <a class="fa fa-arrow-right tour-link tour-link-disabled"></a>
        @endif
      </label>
    </div>
  @endif --}}
  {{-- /////////////////////////////////////////////////////////////////////////////  --}}

  <div class="container-form-add">
    <div class="contenedor-superior">

        @if($boolean_show_formulario)
            <form id="form-add-sucursal" action="{{route('nueva_sucursal')}}" method="post" class="form-group-sm" onsubmit="return false;">
              @csrf
              <h4>Agregar Sucursal</h4>
              <input type="text" maxlength="50" name="name" value="{{old('name')}}" id="name" class="form-control" placeholder="Nombre"/>
              <input type="text" maxlength="30" name="password" value="{{old('password')}}" id="password" class="form-control" placeholder="Contraseña"/>
              <input type="text" maxlength="60" name="comercial" value="{{old('comercial')}}" id="comercial" class="form-control" placeholder="Nombre Comercial"/>

              <div class="separador"></div>

          {{-- si el plan es gratis solo mostrar el checkbox de comida para llevar etc --}}

            @if(Session::get('plan') == 2)

              <div class="form-check">
                <input class="form-check-input" type="radio" name="giro" id="giro_1" value="1" checked>
                <label class="form-check-label" for="giro_1">
                  Comida p/ llevar, Gimnasios, Hoteles
                </label>
              </div>

            @else
              {{-- si es plan premium --}}
               <div class="form-check">
                 <input class="form-check-input" type="radio" name="giro" id="giro_1" value="1">
                 <label class="form-check-label" for="giro_1">
                   Comida p/ llevar, Gimnasios, Hoteles
                 </label>
               </div>

                <div class="form-check">
                  <input class="form-check-input" type="radio" name="giro" id="giro_2" value="2" checked>
                  <label class="form-check-label" for="giro_2">
                    Restaurante c/ comensales
                  </label>
                </div>
            @endif


              <div class="separador"></div>
              <button class="btn btn-primary btn-sm" id="btn-save">Guardar</button>
            </form>
        @else
          <div class="alert alert-info" role="alert">
             Tu plan actual te permite un máximo de {{$limite_integer}} sucursales
          </div>
        @endif
      </div>
    </div>

    @if (count($sucursales) > 0)
      <div class="table-responsive">
        <table class="table table-sm table-hover">
          <thead class="thead-styles">
          <tr>
            <th scope="col">Nombre</th>
            <th scope="col">Contraseña</th>
            <th scope="col">Nombre comercial</th>
            <th scope="col">Giro</th>
            <th scope="col">Acción</th>
          </tr>
        </thead>
        <tbody class="bg-light">
          @foreach ($sucursales as $suc_index)
            <tr>
              <td>{{$suc_index->sucursal}}</td>
              <td>{{$suc_index->pass}}</td>
              <td>{{$suc_index->empresa}}</td>
              <td>{{($suc_index->tipousr == 1) ? 'Comida p/ llevar, Gimnasios, Hoteles' : ''}}   {{( $suc_index->tipousr == 2) ? 'Restaurante c/ comensales': '' }} {{($suc_index->tipousr == 4) ? 'ventas' : ''}}</td>
              <td data-id="{{$suc_index->id}}" class="fa fa-edit text-info cursor-pointer updel" data-accion="edit"></td>
              <td data-id="{{$suc_index->id}}" data-s="{{$suc_index->sucursal}}" class="fa fa-trash-o text-danger cursor-pointer updel" data-accion="del"></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif


    {{-- MOSTRAR SOLO CUANDO Session::get('int_tour') == 1
    aparecera solo cuando la variable de sesion de tour este habilitada (cuenta nueva) --}}
    @if(Session::get('int_tour') == 1)
      <div class="alert alert-success" style="padding-bottom:5rem;" role="alert">
        <span class="tour-span"><strong>Paso número 1.</strong></span>

        <p style="font-size:2rem;">
          @if(count($sucursales) > 0)
              Sucursal agregada con éxito
          @else
            Configura tu cuenta agregando el nombre de tu negocio, una contraseña y nombre comercial.
          @endif
        </p>
        <label class="tour-links-container" style="float:right;">
          @if(count($sucursales) > 0)
            <a class="tour-link" data-toggle="tooltip" data-placement="top" title="Da clic aquí para continuar " href="{{route('mostrar_encuesta')}}">Edita tu encuesta  <span class="fa fa-arrow-right"></span></a>
          @endif
        </label>

      </div>


      <!-- Modal -->
      <div class="modal fade" id="modal-inicio-tour" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">

            <div class="modal-body">

              <div class="slider">

                <div>
                  <img src="https://sondealo.com/assets/images/Capa-2-min.png"/>
                </div>
                <div>
                  <img src="https://sondealo.com/assets/images/testimonials/baltico-min.jpg"/>
                </div>
                <div>
                  <img src="https://sondealo.com/assets/images/capa-7-min.png"/>
                </div>
                <div style="height:100%;display:flex;justify-content:center;">
                  <button id="btn-close-modal-tour" class="btn btn-block btn-secondary">Comenzar a configurar mi cuenta (click)</button>
                </div>

              </div>

            </div>
            <div class="modal-footer">
              <div class="row">
                  <div class="col-md-6">
                    <button style="display:block;margin:auto;" class="btn btn-primary prev" aria-hidden="true"><i class="fa fa-arrow-circle-left"></i> </button>
                  </div>
                  <div class="col-md-6">
                    <button style="display:block;margin:auto;" class="btn btn-success next" aria-hidden="true"> <i class="fa fa-arrow-circle-right"></i></button>
                  </div>
              </div>
            </div>

          </div>
        </div>
      </div>


    @endif
    {{-- /////////////////////////////////////////////////////////////////////////////  --}}




@endsection


@section('modal-title')
  Modificar información
@endsection
@section('modal-body')

  <input type="hidden" id="sucursal_i" value=""/>
  <small>Sucursal</small>
  <input type="text" id="sucursal_str" disabled/>
  <small>Nombre Comercial</small>
  <input type="text" maxlength="60" placeholder="Nombre" id="sucursal_nombre"/>
  <small>Contraseña</small>
  <input type="text" maxlength="30" placeholder="Contraseña" id="sucursal_contrasena"/>
  <small>Giro Negocio</small>
  <select id="sucursal_giro">
    <option value="1">Comida para llevar/Gimnasios/Hoteles</option>
    <option value="2">Restaurante c/ comensales</option>
    <option value="4">Ventas</option>
  </select>

@endsection

@section('js')


  @if(Session::get('int_tour') == 1 && count($sucursales) < 1)

    <link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function(){
      $('#modal-inicio-tour').modal({backdrop: 'static', keyboard: false});
    });

    $('.slider').slick({
       dots: true,
       prevArrow: $('.prev'),
       nextArrow: $('.next'),
       infinite: false,
       speed: 300,
       slidesToShow: 1,
       slidesToScroll: 1,
       autoplay: false,
       autoplaySpeed: 5000,
    });

    document.getElementById('btn-close-modal-tour').addEventListener('click', function(){
      $('#modal-inicio-tour').modal('hide');
    });

    </script>


  @endif



    @if($boolean_show_formulario)

    <script type="text/javascript">


      document.getElementById('name').addEventListener('keypress', validarCaracteres);

    function validarCaracteres(ev)
    {
      if(!PATRON_USUARIO.test(ev.key)){
        return ev.preventDefault();
      }
    }
    document.getElementById('btn-save').addEventListener('click', fnValidateSub);
    function fnValidateSub()
    {
      let txt_name = document.getElementById('name');
      let txt_password = document.getElementById('password');
      let txt_comercial = document.getElementById('comercial');
      let radio_giro = document.querySelector('[name="giro"]:checked');

      let name = txt_name.value.trim();
      let password = txt_password.value.trim();
      let comercial = txt_comercial.value.trim();
      let giro = radio_giro.value;

      if(name != '' && password != '' && comercial != '' && giro != '')
      {
        if(!PATRON_USUARIO.test(name)){
          return Swal.fire({icon:'info', text:'El nombre de sucursal puede tener letras, números y sin espacios'});
        }
        if(name.length < 4){
          return Swal.fire({icon:'info', text:'El nombre de sucursal debe tener mínimo 4 caracteres'});
        }
        showLoader();
        document.getElementById('form-add-sucursal').submit();
      }
    }

    </script>
  @endif

  <script type="text/javascript">

  let icon_updel = document.querySelectorAll('.updel');
  for(let i=0;i<icon_updel.length;i++)
  {
    icon_updel[i].addEventListener('click', capturarAccion);
  }
  function capturarAccion()
  {
    let accion = this.dataset.accion;
    let id = this.dataset.id;
    if(accion == 'edit')
    {
      showLoader();

      fetch("{{route('info_sucursal')}}",{
        method:'post',
        body:JSON.stringify({"id":id}),
        headers:{
          'Content-Type':'application/json',
          'X-CSRF-TOKEN': CSRF_TOKEN
        }
      }).then(res => res.json())
      .then(function(response)
      {
        if(response.status == 200)
        {
          document.getElementById('sucursal_i').value = response.info.id;
          document.getElementById('sucursal_str').value = response.info.sucursal;
          document.getElementById('sucursal_nombre').value = response.info.empresa;
          document.getElementById('sucursal_contrasena').value = response.info.pass;

          let opciones_select = document.querySelectorAll('#sucursal_giro option');

          for (var i = 0; i < opciones_select.length; i++) {
            if(opciones_select[i].value == response.info.tipousr)
            {
              opciones_select[i].setAttribute('selected', 'selected');
            }
          }

          showModal();
        }
        hideLoader();
      });
    }
    else if(accion == 'del')
    {
      if(confirm("¿Estás seguro?, los cambios son irreversibles"))
      {
        let s = this.dataset.s;
        let curr_elem = this;

        showLoader();

        fetch("{{route('eliminar_sucursal')}}",{
          method:'post',
          body:JSON.stringify({"id":id, "s": s}),
          headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
          }
        }).then(res => res.json())
        .then(function(response){
          let _icon = 'info';
          if(response.status == 200){
            _icon = 'success';
            curr_elem.parentNode.remove();
            setTimeout(function(){location.reload();}, 400);
          }
          hideLoader();
          return Swal.fire({icon:_icon, text:response.msg});
        });
      }
    }
  }

  document.getElementById('btn-save-edit').addEventListener('click', updateInfo);

  function updateInfo()
  {
    let id= document.getElementById('sucursal_i').value;
    let comercial= document.getElementById('sucursal_nombre').value.trim();
    let password= document.getElementById('sucursal_contrasena').value.trim();
    let giro = document.getElementById('sucursal_giro').value;

    if(id == '' || comercial == '' || password == '' || giro == ''){
      return Swal.fire({icon:'warning', text:'Llene todos los campos'});
    }

    showLoader();
    hideModal();

    fetch("{{route('actualizar_sucursal')}}",{
      method:'post',
      body:JSON.stringify({"id":id, "comercial":comercial, "password":password, "giro": giro}),
      headers:{
        'Content-Type':'application/json',
        'X-CSRF-TOKEN': CSRF_TOKEN
      }
    }).then(res => res.json())
    .then(function(response){

      let _icon = 'info';
      if(response.status == 200){
        _icon = 'success';
        setTimeout(function(){location.reload();},400);
      }
      hideLoader();
      return Swal.fire({
        icon : _icon,
        text : response.msg
      });
    });
  }
  </script>
@endsection
