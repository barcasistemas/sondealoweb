@extends('master.logged')

@section('title')
  Validar cupones
@endsection

@section('css')
  <link rel="stylesheet" href="{{asset('css/cupones_validar.css')}}"/>
@endsection

@section('content')
  <div class="contenedor-superior">
    {!!$sucursales_html!!}
    <div class="container-form-add">
    </div>
  </div>

  @if($boolean_show)
    <form id="form-validar-cupon" action="{{route('validar_cupon')}}" method="post" class="form-group" onsubmit="return false;">
      <h4>Validar cupón de sucursal : <span>{{$sucursal_url}}</span></h4>
      <input type="hidden" name="s" value="{{$sucursal_url}}"/>
      @csrf
      <input type="text" class="form-control input-sm" name="cupon" id="cupon" placeholder="Cupón"/>
      <button class="btn btn-primary btn-sm" id="btn-validar-cupon">Validar</button>
    </form>
  @else
    <div class="alert alert-info" role="alert">
       Seleccione una sucursal primero
    </div>
  @endif

@endsection

@if($boolean_show)
  @section('js')
    <script type="text/javascript">
      document.getElementById('btn-validar-cupon').addEventListener('click', validarCupon);
      function validarCupon()
      {
        let cupon = document.getElementById('cupon').value.trim();
        if(cupon != ''){
          if(!PATRON_LETRAS_NUMEROS.test(cupon)){
            return Swal.fire({icon:'info', text:'Cupón no valido'});
          }
          showLoader();
          document.getElementById('form-validar-cupon').submit();
        }
      }
    </script>
  @endsection
@endif