@extends('master.logged')

@section('title')
Menú QR
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

    <div class="row" style="width:100%;">  
        <br><br><br>
        <h3>Menu QR</h3><br>
        @php
          $menu_url = "www.sondealo.com/sitio/menu-lang/".$sucursal_url;
        @endphp
        
        <div id="qr" style="width:300px;height:300px;padding:15px;border:1px solid rgba(0,0,0,0.4);">
          {!! QrCode::errorCorrection('H')->size(270)->generate($menu_url) !!}
        </div>
        <button class="btn btn-success btn-sm" style="margin-top: 5px;margin-bottom:10px;margin-left:15px;" id="btn-save-qr">Guardar QR</button>
        @php
          $arr_archivo = explode('/', $menu_url);
          $str_archivo = $arr_archivo[count($arr_archivo)-1];
        @endphp

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