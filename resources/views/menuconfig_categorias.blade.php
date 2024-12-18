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

        <!--
        <div  style="width:90%;">
            <iframe style="float: right;" src="/menu-categorias/{{$sucursal_url}}/?lenguaje1=es"></iframe>
        </div>  -->


        @php
            $switches = ["","","","","","","","","","","",""];
            $video_check = ["","","","","","","","","","","",""];
            $switch_id = ["switch1","switch2","switch3","switch4","switch5","switch6","switch7","switch8","switch9","switch10","switch11","switch12"];
            $video_id = ["video1","video2","video3","video4","video5","video6","video7","video8","video9","video10","video11","video12"];
            $videoswitch_id = ["video_switch1","video_switch2","video_switch3","video_switch4","video_switch5","video_switch6","video_switch7","video_switch8","video_switch9","video_switch10","video_switch11","video_switch12"];
        @endphp
        
            
            <br><br>
            <h3>Categorias</h3><br>

            <div class="float-container">
               
                <div class="float-child">
            @for ($it = 1; $it <= 12; $it++)
            @if ($state_cat[$it-1] == 1)
                @php
                    $switches[$it-1] = "checked";
                @endphp
            @endif

            @if ($video_switch[$it-1] == 1)
                @php
                    $video_check[$it-1] = "checked"
                @endphp
            @endif

            @php
                $id_categorias = $categorias[$it-1]->id;
            @endphp
            <div class="row" > 
                <form method="post" id="form-add-urls" class="form-add form-group-sm col-sm-5 col-lg-3" style="width:80%;" action="{{route('menu_savecategorias')}}">
                    @csrf
                    <input type="hidden" value="{{$it}}" name="menu_id" id="menu_id">
        
                    <label class="switch-categoria" style="width: 90%;">Categoria {{$it}}</label>
                    <label class="switch">
                        <input type="checkbox" id="{{$switch_id[$it-1]}}" name="{{$switch_id[$it-1]}}" {{$switches[$it-1]}} value="1">
                        <span class="slider round"></span>
                    </label>
        
                    <input type="text" name="nombre_categoria" id="nombre_categoria" value="{{$name_cat[$it-1]}}" class="form-control" placeholder="Nombre" maxlength="30"><br><br>
                  <!--  <input type="text" name="id_video" id="id_video" value="{{$id_video[$it-1]}}" class="form-control" placeholder="id Video" maxlength="50"><br> -->

                    <label class="switch-categoria" style="width:90%;">
                        <input type="text" name="{{$video_id[$it-1]}}" id="{{$video_id[$it-1]}}" class="form-control" placeholder="id Video" value="{{$id_video[$it-1]}}">
                          </label>
                        <label class="switch" style="float: right;">
                            <input type="checkbox" id="{{$videoswitch_id[$it-1]}}" name="{{$videoswitch_id[$it-1]}}" value="1" {{$video_check[$it-1]}}>
                            <span class="slider round"></span>
                          </label>

                    <input id="id_categoria" name="id_categoria" type="hidden" value="{{$it}}">
        
                    <h5 style="width:100%;">Foto de portada</h5>
                    <label class="switch-categoria">Tamaño recomendado 255px X 255px</label>
                    <label class="switch-categoria">Tipo de archivo: JPG y PNG</label>
                    
                    <label></label>
                    <input type="file" name="btn-save-urls" id="btn-save-urls" class="btn btn-sm btn-primary" style="float: right;">
                    <br><br> <br>  
                    
                  <!--  <button href="/menu-platillos/{{$sucursal_url}}/{{$id_menu}}/{{$id_categorias}}" name="btn-save-urls" id="btn-save-urls" class="btn btn-sm btn-primary" style="float: right;">Editar Platillos</button> -->
                    <a class="btn btn-sm btn-primary" name="btn-save-urls" id="btn-save-urls" href="/sitio/menu-platillos/{{$sucursal_url}}/{{$id_menu}}/{{$id_categorias}}" style="float: right;">Editar Platillos</a>
                    
                    <br><br><br><br>
                    <div style="float: right;">
                        <button type="submit" name="btn-save-urls" id="btn-save-urls" class="btn btn-sm btn-primary">Guardar Cambios</button>
                    </div>
                </form> 
                </div> 
                <br>
            @endfor  
            </div>   
            
            <div class="float-child">
                <iframe id="preview-frame" style="width:90%; height:1150px;" src="/sitio/menu-categorias/{{$sucursal_url}}/?lenguaje1=es"></iframe>
            </div>
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