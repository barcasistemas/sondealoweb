@extends('master.logged')

@section('title')
  Correos clientes {{ $sucursal_url ?: ''}}
@endsection

@section('css')
  <link rel="stylesheet" href="{{asset('css/correosclientes.css')}}"/>
@endsection

@section('content')
  <div class="contenedor-superior">
    {!!$sucursales_html!!}
    <div class="container-form-add">
    </div>
  </div>

  @if(count($correos_clientes) > 0)
    <h4 style="display:block;float:left;">Correos electrónicos</h4>

    <button class="btn btn-sm btn-success" onclick="correos();" style="display:block;width:150px;margin-bottom:8px;margin-left:calc(100% - 150px);font-size:1.4rem;">
      <i class="fa fa-file-excel-o"></i> Exportar a excel
    </button>


    <table class="table table-sm table-hover">
      <thead class="thead-styles">
        <tr>
          <th>Correo Electrónico</th>
          <th>Fecha</th>
        </tr>
      </thead>
      <tbody class="bg-light">
        @foreach ($correos_clientes as $encuesta)
          <tr>
            <td>{{$encuesta->correo}}</td>
            <td>{{$encuesta->fecha}}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
    {{$correos_clientes->links()}}
  @else
    <div class="alert alert-info" role="alert">
       Sin información
    </div>
  @endif
@endsection

@section('js')

  @if($sucursal_url)

    <script type="text/javascript">
      function correos()
      {
        showLoader();
        fetch("{{route('reporte_correos_clientes', ['sucursal' => $sucursal_url ])}}",{
          headers:{
              'X-CSRF-TOKEN':CSRF_TOKEN
          }
       }).then(  res => res.blob() )
        .catch(error => console.log(error))
        .then( (blob) => {
          let objectURL = URL.createObjectURL(blob);
          let a = document.createElement('a');
          a.href = objectURL;
          a.download = 'correos_{{$sucursal_url}}_{{date("d-m-Y-H-i-s")}}.xlsx';
          document.body.appendChild(a);
          a.click();
          a.remove();
          hideLoader();
        });
      }
    </script>

  @endif
@endsection
