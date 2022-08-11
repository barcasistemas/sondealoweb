@extends('master.logged')

@section('title')
  Historial de cupones
@endsection

@section('css')
  <link rel="stylesheet" href="{{asset('css/historial_cupones.css')}}"/>
@endsection

@section('content')
  <div class="contenedor-superior">
    {!!$sucursales_html!!}
    <div class="container-form-add">
    </div>
  </div>

 @if ($boolean_show)
   <div class="btn-group" role="group" style="margin-bottom:10px;">
     @php
       $disabled_a = ($tipo_reporte == 'activo')   ? 'disabled' : '';
       $disabled_i = ($tipo_reporte == 'inactivo') ? 'disabled' : '';
       $disabled_t = ($tipo_reporte == 'todos')    ? 'disabled' : '';
     @endphp

     <a role="button" href="{{route(Request::route()->getName()).'/'.$sucursal_url.'/'.'activo'}}"  class="btn btn-success {{$disabled_a}}">Cupones activos</a>
     <a role="button" href="{{route(Request::route()->getName()).'/'.$sucursal_url.'/'.'inactivo'}}"  class="btn btn-danger {{$disabled_i}}">Cupones inactivos</a>
     <a role="button" href="{{route(Request::route()->getName()).'/'.$sucursal_url.'/'.'todos'}}"  class="btn btn-warning {{$disabled_t}}">Cupones todos</a>
  </div>
  @if(count($arr_cupones) > 0)
     <table class="table table-sm">
       <thead class="thead-styles">
         <tr>
           <th>Ticket</th>
           <th>Promoción</th>
           <th>Vendedor que generó</th>
           <th>Fecha</th>
           @if ($tipo_reporte != 'activo')
             <th>Canjeado</th>
           @endif
        </tr>
       </thead>
       <tbody>
         @foreach ($arr_cupones as $cupon)
           <tr>
             <td>{{$cupon->folio}}</td>
             <td>{{$cupon->promocion}}</td>
             <td>{{$cupon->meserogenera}}</td>
             <td>{{$cupon->generado}}</td>
             @if ($tipo_reporte != 'activo')
               <td>{{$cupon->canjeado}}</td>
             @endif
          </tr>
         @endforeach
      </tbody>
    </table>
  {{$arr_cupones->links()}}
@else
  <div class="alert alert-info" role="alert">
     Sin información
  </div>
@endif
 @endif
@endsection
