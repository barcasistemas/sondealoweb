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
        <iframe id="preview-frame" src="/menu-lang/{{$sucursal_url.'?'.rand(100,1500)}}"></iframe>

        
    </div>



    <div class="row">  
        <br><br><br>
        <h3>Perfil del restaurante 1</h3><br>
        <form method="post" id="form-add-urls" class="form-add form-group-sm col-sm-5 col-lg-3" action="" onsubmit="return false;">
            @csrf
            <input type="hidden" value="" name="menu_seleccionado">
            <h4 style="width:100%;">Perfil</h4>
            <input type="text" name="url_youtube" id="url_youtube" class="form-control" placeholder="Nombre" maxlength="30"><br>
            <input type="text" name="url_facebook" id="url_facebook" class="form-control" placeholder="Dirección" maxlength="30"><br>
            <input type="text" name="url_instagram" id="url_instagram" class="form-control" placeholder="Teléfono" maxlength="30"><br>

            <h4 style="width:100%;">Logotipo</h4>
            <label class="switch-categoria">Tamaño recomendado 255px X 255px</label>
            <label class="switch-categoria">Tipo de archivo: JPG y PNG</label>
            <label class="switch">
                <input type="checkbox">
                <span class="slider round"></span>
              </label>
            
              <label>Imagen.jpg</label>
            <button type="submit" name="btn-save-urls" id="btn-save-urls" class="btn btn-sm btn-primary">+ Agregar Imagen</button>

            <h4 style="width:100%;">Idiomas</h4>
            <label class="switch-categoria">Español</label>
            <label class="switch">
                <input type="checkbox">
                <span class="slider round"></span>
              </label><br>
            <label class="switch-categoria">English</label>
            <label class="switch">
                <input type="checkbox">
                <span class="slider round"></span>
              </label>
              <button type="submit" name="btn-save-urls" id="btn-save-urls" class="btn btn-sm btn-primary">+ Agregar Idioma</button>

              <h4 style="width:100%;">Redes Sociales</h4>
            <input type="text" name="url_youtube" id="url_youtube" class="form-control" placeholder="URL Facebook" maxlength="30">
            <label class="switch">
                <input type="checkbox">
                <span class="slider round"></span>
              </label>
            <input type="text" name="url_facebook" id="url_facebook" class="form-control" placeholder="URL Instagram" maxlength="30">
            <label class="switch">
                <input type="checkbox">
                <span class="slider round"></span>
              </label>
            <input type="text" name="url_instagram" id="url_instagram" class="form-control" placeholder="URL TikTok" maxlength="30">
            <label class="switch">
                <input type="checkbox">
                <span class="slider round"></span>
              </label>
            <input type="text" name="url_instagram" id="url_instagram" class="form-control" placeholder="URL YouTube" maxlength="30">
            <label class="switch">
                <input type="checkbox">
                <span class="slider round"></span>
              </label>
            <input type="text" name="url_instagram" id="url_instagram" class="form-control" placeholder="URL Número WhatsApp" maxlength="30">
            <label class="switch">
                <input type="checkbox">
                <span class="slider round"></span>
              </label>

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