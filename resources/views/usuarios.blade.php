@extends('master.logged')

@section('title')
  Usuarios adicionales
@endsection

@section('css')
  <link rel="stylesheet" href="{{asset('css/usuarios.css')}}"/>
@endsection

@section('content')
  <div class="contenedor-superior">
  {{-- {!!$sucursales_html!!} --}}
    <div class="container-form-add">
      <form id="form-add-user" class="input-group-sm mb-3" action="{{route('nuevo_usuario')}}" method="post" onsubmit="return false;">
        @csrf
        <h4>Agregar Usuario</h4>
        <input class="form-control" type="text" id="user" name="user" placeholder="Usuario" value="{{old('user')}}" maxlength="30" required/>
        <input class="form-control" type="password" id="password" name="password" placeholder="Contraseña" value="{{old('password')}}" maxlength="30" required/>
        <input class="form-control" type="email" id="email" name="email" placeholder="Correo Electrónico" value="{{old('email')}}" maxlength="100"/>
        <button class="btn btn-primary btn-sm" id="btn-save-user">Guardar</button>
      </form>
    </div>
  </div>

  <table class="table table-sm table-hover">
    <thead class="thead-styles">
      <tr>
        <th>Usuario</th>
        <th>Correo Electrónico</th>
        <th colspan="2">Acción</th>
      </tr>
    </thead>
    <tbody class="bg-light">
      @foreach ($arr_usuarios as $usuario)
        <tr>
          <td>{{$usuario->usuario}}</td>
          <td>{{($usuario->correo == '')?'Sin correo':$usuario->correo}}</td>
          <td data-id="{{$usuario->id}}" data-action="edit" class="fa fa-edit text-info cursor-pointer updel"></td>
          <td data-id="{{$usuario->id}}" data-action="del" class="fa fa-trash-o text-danger cursor-pointer updel"></td>
        </tr>
      @endforeach
    </tbody>
  </table>
  {{$arr_usuarios->links()}}
@endsection

@section('modal-title')
  Asignar facultades a usuario y recuperación de contraseña
@endsection

@section('modal-body')
  <div class="inner-body contendor-facultades" id="contenedor-facultades">

    <h5>Recuperar contraseña</h5>
    <input type="text" id="txt-reset-password" style="display: inline-block;margin-top: 5px;height:30px;" maxlength="20" placeholder="Nueva contraseña"/>
    <button class="btn btn-success btn-sm" id="btn-restore-password">Restaurar contraseña</button>


    <h5>Facultades</h5>

    <div class="form-check">
      <input class="form-check-input" type="checkbox" value="" id="f1">
      <label class="form-check-label" for="f1">
        Historial cupones, reportes
      </label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" value="" id="f2">
      <label class="form-check-label" for="f2">
        Enviar encuesta Whatsapp
      </label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" value="" id="f3">
      <label class="form-check-label" for="f3">
        Editar/Agregar vendedores
      </label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" value="" id="f4">
      <label class="form-check-label" for="f4">
        Editar promociones
      </label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" value="" id="f5">
      <label class="form-check-label" for="f5">
        Editar cupones
      </label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" value="" id="f6">
      <label class="form-check-label" for="f6">
        Editar encuesta
      </label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" value="" id="f7">
      <label class="form-check-label" for="f7">
        Correos clientes
      </label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" value="" id="f8">
      <label class="form-check-label" for="f8">
        Alertas
      </label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" value="" id="f9">
      <label class="form-check-label" for="f9">
        Validar cupon
      </label>
    </div>
    <div class="form-check" style="display:none;">
      <input class="form-check-input" type="checkbox" value="" id="f10">
      <label class="form-check-label" for="f10">
        Asignar repartidores
      </label>
    </div>
  </div>

  <div class="inner-body contendor-sucursales" id="contenedor-sucursales">
    <h5>Sucursales</h5>
    @foreach ($arr_sucursales as $suc)
      <div class="form-check">
        <input class="form-check-input" type="checkbox" value="{{$suc->sucursal}}" id="sucursal_{{$suc->sucursal}}">
        <label class="form-check-label" for="sucursal_{{$suc->sucursal}}">
          {{$suc->sucursal}}
        </label>
      </div>
    @endforeach
  </div>

@endsection

@section('js')
  <script type="text/javascript">

  document.getElementById('user').addEventListener('keypress', function(ev){
    if(!PATRON_USUARIO.test(ev.key)){
      ev.preventDefault();
      return false;
    }
  });

  document.getElementById('btn-save-user').addEventListener('click', validarUser);

  function validarUser()
  {
    let txt_user   = document.getElementById('user');
    let txt_pass   = document.getElementById('password');
    let txt_correo = document.getElementById('email');

    let user = txt_user.value.trim();
    let pass = txt_pass.value.trim();
    let email = txt_correo.value.trim();

    if(user == '' || pass == '')
    {
      return Swal.fire({
        icon:'warning',
        text:'Usuario y contraseña son obligatorios'
      });
    }

    if(!PATRON_USUARIO.test(user)){
      return Swal.fire({
        icon:'warning',
        text:'Usuario no valido, solo letras y números sín espacios'
      });
    }

    if(email != '')
    {
      if(!PATRON_EMAIL.test(email)){
        return Swal.fire({
          icon:'warning',
          text:'El correo electrónico es opcional, pero debe ser valido'
        });
      }
    }
    return document.getElementById('form-add-user').submit();
  }


  let rows_updel = document.querySelectorAll('.updel');
  for (var i = 0; i < rows_updel.length; i++) {
    rows_updel[i].addEventListener('click', catchAccion);
  }

  function catchAccion()
  {
    var id= this.dataset.id;
    let accion = this.dataset.action;

    if(accion == 'del')
    {
      let element = this;
      if(confirm("Los cambios son irreversibles, ¿Éstas seguro?"))
      {
        showLoader();

        fetch("{{route('eliminar_usuario')}}",{
          method:'post',
          body:JSON.stringify({"usuario":id}),
          headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':CSRF_TOKEN
          }
        }).then(res => res.json())
        .catch(error => console.log(error))
        .then(function(response){
          let _icon = 'info';
          if(response.status == 200){
            _icon= 'success';
            setTimeout(function(){location.reload();}, 400);
          }
          Swal.fire({
            icon:_icon,
            text:response.msg
          });
          element.parentNode.remove();
          hideLoader();
        });
      }


    }
    else if(accion == 'edit')
    {
      $('.modal-body input[type="checkbox"]').each(function(){
        $(this).prop('checked', false);
      });

      showLoader();

      fetch("{{route('actualizar_usuario')}}",{
        method:'post',
        body:JSON.stringify({"id":id}),
        headers:{
          'Content-Type':'application/json',
          'X-CSRF-TOKEN':CSRF_TOKEN
        }
      }).then(res => res.json())
      .catch(error => console.log(error))
      .then(function(response)
      {
        if(response.status != 200){
          return Swal.fire({
            icon:'info',
            text:response.msg
          });
        }
        localStorage.setItem("usuario", id);

        let str_sucs = response.info.sucs;
        let arr_sucursales = (str_sucs != '' && str_sucs != null) ? str_sucs.split(','):[];

        let checkbox_sucursales = document.querySelectorAll('#contenedor-sucursales input[type="checkbox"]');
        for(let i=0;i<arr_sucursales.length;i++)
        {
          for(let j=0;j<checkbox_sucursales.length;j++)
          {
            if(arr_sucursales[i] == checkbox_sucursales[j].value){
              checkbox_sucursales[j].checked = true;
            }
          }
        }
        $('#f1').prop('checked', (response.info.f1 == 1) ? true : false);
        $('#f2').prop('checked', (response.info.f2 == 1) ? true : false);
        $('#f3').prop('checked', (response.info.f3 == 1) ? true : false);
        $('#f4').prop('checked', (response.info.f4 == 1) ? true : false);
        $('#f5').prop('checked', (response.info.f5 == 1) ? true : false);
        $('#f6').prop('checked', (response.info.f6 == 1) ? true : false);
        $('#f7').prop('checked', (response.info.f7 == 1) ? true : false);
        $('#f8').prop('checked', (response.info.f8 == 1) ? true : false);
        $('#f9').prop('checked', (response.info.f9 == 1) ? true : false);
        $('#f10').prop('checked',(response.info.f10 == 1) ? true : false);
        $('#modal-edit').modal('show');
        hideLoader();
      });
    }
  }

  document.getElementById('btn-save-edit').addEventListener('click', guardarEdicionFacultades);
  function guardarEdicionFacultades()
  {
    let f1 = ($('#f1').prop('checked'))? 1 : 0;
    let f2 = ($('#f2').prop('checked'))? 1 : 0;
    let f3 = ($('#f3').prop('checked'))? 1 : 0;
    let f4 = ($('#f4').prop('checked'))? 1 : 0;
    let f5 = ($('#f5').prop('checked'))? 1 : 0;
    let f6 = ($('#f6').prop('checked'))? 1 : 0;
    let f7 = ($('#f7').prop('checked'))? 1 : 0;
    let f8 = ($('#f8').prop('checked'))? 1 : 0;
    let f9 = ($('#f9').prop('checked'))? 1 : 0;
    let f10 = ($('#f10').prop('checked'))? 1 : 0;

    let arreglo_sucs = [];
    $('#contenedor-sucursales input[type="checkbox"]:checked').each(function(){
      arreglo_sucs.push($(this).val());
    });
    showLoader();
    fetch("{{route('actualizar_facultades_usuario')}}", {
      method:'post',
      body:JSON.stringify({"usuario":localStorage.getItem("usuario"),"sucursales":arreglo_sucs, "f1":f1,"f2":f2,"f3":f3,"f4":f4,"f5":f5,"f6":f6,"f7":f7,"f8":f8,"f9":f9,"f10":f10 }),
      headers:{
        'Content-Type':'application/json',
        'X-CSRF-TOKEN':CSRF_TOKEN
      }
    }).then(res => res.json())
    .catch(error => console.log(error))
    .then(function(response){
      let _icon= 'info';
      if(response.status == 200){
        _icon = 'success';
        setTimeout(function(){location.reload();}, 500);
      }
      Swal.fire({
        icon:_icon,
        text:response.msg
      });
      hideLoader();
    });
  }

  document.addEventListener("DOMContentLoaded", function(event) {
      localStorage.removeItem("usuario");
    });

  document.getElementById('txt-reset-password').addEventListener('keypress', function(e){
    let espacio = new RegExp('^[\\s]*$');
    if(espacio.test(e.key)){
      e.preventDefault();
      return false;
    }
  });

  document.getElementById('btn-restore-password').addEventListener('click', resetPassword);
  function resetPassword()
  {
    let txt_reset_password = document.getElementById('txt-reset-password');
    let new_password = txt_reset_password.value.trim();
    if(new_password.length < 4){
      return  Swal.fire({icon:'info', text:'La nueva contraseña debe contener al menos 4 caracteres'});
    }

    if(localStorage.getItem("usuario") == null){
      return;
    }

    showLoader();

    fetch("{{route('restablecer_pass_by_admin')}}",{
      method:'post',
      body:JSON.stringify({"usuario" : localStorage.getItem("usuario"), "pass":new_password}),
      headers:{
        'Content-Type':'application/json',
        'X-CSRF-TOKEN':CSRF_TOKEN
      }
    }).then(res => res.json())
    .then(function(response){
      let _icon = 'info';
      if(response.status == 200){
        _icon = 'success';
        txt_reset_password.value = '';
      }

      hideLoader();
      return Swal.fire({icon:_icon, text:response.msg});
    });
  }
  </script>
@endsection
