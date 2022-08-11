@extends('master.logged')

@section('title')
  Encuesta
@endsection

@section('css')
  <link rel="stylesheet" href="{{asset('css/encuestas.css')}}"/>
@endsection

@section('content')
  <div class="contenedor-superior">
    {!!$sucursales_html!!}
    <div class="container-form-add">

    </div>
  </div>

  {{-- MOSTRAR SOLO CUANDO Session::get('int_tour') == 1
  aparecera solo cuando la variable de sesion de tour este habilitada (cuenta nueva) --}}
  @if(Session::get('int_tour') == 1)
    <div class="alert alert-success" role="alert" style="font-size:2rem;">
      <span class="tour-span" ><strong>Paso 2.</strong></span>
        Configura las preguntas de tu encuesta
        <label class="tour-links-container">
          <a class="tour-link" data-toggle="tooltip" data-placement="top" title="Da clic aquí para continuar " href="{{route('generar_qr_encuesta_mesa')}}">Ver código QR de la encuesta <span class="fa fa-arrow-right"></span></a>
       </label>
    </div>
  @endif
  {{-- /////////////////////////////////////////////////////////////////////////////  --}}

  @if(count($arr_preguntas) > 0)

    <div class="contenedor-preview" id="contenedor-preview">

        <div style="padding:10px 0;">
          <table class="table table-sm">
            <tr>
              <td colspan="2" class="text-center">COLORES</td>
            </tr>
            <tr>
              <td class="text-center" colspan="2">
                Header
                <input type="color" id="color-header"  class="inputs-color" value="{{$colorHeader}}"/>
              </td>
              <td class="text-center" style="display:none;">
                Header texto
                <input type="color" id="color-text-header" class="inputs-color"  value="{{$colorHeaderText}}"/>
              </td>
            </tr>
          </table>
        </div>
      <span id="window-close-icon" class="fa fa-window-close"></span>
      <iframe id="preview-frame" src="/preview/{{$sucursal_url.'?'.rand(100,900)}}"></iframe>
    </div>
  @endif

  <div class="contenedor-encuesta" id="contenedor-encuesta">

    @if(count($arr_preguntas) > 0)

      {{-- inicio formatos predeterminados --}}
      {{-- <div>
        <h5 style="color:blue; text-decoration:underline;">Plantillas de encuestas recomendadas según tipo de negocio</h5>
        <select class="form-control form-control-sm" id="select-aplicar-plantilla" size="3"
        style="">
          @foreach ($keys_plantillas as $col => $v)
            <option style="display:inline-block;text-transform:capitalize;" value="{{$col}}" class="{{$v['icono']}}">  {{str_replace('-', ' ', $col )}} </option>
            <br/>
          @endforeach
        </select>
      </div> --}}
      {{-- fin formatos predeterminados --}}


      <div>
        <h5 style="color:gray">Plantillas de encuestas recomendadas según tipo de negocio</h5>
        <div style="width:100%;padding:5px;display:flex;justify-content:space-between;flex-wrap:wrap;">
          @foreach ($keys_plantillas as $col => $v)
            <button style="padding:1rem;background-color:transparent;color:#4785ef;border:2px solid #4785ef;border-radius: 5px;text-transform:capitalize;"
             data-value="{{$col}}" class="{{$v['icono']}} btn-plantilla">&nbsp;{{str_replace('-', ' ', $col )}}</button>
          @endforeach
        </div>
      </div>


    <h4 style="width:100%;">
      Logotipo
       <a id="enlace-vista-previa" href="#preview">Vista previa de encuesta</a>
    </h4>
    <div  {{-- class="no-pointer" --}}  >
     @if(Session::get('plan') == 2)
        <div class="alert alert-danger" role="alert">Actualiza tu plan para cambiar tu logo</div>
     @endif
     <label id="lb-logo" for="inp-img-logo">
       @if(Session::get('plan') != 2)<input type="file" id="inp-img-logo" data-id="{{$logo_info->id}}"/>@endif
       <img src="{{asset('images/logo/'.$logo_info->ruta)}}" />
       @if(Session::get('plan') != 2)<span>Cambiar logo</span>@endif
     </label>
    </div>



   




    <!-- inicio preguntas -->
    <h4 style="width:100%;" class="sondealo-text-color">Encuesta <button id="btn-save-reorder" class="btn btn-primary btn-sm">Guardar reorden de preguntas</button></h4>

    <div class="contenedor-preguntas {{-- no-pointer --}}" id="contenedor-preguntas">

        @foreach ($arr_preguntas as $preg)
          <div class="pregunta">
            <p class="descripcion-pregunta" data-s="{{$preg->s}}" data-id="{{$preg->id}}" data-tipo="{{$preg->tipo}}" data-v2="{{($preg->valor2 == '') ? 0 : $preg->valor2 }}" data-textos="{{$preg->textos}}">{{$preg->pregunta}}<span class="fa fa-edit text-info cursor-pointer editar-pregunta"></span></p>
          </div>
        @endforeach

      @else
        <div class="alert alert-info" role="alert">
           Sin información
        </div>
      @endif
    </div>


    @if(Session::get('plan') == 2)
       <div class="alert alert-danger" role="alert">Actualiza tu plan para agregar más de 5 preguntas</div>
    @endif
    <!-- fin preguntas -->

    <div class="separator"></div>
    <!-- valores -->

    <div class="contenedor-valores {{-- no-pointer --}}" id="contenedor-valores">
      @if (count($arr_preguntas) > 0)
        <h5><strong>Cantidad de preguntas</strong></h5>
        <select id="cantidad-preguntas">
          <option value="-1">- selecciona -</option>
          @php $lim = (Session::get('plan') == 2) ? 5 : 9;  @endphp

          @for ($i=1; $i <= $lim; $i++)
            @php
            $selected = '';
            @endphp
            @if($arr_valores[0]->valor == $i)
              @php
                $selected = 'selected';
              @endphp
            @endif
            <option value="{{$i}}" {{$selected}}>{{$i}}</option>
          @endfor
        </select>

        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="{{$arr_valores[1]->valor}}" id="agregar-no-contestar" {{($arr_valores[1]->valor == 1) ? 'checked':''}}>
          <label class="form-check-label" for="agregar-no-contestar">
            Agregar botón de "No Contestar"
          </label>
        </div>

        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="{{($arr_valores[2]->valor)}}" id="solicitar-correo" {{($arr_valores[2]->valor == 1) ? 'checked':''}}>
          <label class="form-check-label" for="solicitar-correo">
            Solicitar correo para envíar cupones
          </label>
        </div>

        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="{{($arr_valores[3]->valor)}}" id="solicitar-comentario" {{($arr_valores[3]->valor == 1) ? 'checked':''}}>
          <label class="form-check-label" for="solicitar-comentario">
            Solicitar comentario al final de la encuesta
          </label>
        </div>


        <div style="margin-top: 1rem;padding:10px 5px;width:100%;background-color:rgba(0,0,0,0.05);">
          <small class="text-muted">Estos cambios solo aplican en la encuesta online QR y compartida por WhatsApp </small>
        </div>


        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="{{($arr_valores[5]->valor)}}" id="adjuntar-evidencia" {{($arr_valores[5]->valor == 1) ? 'checked':''}}>
          <label class="form-check-label" for="adjuntar-evidencia">
            Adjuntar evidencia
          </label>
        </div>



        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="{{$arreglo_personalizacion_extra->mover_top}}" id="mostrar-seccion-comentarios-arriba" {{($arreglo_personalizacion_extra->mover_top == 1) ? 'checked' : ''}}/>
          <label class="form-check-label" for="mostrar-seccion-comentarios-arriba">
            Mostrar sección de correo y comentarios hasta arriba
          </label>
        </div>

        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="{{$arreglo_personalizacion_extra->siempre_notificacion}}" id="recibir-alerta-siempre" {{($arreglo_personalizacion_extra->siempre_notificacion == 1) ? 'checked' : ''}} />
          <label class="form-check-label" for="recibir-alerta-siempre">
            Recibir alerta siempre que contesten una encuesta
          </label>
        </div>





        <button id="btn-save-valores" class="btn btn-primary btn-sm">Guardar edición valores</button>
      @endif
    </div>
    <!-- fin valores -->

    <div class="separator"></div>

    @if(count($arr_preguntas) > 0)

      {{-- inicio copiar formato --}}
      <div class="contenedor-copia-formato {{-- no-pointer --}}" id="contenedor-copia-formato" style="@if(Session::get('plan') == 2 or count($arr_sucursales) == 1){{'display:none;'}}@endif" >
        <h5 style="color:blue; text-decoration:underline;">Copiar formato de encuesta de otra sucursal</h5>
        <select class="form-control form-control-sm" id="select-copiar-formato">
          <option value="-1">- selecciona -</option>
          @foreach ($arr_sucursales as $suc)
            @if($suc->sucursal != $sucursal_url)
              <option value="{{$suc->sucursal}}">{{$suc->sucursal}}</option>
            @endif
          @endforeach
        </select>
      </div>
      {{-- fin copiar formato --}}



      @endif


  </div>

@endsection

@section('modal-title')
  Editar pregunta
@endsection

@section('modal-body')

  @if(count($arr_preguntas) > 0)

    <input type="hidden" id="sucursal-edit" value="{{$sucursal_url}}"/>
    <input type="hidden" id="id-pregunta-edit" value=""/>

    <h5><strong>Pregunta</strong></h5>
    <div style="position:relative;height:30px;">
      <input type="text" class="form-control form-control-sm" maxlength="255" id="txt-descripcion-edit" aria-label="Text input with dropdown button" style="position:absolute;top:0;left:0;">
      <div class="dropdown" style="position:absolute;top:0;right:0;">
        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
          <span class="caret"></span>
        </button>
        <ul class="dropdown-menu  dropdown-menu-right" aria-labelledby="dropdownMenu1">
          <li><a href="#"><strong>Preguntas recomendadas por SONDEALO</strong></a></li>
          @for ($j=0; $j < count($preguntas_recomendadas); $j++)
            <li><a class="a-pregunta-recomendada" href="#">{{$preguntas_recomendadas[$j]}}</a></li>
          @endfor
        </ul>
      </div>
    </div>

    <div class="contenedor-tipo-preguntas-edit">

      <h5><strong>¿Como quieres que se muestre la pregunta?</strong></h5>

      <input type="radio" name="radio-tipo-edit" id="tipo0" value="0"  checked/>
      <img src="{{asset('images/tipos_encuestas/tipo_cero.png')}}"/>
      <div role="separator" class="divider"></div>

      <input type="radio" name="radio-tipo-edit" id="tipo1" value="1" />
      <img src="{{asset('images/tipos_encuestas/tipo_uno.png')}}"/>
      <div role="separator" class="divider"></div>

      <input type="radio" name="radio-tipo-edit" id="tipo3" value="3" />
      <img src="{{asset('images/tipos_encuestas/tipo_tres.png')}}"/>
      <div role="separator" class="divider"></div>

      <input type="radio" name="radio-tipo-edit" data-target="hidden-container-tipo4" id="tipo4" value="4" />
      <img src="{{asset('images/tipos_encuestas/tipo_cuatro.png')}}"/>
      <div id="hidden-container-tipo4" class="hidden-container">
        <input type="text" id="opcion_uno_4" value="" maxlength="125" placeholder="Opción 1"/>
        <input type="text" id="opcion_dos_4" value="" maxlength="125" placeholder="Opción 2"/>
        <input type="text" id="opcion_tres_4" value="" maxlength="125" placeholder="Opción 3"/>
        <input type="text" id="opcion_cuatro_4" value="" maxlength="125" placeholder="Opción 4"/>
      </div>
      <div role="separator" class="divider"></div>

      <input type="radio" name="radio-tipo-edit" id="tipo5" value="5" />
      <img src="{{asset('images/tipos_encuestas/tipo_cinco.png')}}"/>
      <div role="separator" class="divider"></div>

      <input type="radio" name="radio-tipo-edit" id="tipo6" value="6" />
      <img src="{{asset('images/tipos_encuestas/tipo_seis.png')}}"/>
      <div role="separator" class="divider"></div>

      <input type="radio" name="radio-tipo-edit" data-target="hidden-container-tipo9" id="tipo9" value="9" />
      <img src="{{asset('images/tipos_encuestas/tipo_nueve.png')}}"/>
      <div id="hidden-container-tipo9" class="hidden-container">

        <input type="text" id="txt-add-opcion" placeholder="agregar" maxlength="70"/><button id="btn-add-opcion">Agregar</button>
        <div role="separator" class="divider"></div>

        <input type="hidden" id="opciones-tipo9" value=""/>

        <div id="contenedor-grafico-opcion9">

        </div>

      </div>
      <div role="separator" class="divider"></div>

    </div>
  @endif
@endsection

{{-- si no hay preguntas no mostramos el javascript --}}
@if(count($arr_preguntas) > 0)

  @section('js')

    <script type="text/javascript">
    $(document).ready(function () {

           $('#contenedor-preguntas').sortable({
               start: function(event, ui) {
                   ui.item.data('start_pos', ui.item.index());
                   ui.item.css({'background-color':'rgba(0,0,0,0.05)'});
               },
               stop: function(event, ui) {
                   var start_pos = ui.item.data('start_pos');
                   if (start_pos != ui.item.index())
                   {
                     let arreglo_reorden = [];
                     let preguntas_reorden = document.querySelectorAll('.descripcion-pregunta');
                     for(let i=0;i<preguntas_reorden.length;i++)
                     {
                       let v2 = (preguntas_reorden[i].dataset.v2 == '') ? 0 : preguntas_reorden[i].dataset.v2;

                       arreglo_reorden.push({"id": (i+1),
                       "tipo":parseInt(preguntas_reorden[i].dataset.tipo),
                       "v2": parseInt(v2),
                       "descripcion":preguntas_reorden[i].innerText,
                       "suc":preguntas_reorden[i].dataset.s,
                       "textos":preguntas_reorden[i].dataset.textos});
                     }
                     localStorage.setItem("preguntas_nuevo_orden", JSON.stringify(arreglo_reorden));
                     document.getElementById('btn-save-reorder').style.cssText = 'visibility:visible;';
                   }
                   else{
                     ui.item.css({'background-color':'#fff'});
                   }
               }
           });
           $( "#contenedor-preguntas" ).disableSelection();
       });

       let btn_save_reorder = document.getElementById('btn-save-reorder');
       if(document.body.contains(btn_save_reorder)){
         btn_save_reorder.addEventListener('click', guardarReorden);
       }

       function guardarReorden()
       {
         if( ! confirm('Antes de modificar la encuesta te recomendamos exportar tu informaci\u00F3n a excel en el apartado de reportes, Deseas continuar\u003F '))
         {
           return;
         }

         if(localStorage.getItem("preguntas_nuevo_orden"))
         {
           document.getElementById('btn-save-reorder').style.cssText = 'visibility:hidden;';

           showLoader();

           fetch("{{route('reordenar_preguntas_encuesta')}}",{
           method:'post',
           body:JSON.stringify({"preguntas": JSON.parse(localStorage.getItem("preguntas_nuevo_orden")) }),
           headers:{
           'Content-Type': 'application/json',
           'X-CSRF-TOKEN':CSRF_TOKEN
           }
           }).then(res => res.json())
           .catch(error => console.log(error))
           .then(function(response){
             let _icon = 'info';
             if(response.status == 200){
               _icon = 'success';
               setTimeout(function(){location.reload();}, 400);
             }
             Swal.fire({
               icon:_icon,
               text: response.msg
             });
             hideLoader();
           });
         }
       }


       let preguntas_recomendadas = document.querySelectorAll('.a-pregunta-recomendada');
       for (var l = 0; l < preguntas_recomendadas.length; l++) {
         preguntas_recomendadas[l].addEventListener('click', function(e){
           document.getElementById('txt-descripcion-edit').value = this.innerText;
         });
       }

       $('[name="radio-tipo-edit"]').click(function(){
          $('.hidden-container').css('height', '1px');
          $('#'+$(this).attr('data-target')).css('height', 'auto');
       });

       let rowEditarPregunta = document.querySelectorAll('.editar-pregunta');
       for (var k = 0; k < rowEditarPregunta.length; k++) {
         rowEditarPregunta[k].addEventListener('click', editPregunta);
       }

       function editPregunta()
       {
         $('.hidden-container').css('height', '1px');
         $('#hidden-container-tipo4 input[type="text"]').val('');
         $('#opciones-tipo9').val('');
         $('#contenedor-grafico-opcion9').empty();
         $('#id-pregunta-edit').val('');

         let parent         = this.parentNode;
         let id             = parent.dataset.id;
         let pregunta       = parent.innerText;
         let tipo           = parent.dataset.tipo;
         let v2             = parent.dataset.v2;
         let textos         = parent.dataset.textos;
         let txt_id         = document.getElementById('id-pregunta-edit');
         let txt_pregunta   = document.getElementById('txt-descripcion-edit');
         txt_id.value       = id;
         txt_pregunta.value = pregunta;
         let check_tipo     = document.querySelector('[name="radio-tipo-edit"][value="'+tipo+'"]');
         check_tipo.checked = true;

         let target = check_tipo.dataset.target;
         if(typeof target !== 'undefined'){
           document.getElementById(target).style.height = 'auto';
         }

         if(tipo == 4){
           let arreglo_textos = textos.split(',');
           document.getElementById('opcion_uno_4').value = arreglo_textos[0];
           document.getElementById('opcion_dos_4').value = arreglo_textos[1];
           document.getElementById('opcion_tres_4').value = arreglo_textos[2];
           document.getElementById('opcion_cuatro_4').value = arreglo_textos[3];
         }

         if(tipo == 9){
           let input_tipo9 = document.getElementById('opciones-tipo9');
           let contenedor_grafico_opcion9 = document.getElementById('contenedor-grafico-opcion9');

           let arreglo_textos = (textos != '') ? textos.split(',') : [];
           input_tipo9.value = JSON.stringify(arreglo_textos);

           let html_contenedor = "";
           for(let j=0;j<arreglo_textos.length;j++)
           {
             html_contenedor += '<label class="opcion9">'+arreglo_textos[j]+'<span class="delete-opcion" data-string="'+arreglo_textos[j]+'">x</span></label>';
           }
           contenedor_grafico_opcion9.innerHTML = html_contenedor;
         }

         $('#modal-edit').modal('show');
       }

       // $('#btn-habilitar-edicion').on('dblclick',function(){
         // $('#contenedor-encuesta > div').toggleClass('no-pointer');
       // });



     let elementsEditarValores = document.querySelectorAll('#contenedor-valores select, #contenedor-valores input');
       for (var j = 0; j < elementsEditarValores.length; j++) {
         elementsEditarValores[j].addEventListener('change', function(){
           document.getElementById('btn-save-valores').style.visibility = 'visible';
         });
       }

       document.getElementById('btn-save-valores').addEventListener('click', enviarCambioValores);

       function enviarCambioValores()
       {




         let select_cantidad_preguntas = document.getElementById('cantidad-preguntas');
         let check_no_contestar = document.getElementById('agregar-no-contestar');
         let check_correo       = document.getElementById('solicitar-correo');
         let check_comentario   = document.getElementById('solicitar-comentario');
         let check_adjuntar     = document.getElementById('adjuntar-evidencia');




         let check_mover_top      = document.getElementById('mostrar-seccion-comentarios-arriba');
         let check_siempre_alerta = document.getElementById('recibir-alerta-siempre');
         let mover_top      = (check_mover_top.checked)?1:0;
         let siempre_alerta = (check_siempre_alerta.checked)?1:0;


         let cantidad_preguntas = select_cantidad_preguntas.value;
         let no_contestar = (check_no_contestar.checked)?1:0;
         let correo       = (check_correo.checked)?1:0;
         let comentario   = (check_comentario.checked)?1:0;
         let adjuntar     = (check_adjuntar.checked)?1:0;

          if(! confirm("Los cambios son irreversibles, la encuesta quedara con "+cantidad_preguntas+" preguntas en total ¿Deseas continuar?"))
          {
            return;
          }


         if(parseInt(cantidad_preguntas) < 1){
          return Swal.fire({
             icon:'info',
             text:'Cantidad de preguntas no valida'
           });
         }

         this.style.visibility = 'hidden';

         showLoader();

         fetch("{{route('actualizar_valores')}}",{
           method:'post',
           body:JSON.stringify({"preguntas":parseInt(cantidad_preguntas), "no_contestar":no_contestar, "correo":correo, "comentario":comentario, "suc":'{{$sucursal_url}}', "evidencia":adjuntar,  "mover_top": mover_top, "siempre_alerta": siempre_alerta}),
           headers:{
             'Content-Type':'application/json',
             'X-CSRF-TOKEN':CSRF_TOKEN
           }
         }).then(res => res.json())
         .catch(error => console.log(error))
         .then(function(response){

           hideLoader();


           let _icon='info';
           if(response.status == 200){
             _icon = 'success';
             setTimeout(function(){location.reload();}, 400);
           }
           Swal.fire({
             icon:_icon,
             text: response.msg
           });
           hideLoader();
         });
       }


       document.getElementById('select-copiar-formato').addEventListener('change', copiarFormato);
       function copiarFormato()
       {
         if(this.value == -1)
         {
           return;
         }

         if(confirm("Los cambios son irreversibles ¿Estas seguro?"))
         {
           showLoader();

           fetch("{{route('copiar_encuesta')}}", {
             method:'post',
             body:JSON.stringify({"copiar_desde": this.value.toLowerCase(), "copiar_destino":'{{$sucursal_url}}' }),
             headers:{
               'Content-Type':'application/json',
               'X-CSRF-TOKEN':CSRF_TOKEN
             }
           }).then(res => res.json())
           .catch(error => console.log(error))
           .then(function(response){
             let _icon = 'info';
             if(response.status == 200){
                _icon = 'success';
               setTimeout(function(){location.reload();}, 400);
             }
             Swal.fire({
               icon:_icon,
               text:response.msg
             });
             hideLoader();
           });
         }
       }

       let inputs_4 = document.querySelectorAll('#hidden-container-tipo4 input[type="text"]');
       for(let y=0;y<inputs_4.length;y++){
         inputs_4[y].addEventListener('keypress',validarTexto);
       }

       document.getElementById('txt-add-opcion').addEventListener('keypress', validarTexto);

       document.getElementById('btn-add-opcion').addEventListener('click', addOpcion);

       function addOpcion()
       {
         let txt_add = document.getElementById('txt-add-opcion');
         let opcion = txt_add.value.trim();
         if(opcion != '')
         {
           if(PATRON_TEXTO.test(opcion))
           {
             let contenedor_grafico_opcion9 = $('#contenedor-grafico-opcion9');

             let input_opciones = document.getElementById('opciones-tipo9');
             let arreglo_opciones = [];
             if(input_opciones.value !='')
             {
               arreglo_opciones = JSON.parse(input_opciones.value);
             }

             if(arreglo_opciones.length >= 7){
               return Swal.fire({
                 icon:'info',
                 text:'Máximo 7 opciones'
               });
             }

             arreglo_opciones.push(opcion);
             let html = '';
             for(let i=0;i<arreglo_opciones.length;i++)
             {
               html += '<label class="opcion9">'+arreglo_opciones[i]+'<span class="delete-opcion" data-string="'+arreglo_opciones[i]+'">x</span></label>';
             }

             contenedor_grafico_opcion9.empty().append(html).hide().show('slow');
             input_opciones.value = JSON.stringify(arreglo_opciones);
             txt_add.value = "";
           }
         }
       }

       $(document).on('click', '.delete-opcion', deleteOpcion);

       function deleteOpcion()
       {
         let input_opciones_tipo9 = document.getElementById('opciones-tipo9');
         let opciones = input_opciones_tipo9.value;
         let arreglo_opciones = [];
         if(opciones != '')
         {
           arreglo_opciones = JSON.parse(opciones);
         }

         let opcion_clicked = this.dataset.string;
         for(let i=0;i<arreglo_opciones.length;i++)
         {
           if(arreglo_opciones[i] == opcion_clicked){
             arreglo_opciones.splice(i, 1);
           }
         }

         input_opciones_tipo9.value = JSON.stringify(arreglo_opciones);
         this.parentNode.remove();
       }


       document.getElementById('btn-save-edit').addEventListener('click', saveEdicion);

       function saveEdicion()
       {
         let txt_pregunta = document.getElementById('txt-descripcion-edit');
         let input_opciones_tipo9 = document.getElementById('opciones-tipo9');

         let radio_tipo = document.querySelector('[name="radio-tipo-edit"]:checked');
         if(typeof radio_tipo === 'undefined'){
           return Swal.fire({
             icon:'info',
             text:'Selecciona una opción'
           });
         }
         let pregunta = txt_pregunta.value.trim();
         let tipo     = radio_tipo.value;

         if(pregunta == ''){
           return Swal.fire({
             icon:'warning',
             text:'Falta la pregunta'
           });
         }

         let arreglo_opciones_enviar = [];

         if(tipo == 4){
           $('#hidden-container-tipo4 input[type="text"]').each(function(index){
             if($(this).val().trim() != '' && PATRON_TEXTO.test($(this).val().trim()) ){
               arreglo_opciones_enviar.push($(this).val().trim());
             }
           });

           if(arreglo_opciones_enviar.length != 4){
             return Swal.fire({
               icon:'warning',
               text:'Son necesarias las 4 opciones'
             });
           }
         }

         if(tipo == 9){
           let input_tipo9_enviar = document.getElementById('opciones-tipo9');
           if(input_tipo9_enviar.value.trim() == ''){
             return Swal.fire({
               icon:'warning',
               text:'Capture las opciones mínimo 2 máximo 7'
             });
           }

           arreglo_opciones_enviar = JSON.parse(input_tipo9_enviar.value.trim());

           if(arreglo_opciones_enviar.length < 2 || arreglo_opciones_enviar.length > 7){
             return Swal.fire({
               icon:'warning',
               text:'Capture las opciones mínimo 2 máximo 7'
             });
           }
         }

         let s = document.getElementById('sucursal-edit').value;
         let id = document.getElementById('id-pregunta-edit').value;

         showLoader();

         fetch("{{route('actualizar_pregunta')}}",{
           method:'post',
           body:JSON.stringify({"s":s, "id":id, "pregunta":pregunta, "tipo":tipo, "opciones":arreglo_opciones_enviar}),
           headers:{
             'Content-Type':'application/json',
             'X-CSRF-TOKEN':CSRF_TOKEN
           }
         }).then(res => res.json())
         .catch(error => console.log(error))
         .then(function(response){
           let _icon = 'info';
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

       window.onload = function(){
         localStorage.removeItem("preguntas_nuevo_orden");
       }


       @if(Session::get('plan') != 2)

       document.getElementById('inp-img-logo').addEventListener('change', cambiarLogo);

       function cambiarLogo()
       {
         var archivoVal = this.value;
         var extension  = extension =(archivoVal.substring(archivoVal.lastIndexOf("."))).toLowerCase();
         if(extension != '.jpeg' && extension != '.png' && extension != '.jpg')
         {
           this.value = '';
           return Swal.fire({
             icon:'info',
             text:'Solo imagenes .jpg, .png',
           });
         }

         let size = Number.parseInt(this.files[0].size);
         if(size > 1048576)
         {
           this.value = '';
           return Swal.fire({
             icon:'info',
             text:'Las imagenes deben de ser máximo de 1mb',
           });
         }
         let id = this.dataset.id;
         let formdata=new FormData();
         formdata.append('id', id);
         formdata.append('img',this.files[0]);

         showLoader();

         fetch("{{route('actualizar_imagen_logo')}}",{
           method:'post',
           body:formdata,
           headers:{
             'X-CSRF-TOKEN':CSRF_TOKEN
           }
         }).then(res => res.json())
         .then(function(response){
           hideLoader();
           let _icon = 'info';
           if(response.status == 200){
             _icon = 'success';
             setTimeout(function(){location.reload();}, 400);
           }
           return Swal.fire({icon:_icon,text:response.msg});
         });
       }

       @endif




      // document.getElementById('licar-plantilla').addEventListener('change', aplicarPlantilla);

      $('.btn-plantilla').on('click', aplicarPlantilla);


       function aplicarPlantilla()
       {
         let val = this.dataset.value;

         if(val != '')
         {
           let text = this.innerText;

           if(confirm("Aplicar la plantilla "+text+", ¿Estas seguro?"))
           {
             showLoader();
             fetch("{{route('aplicar_plantilla_encuesta')}}", {
               method:'post',
               body:JSON.stringify({"sucursal":"{{$sucursal_url}}", 'plantilla': val}),
               headers:{
                 'Content-Type':'application/json',
                 'X-CSRF-TOKEN':  CSRF_TOKEN
               }
             }).then(res => res.json())
             .then(function(response)
             {
               let _icon = 'info';
               if(response.status =='200'){
                 _icon = 'success';
                 setTimeout(function(){location.reload();}, 400);
               }
               hideLoader();
               return Swal.fire({icon:_icon, text:response.msg});
             });

           }
         }


         // if(this.value != '-1' && this.value != '')
         // {
         //   if(confirm("Los cambios son irreversibles, ¿Estas seguro?"))
         //   {
         //     showLoader();
         //
         //     fetch("{{route('aplicar_plantilla_encuesta')}}", {
         //       method:'post',
         //       body:JSON.stringify({"sucursal":"{{$sucursal_url}}", 'plantilla': this.value}),
         //       headers:{
         //         'Content-Type':'application/json',
         //         'X-CSRF-TOKEN':  CSRF_TOKEN
         //       }
         //     }).then(res => res.json())
         //     .then(function(response)
         //     {
         //       let _icon = 'info';
         //       if(response.status =='200'){
         //         _icon = 'success';
         //         setTimeout(function(){location.reload();}, 400);
         //       }
         //       hideLoader();
         //       return Swal.fire({icon:_icon, text:response.msg});
         //     });
         //
         //   }
         // }
       }

       document.getElementById('enlace-vista-previa').addEventListener('click', showPreview);
       function showPreview(){
         document.getElementById('contenedor-preview').style.display = 'block';
       }

       document.getElementById('window-close-icon').addEventListener('click', hidePreview);
       function hidePreview(){
         document.getElementById('contenedor-preview').style.display = 'none';
       }



       $(document).on("input" , "#color-header, #color-text-header" , function(ev){

           fetch("{{route('encuesta_personalizar')}}", {
              method:'post',
              body:JSON.stringify({"header_color":$('#color-header').val(), "header_text_color": $('#color-text-header').val() }),
              headers:{
                  'X-CSRF-TOKEN':CSRF_TOKEN,
                  'Content-Type':'application/json'
              }
           }).then(res => res.json())
           .then( (response) => {
            if(response.status == 200){
              document.getElementById('preview-frame').setAttribute("src","/preview/{{$sucursal_url.'?'.rand(100,900)}}")
            }
           });
        });
    </script>


  @endsection
@endif
