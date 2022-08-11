@extends('master.logged')

@section('title')
Reportes
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('css/reportes.css')}}"/>
@endsection

@section('content')
<div class="contenedor-superior">
	{!!$sucursales_html!!}
	<div class="container-form-add">
	</div>
</div>

<div class="contenedor-date">
	@if($boolean_show)
	<fieldset>
		Desde
		<input type="date" class="form-control input-sm" id="desde" name="desde" value="{{ (!empty($desde)) ? substr($desde, 0, 10) : date('Y-m-d') }}"/>
	</fieldset>
	<fieldset>
		Hasta
		<input type="date" class="form-control input-sm" id="hasta" name="hasta" value="{{(!empty($hasta)) ? $hasta : date('Y-m-d')}}"/>
	</fieldset>
	<button class="fa fa-pie-chart btn btn-success btn-sm" id="btn-generar-reporte"> Generar reporte</button>
	@else
	<div class="alert alert-info" role="alert">
		Seleccione una sucursal primero
	</div>
	@endif
</div>



<div id="contenedor-reporte" class="mostrar">
	{{-- comienza info --}}
	@if(count($promedios_preguntas) > 0)

	<div class="info bg-light">
		<div class="card" >
			<div class="card-body">
				<p class="card-text">
					<strong>Periodo:</strong> {{substr($desde,0, 10)}} - {{substr($hasta,0, 10)}}
				</p>
				<p class="card-text">
					<strong>Contestadas:</strong> {{$contestadas}}
				</p>
				<p class="card-text">
					<strong>No contestadas:</strong> {{$no_contestadas}}
				</p>
				<p class="card-text">
					<strong>Calificación:</strong> <span style="color:{{$promedios_preguntas[0]->css_prom_gnral}};">{{$promedios_preguntas[0]->promedios}}</span>
				</p>
			</div>
		</div>
	</div>
	{{-- finaliza info --}}
	@endif


	@if($info_charts)

	<div class="buttons-comm">
		<button id="btn-accion-comentarios" for="contenedor-comentarios" class="btn-acciones fa fa-commenting-o sondealo-text-color"> Comentarios</button>
		<button id="btn-accion-encuestas" for="contenedor-encuestas" class="btn-acciones fa fa-pencil-square-o sondealo-text-color"> Encuestas realizadas</button>
		<button id="btn-accion-vendedores" for="contenedor-vendedores" class="btn-acciones fa fa-user-md sondealo-text-color"> Análisis de vendedores</button>
		<label onclick="getExcel();" class="btn btn-success btn-sm fa fa-file-excel-o"> Generar Excel</label>
	</div>

	@endif



	<div id="contenedor-resultados-accion">
		<div id="contenedor-comentarios" class="ocultar_contenedor">
			<label  class="fa fa-close close_contenedor"></label>
		</div>
		<div id="contenedor-encuestas" class="ocultar_contenedor">
			<label class="fa fa-close close_contenedor"></label>
		</div>
		<div id="contenedor-vendedores" class="ocultar_contenedor">
			<label class="fa fa-close close_contenedor"></label>
		</div>
	</div>

	{{-- inicia barras --}}
	@if(count($promedios_preguntas) > 0)
	<div class="barras bg-light">
		<table class="table table-sm">
			@foreach ($promedios_preguntas as $prom)
			@if ($prom->valor != 9)
			<tr>
				<td colspan="2">{{$prom->pregunta}}</td>
			</tr>
			<tr>
				@php $mult = 10; @endphp
				@if($prom->valor == 1)
				@php $mult = 1; @endphp
				@endif
				<td class="barra-td"><div class="barra" style="width:{{($prom->promedio)*$mult}}%;background-color:{{$prom->css}};"></div></td>
				<td class="promedio"><strong>{{$prom->promedio.$prom->signo}}</strong></td>
			</tr>
			@endif
			@endforeach
		</table>
	</div>
	@endif
	{{-- finaliza barras --}}

	{{-- inicia charts --}}
	@if(count($info_charts) > 0)
		<div class="charts" id="charts">
	    @if(count($info_charts[0]->valores) > 0)
			  @for ($i=0;$i<count($info_charts);$i++)
	        <div class="pregunta-chart bg-light">
	          <p class="sondealo-color">{{$info_charts[$i]->pregunta}}</p>
	          {{-- render del chart en el indice actual --}}
	          {!! ${'chart'.($i+1)}->render() !!}
	        </div>
	     @endfor
	   @else
	     <div class="alert alert-info" role="alert">
	       Sin información
	     </div>
	   @endif
	  </div>
	@endif
	{{-- finaliza charts --}}

</div>


{{-- Modal carousel --}}

<div class="modal fade" id="modal-carousel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Evidencias</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">

					<div id="carousel1" class="carousel slide" data-ride="carousel">
						<ol class="carousel-indicators">
							<li data-target="#carousel1" data-slide-to="0" class="active"></li>
							<li data-target="#carousel1" data-slide-to="1"></li>
						</ol>

						<div class="carousel-inner" role="listbox" id="inner-corousel">

						</div>
						<a class="left carousel-control" href="#carousel1" role="button" data-slide="prev">
							<span class="text-dark fa fa-arrow-left" aria-hidden="true"></span>
							<span class="sr-only">Anterior</span>
						</a>
						<a class="right carousel-control" href="#carousel1" role="button" data-slide="next">
							<span class="text-dark fa fa-arrow-right" aria-hidden="true"></span>
							<span class="sr-only">Siguiente</span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>

{{-- fin modal carousel --}}

<input type="hidden" id="limite-comentarios" value="0"/>
<input type="hidden" id="limite-encuestas" value="0"/>
<input type="hidden" id="limite-vendedores" value="0"/>
<input type="hidden" id="extras_url" value="{{ (isset($desde) && !empty($desde)) ? '/'.substr($desde, 0 , 10).'/' : '' }}{{(isset($hasta) && !empty($hasta)) ? $hasta : ''}}"/>

@endsection

@section('js')
@if ($boolean_show)
<script src="{{asset('js/Chart.min.js')}}"></script>
<script type="text/javascript">
	document.getElementById('btn-generar-reporte').addEventListener('click', setURL);

	function setURL()
	{
		let desde = document.getElementById('desde').value;
		let hasta = document.getElementById('hasta').value;

		if(desde == "" || hasta == ""){
			return Swal.fire({icon:'info',text:'Llene ambos campos'});
		}

		if(desde > hasta){
			return Swal.fire({icon:'info', text:'La fecha de inicio es superior a la final'});
		}

		let current_date = new Date();
		if(desde > current_date){
			return Swal.fire({icon:'info', text:'La fecha de inicio es superior a la fecha actual'});
		}
		showLoader();

		window.location = "{{route(Request::route()->getName()).'/'.$sucursal_url.'/'}}"+desde+'/'+hasta;
	}

	$(function(){

		$('#charts').append('<div class="pregunta-chart" style="display:none;"><p class="sondealo-color"></p><canvas id="myChart" width="420" height="220"></canvas></div>');

		fetch("{{route('info_nps')}}",{
			method:'post',
			body:JSON.stringify({"sucursal":"{{$sucursal_url}}", "desde": "{{$desde}}", "hasta":"{{$hasta_h}}"}),
			headers:{
				'Content-Type':'application/json',
				'X-CSRF-TOKEN': CSRF_TOKEN
			}
		}).then(res => res.json())
			.catch(error => console.log(error))
			.then(function(response){
			if(response.status == 200)
			{
				$('#charts div.pregunta-chart:last-child').find('p').text(response.info.pregunta+' NPS: '+response.info.nps);
				$('#charts div.pregunta-chart:last-child').css('display', 'block');

				var ctx = document.getElementById("myChart");
				var myChart = new Chart(ctx, {
					type: "doughnut",
					data: {
						labels: ["detractores: "+response.info.detractores,
								 "pasivos: "+response.info.pasivos, "promotores: "+response.info.promotores],
						datasets: [
							{
								data: [response.info.detractores, response.info.pasivos, response.info.promotores],
								backgroundColor: [
									"#cf0505",
									"rgba(0,0,0,0.2)",
									"#2c6903"
								],
								borderWidth: 1
							}
						]
					},
					options: {
						maintainAspectRatio: false,
						circumference: Math.PI + 0.2,
						rotation: -Math.PI - 0.12,
						cutoutPercentage: 80,
					}
				});
			}
		});

	});
</script>
<script type="text/javascript">
	let btns_acciones = document.getElementsByClassName('btn-acciones');
	for(let i=0;i<btns_acciones.length;i++)
	{
		btns_acciones[i].addEventListener('click', btnAcciones);
	}

	function btnAcciones()
	{
		let id = this.getAttribute('id');

		if(id != 'btn-accion-excel'){
			$('#'+this.getAttribute('for')).addClass('mostrar_contenedor').removeClass('ocultar_contenedor').siblings().addClass('ocultar_contenedor').removeClass('mostrar_contenedor');
			$(this).addClass('selected_button').siblings().removeClass('selected_button');
			$('.barras').addClass('ocultar_contenedor');
			$('.charts').addClass('ocultar_contenedor');
		}

		switch (id) {
			case 'btn-accion-comentarios':
				let inp_limit = document.getElementById('limite-comentarios');
				if(parseInt(inp_limit.value) == 0){
					getComentarios(inp_limit.value);
				}
				inp_limit.value = 20;
				break;
			case 'btn-accion-encuestas':
				let inp_rec_limit = document.getElementById('limite-encuestas');
				if(parseInt(inp_rec_limit.value) == 0){
					getEncuestas(inp_rec_limit.value)
				}
				inp_rec_limit.value = 20;
				break;
			case 'btn-accion-vendedores':
				let inp_vend = document.getElementById('limite-vendedores');
				if(inp_vend.value == 0){
					getVendedores();
				}
				inp_vend.value = 1;
				break;
		}
	}

	$('.close_contenedor').click(function(){
		$('.barras').removeClass('ocultar_contenedor');
		$('.charts').removeClass('ocultar_contenedor');
		$(this).parent().removeClass('mostrar_contenedor').addClass('ocultar_contenedor').siblings().removeClass('mostrar_contenedor').addClass('ocultar_contenedor');
		$('.btn-acciones').removeClass('selected_button');
	});

	function getComentarios(limite_i)
	{
		showLoader();

		fetch("{{route('info_comentarios')}}",{
			method:'post',
			body:JSON.stringify({"sucursal":"{{$sucursal_url}}", "desde": "{{$desde}}", "hasta":"{{$hasta_h}}", "limite_i":limite_i}),
			headers:{
				'Content-Type':'application/json',
				'X-CSRF-TOKEN': CSRF_TOKEN
			}
		}).then(res => res.json())
			.catch(error => console.log(error))
			.then(function(response)
				  {
			if(response.status == 200)
			{
				let html ='';
				let info = response.info[0];

				for(let i=0;i<info.length;i++)
				{
					html += '<table class="table table-sm table-comentarios">'
						+'<thead class="sondealo-color">'
						+'<tr>'
						+'<th>Fecha: '+info[i].fecha+'</th>'
						+'<th data-id="'+info[i].id+'" class="detalle_comentario" style="cursor:pointer;text-decoration:underline;">Folio: '+info[i].folio+'</th>'
						+'</tr>'
						+'</thead>'
						+'<tbody>'
						+'<tr>'
						+'<td colspan="2" class="'+info[i].css+'">'+info[i].comentarios+'</td>'
						+'</tr>'
						+'</tbody>'
						+'</table>';
				}

				html += '<button class="btn btn-success btn-block" id="btn-mas-comentarios">Mas comentarios</button>'

				$('#contenedor-comentarios').append(html);
			}
			else{
				$('#contenedor-comentarios').append('<div class="alert alert-warning" role="alert">'+response.msg+'</div>');
			}
			hideLoader();
		});
	}

	$(document).on('click', '#btn-mas-comentarios', fnMasComentarios);

	function fnMasComentarios()
	{
		$(this).remove();
		let limite_com = document.getElementById('limite-comentarios');
		let limite_new = parseInt(limite_com.value)+20;
		getComentarios(limite_com.value);
		limite_com.value = limite_new;
	}

	$(document).on('click', '.detalle_comentario', fnDetalleEncuesta);


	function fnDetalleEncuesta()
	{
		showLoader();

		fetch("{{route('info_encuesta_detalle')}}", {
			method:'post',
			body:JSON.stringify({"encuesta":this.dataset.id, "sucursal":"{{$sucursal_url}}"}),
			headers:{
				'Content-Type':'application/json',
				'X-CSRF-TOKEN': CSRF_TOKEN
			}
		}).then(res => res.json())
			.catch(error => console.log(error))
			.then(function(response)
				  {
			$('#modal-edit .modal-body').empty();
			$('#modal-edit .modal-footer').empty();

			if(response.status == 200)
			{
				$('#modal-edit .modal-title').text('Detalle encuesta');
				let info = response.info;

				let html ='';

				html += '<table class="table table-sm table-detalle">'
					+'<tbody>'
					+'<tr>'
					+'<td>Fecha</td>'
					+'<td>'+info.fecha+'</td>'
					+'</tr>'
					+'<tr>'
					+'<td>Ticket</td>'
					+'<td>'+info.ticket+'</td>'
					+'</tr>'
					+'<tr>'
					+'<td>Mesa</td>'
					+'<td>'+info.mesa+'</td>'
					+'</tr>'
					+'<tr>'
					+'<td>Mesero</td>'
					+'<td>'+info.mesero+'</td>'
					+'</tr>';

				let html_preguntas ='';
				let preguntas = info.preguntas;

				for(let j in preguntas)
				{
					let contador = 0;
					for(let k in preguntas[j])
					{
						contador++;
						let numero = k.substring(1);
						let columna_pregunta      = 'p'+numero;
						let columna_respuesta     = 'r'+numero;
						if(contador%2 == 0){
							html_preguntas += '<tr><td>'+preguntas[j][columna_pregunta]+'</td><td>'+preguntas[j][columna_respuesta]+'</td></tr>';
						}
					}
				}

				html += html_preguntas
					+'<tr>'
					+'<td>Correo electrónico</td>'
					+'<td>'+info.correo+'</td>'
					+'</tr>'
					+'<tr>'
					+'<td>Comentario</td>'
					+'<td>'+info.comentario.toLowerCase()+'</td>'
					+'</tr>';

					if(	info.file1 != '' ||  info.file2 != '')
					{
						html += '<tr>'
						+'<td colspan="2"><button class="btn-evidencia btn btn-danger btn-sm btn-block" data-url1="'+info.file1+'" data-url2="'+info.file2+'">Ver evidencia</button></td>'
						+ '</tr>';
					}

					html +='</tbody>'
					+'</table>';

				$('#modal-edit .modal-body').append(html);
			}
			else{
				$('#modal-edit .modal-body').append('<div class="alert alert-warning" role="alert">'+response.msg+'</div>');
			}
			hideLoader();
			showModal();
		});
	}


	function getEncuestas(limite_i)
	{
		showLoader();

		fetch("{{route('info_encuestas')}}",{
			method:'post',
			body:JSON.stringify({"sucursal":"{{$sucursal_url}}", "desde": "{{$desde}}", "hasta":"{{$hasta_h}}", "limite_i":limite_i}),
			headers:{
				'Content-Type':'application/json',
				'X-CSRF-TOKEN':CSRF_TOKEN
			}
		}).then(res => res.json())
			.catch(error => console.log(error))
			.then(function(response)
				  {
			if(response.status == 200)
			{
				let html ='';
				let info = response.info;

				for(let i=0;i<info.length;i++)
				{
					let html_preguntas = '';
					let preguntas = info[i].preguntas;

					for(let j in preguntas)
					{
						let contador = 0;
						for(let k in preguntas[j])
						{
							contador++;
							let numero = k.substring(1);
							let columna_pregunta      = 'p'+numero;
							let columna_respuesta     = 'r'+numero;
							if(contador%2 == 0){
								html_preguntas += '<tr><td>'+preguntas[j][columna_pregunta]+'</td><td>'+preguntas[j][columna_respuesta]+'</td></tr>';
							}
						}
					}

					html += '<table class="table table-sm table-encuestas">'
						+'<tbody>'
						+'<tr>'
						+'<td>Fecha</td>'
						+'<td>'+info[i].fecha+'</td>'
						+'</tr>'
						+'<tr>'
						+'<td>Ticket</td>'
						+'<td>'+info[i].ticket+'</td>'
						+'</tr>'
						+'<tr>'
						+'<td>Mesa</td>'
						+'<td>'+info[i].mesa+'</td>'
						+'</tr>'
						+'<tr>'
						+'<td>Mesero</td>'
						+'<td>'+info[i].mesero+'</td>'
						+'</tr>'
						+ html_preguntas
						+'<tr>'
						+'<td>Correo electrónico</td>'
						+'<td>'+info[i].correo+'</td>'
						+'</tr>'
						+'<tr>'
						+'<td>Comentario</td>'
						+'<td>'+info[i].comentario+'</td>'
						+ '</tr>';

						if(	response.info[i].file1 != '' || 	response.info[i].file2 != '')
						{
							html += '<tr>'
							+'<td colspan="2"><button class="btn-evidencia btn btn-danger btn-sm btn-block" data-url1="'+response.info[i].file1+'" data-url2="'+response.info[i].file2+'">Ver evidencia</button></td>'
							+ '</tr>';
						}

						html += '</tbody>'
						+'</table>';
				}

				html += '<button class="btn btn-success btn-block" id="btn-mas-encuestas">Mas encuestas</button>';
				$('#contenedor-encuestas').append(html);
			}
			else{
				$('#contenedor-encuestas').append('<div class="alert alert-warning" role="alert">'+response.msg+'</div>');
			}
			hideLoader();
		});
	}

		$(document).ready(function(){
			$(document).on('click', '.btn-evidencia', showSlider);

		});

		function showSlider()
		{
			ev1 = this.dataset.url1;
			ev2 = this.dataset.url2;
			let html = '';

			html += '<div class="item active">'
			+'<img style="max-width:100%!important;max-height:100%!important;" src="'+ev1+'" alt="Evidencia 1">'
			+'<div class="carousel-caption"> . </div>'
			+'</div>';

			if(ev2 != '')
			{
				html += '<div class="item">'
				+'<img style="max-width:100%!important;max-height:100%!important;" src="'+ev2+'" alt="Evidencia 2">'
				+'<div class="carousel-caption"> . </div>'
				+'</div>';
			}
			else{
				$('.carousel-indicators li').last().remove();
			}

			document.getElementById('inner-corousel').innerHTML = html;
			$('#modal-carousel').modal('show');
		}


	$(document).on('click', '#btn-mas-encuestas', fnMasEncuestas);

	function fnMasEncuestas()
	{
		$(this).remove();
		let limite_com = document.getElementById('limite-encuestas');
		let limite_new = parseInt(limite_com.value)+20;
		getEncuestas(limite_com.value);
		limite_com.value = limite_new;
	}

	function getVendedores()
	{
		showLoader();
		fetch("{{route('info_promedios_vendedores')}}",{
			method:'post',
			body:JSON.stringify({"sucursal":"{{$sucursal_url}}", "desde": "{{$desde}}", "hasta":"{{$hasta_h}}"}),
			headers:{
				'Content-Type':'application/json',
				'X-CSRF-TOKEN':CSRF_TOKEN
			}
		}).then(res => res.json())
			.then(function(response)
				  {
			let html = '';
			if(response.status == 200)
			{
				let info = response.info;

				for(let i in info)
				{
					html += '<table class="table table-sm table-vendedor">';
					for(let j in info[i])
					{
						html += '<tr><td>'+j+'</td><td>'+info[i][j]+'</td></tr>';
					}
					html += '</table>';
				}
			}
			else
			{
				html += '<div class="alert alert-info" role="alert">'+response.msg+'</div>';
			}

			$('#contenedor-vendedores').append(html);
			hideLoader();
		});
	}

</script>
@endif

@if( ! empty($desde) and ! empty($hasta) )
	<script type="text/javascript">
		function getExcel()
		{
			showLoader();

			fetch("{{route('reporte_excel_global',['sucursal' => $sucursal_url, 'desde' => $desde, 'hasta' => $hasta_h])}}",{
				headers:{
						'X-CSRF-TOKEN':CSRF_TOKEN
				}
			}).then(res => res.blob())
			.then(function(blob){
				let url = URL.createObjectURL(blob);
				let a = document.createElement('a');
				a.href = url;
				a.download = "reporte-{{$sucursal_url}}-{{date('Y-m-d')}}-.xlsx";
				document.body.append(a);
				a.click();
				a.remove();
				hideLoader();
			});
		}
	</script>
@endif
@endsection
