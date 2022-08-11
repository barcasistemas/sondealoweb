@extends('master.logged')

@section('title')
  Cupones
@endsection

@section('css')
  <link rel="stylesheet" href="{{asset('css/cupones.css')}}"/>
@endsection

@section('content')
  <div class="contenedor-superior">
    {!!$sucursales_html!!}
    <div class="container-form-add">
      @if(count($cupones) > 0)
        {{-- MOSTRAR SOLO CUANDO Session::get('int_tour') == 1
        aparecera solo cuando la variable de sesion de tour este habilitada (cuenta nueva) --}}
        @if(Session::get('int_tour') == 1)
          <div class="alert alert-success" role="alert">
            <span class="tour-span">Paso 5.</span>
            Agrega en cada sucursal 5 imágenes de cupones que se mandaran al correo del cliente al finalizar la encuesta.
            <label class="tour-links-container">
              <a class="fa fa-arrow-left tour-link" href="{{route('mostrar_promociones')}}"></a>
              <a class="tour-link" href="{{route('final_tour')}}">Finalizar</a>
            </label>
          </div>
        @endif
       {{-- ///////////////////////////////////////////////////////////////////////////////////// --}}
      @endif
    </div>
  </div>

  <div id="contenedor-cupones">

    @php
     $limite = ( Session::get('plan') == 2 ) ? 2 : 5 ;
     $it=0;
    @endphp

    @foreach ($cupones as $cupon)

      @if($it >= $limite)
        <div class="contenedor-cupon" style="border:1px solid #000000;height:220px;">
          <div class="alert alert-danger" role="alert">
            Actualiza tu plan para agregar más cupones
          </div>
        </div>
      @else
        @php
          /*A remplazar unicamente con {{$promo->ruta}} una vez se actualicen todos los registros*/
          $str_url_arr = explode('/',$cupon->ruta);
          $archivo_name = $str_url_arr[count($str_url_arr)-1];
        @endphp
        <div class="contenedor-cupon">
  				<label>
  					<img src="{{asset('images/cupones').'/'.$archivo_name.'?'.rand(10,200)}}"/>
  					<input type="file" id="img_{{$cupon->id}}" data-id="{{$cupon->id}}" class="inp-img"/>
  					<span class="span-cupon">Cambiar cupón</span>
  				</label>
  				<div class="contenedor-form-cupon">
  					<input type="text" maxlength="90" id="cupon_descripcion_{{$cupon->id}}" name="cupon_descripcion_{{$cupon->id}}" value="{{$cupon->nombre}}" title="{{$cupon->nombre}}"/>
  					<select name="select_{{$cupon->id}}" id="select_{{$cupon->id}}">
              @php
              $selected_7  = ((int)$cupon->valor2 == 7)        ? 'selected':'';
              $selected_15 = ((int)$cupon->valor2 == 15)       ? 'selected':'';
              $selected_30 = ((int)$cupon->valor2 == 30)       ? 'selected':'';
              $selected_NA = ((string)$cupon->valor2 == 'N/A') ? 'selected':'';
              $it++;
              @endphp
              <option value="7" {{$selected_7}}>Vigencia 7 días</option>
              <option value="15" {{$selected_15}}>Vigencia 15 días</option>
              <option value="30" {{$selected_30}}>Vigencia 30 días</option>
              <option value="N/A" {{$selected_NA}}>N/A</option>
  					</select>
  					<button class="btn-save-info" id="btn_{{$cupon->id}}" data-id="{{$cupon->id}}">Guardar cambios</button>
  				</div>
  			</div>
      @endif
    @endforeach
  </div>

  @if(count($cupones) > 0)
    <div class="contenedor-select-copia">
      <small>Copiar cupones de otra sucursal</small>
      <select class="form-control form-control-sm" id="select-copiar-cupones">
        <option value="">- selecciona -</option>
        @foreach ($sucursales_fn as $sucs)
          @if($sucs->sucursal != $sucursal_url)
            <option value="{{$sucs->sucursal}}">{{$sucs->sucursal}}</option>
          @endif
        @endforeach
      </select>
    </div>
  @endif
@endsection

@section('js')
  <script type="text/javascript">
    let imgs = document.querySelectorAll('.inp-img');
    for(let i=0;i<imgs.length;i++)
    {
      imgs[i].addEventListener('change', cambiarImg);
    }
    function cambiarImg()
    {
      let id = this.dataset.id;

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
      if(size > 2097152)
      {
        this.value = '';
        return Swal.fire({
          icon:'info',
          text:'Las imagenes deben de ser máximo de 2mb',
        });
      }

      var fData = new FormData();
      fData.append('id', id);
      fData.append('img', this.files[0]);

      showLoader();

      fetch("{{route('cambiar_cupon')}}",{
        method:'post',
        body:fData,
        headers:{
          'X-CSRF-TOKEN': CSRF_TOKEN
        }
      }).then(res => res.json())
      .catch(error => console.log(error))
      .then(function(response){
        let _icon = 'info';
        if(response.status == 200){
          _icon = 'success';
          setTimeout(function(){location.reload();},300);
        }
        Swal.fire({
          icon:_icon,
          text: response.msg
        });
        hideLoader();
      });
    }


    let arr_btn_actualizar_descripcion = document.querySelectorAll('.btn-save-info');
    for(let j=0;j<arr_btn_actualizar_descripcion.length;j++)
    {
      arr_btn_actualizar_descripcion[j].addEventListener('click', saveDescripcion);
    }

    function saveDescripcion()
    {
      let id = this.dataset.id;
      if(id != '')
      {
        if(confirm("Los cambios son irreversibles ¿Estas seguro?"))
        {
          let txt_descripcion = document.getElementById('cupon_descripcion_'+id);
          let select_vigencia = document.getElementById('select_'+id);

          let descripcion =  txt_descripcion.value.trim();
          let vigencia = select_vigencia.value;

          if(descripcion == ''){
            return Swal.fire({icon:'info', text:'La descripción no puede ir vacía'});
          }
          if(select_vigencia.value == ''){
            vigencia = 'N/A';
          }
          showLoader();
          fetch("{{route('actualizar_info_cupon')}}",{
            method:'post',
            body: JSON.stringify({"id":id, "descripcion":descripcion, "vigencia":vigencia}),
            headers:{
              'Content-Type':'application/json',
              'X-CSRF-TOKEN' : CSRF_TOKEN
            }
          }).then(res => res.json())
          .then(function(response){
            let _icon = 'info';
            if(response.status == 200){
              _icon = 'success';
              setTimeout(function(){location.reload();},250);
            }
            Swal.fire({
              icon:_icon,
              text: response.msg
            });
            hideLoader();
          });
        }
      }
    }

    if(document.body.contains(document.getElementById('select-copiar-cupones'))){
      document.getElementById('select-copiar-cupones').addEventListener('change', copiarCupones);
    }

    function copiarCupones()
    {
      if(this.value != '')
      {
        if(confirm("Los cambios son irreversibles ¿Estás seguro?"))
        {
          showLoader();

          let s = this.value;
          fetch("{{route('copiar_cupones')}}",{
            method:'post',
            body:JSON.stringify({"desde" :s, "actual":"{{$sucursal_url}}" }),
            headers:{
              'Content-Type' : 'application/json',
              'X-CSRF-TOKEN': CSRF_TOKEN
            }
          }).then(res => res.json())
          .then(function(response){
            let _icon = 'info';
            if(response.status == 200){
              _icon = 'success';
              setTimeout(function(){location.reload();}, 400);
            }
            hideLoader();
            return Swal.fire({icon:_icon, text:response.msg});
          });
       }
      }
    }

  </script>
@endsection
