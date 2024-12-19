<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Menu {{$sucursal}}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style type="text/css">
        @import url('https://fonts.cdnfonts.com/css/athelas');
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Athelas', sans-serif;
        }
        body{
            background: snow;
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
            max-height: 50px;
        }
        header .container{
            padding: 10px;
        }
        img.bienvenido{
            display: block;
            margin: auto;
            max-height: 40px;
            margin-top: 1rem;
        }
        p.bienvenido{
            padding: 2rem 0;
            font-size: 1.2rem;
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

    </style>
</head>
<body>

    <header>
        <div class="container">
            <img src="{{asset('images/moriclogo.png')}}"/>
        </div>
    </header>


    <div class="container">

        <img class="bienvenido" src="{{asset('images/moric_bienvenidos.png')}}"/>
        <p class="text-center bienvenido">Elige el menú que deseas consultar</p>

        @forelse ($arreglo_enlaces as $col => $val)

            <a href="{{$val}}" class="link-menu text-center" target="_blank">{{mb_strtoupper($col, 'UTF-8')}}</a>
            
        @empty
            Sin info
        @endforelse

        <div class="links-media-container">
            <a href="https://www.facebook.com/moric.mx" target="_blank" class="link-social fa-brands fa-facebook-f"></a>
            <a href="https://www.instagram.com/moric.mx/" target="_blank" class="link-social fa-brands fa-instagram"></a>
            <a href="tel:8717933027" target="_blank" class="link-social fa-solid fa-phone"></a>
        </div>



    </div>
    
</body>
</html>
