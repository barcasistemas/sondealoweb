@extends('master.logged')

@section('title')
  Generar código QR encuestas
@endsection

@section('css')
  <link rel="stylesheet" href="{{asset('css/encuesta_qr.css')}}"/>
@endsection

@section('content')
  <div class="contenedor-superior">
    {!!$sucursales_html!!}
    <div class="container-form-add">


      {{-- MOSTRAR SOLO CUANDO Session::get('int_tour') == 1
      aparecera solo cuando la variable de sesion de tour este habilitada (cuenta nueva) --}}
      @if(Session::get('int_tour') == 1)
        <div class="alert alert-success" role="alert" style="font-size:2rem;">
          <span class="tour-span"><strong>Paso 3.</strong></span>
          Con este código QR puedes aplicar tu encuesta
          <label class="tour-links-container">
            <a class="tour-link" href="{{route('final_tour')}}">Finalizar</a>
          </label>
        </div>
      @endif
     {{-- ///////////////////////////////////////////////////////////////////////////////////// --}}





      @if($boolean_show_input)
      <div class="cont-m-qr q1">
        <small>Identificador del Qr</small>
        <input type="text" id="txt-mesa" class="form-control form-control-sm" maxlength="20"/>
        <button style="margin-top:5px;" class="btn btn-success btn-sm" id="btn-generar-qr-mesa">Generar</button>
      </div>
    @else
      <div class="alert alert-info" role="alert">
        Selecciona una sucursal
      </div>
    @endif
    </div>
  </div>



  <div class="contenedor-btns-pdf">
    @if($boolean_show_input)
      <button class="btn btn-light btn-sm fa fa-print btn-print-pdf" onclick="printJS({printable:'table-xl', type: 'html', font_size: '12px;', maxWidth: 280, css:'{{asset('css/encuesta_qr.css')}}' });">
        Imprimir QR Grande
      </button>
      <button class="btn btn-light btn-sm fa fa-print btn-print-pdf" onclick="printJS({printable:'table-sm', type: 'html', font_size: '10px;', maxWidth: 150, css:'{{asset('css/encuesta_qr.css')}}' });">
          Imprimir QR Chico
      </button>
    @endif
  </div>

  <div id="container-qrs">
    @if($boolean_show_input)

      <a href="{{$url_qr_default}}" target="_blank" class="btn btn-danger btn-sm">Ir a la encuesta</a>

      <table id="table-xl">
        <tbody>
          <tr>
            <td>
              <div class="qrv">
                <p>Encuesta<br/>{{$sucursal_url}}</p>
                <div class="cont-m-qr qr-lg">
                  {!! QrCode::errorCorrection('H')->size(280)->generate($url_qr_default) !!}
                </div>
                <p></p>
                <p class="small" style="visibility:hidden;">CUÉNTANOS TU EXPERIENCIA</p>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <table id="table-sm" style="visibility: hidden;">
        <tbody>
          <tr>
            <td>
              <div class="qrv" >
                <p>Encuesta<br/>{{$sucursal_url}}</p>
                <div class="cont-m-qr qr-sm">
                  {!! QrCode::errorCorrection('H')->size(150)->generate($url_qr_default) !!}
                </div>
                <p></p>
                <p class="small" style="visibility:hidden;">CUÉNTANOS TU EXPERIENCIA</p>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

    @endif
  </div>

  <input type="hidden" id="contador-xl" value="1"/>
  <input type="hidden" id="contador-sm" value="1"/>

@endsection

@section('js')
  <script src="{{asset('js/print.min.js')}}"></script>
  <script type="text/javascript">

  if(document.body.contains(document.getElementById('txt-mesa')))
  {
    document.getElementById('txt-mesa').addEventListener('keypress', function(ev){
      if(!PATRON_TEXTO.test(ev.key)){
        ev.preventDefault();
        return false;
      }
    });

    document.getElementById('btn-generar-qr-mesa').addEventListener('click', function(e){
      e.preventDefault();

      let txt_mesa = document.getElementById('txt-mesa');
      let mesa = txt_mesa.value.trim();

      if(mesa == ''){
        return Swal.fire({icon:'warning', text:'Ingrese el identificador de la mesa'});
      }

      if(!PATRON_TEXTO.test(mesa)){
        return Swal.fire({icon:'info', text:'Mesa no valida, solo letras y números'});
      }

      showLoader();

      fetch("{{route('get_qr')}}",{
        method:'post',
        body:JSON.stringify({"mesa":mesa}),
        headers:{
          'Content-Type':'application/json',
          'X-CSRF-TOKEN':CSRF_TOKEN
        }
      }).then(res => res.json())
      .then(function(response)
      {
        hideLoader();

        if(response.status == 200)
        {
          let qr_xl = response.qr_xl;
          let qr_sm = response.qr_sm;

          let contador_xl = document.getElementById('contador-xl');
          let contador_sm = document.getElementById('contador-sm');

          let val_xl = contador_xl.value;
          let val_sm = contador_sm.value;

          let table_xl =  document.getElementById('table-xl');
          let table_sm  =  document.getElementById('table-sm');

          let last_tr_xl = $('#table-xl > tbody > tr').last();
          if(parseInt(val_xl) % 2 == 0){
            table_xl.innerHTML +=  '<tr><td>'+qr_xl+'</td></tr>';
          }
          else{
            last_tr_xl.append('<td>'+qr_xl+'</td>');
          }

          let last_tr_sm = $('#table-sm > tbody > tr').last();
          if(parseInt(val_sm) % 3 == 0){
            table_sm.innerHTML += '<tr><td>'+qr_sm+'</td></tr>';
          }
          else{
            last_tr_sm.append('<td>'+qr_sm+'</td>');
          }

          contador_xl.value = parseInt(val_xl) + 1;
          contador_sm.value = parseInt(val_sm) + 1;
          return;
        }

        return Swal.fire({
          icon:'info',
          text:response.msg
        });
      });
    });
  }
  </script>
@endsection
