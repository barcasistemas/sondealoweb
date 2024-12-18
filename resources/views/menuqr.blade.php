@extends('master.logged')

@section('title')
    Menú QR
@endsection

@section('css')
  <link rel="stylesheet" href="{{asset('css/menu_qr.css')}}"/>
@endsection

@section('content')
  <div class="contenedor-superior">
    {!!$sucursales_html!!}
    <div class="container-form-add">
    </div>
  </div>

  @if($boolean_show)
    <h4>Puedes subir tu menú en formato pdf</h4>
    @php   $label ='Subir Menú';  @endphp

    @if ($menu_url != '')
      <div id="qr" style="width:300px;height:300px;padding:15px;border:1px solid rgba(0,0,0,0.4);">
        {!! QrCode::errorCorrection('H')->size(270)->generate($menu_url) !!}
      </div>
      <button class="btn btn-success btn-sm" style="margin-top: 5px;margin-bottom:10px;margin-left:15px;" id="btn-save-qr">Guardar QR</button>
      @php
        $arr_archivo = explode('/', $menu_url);
        $str_archivo = $arr_archivo[count($arr_archivo)-1];
      @endphp

      <a href="{{/*public_path().'/menu_qr/'.$str_archivo*/$menu_url}}" target="_blank" class="alert-link">Ver Menú</a>
      @php  $label= 'Cambiar Menú';  @endphp
    @else
      <div class="alert alert-info" role="alert">
         Sin menú
      </div>
    @endif

    <div class="contenedor-file">
      <form method="post" id="form-cambiar-menu" action="{{route('subir_menu_qr')}}" enctype="multipart/form-data" onsubmit="return false;">
        @csrf
        <input type="hidden" name="si" value="{{$id_sucursal}}"/>
        <input type="hidden" name="sn" value="{{$sucursal_url}}"/>
        <input type=file id="menu" name="menu">
        <label for="menu">{{$label}}</label>
      <form>
    </div>

  @endif

@endsection
@if($boolean_show)

  @section('js')
    <script src="{{asset('js/html2canvas.min.js')}}"></script>
    <script type="text/javascript">

      let btn = document.getElementById('btn-save-qr');

      if(document.body.contains(btn)){
        btn.addEventListener('click', function(){
          html2canvas(document.querySelector("#qr")).then(canvas => {
            let dataURL = canvas.toDataURL('image/png');
            let url = dataURL.replace(/^data:image\/png/,'data:application/octet-stream');
            let downloadLink = document.createElement('a');
            downloadLink.setAttribute('download', "{{$sucursal_url}}"+".png");
            downloadLink.setAttribute('href', url);
            downloadLink.click();
          });
        });
      }

      document.getElementById('menu').addEventListener('change', subirMenu);

      function subirMenu()
      {
        var archivoVal = this.value;
        var extension  = extension =(archivoVal.substring(archivoVal.lastIndexOf("."))).toLowerCase();
        if(extension != '.pdf')
        {
          this.value = '';
          return Swal.fire({
            icon:'info',
            text:'Solo archivos .pdf',
          });
        }

        let size = Number.parseInt(this.files[0].size);
				if(size > 15728640)
				{
          this.value = '';
          return Swal.fire({
            icon:'info',
            text:'El archivo debe de ser maximo 15 mb',
          });
				}
        document.getElementById('form-cambiar-menu').submit();
      }      
    </script>
  @endsection
@endif
