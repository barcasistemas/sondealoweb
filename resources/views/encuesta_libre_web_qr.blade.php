<!DOCTYPE html>
<html lang="es-MX">
	<head>
		<meta type="utf-8"/>
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1,maximum-scale=1,minimum-scale=1"/>
		<title>Contesta esta encuesta de {{$sucursal}}</title>
		{{-- <style type="text/css">
			 *{
				margin: 0;
				padding: 0;
				box-sizing: border-box;
				font-family: sans-serif;
			}
			header, section{
				width: 100%;
			}
			.contenedor{
				margin: auto;
				width: 98%;
			}
			.principal input[type="radio"]{
				display: none;
			}
			.principal label{
				cursor: pointer;
			}

			header{
				padding: 5px;
				height: 70px;
				color: white;
				font-size: 0.8rem;
			}
			header img{
				margin-bottom: 5px;
				display: block;
				height: 35px;
			}
			header .contenedor{
				position: relative;
				max-width: 600px;
			}


			.principal{
				padding: 15px 0 30px 0;
				width: 100%;
				max-width: 600px;
				overflow-y: hidden;
				overflow-x: auto;
				position: relative;
			}
			.principal fieldset{
				padding: 10px;
				border-style: none;
			}
			.principal fieldset legend{
				font-size: 1rem;
			}
			.tipo-0 img, .tipo-1 img{
				margin-right: 30px;
				height: 23px;
			}
			.tipo-3 input, .tipo-6 input, .tipo-9 select{
				height: 25px;
				width: 50%;
			}
			.tipo-4 label{
				display: inline-block;
				margin-right: 10px;
				margin-bottom: 5px;
				padding: 5px;
				border-style: none;
				color: white;
				text-align: center;
				border-radius: 5px;
			}
			.tipo-4 label:nth-child(2){
				background: #e31010;
			}
			.tipo-4 label:nth-child(3){
				background: #feae02;
			}
			.tipo-4 label:nth-child(4){
				background: #63b503;
			}
			.tipo-4 label:nth-child(5){
				background: #2c6903;
			}
			.tipo-5 img, .tipo-8 img{
				margin-right: 20px;
				height: 23px;
			}
			.correo-comentarios input{
				width: 100%;
				padding:7px;
			}
			.botones-final button{
				width: 45%;
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
			.checked{
				opacity: 0.5;
			}

			.img-logo{
				position: absolute;
				right: 5px;
				top: 0;
			  height: 60px;
				max-width: 100px;
			}

			#audio{
				display: none;
			}

			.attached{
			  background-color: rgba(0,0,255,0.9);
				color: #ffffff;
			}

			.input-text-type10{
				width: 100%;
				margin-top: 5px;
				padding: 5px;
				border:1px groove blue;
			}
			.hidden{
				display: none;
			}

			.tipo-10 select{
				width: 100%;
				overflow: hidden;
			}
			.tipo-10 select option{
				font-size: 0.8rem;
				padding: 5px 2px;
			}
			.tipo-10 select option:hover{
				background-color: #0658C9;
				color: #ffffff;
			}



			</style> --}}

      		<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

			<style type="text/css">
			@import url('https://fonts.googleapis.com/css2?family=Encode+Sans+Semi+Expanded:wght@300;400&display=swap');

			*{
			    margin: 0;
			    padding: 0;
			    box-sizing: border-box;
			    font-family: 'Encode Sans Semi Expanded', sans-serif;
			}
			body{
				background-color: #f5f5fb;
			}
			.contenedor{
				 width: 100%;
				 margin: auto;
				 padding: 0 1rem;

			}
			.principal{
				max-width: 700px;
				padding-bottom: 3rem;
			}
			header{
			    width: 100%;
			    height: 80px;
			    background: #0658C9;
			}

			header img{
			    max-width: 48%;
					max-height: 70px;
			}
			header .contenedor{
				display: flex;
				justify-content: space-between;
				align-items: center;
				height: 100%;
				border:1px solid red;
				padding: 5px;
			}

			@media(min-width:500px){
				header img{
					max-height: 70px;
					padding: 5px;
					margin-right: 2rem;
					max-width: 180px;
					align-self: center;
				}
				header .contenedor{
					justify-content: center;
				}
			}

			input[type="radio"]{
			    display: none;
			}

			.pregunta{
				position: relative;
			    margin: auto;
				margin-top: 0.8rem;
			    padding:3rem 0.5rem 1rem 0.5rem;
			    border: 1px solid rgba(0,0,0,0.1);
			    border-radius: 10px;
				background-color: #ffffff;
				text-align: center;
			}

			label.opcion{
				margin-right: 1rem;
			    display: inline-block;
			    font-size: 1.5rem;
			}

			.pregunta-selected{
			    border-left: 5px solid #4785ef!important;
			    box-shadow: 1px 1px 2px -1px rgba(0,0,0,0.75);
			}			/*---------------------------*/

			/*estilos globales*/
			.fa-frown, .fa-times-circle, .t4-op1{
			    color: #f2495e;
			}
			.fa-meh, .t4-op2{
			    color: #faaf41;
			}
			.fa-smile, .t4-op3{
			    color: #9ad645;
			}
			.fa-laugh-beam, .fa-check-circle, .t4-op4{
			    color: #60cc64;
			}
			/*estilos pregunta tipo 4*/

			.pt4{
			    font-size: 1rem!important;
			    border-width: 2px;
			    border-style: solid;
			    border-radius: 5px;
			    padding: 2px 10px;
			}
			.t4-op1{  border-color: #f2495e!important; }
			.t4-op2{  border-color: #faaf41!important; }
			.t4-op3{  border-color: #9ad645!important; }
			.t4-op4{  border-color: #60cc64!important; }

			/*estilos preguntas tipo 4 checked*/
			.t4-op1-checked{ background-color: #f2495e!important;  color:#ffffff!important; }
			.t4-op2-checked{ background-color: #faaf41!important;  color:#ffffff!important; }
			.t4-op3-checked{ background-color: #9ad645!important;  color:#ffffff!important; }
			.t4-op4-checked{ background-color: #60cc64!important;  color:#ffffff!important; }

			/*estilos preguntas tipo 5*/

			.pt5{
			    font-size: 0.9rem!important;
			    margin-right: 0.3rem!important;
			}

			.t5-op1{  color: #CA0805; }
			.t5-op2{  color: #E31010; }
			.t5-op3{  color: #f2495e; }
			.t5-op4{  color: #FEDE02; }
			.t5-op5{  color: #FEC002; }
			.t5-op6{  color: #FEB007; }
			.t5-op7{  color: #9ad645; }
			.t5-op8{  color: #6FCA05; }
			.t5-op9{  color: #60dd64; }
			.t5-op10{ color: #60cc64; }

			.white{
			    color: #ffffff;
			}

			#audio{
				display: none;
			}

			.checked{
				transform: scale(0.95);
			}

			.attached{
				background-color: rgba(0,0,255,0.9);
				color: #ffffff;
			}

			.hidden{
				display: none;
			}

			.botones-final button{
				width: 45%;
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
				background-color: #4785ef;
			}

			.tipo-0 label{
				font-size: 1.7rem!important;
			}

			.pregunta legend,
			.tipo-9 select,
			.tipo-3 input,
			.tipo-6 input,
			.correo-comentarios input{
				color: #4785ef!important;
			}

			.tipo-9 select, .tipo-3 input, .tipo-6 input, .correo-comentarios input{
				width: 100%;
				padding:0.5rem;
				border: 1px solid rgba(0,0,0,0.2);
			}

			.tipo-4 label, .tipo-5 label{
				margin-bottom: 0.2rem;
			}

			.pregunta legend{
				position: absolute;
				top: 0.5rem;
				left: 0;
				width: 100%;
				text-align: center;
				font-size: 0.9rem;
			}

			.correo-comentarios, .botones-final{
				margin:0.5rem 0;
				background-color: #ffffff;
				border-style: none;
			}

			</style>

	</head>
	<body id="body">

		{{-- Original --}}
		{{-- <header style="background-color: {{$colorHeader}};">
			<div class="contenedor">
				<img src="/images/sondealogo.png"/>
				Ayúdanos, contestando las siguientes preguntas

				<img class="img-logo" src="/images/logo/{{$nombre_logo}}"/>

			</div>
		</header> --}}


			<header style="background-color: {{$colorHeader}};">
				<div class="contenedor">
					<img src="/images/sondealogo.png"/>
					<img class="img-logo" src="/images/logo/{{$nombre_logo}}"/>
				</div>
			</header>

		<section>

			<div class="contenedor principal">
				
					@if ($lang_en_bool)
						<select id="select-translation" style="position:absolute;right:0;top:5px;font-size:0.8rem;padding:5px;">
							<option value="es" checked>Español</option>
							<option value="en">English</option>
						</select>											
					@endif

				{!!$html!!}

				<input type="hidden" id="cantidad-preguntas" value="<?=$obligatorias?>"/>
				<audio id="audio" controls>
					<source type="audio/wav" src="{{asset('sounds/button.wav')}}">
				</audio>
			</div>
		</section>

		<script type="text/javascript" src="{{asset('js/sweetalert29.js')}}"></script>

		<script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function(){

                let lbOpcionesCero = document.querySelectorAll('.pt01');

                for(let i=0;i<lbOpcionesCero.length;i++)
                {
                    lbOpcionesCero[i].addEventListener('click', function(){
                        fnChangeBtnClass(this, 'far', 'fas');
                    });
                }

                function fnChangeBtnClass(elem, claseadd, claseremove)
                {
                    let parentOpciones = elem.parentNode;
                    let children = parentOpciones.querySelectorAll('.opcion');

                    for(let j=0;j<children.length;j++)
                    {
                        children[j].classList.remove(claseremove);
                        children[j].classList.add(claseadd);
                    }

                    elem.classList.remove(claseadd);
                    elem.classList.add(claseremove);
                }


                let lbOpcionesCuatro = document.querySelectorAll('.pt4');
                for(let k=0;k<lbOpcionesCuatro.length;k++)
                {
                    lbOpcionesCuatro[k].addEventListener('click', fnChangeBtnFourClass);
                }

                function fnChangeBtnFourClass()
                {
                    let parentOpciones = this.parentNode;
                    let children = parentOpciones.querySelectorAll('.opcion');

                    for(let j=0;j<children.length;j++)
                    {
                        children[j].classList.remove(children[j].dataset.classchecked);
                    }
                    this.classList.add(this.dataset.classchecked);
                }


                let lbOpcionesCinco = document.querySelectorAll('.pt5');
                for(let l=0;l<lbOpcionesCinco.length;l++)
                {
                    lbOpcionesCinco[l].addEventListener('click', fnChangeBtnFiveClass);
                }

                function fnChangeBtnFiveClass()
                {
                    let parentOpciones = this.parentNode;
                    let children = parentOpciones.querySelectorAll('.opcion');
                    for(let j=0;j<children.length;j++)
                    {
                        let childI = children[j].querySelector('i');
                        children[j].querySelector('.fa-stack-1x').classList.remove('white');

                        childI.classList.remove('fas');
                        childI.classList.add('far');
                    }

                    let currentStrong = this.querySelector('.fa-stack-1x');
                    currentStrong.classList.add('white');

                    let currentChild = this.querySelector('i');

                    currentChild.classList.remove('far');
                    currentChild.classList.add('fas');

                }


                let preguntas = document.querySelectorAll('.pregunta');
                for(let x=0;x<preguntas.length;x++)
                {
                    preguntas[x].addEventListener('click', fnBorde);
                }

                function fnBorde()
                {
                    for(let i=0;i<preguntas.length;i++)
                    {
                        preguntas[i].classList.remove('pregunta-selected');
                    }
                    this.classList.add('pregunta-selected');
                }
            });

        </script>

		<script type="text/javascript">


		document.addEventListener('DOMContentLoaded', function(){

			if(document.body.contains(document.getElementById('select-translation')))
			{
				let select_translate = document.getElementById('select-translation');
				select_translate.addEventListener('change', function(){
					let elements_translation = document.querySelectorAll('.translation');
					for(let x = 0;x<elements_translation.length; x++)
					{
						if(elements_translation[x].dataset.lang !== this.value){
							elements_translation[x].classList.add('hidden');
						}else{
							elements_translation[x].classList.remove('hidden');
						}
					}
				});
			}
		});


		{{-- empieza codigo fuente javascript tipo 10 --}}
		document.addEventListener('DOMContentLoaded', function(){

				let selectType10 = document.querySelectorAll(".tipo-10 select");

				for(let i=0;i<selectType10.length;i++)
				{
					selectType10[i].addEventListener('change', showInput10);
				}

				function showInput10()
				{
					let preguntaIndex = this.dataset.id;
					let option = this.options[this.selectedIndex];
					if(option.dataset.showinput == 1)
					{
						document.getElementById('text_for_pregunta_'+preguntaIndex).classList.remove('hidden');
					}else{
						document.getElementById('text_for_pregunta_'+preguntaIndex).classList.add('hidden');
					}
				}
			});
			{{-- termina codigo fuente javascript tipo 10 --}}


			if(document.body.contains(document.getElementById('btn-pedir-folio'))){
				let btn_folio = document.getElementById('btn-pedir-folio');
				btn_folio.addEventListener('click', function(){
					let txt_folio = document.getElementById('txt-pedir-folio');
					if(txt_folio.value.trim() == '' || txt_folio.value.trim().length <= 2){
						txt_folio.style.border='1px solid red';
						document.getElementById('txt-msj-pedir-folio').innerText = "Proporcione un número de expediente valido";
						return;
					}
					document.getElementById('inp-pedir-folio').value = txt_folio.value.trim();
					document.getElementById('modal-pedir-folio').style.display = 'none';
				});
			}

			if(document.body.contains(document.getElementById('modal-suc-domicilios'))){
				let radio_domicilios = document.querySelectorAll('#modal-suc-domicilios input[type="radio"]');
				for (var index = 0; index < radio_domicilios.length; index++) {
					radio_domicilios[index].addEventListener('change',function(){
						document.getElementById('inp-sucursal-domicilio').value=this.value;
						document.getElementById('modal-suc-domicilios').style.display ='none';
					});
				}
			}

			if(document.body.contains(document.getElementById('input_evidencia'))){

				document.getElementById('input_evidencia').addEventListener('click', function(e){
					if(this.value != ''){
						if(!confirm('Ya hay una imagen adjunta, ¿deseas remplazarla?'))
						{
							e.preventDefault();
							return false;
						}
					}
				});

				document.getElementById('input_evidencia').addEventListener('change', fnValidarImg);
			}

			function fnValidarImg()
			{
				var elem = this;

				let parentNode = elem.parentNode;
				let span = elem.parentNode.querySelector('span');

				if(elem.files.length > 1)
				{
					elem.value = '';
					return Swal.fire({icon:'warning', text:'Máximo 1 imagen '});
				}

				for(let i=0;i<elem.files.length;i++)
				{
					let name = elem.files.item(i).name;
					let arr_name = name.split('.');
					let size_arr_name = arr_name.length;
					let extension = arr_name[size_arr_name -1].toLowerCase();

					if(extension == 'png' || extension == 'jpg' || extension == 'jpeg')
					{
						let size = Number.parseInt(elem.files[i].size);
						if(size > 4194304)
						{
							elem.value = '';
							parentNode.classList.remove('attached');
							span.innerText = '¿Algún inconveniente? Adjunta una imagen';

							return Swal.fire({icon:'info', text:'Imagenes máximo de 4 mb'});
						}

						parentNode.classList.add('attached');
						span.innerText = 'Imagen Adjuntada';
					}
					else
					{
						elem.value = '';
						parentNode.classList.remove('attached');
						span.innerText = '¿Algún inconveniente? Adjunta una imagen';

						return Swal.fire({icon:'info', text:'Solo imagenes png o jpg'});
					}
				}
			}

			var audio_presionar =  document.getElementById('audio');

			var radios = document.querySelectorAll('input[type="radio"]');
			for(let j=0;j<radios.length;j++)
			{
				radios[j].addEventListener('change',function(e){
					let parent = this.parentNode;
					let parent_parent = parent.parentNode;
					let sibblings = parent_parent.children;
					for(let k=0;k<sibblings.length;k++)
					{
						sibblings[k].classList.remove('checked');
					}
					parent.classList.add('checked');
					audio_presionar.play();
				});
			}

			document.getElementById('btn-finalizar-encuesta').addEventListener('click', finalizarEncuesta);

			function finalizarEncuesta()
			{
				let obligatoria_check  = document.querySelectorAll('fieldset input[type="radio"]:checked');
				let obligatoria_select = document.querySelectorAll('select.obligatoria');

				let suma_actual = 0;
				for(let i=0;i<obligatoria_select.length;i++)
				{
					if(obligatoria_select[i].value != ''){
						suma_actual++;
					}
				}

				suma_actual+=obligatoria_check.length;

				let obligatorias = document.getElementById('cantidad-preguntas').value;

				if(obligatorias > suma_actual){
					return Swal.fire({icon:'info', text:'Faltan preguntas'});
				}

				let arreglo_tipo_3 = document.querySelectorAll('.type-3');
				let arreglo_tipo_6 = document.querySelectorAll('.type-6');

				let arreglo_final = [];

				for(let j=0;j<obligatoria_check.length;j++)
				{
					let value = obligatoria_check[j].dataset.value;

					if(obligatoria_check[j].dataset.type == 8)
					{
						if(parseInt(obligatoria_check[j].dataset.value) >= 9){
							value = '3';
						}else if(parseInt(obligatoria_check[j].dataset.value) >= 7){
							value = '2';
						}else{
							value = '1';
						}
					}

					arreglo_final.push({"id":obligatoria_check[j].dataset.id, "value":value,
										"tipo":obligatoria_check[j].dataset.type, "str": obligatoria_check[j].dataset.str, "v2":obligatoria_check[j].dataset.typev2});
				}

				for(let k=0;k<obligatoria_select.length;k++)
				{
					if( parseInt(obligatoria_select[k].dataset.type) == 9 )
					{
						arreglo_final.push({"id":obligatoria_select[k].dataset.id, "value":"0",
											"tipo": "9", "str":obligatoria_select[k].value, "v2": obligatoria_select[k].dataset.typev2});
					}
					if( parseInt(obligatoria_select[k].dataset.type) == 10 )
					{

						let optionSelected = obligatoria_select[k].options[obligatoria_select[k].selectedIndex];
						let indexPregunta =  obligatoria_select[k].dataset.id;

						let str10 = obligatoria_select[k].value;

						if(optionSelected.dataset.showinput == 1){
							str10 = document.getElementById('text_for_pregunta_'+indexPregunta).value.trim();
						}

						if(str10 == ''){
							obligatoria_select[k].style.border = '1px solid #ff0000';
							Swal.fire({icon:"info", text:"Nos podria indicar cual otro"});
							return;
						}

						arreglo_final.push({"id":indexPregunta, "value":"0",
							"tipo": "10", "str": str10, "v2" : obligatoria_select[k].dataset.typev2});
					}
				}

				for(let l=0;l<arreglo_tipo_3.length;l++){
					arreglo_final.push({"id":arreglo_tipo_3[l].dataset.id, "value":"0",
										"tipo": "3", "str":arreglo_tipo_3[l].value, "v2":arreglo_tipo_3[l].dataset.typev2});
				}

				for(let m=0;m<arreglo_tipo_6.length;m++){
					arreglo_final.push({"id":arreglo_tipo_6[m].dataset.id, "value":"0",
										"tipo":"6", "str":arreglo_tipo_6[m].value, "v2":arreglo_tipo_6[m].dataset.typev2});
				}

				let correo = '';
				if(document.body.contains(document.getElementById('txt_correo'))){
					correo = document.getElementById('txt_correo').value.trim();
				}

				let comentario = '';
				if(document.body.contains(document.getElementById('txt_comentarios'))){
					comentario = document.getElementById('txt_comentarios').value.trim();
				}

				var formulario = new FormData();
				formulario.append('s', '{{$sucursal}}');
				formulario.append('preguntas', JSON.stringify(arreglo_final) );
				formulario.append('comentario', comentario);
				formulario.append('correo', correo);
				formulario.append('mesa', '{{$mesa}}' );

				if(document.body.contains(document.getElementById('modal-suc-domicilios'))){
					formulario.append('vendedor', document.getElementById('inp-sucursal-domicilio').value);
				}

				if(document.body.contains(document.getElementById('inp-pedir-folio'))){
					formulario.append('folio', document.getElementById('inp-pedir-folio').value);
				}

				if(document.body.contains(document.getElementById('input_evidencia')))
				{
					let elem = document.getElementById('input_evidencia');
					if(elem.value != '')
					{
						if(elem.files.length == 1)
						{
              				formulario.append('file1', elem.files[0]);
						}
						else if(elem.files.length == 2)
						{
							formulario.append('file1', elem.files[0]);
							formulario.append('file2', elem.files[1]);
						}
						else{
							elem.value = '';
						}
					}
				}

        		document.getElementById('body').style.pointerEvents  = 'none';
				appendLoader();

				{!! $javascript ?? '' !!}

				fetch("{{route('guardar_encuesta_qr')}}", {
					method:'post',
					body: formulario ,
					headers:{
						'X-CSRF-TOKEN':'{{csrf_token()}}'
					}
							}).then(res => res.json())
								.then(function(response)
					{
						removeLoader();

  					let _icon = 'info';
  					if(response.status == 200){
  						_icon = 'success';

							if('{{$mesa}}' == 'movilstand')
							{
								setTimeout(function(){location.reload();}, 3500);
							}
							else
							{
								setTimeout(function(){window.location = "https://sondealo.com";}, 12000);

								setTimeout(function(){
									Swal.fire({
										imageUrl: 'https://sondealo.com/sitio/images/sondealogo.png',
										imageWidth: 250,
										html:'<p style="color:#fff;">Te interesaría contratar SONDEALO para tu negocio, sigue el siguiente enlace para obtener mas información</p><a href="https://sondealo.com" style="margin-top:2rem;text-decoration:none;border-style:none;display:inline-block;padding:0.5rem;background-color:white;color:#000;">Ir a sondealo</a>',
										imageHeight: 50,
										background:'#0658C9',
										imageAlt: 'Sondealo img',
										showConfirmButton: false,
										allowOutsideClick: false
									});
								}, 1100);

							}

  					}

  					return Swal.fire({
  						position: 'top-end',
  						icon: _icon,
  						title: response.msg,
  						showConfirmButton: false,
  						allowOutsideClick: false,
  						timer: 1000
  					});
				});
			}


			function appendLoader()
			{
				document.querySelector('body').innerHTML +='<div id="loadElem" style="z-index: 2000;position:fixed;top:0;left:0;width:100vw;height:100vh;background-color:rgba(255,255,255,0.5);">'
					+'<div style="margin:auto;width: 98%;min-height: 100vh;max-width: 1024px;display:flex;">'
					+'<img style="margin: auto;height:20px;" src="https://sondealo.com/sitio/images/loader.gif"/>'
			  	+'</div>'
			    +'</div>';
			}
			function removeLoader()
			{
				document.getElementById('loadElem').remove();
			}

		</script>
	</body>
</html>
