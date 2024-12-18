@extends('master.logged')

@section('title')
Galería menú
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

    <div class="row">
        
        <form id="form-add-imagen" enctype='multipart/form-data' class="form-add form-group-sm col-sm-6 col-lg-8"  action="{{route('menu_item_store')}}" method="post" onsubmit="return false;">
            @csrf

            <h5 style="width:100%;">Agregar Imagen</h5>
            <input type="text" class="form-control" name="name" id="name" value="{{old('name')}}" placeholder="Nombre"/>
            <select name="categoria" id="categoria" class="form-control">
                <option value="-1">-- seleccionar categoria --</option>
                @forelse ($categorias as $categoria )
                    <option value="{{$categoria->id}}">{{$categoria->nombre}}</option>
                @empty
                    <option value="-2">empty</option>
                @endforelse
            </select>


            <input type="text" class="form-control" name="ingredientes" id="ingredientes" maxlength="255" value="{{old('ingredientes')}}" placeholder="Ingredientes" required/>
            <input type="number" class="form-control" name="precio" step="0.1" min="0" id="precio" value="{{old('precio')}}" placeholder="Precio" required/>



            <label for="image" id="label-for-image" class="btn btn-secondary btn-sm"><span class="fa fa-paperclip"></span> Imagen</label>
            <input type="file" id="image" name="image" value="{{old('image')}}" style="display:none;">
            <button id="btn-save-imagen" name="btn-save-imagen" class="btn btn-primary btn-sm" style="max-height:30px;">Agregar Imagen</button>
        </form>

        <div class="col-sm-1 col-lg-1"></div>

        <form method="post" id="form-add-categoria" class="form-add form-group-sm col-sm-5 col-lg-3" action="{{route('menu_categoria_store')}}" onsubmit="return false;">
            @csrf
            <input type="hidden" value="{{$id_menu}}" name="menu_seleccionado">
            <h5 style="width:100%;">Agregar Categoría</h5>
            <input type="text" name="name_categoria" id="name_categoria" class="form-control" maxlength="30">
            <button type="submit" name="btn-save-categoria" id="btn-save-categoria" class="btn btn-sm btn-primary">Agregar</button>
        </form>
        




    </div>


    @endif

    <div class="row contenedor-menu-items">

        @for ($i=0;$i<count($categorias);$i++ )
            
            <div class="col-sm-12 " style="max-height: 50px!important;overflow:hidden;margin:1rem 0;">
                <div class="alert alert-info" role="alert">{{Str::ucfirst($categorias[$i]->nombre)}}</div>
            </div>

            @php $items = (isset($categorias[$i]->items[0])) ? $categorias[$i]->items[0] : [] ; @endphp

            @foreach($items as $item )  

                @php $imagenes = $item->imagenes; @endphp  
                
                <div class="menu-item-layout" style="background-color:#f2f2f2;">
                    <div class="panel panel-default">

			<label data-item="{{$item->id}}" class="edit-item fa fa-edit text-info cursor-pointer"></label>                       

                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>{{Str::upper($item->nombre)}}</strong></h3>
                           
                            <p class="panel-ingredientes">{{$item->ingredientes}}</p>
                            <p class="fa fa-usd panel-precio">{{$item->precio}}</p>
                           
                           
                             <label data-item="{{$item->id}}" class="del-item">x</label>
                        </div>
                        <div class="panel-body" >
                            
                            @foreach ($imagenes as $img)
                                <div class="panel-img">
                                    <img src="{{$img->ruta_servidor}}?{{rand(0,30)}}" alt="imagen {{$item->nombre}}"/>
                                </div>
                            @endforeach
                            
                        </div>
                    </div>
                </div>
            @endforeach
        @endfor

    </div>

@endsection



@section('modal-title')
    <label id="label-nombre-editar"></label>  
@endsection

@section('modal-body')
    <div class="form-group-sm row">

        <input type="hidden" name="key-item-editar" id="key-item-editar">

        <div class="col-sm-12">
            <div class="col-sm-6" style="position: relative;">
                <img src="" id="img-item-editar" data-key="" style="max-width:140px;margin:auto;"/>
                <label for="inp-imagen-edit-item" role="button" class="btn btn-success btn-sm" style="position: absolute;bottom:5px;left:5px;">Cambiar</label>
                <input type="file" id="inp-imagen-edit-item" style="display: none;">
            </div>
            <div class="col-sm-6">
                <img src="" id="img-item-editar-previsualizar" style="max-width:140px;margin:auto;"/>
            </div>
        </div>

        <div class="col-sm-6">
            <label>*Nombre</label>
            <input type="text" class="form-control" name="txt-nombre-editar" id="txt-nombre-editar">
        </div>
    
        <div class="col-sm-6">
            <label>*Categorías</label>
            <select name="categoria-editar" id="categoria-editar" class="form-control">
                <option value="-1">-- seleccionar categoría --</option>
                @forelse ($categorias as $categoria )
                    <option value="{{$categoria->id}}">{{$categoria->nombre}}</option>
                @empty
                    <option value="-2">empty</option>
                @endforelse
            </select>
        </div>

        <div class="col-sm-12">
            <label>*Ingredientes</label>
            <input type="text" class="form-control" name="txt-ingredientes-editar" id="txt-ingredientes-editar">
        </div>

        <div class="col-sm-4">
            <label>*Precio</label>
            <input type="number" class="form-control" name="txt-precio-editar" id="txt-precio-editar">
        </div>    

    </div>

    
@endsection





@section('js')

<script type="text/javascript"> 

    const selectMenu = document.getElementById('select_menu');
    selectMenu.addEventListener('change', function(){        
        if(this.value > 0){
            window.location ="{{route(Request::route()->getName())}}/{{$sucursal_url}}/"+this.value; 
        }
    });

</script>

@if ($boolean_show_form)

<script type="text/javascript">

    document.addEventListener("DOMContentLoaded", function(){

        const btnSaveCat = document.getElementById('btn-save-categoria');
        const txtCat = document.getElementById('name_categoria');
        btnSaveCat.addEventListener('click', function(){

            if(txtCat.value.trim() != ''){
                showLoader();
                document.getElementById('form-add-categoria').submit();
            }
        });

        const btnSaveItem = document.getElementById('btn-save-imagen');
        const txtName = document.getElementById('name');
        const selectCategoria = document.getElementById('categoria');
        const inpImage = document.getElementById('image');
        const labelForImage = document.getElementById('label-for-image');

        const txtIngredientes = document.getElementById('ingredientes');
        const txtPrecio = document.getElementById('precio');




        inpImage.addEventListener('change', function(){

            var archivoVal = this.value;
            var extension  = extension =(archivoVal.substring(archivoVal.lastIndexOf("."))).toLowerCase();
            if(extension != '.jpeg' && extension != '.png' && extension != '.jpg')
            {
                this.value = '';
                labelForImage.style.color = '#ff0000';
                return Swal.fire({
                    icon:'info',
                    text:'Solo imagenes .jpg, .png',
                });
            }

            let size = Number.parseInt(this.files[0].size);
            if(size > 262144)
            {
                this.value = '';
                labelForImage.style.color = '#ff0000';
                return Swal.fire({
                    icon:'info',
                    text:'Las imagenes deben de ser máximo de 250kb',
                });
            }
            labelForImage.style.color = '#00ff00';
        });

        btnSaveItem.addEventListener('click', function(){
            let categoria = selectCategoria.value;
            let name = txtName.value.trim();

            if(name == '' || name.length < 5){
                return Swal.fire({
                    icon:'info',
                    text:'El nombre debe de ser al menos de 5 caracteres',
                });
            }

            if(categoria == '' || typeof categoria == 'undefined' || categoria < 1)
            {
                return Swal.fire({
                    icon:'info',
                    text:'Escoge una categoría',
                });
            }

            if(inpImage.value == ''){
                return Swal.fire({
                    icon:'info',
                    text:'Selecciona una imagen',
                });
            }

            if(txtIngredientes.value.trim() == ''){
                return Swal.fire({
                    icon:'info',
                    text:'Agregue los ingredientes',
                });
            }

            if( isNaN (txtPrecio.value.trim() ) ){
                return Swal.fire({
                    icon:'info',
                    text:'Precio no valido',
                });
            }


            
            showLoader();
            document.getElementById('form-add-imagen').submit();
        });


        const items = document.querySelectorAll('.del-item');
        items.forEach((item, i) => {
            item.addEventListener('click', deleteItem);
        });

        function deleteItem()
        {
            if(confirm("¿Estás seguro de eliminar este elemento?"))
            {
                showLoader();
                let elem = this;

                fetch('{{route("menu_item_delete")}}',{
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN' : CSRF_TOKEN,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({'item' : elem.dataset.item})
                })
                .then(response => response.json())
                .then( (res)=> {
                    let _icon = 'info';

                    if(res.status == 200)
                    {
                        _icon = 'success';
                        elem.parentNode.parentNode.parentNode.remove();
                    }

                    Swal.fire({
                        icon:_icon, 
                        text: res.msg
                    });

                    hideLoader();
                });
            }
        }




    });

</script>




<script type="text/javascript">


    const btnEditItem = document.querySelectorAll('.edit-item');
    const lbEditNombreItem = document.getElementById('label-nombre-editar');
    const txtEditNombreItem= document.getElementById('txt-nombre-editar');
    const selectEditCategoriaItem = document.getElementById('categoria-editar');
    const txtEditIngredientesItem = document.getElementById('txt-ingredientes-editar');
    const txtEditPrecioItem = document.getElementById('txt-precio-editar');
    const imgEditImagenitem = document.getElementById('img-item-editar');
    const keyEditItem = document.getElementById('key-item-editar');
    const imgEditImagenItemPrevisualizacion = document.getElementById('img-item-editar-previsualizar');
    const inpImgEditItem = document.getElementById('inp-imagen-edit-item');



    inpImgEditItem.addEventListener('change', function(){

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
        if(size > 262144)
        {
            this.value = '';
            return Swal.fire({
                icon:'info',
                text:'Las imagenes deben de ser máximo de 250kb',
            });
        }

        let url = URL.createObjectURL(this.files[0]);
        imgEditImagenItemPrevisualizacion.src = url;

    });




    btnEditItem.forEach((item, i) => {
        item.addEventListener('click', function(){
            showLoader();
            fetch("{{route('menu_item_get')}}/"+item.dataset.item)
              .then(response => response.json())
              .then(data => {

                    if(data.status == 200)
                    {
                        imgEditImagenitem.dataset.key = data.item.imagenes[0].id;
                        keyEditItem.value             = data.item.id;
                        lbEditNombreItem.innerText    = data.item.nombre.toUpperCase();    
                        txtEditNombreItem.value       = data.item.nombre;     
                        txtEditIngredientesItem.value = data.item.ingredientes;           
                        txtEditPrecioItem.value       = data.item.precio;     


                        selectEditCategoriaItem.querySelector('option[value="'+data.item.id_categoria+'"]').setAttribute('selected', true);
                        imgEditImagenitem.src = data.item.imagenes[0].ruta_servidor;

                        imgEditImagenItemPrevisualizacion.src="";
                        inpImgEditItem.value = '';


                        
                        showModal();
                    }
                    else{
                        Swal.fire({
                            icon:'warning',
                            text:data.msg
                        });
                    }
                                      
                    hideLoader();
                   
              });
        });
    });



    const btnSaveEdit = document.getElementById('btn-save-edit');

    btnSaveEdit.addEventListener('click', guardarEdicion);

    function guardarEdicion()
    {
        let key = keyEditItem.value;
        let nombre = txtEditNombreItem.value.trim();
        let categoria = selectEditCategoriaItem.value;
        let ingredientes = txtEditIngredientesItem.value.trim();
        let precio = txtEditPrecioItem.value;
        let img = inpImgEditItem.value;

        if(key == '' || nombre == '' || categoria == '' || precio == '') {
            return Swal.fire({icon:'warning', text: 'Los campos con * son obligatorios'});
        }


        let form = new FormData();
        form.append('key', key);
        form.append('nombre', nombre);
        form.append('categoria', categoria);
        form.append('ingredientes', ingredientes);
        form.append('precio', precio);
        form.append('img_key',  imgEditImagenitem.dataset.key);
        form.append('img', inpImgEditItem.files[0]);

        showLoader();

        fetch("{{route('menu_item_update')}}",{
           method:'post',
           headers:{
               'X-CSRF-TOKEN':CSRF_TOKEN,
           },
           body:form
        }).then(res => res.json())
        .then((response) => {
            let _icon = 'info';
            if(response.status == 200)
            {
                _icon = 'success';
                hideModal();
                setTimeout(function(){location.reload();}, 500);
            }
            Swal.fire({
                icon:_icon,
                text:response.msg
            });

            hideLoader();

        });
    }

</script>


@endif


    
@endsection
