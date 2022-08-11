@extends('sondealo_administracion.master_admin')

@section('title')
Encuestas realizadas
@endsection

@section('content')
  <div class="row">
    <div class="col-md-5">
      <small class="text-muted">Desde</small>
      <input type="date" class="form-control" name="txt-desde" id="txt-desde" value="@if($desde_inicial == ''){{date('Y-m-d')}}@else{{$desde_inicial}}@endif"/>
    </div>
    <div class="col-md-5">
      <small class="text-muted">Hasta</small>
      <input type="date" class="form-control" name="txt-hasta" id="txt-hasta" value="@if($hasta_inicial == ''){{date('Y-m-d')}}@else{{$hasta_inicial}}@endif"/>
    </div>
    <div class="col-md-2">
      <button class="btn btn-success btn-block" id="btn-generar-reporte" style="margin-top:2rem;">Generar</button>
    </div>
  </div>

  <div class="row">
    <table class="table table-sm table-striped" style="margin-top:2rem;">
      <thead>
        @if(count($sucursalesEncuestas) > 0)
          <tr>
            <th colspan="2" class="text-center">ENCUESTAS REALIZADAS POR SUCURSAL Y PERÍODO</th>
          </tr>
          <tr>
            <th>Periodo: {{$desde_inicial ?? ''}} - {{$hasta_inicial ?? ''}}</th>
              {{-- <th><a class="fa fa-file-excel-o btn btn-sm btn-success float-right"> Excel</a></th> --}}
          </tr>
          <tr>
            <th>Sucursal</th>
            <th>Conteo</th>
          </tr>
        @endif
      </thead>
      <tbody>
        @forelse ($sucursalesEncuestas as $var)
          <tr>
            <td>{{$var->sucursal}}</td>
            <td>{{$var->conteo}}</td>
          </tr>
        @empty
          <tr>
            <td  class="alert alert-info" colspan="2">Sin información</td>
          </tr>
        @endforelse
      </tbody>
    </table>
    @if(count($sucursalesEncuestas) > 0) {{$sucursalesEncuestas->links('pagination::bootstrap-4')}}  @endif
  </div>

@endsection

@section('js')
  <script type="text/javascript">

    document.getElementById('btn-generar-reporte').addEventListener('click', function(){
      let txt_desde = document.getElementById('txt-desde');
      let txt_hasta = document.getElementById('txt-hasta');

      let desde = txt_desde.value.trim();
      let hasta = txt_hasta.value.trim();

      if(desde == '' || hasta == ''){
        return;
      }

      let date_desde = Date.parse(desde);
      let date_hasta = Date.parse(hasta);

      if(date_hasta < date_desde){
        return Swal.fire({icon:'info', text:'la fecha final no puede ser mayor a la de inicio'});
      }

      let url = '/admin/encuestas-recientes/'+desde+'/'+hasta;
      window.location = url;
    });

  </script>
@endsection
