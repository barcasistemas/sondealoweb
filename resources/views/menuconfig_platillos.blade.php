@extends('master.logged')

@section('title')
Platillos Menú
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

    <!--
    <div  style="width:90%;">
        <iframe style="float: right;" src="/menu-categorias/{{$sucursal_url}}/?lenguaje1=es"></iframe>
    </div>  -->


    @php
        $switches = ["","","","","","","","","","","",""];
        $switch_id = ["switch1","switch2","switch3","switch4","switch5","switch6","switch7","switch8","switch9","switch10","switch11","switch12"];
    @endphp
    
        
        <br><br>
        <h3>Platillos</h3><br>

        <div class="float-container">
           
            <div class="float-child">


        @foreach ($array_items as $item)

    <div class="row" > 
        <form method="post" id="form-add-urls" class="form-add form-group-sm col-sm-5 col-lg-3" style="width:80%;" action="{{route('menu_updateplatillo')}}">
            @csrf
            

            <label class="switch-categoria" style="width: 90%;">Platillo </label>
            
            <input type="text" name="nombre_platillo" id="nombre_platillo" value="{{$item->nombre}}" class="form-control" placeholder="Nombre" maxlength="30"><br><br>
            <input type="text" name="ingredientes_platillo" id="ingredientes_platillo" value="{{$item->ingredientes}}" class="form-control" placeholder="Ingredientes"><br><br>
            <input type="text" name="precio_platillo" id="precio_platillo" value="{{$item->precio}}" class="form-control" placeholder="Precio" maxlength="30"><br><br>
            <input type="text" name="recomen_platillo" id="recomen_platillo" value="{{$item->recomen}}" class="form-control" placeholder="Recomendación" maxlength="30"><br><br>
            <input id="id_platillo" name="id_platillo" type="hidden" value="{{$item->id}}">
            <input id="id_categoria" name="id_categoria" type="hidden" value="{{$item->id_categoria}}">

            <h5 style="width:100%;">Foto de platillo</h5>
            @php $imagenes = $item->imagenes_url ; @endphp

            @foreach ($imagenes as $img)
            <img id="" height="150px" width="150px" class="bienvenido" src="{{$img->ruta_servidor}}"/>   
            @endforeach

            <br>
            <label class="switch-categoria">Tamaño recomendado 255px X 255px</label>
            <label class="switch-categoria">Tipo de archivo: JPG y PNG</label>
            
            <label></label>
            <input type="file" name="btn-save-urls" id="btn-save-urls" class="btn btn-sm btn-primary" style="float: right;">+ Agregar Imagen</button>
            
            <br><br><br><br>
            <div style="float: right;">
                <button type="submit" name="btn-save-urls" id="btn-save-urls" class="btn btn-sm btn-primary">Guardar Cambios</button>
            </div>
        </form> 
        </div> 
        <br>
        @endforeach

        <br><br>
        <button href="" name="addplatillo" id="addplatillo" class="btn btn-sm btn-primary" style="float: right;">+Agregar Platillo</button>
        </div>   
        
        <div class="float-child">
            <iframe id="preview-frame" style="width:90%; height:1150px;" src="/sitio/menu-seccion/{{$sucursal_url}}/{{$id_categoria}}"></iframe>
        </div>
        </div>


        <div id="myModal" class="modal fade" tabindex="-1" style="height:110%;">
            <div class="modal-dialog" style="height:100%;">
              <div class="modal-content" style="height:100%;">
                <div class="modal-header">
                    
                    <h4 class="modal-title">Agregar platillo</h4>

                 <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                </div>
                <div class="modal-body" >
                    <form method="post" id="form-add-urls" class="form-add form-group-sm col-sm-5 col-lg-3" style="width:100%;" action="{{route('menu_saveplatillo')}}">
                        @csrf
                        
            
                        <label class="switch-categoria" style="width: 90%;">Platillo </label>
                        
                        <input type="text" name="nombre_platillo" id="nombre_platillo" value="" class="form-control" placeholder="Nombre" maxlength="30"><br><br>
                        <input type="text" name="ingredientes_platillo" id="ingredientes_platillo" value="" class="form-control" placeholder="Ingredientes"><br><br>
                        <input type="text" name="precio_platillo" id="precio_platillo" value="" class="form-control" placeholder="Precio" maxlength="30"><br><br>
                        <input type="text" name="recomen_platillo" id="recomen_platillo" value="" class="form-control" placeholder="Recomendación" maxlength="30"><br><br>
                        <input id="id_platillo" name="id_platillo" type="hidden" value="">
                        <input id="id_categoria" name="id_categoria" type="hidden" value="{{$id_categoria}}">
            
                        <h5 style="width:100%;">Foto de platillo</h5>
            
                        <br>
                        <label class="switch-categoria">Tamaño recomendado 255px X 255px</label>
                        <label class="switch-categoria">Tipo de archivo: JPG y PNG</label>
                        
                        <label>Imagen.jpg</label>
                        <button type="" name="btn-save-urls" id="btn-save-urls" class="btn btn-sm btn-primary" style="float: right;">+ Agregar Imagen</button>
                        
                        <br><br><br><br>
                        <div style="float: right;">
                            <button type="submit" name="btn-save-urls" id="btn-save-urls" class="btn btn-sm btn-primary">Guardar Cambios</button>
                        </div>
                    </form> 
                </div>
                <div class="center">
                    
                  <br>
                </div>
              </div>
            </div>
          </div>
        
    @endif
    

@endsection


@section('js')

    <script type="text/javascript">
      $(document).ready(function() {
           $('#addplatillo').on('click', function (e) {
            $('#myModal').modal('show');        
           });   
        });
       

    </script>
@endsection