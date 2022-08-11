<!DOCTYPE html>
<html lang="es-MX">
	<head>
		<meta type="utf-8"/>
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1,maximum-scale=1,minimum-scale=1"/>
		<title>Restablecer contraseña</title>
		<style type="text/css">
			*{
				margin: 0;
				padding: 0;
				box-sizing: border-box;
			}
			.contenedor{
				margin: auto;
				width: 100%;
				max-width: 400px;
				border: 1px solid #0658c9;
			}
			.contenedor > div{
				padding: 15px;
				width: 100%;
			}
			.contenedor > div p{
				margin-bottom: 1rem;
			}
			.enlace{
				display: block;
				text-align: center;
				padding: 15px;
				border: 1px solid #0658c9;
				text-decoration: none;
			}
			.contenedor > div small{
				display: block;
				margin-bottom: 10px;
				width: 100%;
			}
		</style>
	</head>
	<body>
		<div class="contenedor">
			<div>
				<p>Se ha solicitado un cambio de contraseña de su cuenta en SONDEALO</p>

				<p>Usuario: {{$usuario}}</p>

				<a class="enlace" href="{{$enlace}}">
					Cambiar contraseña
				</a>
			</div>
			<div>
				<p>Si tu no realizaste la solicitud de cambio de contraseña, ignora este mensaje</p>
				<small>Si tienes problemas al dar click en el boton "Cambiar contraseña" copia y pega el siguiente enlace en el navegador</small>

				<small>{{$enlace}}</small>
			</div>
		</div>
	</body>
</html>
