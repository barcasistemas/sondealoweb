<!DOCTYPE html>
<html lang="es-MX">
	<head>
		<meta type="utf-8"/>
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1,maximum-scale=1,minimum-scale=1"/>
		<title>Vista Previa</title>
		<style type="text/css">
			*{
				margin: 0;
				padding: 0;
				box-sizing: border-box;
			}

			header, section{
				width: 100%;
			}
			.contenedor{
				margin: auto;
				width: 98%;
			}
			header{
				padding: 5px;
				height: 70px;
				color: white;
				text-align: center;
			}
			header img{
				margin: auto;
				display: block;
				height: 40px;
			}
			.principal{
				padding: 15px 0 30px 0;
				width: 100%;
				max-width: 550px;
				position: relative;
			}
			.principal fieldset{
				padding: 10px;
				border-style: none;
				margin-top: -5px;
			}
			.tipo-0 img, .tipo-1 img{
				margin-right: 20px;
				height: 23px;
			}
			.tipo-3 input, .tipo-6 input, .tipo-9 select{
				height: 25px;
				width: 50%;
			}
			.tipo-4 button{
				height: 25px;
				margin-right: 10px;
				width: 22%;
				border-style: none;
				color: white;
				border-radius: 5px;
			}
			.tipo-4 button:nth-child(2){
				background: #e31010;
			}
			.tipo-4 button:nth-child(3){
				background: #feae02;
			}
			.tipo-4 button:nth-child(4){
				background: #63b503;
			}
			.tipo-4 button:nth-child(5){
				background: #2c6903;
			}
			.tipo-5 img{
				margin-right: 15px;
				height: 23px;
			}
			.correo-comentarios input{
				width: 100%;
				padding:7px;
			}
			.botones-final button{
				width: 40%;
				padding: 5px;
				color: white;
				border-style: none;
				border-radius: 5px;
			}
			.btn-no-contestar{
				float: left;
				background-color: #e31010;
			}
			.btn-finalizar{
				float: right;
				background-color: #0456a3;
			}
		</style>
	</head>
	<body>
		<header style="background-color: {{$colorHeader}};" >
			<div class="contenedor">
				<img src="{{asset('images/sondealogo.png')}}"/>
				Ayúdanos, contestando las siguientes preguntas
			</div>
		</header>
		<section>
			<div class="contenedor principal">
        {!! $html !!}
			</div>
		</section>
	</body>
</html>
