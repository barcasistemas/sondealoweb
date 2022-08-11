
@php
session();
$varlang = '';
session()->forget('langu3');
$varlang = $_GET['lenguaje1'];
session(['langu3' => $varlang]);
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
            max-height: 150px;
            min-width: 150px;
            padding: 10px;
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
        }

        a span {
            position: absolute;
            top: 50%;
            left: 50%;
            margin-top: -1em;
            margin-left: -50%;
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

        .center{
            margin: auto;
            text-align: center;
            align-content: center;
            margin-top: 2rem;
        }

        .modal-content{
            background-color: rgba(0,0,0,.0001) !important;
        }

    </style>
</head>
<body>

    <header>
        <div class="container">
            @if ($varlang == 'es')
            <p class="text-center bienvenido">Categorias</p>
            @elseif ($varlang == 'en')
            <p class="text-center bienvenido">Categories</p>
            @endif
        </div>
    </header>

    <div id="myModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
          
            <div class="modal-content">

            <div class="modal-body">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">X</button>
               <!-- <iframe width="100%" height="315" src="https://www.youtube.com/embed/ZSPeXlLSO34?autoplay=1" title="video" frameborder="0" allow="autoplay" allowfullscreen></iframe> -->
               <div id="player"></div>
            </div>
            </div>
        </div>
      </div>
      
    

    <div class="center">
        @foreach ($array_categorias as $categoria)
        @if ($varlang == 'es')
           <a href="/sitio/menu-seccion/{{$sucursal}}/{{$categoria->id}}"><img class="bienvenido" src="https://sondealo.com/sitio/images{{$categoria->imagen_url}}"/><span>{{$categoria->nombre}}</span></a>

        @elseif ($varlang == 'en')
        <a href="/sitio/menu-seccion/{{$sucursal}}/{{$categoria->id}}"><img class="bienvenido" src="https://sondealo.com/sitio/images{{$categoria->imagen_url}}"/><span>{{$categoria->nombre_en}}</span></a>
        @endif        
        @endforeach
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js" integrity="sha256-eTyxS0rkjpLEo16uXTS0uVCS4815lc40K2iVpWDvdSY=" crossorigin="anonymous"></script>
   
    <script type="text/javascript">
        $(window).on('load', function() {
            $('#myModal').modal('show');
        });
    </script>    


    <script>
        var tag = document.createElement('script');
  
        tag.src = "https://www.youtube.com/iframe_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        var player;
        
        function onYouTubeIframeAPIReady() {
          player = new YT.Player('player', {
            height: '310',
            width: '380',
            videoId: 'K8mHPaUY-Iw',
            playerVars: { 'autoplay': 1, 'controls': 0 },
            events: {
              'onReady': onPlayerReady,
              'onPlaybackQualityChange': onPlayerPlaybackQualityChange,
              'onStateChange': onPlayerStateChange,
              'onError': onPlayerError
            }
          });
        }

        function onPlayerReady(event) {
          event.target.setVolume(0);
          event.target.playVideo();
        }

        var done = false;
        function onPlayerStateChange(event) {
          if (event.data == YT.PlayerState.PLAYING && !done) {
            
            done = true;
          }
        }
        function stopVideo() {
            
          player.stopVideo();
        }

        function onPlayerPlaybackQualityChange(){
            
        }

        function onPlayerError(){

        }
      </script>
  

</body>
</html>