@php
    session();
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Menú -{{$sucursal}}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
            background: rgb(0, 0, 0);
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
            background-color: #000;
        }
        header img{
            display: block;
            margin:auto;
            max-height: 40px;
        }
        header .container{
            padding: 10px;
        }
        img.bienvenido{
            display: block;
            margin: auto;
            max-height: 120px;
            margin-top: 5rem;
        }
        p.bienvenido{
            padding: 2rem 0;
            font-size: 2rem;
            color: #fff;
        }

        p.selectlang{
            padding: 2rem 0;
            font-size: 1rem;
            color: #fff;
        }
        h2{
            padding: 2rem;
        }
        a.link-menu{
            margin: auto;
            display: block;
            width: 80%;
            margin-bottom: 2rem;
            padding: 1rem;
            background-color: #000;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            max-width: 400px;
            transition: all 0.5s;
        }
        a.link-menu:hover{
            border:2px solid #D6AF46;
            color: #D6AF46;
            background-color: #fff;
        }
        .links-media-container{
            width: 100%;
            padding: 1rem;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        a.link-social{
            display: block;
            margin-right: 10px;
            padding: 0.8rem;
            border-radius: 50%;
            font-size: 1.2rem;
            border:2px solid #D6AF46;
            color: #D6AF46;
            text-decoration: none;
        }
        a.link-social:nth-child(1){
            padding: 0.8rem 1rem!important;
        }

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

        .fa {
            padding: 20px;
            font-size: 30px;
            width: 70px;
            text-align: center;
            text-decoration: none;
            margin: 2px 2px;
            border-radius: 50%;
        }

        .fa:hover {
            opacity: 0.7;
        }

        .fa-facebook {
            /*
            background: #3B5998;
            color: white; */
            background: #000000;
            color: white;
            border-width: thin;
            border-style: solid;
            border-color: white;
        }

        .fa-whatsapp {
            /*
            background: #4bb766;
            color: white; */
            background: #000000;
            color: white;
            border-width: thin;
            border-style: solid;
            border-color: white;
        }

        .fa-twitter {
            background: #55ACEE;
            color: white;
        }

        .fa-google {
            background: #dd4b39;
            color: white;
        }

        .fa-linkedin {
            /*
            background: #007bb5;
            color: white; */
            background: #000000;
            color: white;
            border-width: thin;
            border-style: solid;
            border-color: white;
        }

        .fa-youtube {
            background: #bb0000;
            color: white;
        }

        .fa-instagram {
            /*
            background: #125688;
            color: white; */
            background: #000000;
            color: white;
            border-width: thin;
            border-style: solid;
            border-color: white;
        }

        .fa-tiktok {
            background: #000000;
            color: white;
            border-width: thin;
            border-style: solid;
            border-color: white;
        }

    </style>
</head>
<body>

    <header>
        <div class="container">
            @if ($encuesta_switch == 1)
            <a class="btn btn-light" href="/sitio/qr-encuesta/{{$sucursal}}" style="font-weight:bold;">Encuesta o comentario</a>
            <!-- <button type="button" class="btn btn-light" style="font-weight:bold;">Comentarios y opiniones</button> -->
            @endif
        </div>
    </header>


    <div class="container">

        @if ($logo_switch == 1)
            <img class="bienvenido" src="https://sondealo.com/sitio/images/{{$image_url}}"/>
        @endif
        <p class="text-center bienvenido">{{$name_comercial}}</p>
        <p class="text-center selectlang">Selecciona un idioma: </p>
        <form action="/sitio/menu-categorias/{{$sucursal}}" method="get"> 
        <div class="container">
            <!--
        <select id="lenguaje1" name="lenguaje1" class="form-select">
            <option value="es" selected>Español</option>
            <option value="en">English</option>
          </select>
        -->
          <div class="center"> 
         <!-- <input type="submit" id="btnAceptar" class="btn btn-light" name="submit" value="Aceptar" /> -->
        </div>
        </div> 
        </form> 

        <div class="center">  
            @if ($esp_switch == 1)
                 <a class="btn btn-outline-light" id="btnAceptar" href="/sitio/menu-categorias/{{$sucursal}}/?lenguaje1=es">Español</a>
            @endif
          <!--- <button type="button" id="btnAceptar" class="btn btn-light">Aceptar</button> --->
        </div>
        <div class="center">
            @if ($eng_switch == 1)
                 <a class="btn btn-outline-light" id="btnAceptar" href="/sitio/menu-categorias/{{$sucursal}}/?lenguaje1=en">English</a>
            @endif 
        </div>
  
        <div class="center">
            @if ($facebook_switch == 1)
                 <a href="{{$facebook_url}}" class="fa fa-facebook"></a>
            @endif
            @if ($insta_switch == 1)
                 <a href="{{$insta_url}}" class="fa fa-instagram"></a>
            @endif
            @if ($whatsapp_switch == 1)
                 <a href="https://wa.me/{{$whatsapp_url}}?text=Quiero%20hacer%20una%20reservación" class="fa fa-whatsapp"></a>
            @endif
            @if ($tiktok_switch == 1)
                 <a href="{{$tiktok_url}}" class="fa fa-tiktok"></a>
            @endif
            
        </div>

        <div class="center">
            @if ($url_switch == 1)
                 <a class="btn btn-outline-light" id="btnAceptar" href="{{$page_url}}">Sitio Web</a>
            @endif
        </div>
        

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js" integrity="sha256-eTyxS0rkjpLEo16uXTS0uVCS4815lc40K2iVpWDvdSY=" crossorigin="anonymous"></script>

    <script type="text/javascript">
       $(document).ready(function() {
        $('#btnAceptar').on('click', function (e) {

        var e = document.getElementById("lenguaje1");
        var lang = e.value;

        if(lang == 'es'){
            
          //  window.location = "/menu-categorias/{{$sucursal}}";
        }

       if(lang == 'en'){
            
         //  window.location = "/menu-categorias/{{$sucursal}}";
       }
      
        if(lang == 0){
            alert("Selecciona un idioma");
        }  
       });
    });
    </script>

</body>
</html>
