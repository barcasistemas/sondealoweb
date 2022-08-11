@extends('master.logged')

@section('title')
  Promociones
@endsection

@section('css')
  <link rel="stylesheet" href="{{ asset('css/promociones.css')}}"/>
@endsection

@section('content')
  <div class="contenedor-superior">
    {!!$sucursales_html!!}
    <div class="container-form-add">
      @if(count($arreglo_promociones) > 0)
        {{-- MOSTRAR SOLO CUANDO Session::get('int_tour') == 1
        aparecera solo cuando la variable de sesion de tour este habilitada (cuenta nueva) --}}
        @if(Session::get('int_tour') == 1)
          <div class="alert alert-success" role="alert">
            <span class="tour-span">Paso 4.</span>
            Agrega en cada sucursal las 5 imágenes que quieras que tu cliente vea el finalizar la encuesta •
            se puede repetir imágenes
            <label class="tour-links-container">
              <a class="fa fa-arrow-left tour-link" href="{{route('vendedores')}}"></a>
              <a class="fa fa-arrow-right tour-link" href="{{route('mostrar_cupones')}}"></a>
            </label>
          </div>
        @endif
       {{-- ///////////////////////////////////////////////////////////////////////////////////// --}}
      @endif
    </div>
  </div>
  <div class="contenedor-promociones">

    @php
     $limite = ( Session::get('plan') == 2 ) ? 2 : 5 ;
     $it=0;
    @endphp


    @foreach ($arreglo_promociones as $promo)
        <div class="contenedor-imagenes">

        @if($it >= $limite)
          <div class="alert alert-danger" role="alert">
            Actualiza tu plan para agregar más promociones
          </div>
          <img src="{{asset('images/sondealogo.png')}}"/>
        @else
          <label for="promocion_{{$promo->id}}"> Cambiar imagen</label>
          <input type="file" data-prom="{{$promo->id}}" name="promocion_{{$promo->id}}" id="promocion_{{$promo->id}}"/>
          @php
            /*A remplazar unicamente con {{$promo->ruta}} una vez se actualicen todos los registros*/
            $str_url_arr = explode('/',$promo->ruta);
            $archivo_name = $str_url_arr[count($str_url_arr)-1];
            $it++;
          @endphp
          <img src="{{asset('images/promos').'/'.$archivo_name.'?'.rand(10,200)}}"/>
        @endif

        </div>
    @endforeach

  </div>

  @if(count($arreglo_promociones) > 0)
    <div class="contenedor-select-copia">
      <small>Copiar imagenes de otra sucursal</small>
      <select class="form-control form-control-sm" id="select-copiar-promociones">
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
  var file_inputs = document.querySelectorAll('.contenedor-imagenes input[type="file"]');
  for(let i=0;i<file_inputs.length;i++)
  {
    file_inputs[i].addEventListener('change', fnValidateImg);
  }

  function fnValidateImg()
  {
      var archivoVal = this.value;
      var extension  = extension =(archivoVal.substring(archivoVal.lastIndexOf("."))).toLowerCase();
      if(extension == '.jpeg' || extension == '.png' || extension == '.jpg')
      {
        let size = Number.parseInt(this.files[0].size);
        if(size > 2097152)
        {
          Swal.fire({
          icon:'info',
          text:'Las imagenes deben de ser máximo de 2mb',
           });
          this.value = '';
          return;
        }

        showLoader();

        var formData = new FormData();
        formData.append('prom', this.dataset.prom);
        formData.append('img', this.files[0]);
        formData.append('_token', CSRF_TOKEN);

        $.ajax({
             type:'POST',
             url: "{{route('cambiar_promocion')}}",
             data:formData,
             cache:false,
             contentType: false,
             processData: false,
             success:function(response)
             {
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
             },
             error: function(data){
                 console.log("error");
                 console.log(data);
             }
         });
      }
      else
      {
        Swal.fire({
          icon:'info',
          text:'Solo imagenes .png o .jpg',
        });
        this.value = '';
      }
  }

  if(document.body.contains(document.getElementById('select-copiar-promociones'))){
    document.getElementById('select-copiar-promociones').addEventListener('change', copiarPromociones);
  }

  function copiarPromociones()
  {
    if(this.value != '')
    {
      if(confirm("Los cambio son irreversibles ¿Estás seguro?"))
      {
        let s = this.value;
        showLoader();

        fetch("{{route('copiar_promociones')}}",{
          method:'post',
          body:JSON.stringify({"desde": s, "actual": "{{$sucursal_url}}" }),
          headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
          }
        }).then(error => error.json())
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


  form

@endsection
