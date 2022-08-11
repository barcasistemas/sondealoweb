@extends('master.logged')

@section('title')
Categorias Menú
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
        <iframe id="preview-frame" src="/menu-categorias/{{$sucursal_url}}/?lenguaje1=es"></iframe>
    </div>



    <div class="row">  
        <br><br><br>
        <form method="post" id="form-add-urls" class="form-add form-group-sm col-sm-5 col-lg-3" action="" onsubmit="return false;">
            @csrf
            <input type="hidden" value="" name="menu_seleccionado">
            <h5 style="width:100%;">Categorias</h5>

            <label class="switch-categoria">Categoria 1</label>
            <label class="switch">
                <input type="checkbox">
                <span class="slider round"></span>
              </label>

              <label class="switch-categoria">Categoria 2</label>
              <label class="switch">
                  <input type="checkbox">
                  <span class="slider round"></span>
                </label>
                
                <label class="switch-categoria">Categoria 3</label>
                <label class="switch">
                    <input type="checkbox">
                    <span class="slider round"></span>
                  </label>
                  
                  <label class="switch-categoria">Categoria 4</label>
                  <label class="switch">
                      <input type="checkbox">
                      <span class="slider round"></span>
                    </label>
                    
                    <label class="switch-categoria">Categoria 5</label>
                    <label class="switch">
                        <input type="checkbox">
                        <span class="slider round"></span>
                      </label>
                      
                      <label class="switch-categoria">Categoria 6</label>
                      <label class="switch">
                          <input type="checkbox">
                          <span class="slider round"></span>
                        </label>
                        
                        <label class="switch-categoria">Categoria 7</label>
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider round"></span>
                          </label>
                          
                          
                          <label class="switch-categoria">Categoria 8</label>
                          <label class="switch">
                              <input type="checkbox">
                              <span class="slider round"></span>
                            </label>              
              
            
            <button type="submit" name="btn-save-urls" id="btn-save-urls" class="btn btn-sm btn-primary">Guardar</button>
        </form>    

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