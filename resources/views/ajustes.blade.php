@extends('master.logged')

@section('title')
  Ajustes
@endsection

@section('css')
  <link rel="stylesheet" href="{{asset('css/ajustes.css')}}"/>
@endsection

@section('content')
  <div class="main-container">

    <div>
      <form id="form-update-password" method="post" action="{{route('actualizar_contrasenia_usuario')}}" onsubmit="return false;" class="form-group-sm">
        @csrf
        <h4>Cambiar contraseña</h4>
        <small>Contraseña actual</small>
        <input class="form-control" type="password" value="" id="password_actual" name="password_actual"/>
        <small>Nueva contraseña</small>
        <input class="form-control" type="password" value="{{old('password_1')}}" id="password_1" name="password_1"/>
        <small>Confirmar nueva contraseña</small>
        <input class="form-control" type="password" value="{{old('password_2')}}" id="password_2" name="password_2"/>
        <input type="submit" id="btn-guardar-password" value="Cambiar contraseña" class="btn btn-primary btn-sm"/>
      </form>
    </div>

    <div style="position:relative;">
      <button id="btn-update-info" class="fa fa-edit"> Editar Información</button>
      <table class="table table-sm">
        <thead>
          <tr>
            <th colspan="2">Información</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Usuario</td>
            <td>{{Session::get('user')}}</td>
          </tr>
          <tr>
            <td>Nombre</td>
            <td>{{Session::get('nombre')}}</td>
          </tr>
          <tr>
            <td>Correo Electrónico</td>
            <td>{{Session::get('correo') ?: 'Sin correo electrónico' }}</td>
          </tr>
          <tr>
            <td>Teléfono</td>
            <td>{{Session::get('telefono') ?: 'Sin teléfono'}}</td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>
@endsection


@section('modal-title')
  Actualizar correo electrónico y/o teléfono de "{{Session::get('user')}}"
@endsection

@section('modal-body')
  <small>Correo Electrónico</small>
  <input type="email" id="txt-email-update" maxlength="50" value="{{Session::get('correo')}}" name="txt-email-update" class="form-control"/>
  <small>Teléfono</small>
  <input type="phone" id="txt-phone-update" maxlength="10" value="{{Session::get('telefono')}}" name="txt-phone-update" class="form-control"/>
@endsection

@section('js')
  <script type="text/javascript">

  document.getElementById('btn-guardar-password').addEventListener('click', saveNewPassword);

  function saveNewPassword()
  {
    let inp_password_actual = document.getElementById('password_actual');
    let inp_password_1 = document.getElementById('password_1');
    let inp_password_2 = document.getElementById('password_2');

    let password_1 = inp_password_1.value.trim();
    let password_2 = inp_password_2.value.trim();
    let password_actual = inp_password_actual.value.trim();

    if(password_1 == '' || password_2 == '' || password_actual == ''){
      return Swal.fire({icon:'info', text: 'llene todos los campos'});
    }
    if(password_1 != password_2){
      return Swal.fire({icon:'info', text: 'Las contraseñas nuevas no coinciden'});
    }
    if(password_1.length < 6){
      return Swal.fire({icon:'info', text: 'La contraseña debe contener al menos 6 caracteres'});
    }
    showLoader();
    document.getElementById('form-update-password').submit();
  }

  document.getElementById('btn-update-info').addEventListener('click', showModal);


  document.getElementById('btn-save-edit').addEventListener('click', updateUserInfo);

  function updateUserInfo()
  {
    let txt_email_update = document.getElementById('txt-email-update');
    let txt_phone_update = document.getElementById('txt-phone-update');

    let email = txt_email_update.value.trim();
    let phone = txt_phone_update.value.trim();

    if(email == ''){
      return Swal.fire({icon:'warning', text:'¡El correo electrónico es obligatorio!'});
    }
    if(!PATRON_EMAIL.test(email)){
      return Swal.fire({icon:'info', text:'El correo electrónico no es valido'});
    }

    if(phone != '')
    {
      if(phone.length != 10 || !PATRON_NUMEROS.test(phone))
      {
        return Swal.fire({icon:'info', text:'El telefóno no es valido'});
      }
    }

    hideModal();
    showLoader();

    fetch("{{route('actualizar_informacion_usuario')}}",{
      method:'post',
      body:JSON.stringify({"email":email, "phone":phone}),
      headers:{
        'Content-Type':'application/json',
        'X-CSRF-TOKEN':CSRF_TOKEN
      }
    }).then(res => res.json())
    .then(function(response){
      let _icon = 'info';
      if(response.status == 200){
        _icon = 'success';
        setTimeout(function(){location.reload();}, 300);
      }
      hideLoader();
      return Swal.fire({
        icon:_icon,
        text:response.msg
      });
    });
  }


  </script>
@endsection
