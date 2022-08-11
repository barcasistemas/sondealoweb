@php
session();
$varlang = session('langu3');
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Menú -{{$sucursal}}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

    <style type="text/css">
        @import url('https://fonts.cdnfonts.com/css/athelas');
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Athelas', sans-serif;
        }
        body{
            background: rgb(255, 255, 255);
        }
        .container{
            margin: auto;
            width: 100%;
            max-width: 1024px;
            max-height: 150px;
        }
        .text-center{
            text-align: center;
        }
        header{
            width: 100%;
            height: 80px;
            background-color: rgb(255, 255, 255);
        }
     
        header .container{
            padding: 0px;
        }
       
        img{
            margin: auto;
            max-height: 450px;
            min-width: 150px;
            padding: 2px;
        }

        
        .bold{
            font-weight: bold;
        }

        p.bienvenido{
            padding: 2rem 0;
            font-size: 2rem;
            color: rgb(0, 0, 0);
        }
        h2{
            padding: 2rem;
        }

        a{
            position: relative;
            display: inline-block;
            text-decoration: none;
        }

        a p{
            color: #f90;
            text-decoration: none;
            font-size: 1rem;
        }

        a span {
            position: absolute;
            top: 50%;
            left: 50%;
            margin-top: -1em;
            margin-left: -50%;
            text-align: center;
            width: 100%;
            height: 2em;
            color: #f90;
            background-color: rgba(0,0,0,0.5);
         }​


        .form-select{
            width: 50%;
            margin: auto;
        }

        .btn-light{
            width: 40%;
            margin: auto;
            margin-top: 2rem;
            font-weight: bold;
        }

        .modal-dialog{
            height:95%
        }
        .modal-content{
            height:95%
        }
        .modal-body{
            height: 95%;
            overflow:auto;
            align-content: center;
        }

        .center{
            margin: auto;
            text-align: center;
            align-content: center;
            margin-top: 0.5rem;
        }

        #rcorners2 {
          background-color: rgb(104, 104, 104)  
          border-radius: 25px;
          border: 2px solid #73AD21;
          padding: 20px;
          width: 200px;
          height: 150px;
        }

        .wrapper1, .wrapper2{
            width: 100%; 
            border: none 0px RED;
            overflow-x: scroll; 
            overflow-y:hidden;
        }
        .wrapper1{
            height: 20px; 
        }
        
        .wrapper2{
            height: 150px; 
        }
        
        .div1 {
            width:1500px;
            height: 20px; 
        }
        
        .div2 {
            width: max-content; 
            height: 150px; 
            background-color: #ffffff;
            overflow: auto;
            max-height: 150px;
        }

        table{
            margin-top: 100px;
        }

    </style>
</head>
<body>

    <header>
        <div class="container">
          <!--  <p class="text-center bienvenido">Categorias</p> -->
        <div class="wrapper2">
            <div class="div2">
                @foreach ($array_categorias as $categoria)
                @if ($varlang == 'es')
                <a href="/sitio/menu-seccion/{{$sucursal}}/{{$categoria->id}}"><img width="150px" height="150px" class="bienvenido" src="https://sondealo.com/sitio/images{{$categoria->imagen_url}}"/><span>{{$categoria->nombre}}</span></a>
                @elseif ($varlang == 'en')
                   <a href="/sitio/menu-seccion/{{$sucursal}}/{{$categoria->id}}"><img width="150px" height="150px" class="bienvenido" src="https://sondealo.com/sitio/images{{$categoria->imagen_url}}"/><span>{{$categoria->nombre_en}}</span></a>
                @endif
                @endforeach
            </div>
        </div>
        </div>
    </header>

    <table style="width: 100%">
        @php $contador = 1; @endphp
         @foreach ($array_items as $item)
         @php $imagenes = $item->imagenes_url ; @endphp
         
         <tr>
            
            <td style="width: 50%" align="center">
                @foreach ($imagenes as $img)
                
                <img id="{{$contador}}" height="150px" width="150px" class="bienvenido" src="{{$img->ruta_servidor}}"/>
               
              @endforeach
               
            </td>
            <td style="width: 50%" align="center">
                <br>
                @if ($varlang == 'es')
                <div class="bold"><b> {{$item->nombre}} </b></div>
                <br>
                {{$item->ingredientes}}
                @elseif ($varlang == 'en')
                <div class="bold"><b> {{$item->nombre_en}} </b></div>
                <br>
                {{$item->ingredientes_en}}
                @endif
              
              <br>
             ${{$item->precio}}
               
            </td>
            
         </tr>
         
         <div id="myModal{{$contador}}" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                    @if ($varlang == 'es')
                    <h4 class="modal-title">{{$item->nombre}}</h4>
                    @elseif ($varlang == 'en')
                    <h4 class="modal-title">{{$item->nombre_en}}</h4>
                    @endif
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="{{$img->ruta_servidor}}"/>
                </div>
                <div class="center">
                    @if ($varlang == 'es')
                    <p><h4>Se recomienda acompañar con:</h4></p>
                    @elseif ($varlang == 'en')
                    <p><h4>It is recommended to accompany with:</h4></p>
                    @endif
                  @if ($varlang == 'es')
                  <a href="/sitio/menu-seccion/{{$sucursal}}/{{$item->recom_catid}}"><p>{{$item->recomen}}</p></a>
                  @elseif ($varlang == 'en')
                  <a href="/sitio/menu-seccion/{{$sucursal}}/{{$item->recom_catid}}"><p>{{$item->recomen_en}}</p></a>
                  @endif
                  
                  <br>
                </div>
              </div>
            </div>
          </div>
         
          @php
            $contador = $contador+1;
          @endphp

        @endforeach
    </table>


    
    

      <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
      <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js" integrity="sha256-eTyxS0rkjpLEo16uXTS0uVCS4815lc40K2iVpWDvdSY=" crossorigin="anonymous"></script>

      <script type="text/javascript">
      /*
        $('body').on('click','img',function(){
            $('#myModal').modal('show');
        })
        */

        /*
        $(document).ready(function() {
           $('#imgclick2').on('click', function (e) {
            $('#myModal2').modal('show');        
           });   
        });
        */
        $('body').on('click','img',function(){
          
            var id = this.id;
           // $('#'+id).on('click', function (e) {
            $('#myModal'+id).modal('show');        
          // });  
            
       });
         

      </script>

      <script type="text/javascript">
        $(function(){
          $(".wrapper1").scroll(function(){
             $(".wrapper2")
             .scrollLeft($(".wrapper1").scrollLeft());
          });
          $(".wrapper2").scroll(function(){
             $(".wrapper1")
             .scrollLeft($(".wrapper2").scrollLeft());
          });
        });
      </script>
</body>
</html>