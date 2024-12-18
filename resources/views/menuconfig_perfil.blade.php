@extends('master.logged')

@section('title')
Perfil Menú
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('css/galeria_menu.css')}}"/>

@endsection

@section('content')
<div class="contenedor-superior">
	{!!$sucursales_html!!}
	<div class="container-form-add">
	</div>
</div>

<div class="col-sm-12">
    <label>Menú: </label>
    <select name="select_menu" id="select_menu">
        <option value="-1">-- selecciona --</option>
        @forelse ($menus as  $m)
            @if($m->id != $id_menu)            
                <option value="{{$m->id}}">{{$m->nombre}}</option>
            @endif
        @empty
            <option value="-1">vacio</option>
        @endforelse
    </select>
</div>



    @if ($boolean_show_form)

    <div class="contenedor-preview" id="contenedor-preview">
      <br><br><br><br><br><br>
        <iframe id="preview-frame" src="/sitio/menu-lang/{{$sucursal_url.'?'.rand(100,1500)}}"></iframe>

        
    </div>


    @php
      $esp_check = "";
      $eng_check = "";
      $encuesta_check = "";
      $facebook_check = "";
      $tiktok_check = "";
      $insta_check = "";
      $whatsapp_check = "";
      $url_check = "";
      $youtube_check = "";
      $logo_check = "";
    @endphp
    @if ($esp_switch == 1)
    @php
      $esp_check = "checked";
    @endphp
    @endif
    @if ($eng_switch == 1)
    @php
      $eng_check = "checked";
    @endphp
    @endif
    @if ($encuesta_switch == 1)
    @php
      $encuesta_check = "checked";
    @endphp
    @endif
    @if ($url_switch == 1)
    @php
      $url_check = "checked";
    @endphp
    @endif
    @if ($facebook_switch == 1)
    @php
      $facebook_check = "checked";
    @endphp
    @endif
    @if ($tiktok_switch == 1)
    @php
      $tiktok_check = "checked";
    @endphp
    @endif
    @if ($insta_switch == 1)
    @php
      $insta_check = "checked";
    @endphp
    @endif
    @if ($whatsapp_switch == 1)
    @php
      $whatsapp_check = "checked";
    @endphp
    @endif
    @if ($youtube_switch == 1)
    @php
      $youtube_check = "checked";
    @endphp
    @endif
    @if ($logo_switch == 1)
    @php
      $logo_check = "checked";
    @endphp   
    @endif

    <div class="row" style="width:100%;">  
        <br><br><br>
        <h3>Perfil del restaurante</h3><br>
        <form method="post" id="form-add-urls" class="form-add form-group-sm col-sm-5 col-lg-3" style="width:50%;" action="{{route('menu_saveperfil')}}" >
            @csrf
            <input type="hidden" value="" name="menu_seleccionado">
            <h4 style="width:100%;">Perfil</h4>
            <input type="text" name="nombre_sucursal" id="nombre_sucursal" class="form-control" placeholder="Nombre" maxlength="30" value="{{$name_comercial}}"><br>
          <!--  <input type="text" name="dir_sucursal" id="dir_sucursal" class="form-control" placeholder="Dirección" maxlength="30"><br> -->
            <input type="text" name="url_web" id="url_web" class="form-control" placeholder="Sitio web" maxlength="30" value="{{$urlweb}}"><br>

            <h4 style="width:100%;">Logotipo</h4>
            <label class="switch-categoria">Tamaño recomendado 255px X 255px</label>
            <label class="switch-categoria">Tipo de archivo: JPG y PNG</label>
            <label class="switch">
                <input type="checkbox" id="logo_switch" name="logo_switch" value="1" {{$logo_check}}>
                <span class="slider round"></span>
              </label>
            
              <label></label>
            <input type="file" name="btn-save-urls" id="btn-save-urls" class="btn btn-sm btn-primary" style="float: right;">+ Agregar Imagen</button>

            <h4 style="width:100%;">Idiomas</h4>
            <label class="switch-categoria">Español</label>
            <label class="switch">
                <input type="checkbox" id="esp_switch" name="esp_switch" value="1" {{$esp_check}}>
                <span class="slider round"></span>
              </label>
            <!--  <button class="btn"><i class="fa fa-trash"></i></button> -->

              <br>
            <label class="switch-categoria">English</label>
            <label class="switch">
                <input type="checkbox" id="eng_switch" name="eng_switch" value="1" {{$eng_check}}>
                <span class="slider round"></span>
              </label>
            <!--  <button class="btn"><i class="fa fa-trash"></i></button> -->

            <!--
              <div style="float: right;">
              <button type="submit" name="btn-save-urls" id="btn-save-urls" class="btn btn-sm btn-primary">+ Agregar Idioma</button>
            </div> -->

              <h4 style="width:100%;">Redes Sociales</h4>

              <label class="switch-categoria" style="width:90%;">
            <input type="text" name="url_facebook" id="url_facebook"class="form-control" placeholder="URL Facebook" value="{{$facebookurl}}">
              </label>
            <label class="switch" style="float: right;">
                <input type="checkbox" id="facebook_switch" name="facebook_switch" value="1" {{$facebook_check}}>
                <span class="slider round"></span>
              </label>

             <br><br>
             <label class="switch-categoria" style="width:90%;">
              <input type="text" name="url_instagram" id="url_instagram" class="form-control" placeholder="URL Instagram" value="{{$instaurl}}">
            </label> 
            <label class="switch" style="float: right;">
                <input type="checkbox" id="insta_switch" name="insta_switch" value="1" {{$insta_check}}>
                <span class="slider round"></span>
              </label>

              <br><br>
              <label class="switch-categoria" style="width:90%;">
                <input type="text" name="url_tiktok" id="url_tiktok" class="form-control" placeholder="URL TikTok" value="{{$tiktokurl}}">
              </label>
            <label class="switch" style="float: right;">
                <input type="checkbox" id="tiktok_switch" name="tiktok_switch" value="1" {{$tiktok_check}}>
                <span class="slider round"></span>
              </label>

              <br><br>
              <label class="switch-categoria" style="width:90%;">
                <input type="text" name="url_youtube" id="url_youtube" class="form-control" placeholder="URL YouTube" value="{{$youtubeurl}}">
              </label>           
            <label class="switch" style="float: right;">
                <input type="checkbox" id="youtube_switch" name="youtube_switch" value="1" {{$youtube_check}}>
                <span class="slider round"></span>
              </label>

              <br><br>

              <label class="switch-categoria" style="width:90%;">
                <input type="text" name="num_whatsapp" id="num_whatsapp" class="form-control" placeholder="Número WhatsApp" maxlength="30" value="{{$whatsappurl}}">
              </label>
            <label class="switch" style="float: right;">
                <input type="checkbox" id="whatsapp_switch" name="whatsapp_switch" value="1" {{$whatsapp_check}}>
                <span class="slider round"></span>
              </label>

              <br>
              <h4 style="width:100%;">Encuesta</h4>
              <label class="switch-categoria" style="width: 90%;">
                <h5>Encuesta Sondealo</h5>
                <p>
                  ¿Desea agregar el botón en dónde podrán realizar la encuesta del servicio?
                </p>
              </label>
            <label class="switch">
                <input type="checkbox" id="encuesta_switch" name="encuesta_switch" value="1" {{$encuesta_check}}>
                <span class="slider round"></span>
              </label>

              <br><br>
              <div style="float: right;">
                <button type="submit" name="btn-save-urls" id="btn-save-urls" class="btn btn-sm btn-primary">Guardar Cambios</button>
              </div>
              

        </form>  
    </div>  

    @endif


@endsection


@section('js')

<script type="text/javascript">
    var coll = document.getElementsByClassName("collapsible");
    var i;

    for (i = 0; i < coll.length; i++) {
       coll[i].addEventListener("click", function() {
       this.classList.toggle("active");
       var content = this.nextElementSibling;
       if (content.style.display === "block") {
          content.style.display = "none";
        } else {
          content.style.display = "block";
    }
  });
} 
</script>

<script type="text/javascript"> 

    const selectMenu = document.getElementById('select_menu');
    selectMenu.addEventListener('change', function(){        
        if(this.value > 0){
            window.location ="{{route(Request::route()->getName())}}/{{$sucursal_url}}/"+this.value; 
        }
    });

</script>
@endsection