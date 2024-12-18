<!DOCTYPE html>
<html lang="es-MX">
	<head>
		<meta type="utf-8"/>
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1,maximum-scale=1,minimum-scale=1"/>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
		<style type="text/css">
			#tabs{
				background-color: snow;
			}

			#tab-content{
				padding: 10px;
				background-color: #0658c9;
				min-height: calc(100vh - 40px);
			}
			.tab-pane{
				padding: 0.5rem;
				font-size: 0.8rem;
				color: rgba(0,0,0,0.8);
				background-color: white;
			}
			.logo{
				max-height: 90px;
				border-radius: 10px;
				padding: 5px 10px;
			}
			.table-sm td:nth-child(1){
				font-weight: bold;
			}
			#encuestas table, #vendedores table{
				margin-top:1rem;
				box-shadow: 0px 1px 1px 1px rgba(0,0,0,0.29);
				border-radius: 5px;
			}
      .alerta{
        background-color: rgba(255,0,0,0.1);
      }
			.table-sm td{
				width: 50%!important;
			}
		</style>

		<title>tabs</title>
	</head>
	<body>

		<ul class="nav nav-tabs justify-content-center" id="tabs" role="tablist">
			{{-- menu evaluacion --}}
			<li class="nav-item" role="presentation">
				<button class="nav-link bi bi-pie-chart-fill active" id="evaluacion-tab" data-bs-toggle="tab" data-bs-target="#evaluacion" type="button" role="tab" aria-controls="evaluacion" aria-selected="true">
				</button>
			</li>
			{{-- menu comentarios --}}
			<li class="nav-item" role="presentation">
				<button class="nav-link bi bi-chat-dots-fill" id="comentarios-tab" data-bs-toggle="tab" data-bs-target="#comentarios" type="button" role="tab" aria-controls="comentarios" aria-selected="false">
				</button>
			</li>
			{{-- menu encuestas --}}
			<li class="nav-item" role="presentation">
				<button class="nav-link bi bi-clipboard-check" id="encuestas-tab" data-bs-toggle="tab" data-bs-target="#encuestas" type="button" role="tab" aria-controls="encuestas" aria-selected="false">
				</button>
			</li>
			{{-- menu vendedores --}}
			<li class="nav-item" role="presentation">
				<button class="nav-link bi bi-person-lines-fill" id="vendedores-tab" data-bs-toggle="tab" data-bs-target="#vendedores" type="button" role="tab" aria-controls="vendedores" aria-selected="false">
				</button>
			</li>
		</ul>

		{{-- comienza tab container --}}
		<div class="tab-content" id="tab-content">

			{{-- evaluacion  tab--}}
			<div class="tab-pane fade show active" id="evaluacion" role="tabpanel" aria-labelledby="evaluacion-tab">
			</div>

			{{-- comentarios y preguntas tab --}}
			<div class="tab-pane fade" id="comentarios" role="tabpanel" aria-labelledby="comentarios-tab">

				<ul class="h6 nav nav-tabs justify-content-center"  role="tablist">
					<li class="nav-item" role="presentation">
						<button class="nav-link active" id="btn-show-comentarios" data-bs-toggle="tab" data-bs-target="#comentarios-row" type="button" role="tab" aria-controls="comentarios" aria-selected="true">Comentarios</button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="btn-show-preguntas" data-bs-toggle="tab" data-bs-target="#preguntas-row" type="button" role="tab" aria-controls="preguntas" aria-selected="false">Preguntas Abiertas</button>
					</li>
				</ul>

				<div class="tab-content">
					{{-- comentarios tab --}}
					<div class="tab-pane fade show active" id="comentarios-row" role="tabpanel" aria-labelledby="comentarios-tab">

					</div>
					{{-- preguntas tab --}}
					<div class="tab-pane fade" id="preguntas-row" role="tabpanel" aria-labelledby="preguntas-tab">
					</div>
				</div>

			</div>

			{{-- encuestas tab --}}
			<div class="tab-pane fade" id="encuestas" role="tabpanel" aria-labelledby="encuestas-tab">
				<p class="h6 text-center text-primary">Encuestas de "{{$sucursal}}"</p>
				<p class="text-center text-muted">Período {{$desde}} - {{$hasta}}</p>
			</div>

			{{-- vendedores tab --}}
			<div class="tab-pane fade" id="vendedores" role="tabpanel" aria-labelledby="vendedores-tab">
				<p class="h6 text-center text-primary">Vendedores de "{{$sucursal}}"</p>
				<p class="text-center text-muted">Período {{$desde}} - {{$hasta}}</p>
			</div>
		</div>


		<div class="modal" tabindex="-1" id="modal-evidencia">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title">Evidencia</h5>
		      </div>
		      <div class="modal-body" id="modal-evidencia-body">
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
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		      </div>
		    </div>
		  </div>
		</div>


		<div class="modal" tabindex="-1" id="modal-detalle" style="font-size:0.8rem;">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Detalle</h5>
					</div>
					<div class="modal-body" id="modal-detalle-body">

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
					</div>
				</div>
			</div>
		</div>


		<div id="spinner" style="display:none;z-index: 10000; position:fixed;top:0;left:0;min-width:100vw;min-height:100vh;background-color:rgba(255,255,255,0.7);">
			<div class="text-center" style="width:100%;min-height:100vh;display:flex;justify-content:center;">
				<img style="margin:auto;max-height:20px;" src="{{asset('images/loader.gif')}}"/>
			</div>
		</div>



    <input type="hidden" id="contador-comentarios" value="0"/>
    <input type="hidden" id="contador-encuestas" value="0"/>
    <input type="hidden" id="contador-vendedores" value="0"/>
    <input type="hidden" id="contador-preguntas" value="0"/>

		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <script src="{{asset('js/Chart.min.js')}}"></script>
    <script src="{{asset('js/sweetalert29.js')}}"></script>
    <script type="text/javascript">

function getChartsVendedorForm()
		{

			if('{{$sucursal}}' != 'bernini'){
				return;
			}

			let preguntas =[
				'Personas en la mesa',
				'Tipo de servicio',
				'Rango de días',
				'Rango de edades'
			];

     			document.querySelector('.mt-4').innerHTML += '<div class="col-12" style="margin-top:10px;margin-bottom:10px;text-align:center;padding:5px;background-color:black;color:white;">Formulario Vendedor</div>';



			for (var k = 1; k <= 4; k++) {
				document.querySelector('.mt-4').innerHTML += '<div class="col-12"><p class="bg-primary text-white pl-1 pr-1">'+preguntas[k-1]+'</p><canvas id="chart_fv'+k+'"></canvas><div id="label_fv'+k+'" style="margin-top:1rem;display:flex;flex-wrap:wrap;justify-content:space-around;"></div></div>';
			}

			fetch("{{route('reporte_movil_formulario_vendedor')}}",{
				method:'post',
				body:JSON.stringify({
          "sucursal" : "{{$sucursal}}",
          "desde"  : "{{$desde_h}}",
          "hasta"  : "{{$hasta_h}}"
      	}),
        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN': '{{csrf_token()}}'
        }
			}).then(res => res.json())
			.then( (response) => {
				if(response.length < 1)
				{
					return;
				}

				let arreglo_colores =  ['#2c6903', '#3498db', '#feae02', '#8e44ad' , '#cf0505'];

				let colores = arreglo_colores.slice(0, response.length-1);

				for (var i = 0; i < response.length; i++)
				{
					let arreglo_labels = response[i].labels;
					let arreglo_valores = response[i].valores;

					var datos={
	                type:'pie',
	                data:{
	                    datasets:[{
	                        label:"",
	                        data:arreglo_valores,
	                        borderWidth:2,
	                        backgroundColor: colores,
	                        borderColor:"#000"
	                    }],
	                    labels: arreglo_labels,

	                },
	                options:{
	                    legend: {
	                        display: false,
	                        position: 'bottom'},
	                    responsive:true,

	                    scales: {
	                        yAxes: [{
	                            display: false,
	                            ticks: {
	                                suggestedMin: 0,
	                                beginAtZero: true,
	                                max: 10
	                            }
	                        }]
	                    }
	                }
	            };


							var canvas=document.getElementById('chart_fv'+(i+1)).getContext('2d');
							window.bar = new Chart(canvas, datos);

							let html_labels = '';

	            for (var j = 0; j < arreglo_labels.length; j++) {
	              html_labels += '<label style="font-size:0.8rem;"><strong class="bi bi-square-fill" style="color:'+colores[j]+';"></strong> '+arreglo_labels[j]+'&nbsp;&nbsp;</label>';
	            }
	           document.getElementById('label_fv'+(i+1)).innerHTML = html_labels;
				}

			});

		}



		function showSpinner()
		{
			document.getElementById('spinner').style.display = 'block';
		}
		function hideSpinner()
		{
			document.getElementById('spinner').style.display = 'none';
		}

    document.addEventListener('DOMContentLoaded', general);

    function general()
    {
			showSpinner();

      fetch("{{route('reporte_movil_general')}}", {
        method:'post',
        body:JSON.stringify({
          "sucursal" : "{{$sucursal}}",
          "desde"    : "{{$desde}}",
          "hasta"    : "{{$hasta}}",
          "desde_h"  : "{{$desde_h}}",
          "hasta_h"  : "{{$hasta_h}}"
      }),
        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN': '{{csrf_token()}}'
        }
      }).then(res => res.json())
      .then(function(response)
      {
				hideSpinner();

        if(response.status == 200)
        {
          document.getElementById('evaluacion').innerHTML = response.general;
          if(response.general_info_charts.length > 0)
          {
            generarCharts(response.general_info_charts);
          }
          return;
        }
        document.getElementById('evaluacion').innerHTML= response.msg;
        document.getElementById('comentarios').innerHTML= response.msg;
        document.getElementById('encuestas').innerHTML= response.msg;
        document.getElementById('vendedores').innerHTML= response.msg;
      });

    }

    function generarCharts(arreglo_general)
    {
	getChartsVendedorForm();


      for (let i = 0; i < arreglo_general.length; i++)
      {
        let labels = arreglo_general[i].labels;
        let colores = arreglo_general[i].colores;
        let valores = arreglo_general[i].valores;

        var datos={
                type:'pie',
                data:{
                    datasets:[{
                        label:"",
                        data:valores,
                        borderWidth:2,
                        backgroundColor: colores,
                        borderColor:"#000"
                    }],
                    labels: labels,

                },
                options:{
                    legend: {
                        display: false,
                        position: 'bottom'},
                    responsive:true,

                    scales: {
                        yAxes: [{
                            display: false,
                            ticks: {
                                suggestedMin: 0,
                                beginAtZero: true,
                                max: 10
                            }
                        }]
                    }
                }
            };

            var canvas=document.getElementById('chart_'+(i+1)).getContext('2d');
            window.bar = new Chart(canvas, datos);

            let html_labels = '';

            for (var j = 0; j < labels.length; j++) {
              html_labels += '<label style="font-size:0.8rem;"><strong class="bi bi-square-fill" style="color:'+colores[j]+';"></strong> '+labels[j]+'&nbsp;&nbsp;</label>';
            }
            document.getElementById('label_'+(i+1)).innerHTML = html_labels;
      }
    }


    /*inicia comentarios*/
    document.getElementById('comentarios-tab').addEventListener('click', comentarios);
    function comentarios()
    {
			if(document.body.contains(document.getElementById('btn-mas-comentarios'))){
				document.getElementById('btn-mas-comentarios').remove();
			}

      let contador_comentarios = document.getElementById('contador-comentarios').value;

			if(parseInt(contador_comentarios) == 0){
				showSpinner();
			}

      fetch("{{route('reporte_movil_comentarios')}}", {
        method:'post',
        body:JSON.stringify({
          "sucursal" : "{{$sucursal}}",
          "desde"    : "{{$desde_h}}",
          "hasta"    : "{{$hasta_h}}",
          "limite_i" : contador_comentarios
        }),
        headers:{
          'Content-Type':'application/json',
          'X-CSRF-TOKEN': '{{csrf_token()}}'
        }
      }).then(res => res.json())
      .then(function(response){

				hideSpinner();
        if(response.status == 200)
        {
          document.getElementById('comentarios-row').innerHTML += response.info;
					document.getElementById('contador-comentarios').value = parseInt(contador_comentarios)+20;
          return;
        }
        document.getElementById('comentarios-row').innerHTML += response.msg;
      });
    }

    document.addEventListener('click', function (e) {
      let id = e.target.getAttribute('id');

      switch (id) {
        case 'btn-mas-comentarios':
          comentarios();
          e.target.remove();
          break;
				case 'btn-mas-encuestas':
					encuestas();
					e.target.remove();
					break;
				case 'btn-show-preguntas':
					preguntas();
					break;
      }

    }, false);


		document.addEventListener('click', function(e){
			if(typeof e.target.dataset.encuesta !== 'undefined')
			{
				let encuesta = e.target.dataset.encuesta;
				showSpinner();
				fetch("{{route('reporte_movil_encuesta_detalle')}}",{
					method:'post',
					body:JSON.stringify({"encuesta": encuesta, "sucursal":"{{$sucursal}}"}),
					headers:{
						'Content-Type':'application/json',
						'X-CSRF-TOKEN':'{{csrf_token()}}'
					}
				}).then(res => res.json())
				.then(function(response){
					hideSpinner();
					if(response.status == 200)
					{
						document.getElementById('modal-detalle-body').innerHTML = response.html;
						var modal_detalle = new bootstrap.Modal(document.getElementById('modal-detalle'), {
							keyboard: false
						});
						modal_detalle.show();
					}
				});

			}

			if(typeof e.target.dataset.btnevidencia !== 'undefined')
			{
					ev1 = e.target.dataset.url1;
					ev2 = e.target.dataset.url2;
					let html = '';

					html += '<div class="item active">'
					+'<img style="max-width:100%!important;max-height:565px!important;" src="'+ev1+'" alt="Evidencia 1">'
					+'<div class="carousel-caption"> . </div>'
					+'</div>';

					if(ev2 != '')
					{
						html += '<div class="item">'
						+'<img style="max-width:100%!important;max-height:565px!important;" src="'+ev2+'" alt="Evidencia 2">'
						+'<div class="carousel-caption"> . </div>'
						+'</div>';
					}
					document.getElementById('inner-corousel').innerHTML = html;
					var modal_evidencia = new bootstrap.Modal(document.getElementById('modal-evidencia'), {
	  				keyboard: false
					});
					modal_evidencia.show();
			}
		});


		document.getElementById('encuestas-tab').addEventListener('click', encuestas);

		function encuestas()
		{
			let contador_encuestas = document.getElementById('contador-encuestas').value;

			if(document.body.contains(document.getElementById('btn-mas-encuestas'))){
				document.getElementById('btn-mas-encuestas').remove();
			}

			if(parseInt(contador_encuestas) == 0){
				showSpinner();
			}

			fetch("{{route('reporte_movil_encuestas')}}",{
				method:'post',
				body:JSON.stringify({"sucursal":"{{$sucursal}}", "desde": "{{$desde_h}}", "hasta":"{{$hasta_h}}", "limite_i":contador_encuestas}),
				headers:{
					'Content-Type':'application/json',
					'X-CSRF-TOKEN':'{{csrf_token()}}'
				}
			}).then(res => res.json())
			.then(function(response){
				hideSpinner();
				if(response.status == 200)
				{
					document.getElementById('encuestas').innerHTML += response.html;
					document.getElementById('contador-encuestas').value = parseInt(contador_encuestas)+10;
					return;
				}
				document.getElementById('encuestas').innerHTML += response.msg;
			});
		}


		document.getElementById('vendedores-tab').addEventListener('click', vendedores);

		function vendedores()
		{
			let contador_vendedores = document.getElementById('contador-vendedores').value;
			if(parseInt(contador_vendedores) > 0){
				return;
			}

			if(parseInt(contador_vendedores) == 0){
				showSpinner();
			}

			fetch("{{route('reporte_movil_vendedores')}}",{
				method:'post',
				body:JSON.stringify({"sucursal":"{{$sucursal}}", "desde": "{{$desde_h}}", "hasta":"{{$hasta_h}}"}),
				headers:{
					'Content-Type':'application/json',
					'X-CSRF-TOKEN':'{{csrf_token()}}'
				}
			}).then(res => res.json())
			.then(function(response){
				hideSpinner();
				if(response.status == 200)
				{
					document.getElementById('vendedores').innerHTML += response.html;
					document.getElementById('contador-vendedores').value = 20;
					return;
				}
					document.getElementById('vendedores').innerHTML += response.msg;
			});
		}


		function preguntas()
		{
			let contador_preguntas = document.getElementById('contador-preguntas').value;

			if(parseInt(contador_preguntas) == 0){
				showSpinner();
			}else{
				return;
			}

			fetch("{{route('reporte_movil_preguntas')}}",{
				method:'post',
				body:JSON.stringify({"sucursal":"{{$sucursal}}", "desde": "{{$desde_h}}", "hasta":"{{$hasta_h}}"}),
				headers:{
					'Content-Type':'application/json',
					'X-CSRF-TOKEN':'{{csrf_token()}}'
				}
			}).then(res => res.json())
			.then(function(response){
				hideSpinner();
				document.getElementById('contador-preguntas').value = 30;
				if(response.status==200)
				{
					document.getElementById('preguntas-row').innerHTML = response.html;
					return;
				}
				document.getElementById('preguntas-row').innerHTML = response.msg;
			});
		}

    </script>



  </body>
</html>
