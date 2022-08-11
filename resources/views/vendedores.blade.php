@extends('master.logged')

@section('css')
  <link rel="stylesheet" href="{{asset('css/vendedores.css')}}"/>
@endsection

@section('title')
  Vendedores
@endsection

@section('content')
  <div class="contenedor-superior">

  {!!$sucursales_html!!}

  @if($sucursal_url != '')
    <div class="container-form-add">

        {{-- MOSTRAR SOLO CUANDO Session::get('int_tour') == 1
        aparecera solo cuando la variable de sesion de tour este habilitada (cuenta nueva) --}}
        @if(Session::get('int_tour') == 1)
          @if ($boolean_agregar_vendedores)
            <div class="alert alert-success" role="alert">
              <span class="tour-span">Paso 3.</span>
              Agrega vendedores dentro de la sucursal actual.
              <label class="tour-links-container">
                <a class="fa fa-arrow-left tour-link" href="{{route('mostrar_encuesta')}}"></a>
                @if(count($arr_vendedores) > 0)
                  <a class="fa fa-arrow-right tour-link" href="{{route('mostrar_promociones')}}"></a>
                @else
                  <a class="fa fa-arrow-right tour-link tour-link-disabled"></a>
                @endif
              </label>
            </div>
          @else
            {{-- se mostrara en tour --}}
            <div class="alert alert-success" role="alert">
              <span class="tour-span">Paso 3.</span>
                  Omitir este paso
                  <a class="fa fa-arrow-right tour-link" href="{{route('mostrar_promociones')}}"></a>
            </div>
          @endif
        @endif
        {{-- ///////////////////////////////////////////////////////////////////////////////////// --}}
      @if($boolean_agregar_vendedores)

        <form id="form-add-vendedor" class="form-add form-group-sm" action="{{route('nuevo_vendedor')}}" method="post" onsubmit="return false;">
          @csrf
          <input type="hidden" name="s" value="{{Session::get('sucursal_fijada')}}"/>
          <h4>Agregar Vendedor</h4>
          <input type="text" class="form-control" name="name" id="name" value="{{old('name')}}" placeholder="Nombre"/>
          <input type="text" class="form-control" name="password" id="password" placeholder="Contraseña"/>
          <button id="btn-save" name="btn-save" class="btn btn-primary btn-sm" style="max-height:30px;">Guardar</button>
        </form>
        {{-- ////////////////////////////////////////////////////////////////////////// --}}
      @endif

    </div>
  @endif

  </div>

  @if($boolean_agregar_vendedores)

      @if(count($arr_vendedores) > 0)
        <div class="table-responsive">
          <table class="table table-sm table-hover">
            <thead class="thead-styles">
            <tr>
              <th scope="col">Nombre</th>
              <th scope="col">Contraseña</th>
              <th scope="col" colspan="2">Acción</th>
            </tr>
          </thead>
          <tbody class="bg-light">
            @foreach ($arr_vendedores as $vendedor)
              <tr>
                <td class="text-lowercase">{{ $vendedor->nombre}}</td>
                <td>{{ $vendedor->clave}}</td>
                <td class="fa fa-edit text-info cursor-pointer updel" data-action="edit" data-id="{{$vendedor->id}}"></td>
                <td class="fa fa-trash-o text-danger cursor-pointer updel" data-action="del" data-id="{{$vendedor->id}}"></td>
              </tr>
            @endforeach
          </tbody>
          </table>
        </div>
       @else
         <div class="alert alert-info" role="alert">
            Sin información
         </div>
       @endif

    @else
      <div class="alert alert-info" role="alert">
        No se pueden agregar vendedores en esta sucursal
      </div>
    @endif

@endsection

@section('modal-title')
  Editar Vendedor
@endsection

@section('modal-body')
  <div class="form-group" role="form">
    <input type="hidden" id="vendedor" value=""/>
    <small>Nombre</small>
    <input type="text" id="txt-name" value="" maxlength="90" placeholder="Nombre" class="form-control"/>
    <small>Contraseña</small>
    <input type="text" id="txt-password" value="" maxlength="30" placeholder="Contraseña" class="form-control"/>
  </div>
@endsection

@section('js')
  <script type="text/javascript">

    if(document.body.contains(document.getElementById('password'))){
      document.getElementById('password').addEventListener('keypress', function(ev){
        let esp = new RegExp('^[\\s]*$');
        if(esp.test(ev.key)){
          ev.preventDefault();
          return;
        }
      });
    }

    let updel = [];
    updel = document.querySelectorAll('.updel');
    if(updel.length > 0){
      for(let i=0;i<updel.length;i++)
      {
        updel[i].addEventListener('click', fnAccion);
      }
    }

    function fnAccion()
    {
      let accion = this.dataset.action;
      let id     = this.dataset.id;
      if(accion == '' || id == ''){
        return;
      }

      if(accion == 'edit')
      {
        showLoader();

        fetch("{{route('info_vendedor')}}", {
          method:'post',
          body:JSON.stringify({"vendedor": id}),
          headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':CSRF_TOKEN
          }
        }).then(res => res.json())
        .catch(error => console.log(error))
        .then(function(response){
          if(response.status == 200)
          {
            document.getElementById('vendedor').value     = id;
            document.getElementById('txt-name').value     = response.info.nombre;
            document.getElementById('txt-password').value = response.info.password;
            showModal();
          }
          else if(response.status == 205){
            location.reload();
          }
          else
          {
            Swal.fire({
              icon:'info',
              text:response.msg
            });
          }
          hideLoader();
        });

      }
      else if(accion == 'del')
      {
        var element = this;
        if(confirm("La acción es irreversible"))
        {
          showLoader();

          fetch("{{route('eliminar_vendedor')}}",{
            method:'post',
            body:JSON.stringify({"vendedor":id}),
            headers:{
              'Content-Type':'application/json',
              'X-CSRF-TOKEN':CSRF_TOKEN
            }
          }).then(res => res.json())
          .catch(error => console.log(error))
          .then(function(response){
            let _icon = 'info';
           if(response.status == 200){
              element.parentNode.remove();
              _icon = 'success';
            }
            Swal.fire({
              icon:_icon,
              text:response.msg
            });
            hideLoader();
          });
        }
      }
    }

    document.getElementById('btn-save-edit').addEventListener('click', fnSaveEdit);

    function fnSaveEdit() {
      let vendedor = document.getElementById('vendedor').value;
      let name = document.getElementById('txt-name').value.trim();
      let password = document.getElementById('txt-password').value.trim();

      if(vendedor !='' && name != '' && password != '')
      {
        if(isInteger(vendedor))
        {
          hideModal();
          showLoader();

          fetch("{{route('actualizar_vendedor')}}",{
            method:'post',
            body:JSON.stringify({"vendedor":vendedor, "name":name, "password":password}),
            headers:{
              'Content-Type':'application/json',
              'X-CSRF-TOKEN':CSRF_TOKEN
            }
          }).then(res => res.json())
          .catch(error => console.log(error))
          .then(function(response)
          {
             let _icon = 'info';
             if(response.status == 200){
               _icon = 'success';
               window.setTimeout(function(){location.reload();}, 500);
             }
             Swal.fire({
               icon:_icon,
               text:response.msg
             });
             hideLoader();
          });
        }
      }
    }


    if(document.body.contains(document.getElementById('btn-save')) ){
      let btn_save = document.getElementById('btn-save');
      btn_save.addEventListener('click', fnValidateSubmit);
    }

    function fnValidateSubmit()
    {
      let txt_name = document.getElementById('name');
      let txt_password = document.getElementById('password');
      if(txt_name.value.trim() != '' && txt_password.value.trim() != '')
      {
        showLoader();
        document.getElementById('form-add-vendedor').submit();
      }
    }
  </script>

@endsection
