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

        /*
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Athelas', sans-serif;
        }
        */
        body {
            background-color: #090909;
        }

        .container {
            margin: auto;
            width: 100%;
            max-width: 1024px;
            max-height: 250px;
        }

        .container0 {
            margin-top: 20px;
            width: 100%;
            max-width: 1024px;
            max-height: 80px;
        }

        .text-center {
            text-align: center;
        }

        header {
            width: 100%;
            height: 80px;
            background-color: #090909;
        }

        header .container {
            padding: 0px;
        }

        img {
            margin: auto;
            max-height: 450px;
            
            padding: 2px;
        }


        .bold {
            font-weight: bold;
            color: #fff;
        }

        p.bienvenido {
            padding: 2rem 0;
            font-size: 2rem;
            color: rgb(0, 0, 0);
        }

        h2 {
            padding: 2rem;
        }

        a {
            position: relative;
            display: inline-block;
            text-decoration: none;
        }

        a p {
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
            background-color: rgba(0, 0, 0, 0.5);
        }

        ​ .form-select {
            width: 50%;
            margin: auto;
        }

        .btn-light {
            width: 40%;
            margin: auto;
            margin-top: 2rem;
            font-weight: bold;
        }

        .modal-dialog {
            height: 95%
        }

        .modal-content {
            height: 95%
        }

        .modal-body {
            height: 95%;
            overflow: auto;
            align-content: center;
        }

        .center {
            margin: auto;
            text-align: center;
            align-content: center;
            margin-top: 0.5rem;
        }

        #rcorners2 {
            background-color: rgb(104, 104, 104) border-radius: 25px;
            border: 2px solid #73AD21;
            padding: 20px;
            width: 200px;
            height: 150px;
        }

        .wrapper1,
        .wrapper2 {
            width: 100%;
            border: none 0px RED;
            overflow-x: scroll;
            overflow-y: hidden;
        }

        .wrapper1 {
            height: 20px;
        }

        .wrapper2 {
            height: 200px;
        }

        .div1 {
            width: 1500px;
            height: 20px;
        }

        .div2 {
            width: max-content;
            height: 150px;
            background-color: #ffffff;
            overflow: auto;
            max-height: 150px;
        }

        table {
            border-collapse: separate;
            border-spacing: 0rem 1rem;
            background-color: transparent;
            border: 1px solid black;
        }

        .textMenu {
            color: #fff;

        }

        .backTable {
            background-color: #1a1a1a;
            height: 25%;
        }

        tr.menu {
            background-color: #1a1a1a;
        }

        td.menu{
            padding: 5px;
        }

        .nav {
            background-color: #AF2423;


        }

        .nav:has(.dropdown:target) {


            --clip: inset(0 0 0 0);

        }

        .nav__container {
            width: 100%;

            height: 50px;

            display: grid;
            
            align-items: center;


        }

        .nav__title {
            color:#000;
            grid-area: title;
            float: center;
            text-align: center;
        }


        .nav__menu {
            grid-area: img;
            position: relative;
            z-index: 10;

            transform: var(--img1);
        }

        .nav__menu--second {
            transform: var(--img2);
        }

        auto .nav__icon {
            width: 30px;
            display: block;
        }

        .dropdown {
            position: absolute;
            background-color: #000;
            width: 100%;
            height: 50%;

            bottom: 0;

            padding: 1em;

            display: grid;
            align-content: center;
            gap: 1rem;

            overflow-y: auto;


            clip-path: var(--clip, inset(100% 0 0 0));
            transition: clip-path .5s;
        }

        .dropdown__list {
            list-style: none;
        }

        .dropdown__link {
            color: rgb(184, 187, 191);
            padding: 1em .7em;
            text-decoration: none;

            display: flex;
            align-items: center;
            gap: .6rem;

            position: relative;
            background-color: var(--bg, transparent);
            border-radius: 6px;
        }

        .dropdown__link2 {
            color: rgb(184, 187, 191);
            text-decoration: none;
            float: right;
            background-color: var(--bg, transparent);
            border-radius: 1px;
        }

        .dropdown__list:has( :checked) {
            --rows: 1fr;
            --rotate: rotate(180deg);
            --bg: #28303B;
        }

        .dropdown__check {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .dropdown__arrow {
            margin-left: auto;
            transform: var(--rotate, 0);
            transition: .2s transform;
        }

        .dropdown__content {
            display: grid;
            grid-template-rows: var(--rows, 0fr);
            transition: .5s grid-template-rows;
        }

        .dropdown__sub {
            overflow: hidden;

        }

        .dropdown__li {
            width: 85%;
            list-style: none;
            margin-left: auto;
        }

        .dropdown__anchor {
            padding: 1em 0;
            display: block;
            color: #B6BABE;
            text-decoration: none;
        }

        a.bienvenido {
            padding-top: 1.5rem;
            font-size: 1.2rem;
            color: #fff;
            font-weight: bold;
        }

        .centertext {
            
            float: center;
            align-items: center;
            width: 100%;
        }

        .text-center {
            text-align: center;
        }

        p.title{
            font-size: 1rem;
            font-weight: bold;
            margin-top: 10px;
        }

        .whiteimage {
            color: #fff;
}

.searchtext{
    width: 90%;
    margin-left: 20px;
    margin-top: 0px;
    background-color: transparent;
    border-color: #28303B;
}

img.filtro{
    max-width: 30px;
    max-height: 30px;
}

table.menu{
    margin-top: 290px;
}


    </style>
</head>

<body>

    @php
    $idvideo_cat = "";
    $switchvideo_cat = "";
    @endphp
    <header>

        <nav class="nav">

            <div class="nav__container">
                
            
                
                <p class="text-center title">
                    Bienvenido

                    
                </p>

                <span class="iwt">

                
            </div>


                <ul class="dropdown" id="menu">



                    <li class="dropdown__list">
                        <div>
                            <span class="centertext" style="display: flex; justify-content: center;" width="100%">Seleccione un menú</span>
                            <a href="#" class="btn-close btn-close-white dropdown__link2">

                            </a>
                        </div>

                    </li>

                    <li class="dropdown__list">
                        <a href="#" class="dropdown__link">
                            <span class="dropdown__span">Menú Principal</span>
                        </a>
                    </li>


                    <li class="dropdown__list">
                        <a href="#" class="dropdown__link">
                            <span class="dropdown__span">Bebidas</span>
                        </a>
                    </li>

                    <li class="dropdown__list">
                        <a href="#" class="dropdown__link">
                            <span class="dropdown__span">Mixología</span>
                        </a>
                    </li>

                    <li class="dropdown__list">
                        <a href="#" class="dropdown__link">
                            <span class="dropdown__span">Postres</span>
                        </a>
                    </li>




                    <li class="dropdown__list">
                        <a href="#" class="dropdown__link">
                            <span class="dropdown__span">Ayuda</span>
                        </a>
                    </li>

                </ul>

            </div>



        </nav>

        <div class="container">
            <!--  <p class="text-center bienvenido">Categorias</p> -->
            <div class="wrapper2">

           
                <p class="text-center">
                    <a class="bienvenido" href="#menu">Menú Principal</a>
                    <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="28px" height="28px" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="text-center font-bold whiteimage">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd">

                    </path>
        </svg>
        </span>
                    <br>
                    <span class="textMenu">
                    Menú
                    </span>

                    <table style="width: 100%; max-height: 80px; margin-top: 40px;">
                    <thead>
                                        <tr>
                                            <th style="width: 90%;">
                                            <input type="text" name="search" placeholder="Búsqueda"
                                                    class="form-control searchtext" value="">
                                            </th>
                                            <th style="width: 10%;">
                                            <a href="#">
                                                    <img width="24px" height="24px" class="filtro" src="https://sondealo.com/sitio/images/menu/filtrar.png" />
                                                    </a>
                                            </th>
                                        </tr>
                                    </thead>
                </table>
                    
                </p>

                

                                                    

                                                    
  
                                                    

            </div>
        </div>

        <div class="container0">
            <!--  <p class="text-center bienvenido">Categorias</p> -->
            <div class="wrapper2">
                <div class="div2">
                    @foreach ($array_categorias as $categoria)
                    @if ($categoria->id == $id)
                    @php
                    $idvideo_cat = $categoria->id_video;
                    $switchvideo_cat = $categoria->video_switch;
                    @endphp
                    @endif
                    @if ($varlang == 'es')
                    <a href="/sitio/menu-seccion/{{$sucursal}}/{{$categoria->id}}"><img width="50px" height="50px" class="bienvenido" src="https://sondealo.com/sitio/images{{$categoria->imagen_url}}" /><span>{{$categoria->nombre}}</span></a>
                    @elseif ($varlang == 'en')
                    <a href="/sitio/menu-seccion/{{$sucursal}}/{{$categoria->id}}"><img width="50px" height="50px" class="bienvenido" src="https://sondealo.com/sitio/images{{$categoria->imagen_url}}" /><span>{{$categoria->nombre_en}}</span></a>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </header>



    <!-- <label id="video_switch">{{$switchvideo_cat}}</label> -->
    <table style="width: 95%" align="center" margin-top: 50px" class="containter0 menu">
        @php $contador = 1; @endphp
        @foreach ($array_items as $item)
        @php $imagenes = $item->imagenes_url ; @endphp

        <tr class="spaceUnder backTable">


            <td style="width: 90%" align="center">
                <br>
                @if ($varlang == 'es')
                <div class="bold"><b>
                        <span class="textMenu">
                            {{$item->nombre}}
                        </span>
                    </b></div>

                <br>
                {{$item->ingredientes}}
                @elseif ($varlang == 'en')
                <div class="bold"><b>
                        <span class="textMenu">
                            {{$item->nombre_en}}
                        </span>
                    </b></div>
                <br>
                <span class="textMenu">
                    {{$item->ingredientes_en}}
                </span>
                @endif

                <br>
                <span class="textMenu">
                    ${{$item->precio}}
                </span>


            </td>

            <td style="width: 10%" align="center">
                @foreach ($imagenes as $img)

                <img id="{{$contador}}" height="90px" width="90px" class="bienvenido" src="{{$img->ruta_servidor}}" />

                @endforeach

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
                        <img src="{{$img->ruta_servidor}}" />
                    </div>
                    <div class="center">
                        @if ($varlang == 'es')
                        <p>
                        <h4>Se recomienda acompañar con:</h4>
                        </p>
                        @elseif ($varlang == 'en')
                        <p>
                        <h4>It is recommended to accompany with:</h4>
                        </p>
                        @endif
                        @if ($varlang == 'es')
                        <a href="/sitio/menu-seccion/{{$sucursal}}/{{$item->recom_catid}}">
                            <p>{{$item->recomen}}</p>
                        </a>
                        @elseif ($varlang == 'en')
                        <a href="/sitio/menu-seccion/{{$sucursal}}/{{$item->recom_catid}}">
                            <p>{{$item->recomen_en}}</p>
                        </a>
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

    <div id="myModalYT" class="modal fade" tabindex="-1">
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



    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js" integrity="sha256-eTyxS0rkjpLEo16uXTS0uVCS4815lc40K2iVpWDvdSY=" crossorigin="anonymous"></script>

    @if ($switchvideo_cat == 1)
    <script type="text/javascript">
        $(window).on('load', function() {
            $('#myModalYT').modal('show');
        });
    </script>
    @endif



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
                videoId: '{{$idvideo_cat}}',
                playerVars: {
                    'autoplay': 1,
                    'controls': 0
                },
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

        function onPlayerPlaybackQualityChange() {

        }

        function onPlayerError() {

        }
    </script>

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
        $('body').on('click', 'img', function() {

            var id = this.id;
            // $('#'+id).on('click', function (e) {
            $('#myModal' + id).modal('show');
            // });  

        });
    </script>

    <script type="text/javascript">
        $(function() {
            $(".wrapper1").scroll(function() {
                $(".wrapper2")
                    .scrollLeft($(".wrapper1").scrollLeft());
            });
            $(".wrapper2").scroll(function() {
                $(".wrapper1")
                    .scrollLeft($(".wrapper2").scrollLeft());
            });
        });
    </script>
</body>

</html>