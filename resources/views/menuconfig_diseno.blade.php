@extends('master.logged')

@section('title')
Diseño Menú
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
        <iframe id="preview-frame" src="/menu-lang/{{$sucursal_url.'?'.rand(100,1500)}}"></iframe>

        
    </div>



    <div class="row" style="width:100%;">  
        <br><br><br>
        <h3>Diseño</h3><br>
        <form method="post" id="form-add-urls" class="form-add form-group-sm col-sm-5 col-lg-3" style="width:50%;" action="{{route('menu_savediseno')}}" onsubmit="return false;">
            @csrf
            <input type="hidden" value="" name="menu_seleccionado">
            <h4 style="width:100%;">Colores</h4>



            <div class="float-containerB">
                <div class="float-childB">
                  <input type="text" name="color_titulo" id="color_titulo" class="form-control" placeholder="Color de títulos" style="width:97%;" maxlength="30">
                </div>
                <div class="float-childB2">
                   <input type="color" id="color-header"  class="inputs-color" value="{{$colorHeader}}"/>
                </div>
            </div>
            <br><br>

            <div class="float-containerB">
                <div class="float-childB">
                  <input type="text" name="color_texto" id="color_texto" class="form-control" placeholder="Color de texto" style="width:97%;" maxlength="30">
                </div>
                <div class="float-childB2">
                   <input type="color" id="color-header"  class="inputs-color" value="{{$colorHeader}}"/>
                </div>
            </div>
            <br><br>

            <div class="float-containerB">
                <div class="float-childB">
                  <input type="text" name="color_fondo" id="color_fondo" class="form-control" placeholder="Color de fondo" style="width:97%;" maxlength="30">
                </div>
                <div class="float-childB2">
                   <input type="color" id="color-header"  class="inputs-color" value="{{$colorHeader}}"/>
                </div>
            </div>
            <br><br>

            <div class="float-containerB">
                <div class="float-childB">
                  <input type="text" name="color_botones" id="color_botones" class="form-control" placeholder="Color de botones" style="width:97%;" maxlength="30">
                </div>
                <div class="float-childB2">
                   <input type="color" id="color-header"  class="inputs-color" value="{{$colorHeader}}"/>
                </div>
            </div>



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

<script type="text/javascript"> 

    const selectMenu = document.getElementById('select_menu');
    selectMenu.addEventListener('change', function(){        
        if(this.value > 0){
            window.location ="{{route(Request::route()->getName())}}/{{$sucursal_url}}/"+this.value; 
        }
    });

</script>
@endsection