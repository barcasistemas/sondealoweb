@extends('sondealo_administracion.master_admin')

@section('title')
  Usuarios - Monitor
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('css/monitor.css')}}"/>
<style type="text/css">
.money:before{
  content: '$ ';
}
</style>
@endsection

@section('content')
  <div class="table-responsive">
    <a class="btn btn-success btn-sm" style="margin-bottom:10px;float:right;" href="{{route('reporte_excel_usuarios_plataforma')}}">
      <i class="fa fa-file-excel-o"></i> Generar Excel
    </a>

    <table class="table table-sm table-hover">
      <thead class="thead-styles">
        <tr>
          <th scope="col">Usuario</th>
          <th scope="col">Nombre</th>
          <th scope="col">Teléfono</th>
          <th scope="col">Mensualidad</th>
          <th scope="col">Inicio</th>
          <th scope="col" colspan="2">Acción</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($usuarios as $usuario)
          <tr>
            <td>{{$usuario->usuario}}</td>
            <td>{{ucfirst(mb_strtolower($usuario->nombre, 'UTF-8'))}}</td>
	    <td>{{$usuario->telefono}}</td>
            <td class="money">{{$usuario->total_pago}}</td>
            <td>{{$usuario->inicio}}</td>
            <td data-user="{{$usuario->id}}" data-accion="info" class="fa fa-info-circle text-info cursor-pointer infodel"></td>
            @if(Session::get('user') == 'sondealo')
           	 <td data-user="{{$usuario->id}}" data-accion="del" class="fa fa-trash-o text-danger cursor-pointer infodel"></td>
           @endif 
	 </tr>
        @endforeach
      </tbody>
    </table>
  <div>
  {{$usuarios->links('pagination::bootstrap-4')}}
@endsection

@section('modal-title')
  Detalle
@endsection

@section('modal-body')
  <div id="contenedor-modal-body" style="width:100%;">
  </div>

@endsection


@section('js')
  <script type="text/javascript">

    let infodel_elem = document.querySelectorAll('.infodel');
    for (var i = 0; i < infodel_elem.length; i++) {
      infodel_elem[i].addEventListener('click', fnCatchAction);
    }

    function fnCatchAction()
    {
      let action = this.dataset.accion;
      let user   = this.dataset.user;
      let element = this;

      if(action == 'del')
      {
        if(confirm('Los cambios son irreversibles, ¿Estás seguro?'))
        {
          let pass = prompt("Para continuar, ingresa tu contraseña");

          if(pass == null){
            return;
          }

          if(pass.trim() != '')
          {
            showLoader();

            fetch("{{route('eliminar_cuenta_usuario')}}",{
              method:'post',
              body:JSON.stringify({"user_delete":user, "pass_comprobacion":pass}),
              headers:{
                'Content-Type':'application/json',
                'X-CSRF-TOKEN':CSRF_TOKEN
              }
            }).then(res => res.json())
            .then(function(response){
              let _icon = 'info';
              if(response.status == 200){
                _icon = 'success';
                element.parentNode.remove();
              }
              hideLoader();

              return Swal.fire({icon:_icon, text:response.msg});
            });
          }
        }

      }
      else
      {
        showLoader();
        fetch("{{route('info_detalle_plan_usuario')}}",{
          method:'post',
          body:JSON.stringify({"user":user}),
          headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':CSRF_TOKEN
          }
        }).then(res => res.json())
        .then(function(response){
          hideLoader();
          if(response.status == 200)
          {
            let html ='';
            let sucursales = response.info.sucursales;

            html = '<div class="alert alert-success" role="alert" style="display:flex;flex-wrap:wrap;justify-content:space-between;">'
            +'<span class="span-inp"><strong>Nombre </strong><input type="text" id="txt-upd-name" value="'+response.info.nombre+'" disabled/></span>'
            +'<span class="span-inp"><strong>Correo Electrónico </strong><input type="email" id="txt-upd-email" value="'+response.info.correo+'" disabled/></span>'
            +'</div>'
            +'<table class="table table-sm">'
            +'<thead>'
            +'<tr>'
            +'<th class="text-center">Encuestas realizadas desde '+response.info.semana_atras+' - {{date('Y-m-d')}}</th>'
            +'</tr>'
            +'<tr>'
            +'<th>Sucursal</th>'
            +'<th>Conteo</th>'
            +'</tr>'
            +'</thead>'
            +'<tbody>';

            if(sucursales.length > 0)
            {
              for (var i = 0; i < sucursales.length; i++)
              {
                html +=   '<tr><td>'+sucursales[i].sucursal+'</td><td>'+sucursales[i].conteo+'</td></tr>';
              }
            }
            else{
              html += '<tr><td><strong>Sín Sucursales</strong></td></tr>';
            }
            html += '</tbody>'
            +'</table>'
            +'<div class="alert alert-success" role="alert" style="display:flex;flex-wrap:wrap;justify-content:flex-end;">'
            +'<span><strong>Fecha de registro:</strong> '+response.info.fec+'</span>'
            +'</div>';

            document.getElementById('contenedor-modal-body').innerHTML = html;
            showModal();
          }
          else
          {
            return Swal.fire({icon:'info', text: response.msg});
          }
        });
      }
    }

    let page_links = document.querySelectorAll('.page-link');
    for (let k = 0; k < page_links.length; k++) {
      page_links[k].addEventListener('click', showLoader);
    }
  </script>

@endsection
