@extends('master.logged')

@section('title')
  Alertas
@endsection

@section('css')
  <link rel="stylesheet" href="{{asset('css/alertas.css')}}"/>
@endsection

@section('content')
  <div class="contenedor-superior">
  {!!$sucursales_html!!}
    <div class="container-form-add">
    </div>
  </div>

  <div class="contenedor-alertas">

    @if(Session::has('sucursal_fijada'))
      @if (count($arreglo_alertas) > 0)
          <h4>Alertas</h4>
          @for ($i=0; $i < count($arreglo_alertas); $i++)
          <table class="table table-sm">
            <tbody>
                <tr>
                  <td>Fecha</td>
                  <td>{{$arreglo_alertas[$i]->fecha}}</td>
                </tr>
                <tr>
                  <td>Ticket</td>
                  <td>{{$arreglo_alertas[$i]->folio}}</td>
                </tr>
                <tr>
                  <td>Mesa</td>
                  <td>{{$arreglo_alertas[$i]->mesa}}</td>
                </tr>
                <tr>
                  <td>Vendedor</td>
                  <td>{{$arreglo_alertas[$i]->mesero}}</td>
                </tr>

                  @foreach ($preguntas_sucursal as  $preg)
                    <tr>
                      <td>{{$preg->pregunta}}</td>
                      <td>{{$arreglo_alertas[$i]->{'p'.$preg->id} }}</td>
                    </tr>
                  @endforeach

                <tr>
                  <td>Correo</td>
                  <td>{{$arreglo_alertas[$i]->correo}}</td>
                </tr>
                <tr>
                  <td>Comentario</td>
                  <td class="alerta">{{$arreglo_alertas[$i]->comentarios}}</td>
                </tr>
		  @php
                    $imgs ='';
                    if($arreglo_alertas[$i]->evidencia != '')
                    {

                      $ev1 = ($arreglo_alertas[$i]->evidencia->ruta_evidencia_1 != '') ? '/sitio/images/evidencia/'.substr($arreglo_alertas[$i]->evidencia->ruta_evidencia_1, 44) :'';
                      $ev2 = ($arreglo_alertas[$i]->evidencia->ruta_evidencia_2 != '') ? '/sitio/images/evidencia/'.substr($arreglo_alertas[$i]->evidencia->ruta_evidencia_2, 44) :'';

                      echo  '<tr><td>Evidencia (Da clíck)</td>'
                           .'<td><span style="cursor:pointer;" class="evidencia fa fa-image text-danger" data-url1="'.$ev1.'" data-url2="'.$ev2.'"></span></td></tr>';
                    }
                  @endphp
            </tbody>
          </table>
        @endfor
      @else
        <div class="alert alert-info" role="alert">
           Sin alertas
        </div>
      @endif

    @else
    <div class="alert alert-info" role="alert">
      Seleccione una sucursal primero
    </div>
  @endif

  </div>


<div class="modal fade" id="modal-carousel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Evidencias</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <div id="carousel1" class="carousel slide" data-ride="carousel">
          <ol class="carousel-indicators">
            <li data-target="#carousel1" data-slide-to="0" class="active"></li>
            <li data-target="#carousel1" data-slide-to="1"></li>
          </ol>

          <div class="carousel-inner" role="listbox" id="inner-corousel">

          </div>
          <a class="left carousel-control" href="#carousel1" role="button" data-slide="prev">
            <span class="text-dark fa fa-arrow-left" aria-hidden="true"></span>
            <span class="sr-only">Anterior</span>
          </a>
          <a class="right carousel-control" href="#carousel1" role="button" data-slide="next">
            <span class="text-dark fa fa-arrow-right" aria-hidden="true"></span>
            <span class="sr-only">Siguiente</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('js')
  <script type="text/javascript">

    document.addEventListener('DOMContentLoaded', () => {
      let elem_ev = document.querySelectorAll('.evidencia');
      for (let i = 0; i < elem_ev.length; i++)
      {
        elem_ev[i].addEventListener('click', showSlider);
      }
    });

    function showSlider()
    {
      ev1 = this.dataset.url1;
      ev2 = this.dataset.url2;
      let html = '';

      html += '<div class="item active">'
      +'<img src="'+ev1+'" alt="Evidencia 1">'
      +'<div class="carousel-caption"> . </div>'
      +'</div>';

      if(ev2 != '')
      {
        html += '<div class="item">'
        +'<img src="'+ev2+'" alt="Evidencia 2">'
        +'<div class="carousel-caption"> . </div>'
        +'</div>';
      }
      else{
	$('.carousel-indicators li').last().remove();
      }

      document.getElementById('inner-corousel').innerHTML = html;
      $('#modal-carousel').modal('show');
    }

  </script>

@endsection
