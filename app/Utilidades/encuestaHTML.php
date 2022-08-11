<?php
namespace App\Utilidades;

class encuestaHTML
{
	public static $pregunta;
	public static $textos;
	public static $id;
	public static $pregunta_en;
	public static $textos_en;

	// public static function tipo0()
	// {
	//
	// 	return '<fieldset class="tipo-0">'
	// 		.'<legend> <span class="translation" data-lang="es">'.self::$pregunta.'</span>  <span class="translation hidden" data-lang="en">'.self::$pregunta_en.'</span> </legend>'
	// 			.'<label>'
	// 			.'<img src="/images/encuestas/emojibad.png"/>'
	// 			.'<input type="radio" class="obligatoria type-0" data-str="Malo" name="pregunta_'.self::$id.'" data-value="0" data-type="0" data-typev2="0" data-id="'.self::$id.'"/>'
	// 				.'</label>'
	// 				.'<label>'
	// 				.'<img src="/images/encuestas/emojiregular.png" />'
	// 				.'<input type="radio" class="obligatoria type-0" data-str="Regular" name="pregunta_'.self::$id.'" data-value="0.5" data-type="0" data-typev2="0" data-id="'.self::$id.'"/>'
	// 					.'</label>'
	// 					.'<label>'
	// 					.'<img src="/images/encuestas/emojiwell.png" />'
	// 					.'<input type="radio" class="obligatoria type-0" data-str="Bueno" name="pregunta_'.self::$id.'" data-value="0.75" data-type="0" data-typev2="0" data-id="'.self::$id.'"/>'
	// 						.'</label>'
	// 						.'<label>'
	// 						.'<img src="/images/encuestas/emojigood.png" />'
	// 						.'<input type="radio" class="obligatoria type-0" data-str="Excelente" name="pregunta_'.self::$id.'" data-value="1" data-type="0" data-typev2="0" data-id="'.self::$id.'"/>'
	// 							.'</label>'
	// 							.'</fieldset>';
	// }


	public static function tipo0()
	{

		return '<fieldset class="tipo-0 pregunta">'
			  	.'<legend> <span class="translation" data-lang="es">'.self::$pregunta.'</span>  <span class="translation hidden" data-lang="en">'.self::$pregunta_en.'</span> </legend>'
					.'<label class="far fa-frown opcion pt01">'
					//.'<img src="/images/encuestas/emojibad.png"/>'
					.'<input type="radio" class="obligatoria type-0" data-str="Malo" name="pregunta_'.self::$id.'" data-value="0" data-type="0" data-typev2="0" data-id="'.self::$id.'"/>'
					.'</label>'
					.'<label class="far fa-meh opcion pt01">'
					//.'<img src="/images/encuestas/emojiregular.png" />'
					.'<input type="radio" class="obligatoria type-0" data-str="Regular" name="pregunta_'.self::$id.'" data-value="0.5" data-type="0" data-typev2="0" data-id="'.self::$id.'"/>'
					.'</label>'
					.'<label class="far fa-smile opcion pt01">'
					//.'<img src="/images/encuestas/emojiwell.png" />'
					.'<input type="radio" class="obligatoria type-0" data-str="Bueno" name="pregunta_'.self::$id.'" data-value="0.75" data-type="0" data-typev2="0" data-id="'.self::$id.'"/>'
					.'</label>'
					.'<label class="far fa-laugh-beam opcion pt01">'
					//.'<img src="/images/encuestas/emojigood.png" />'
					.'<input type="radio" class="obligatoria type-0" data-str="Excelente" name="pregunta_'.self::$id.'" data-value="1" data-type="0" data-typev2="0" data-id="'.self::$id.'"/>'
					.'</label>'
					.'</fieldset>';



	}

	// public static function tipo1()
	// {
	// 	return '<fieldset class="tipo-1">'
	// 		.'<legend> <span class="translation" data-lang="es">'.self::$pregunta.'</span>  <span class="translation hidden" data-lang="en">'.self::$pregunta_en.'</span>  </legend>'
	// 			.'<label>'
	// 			.'<img src="/images/encuestas/no.png"/>'
	// 			.'<input type="radio" class="obligatoria type-1" data-str="No" name="pregunta_'.self::$id.'" data-value="0" data-type="1"  data-typev2="0" data-id="'.self::$id.'"/>'
	// 				.'</label>'
	// 				.'<label>'
	// 				.'<img src="/images/encuestas/si.png"/>'
	// 				.'<input type="radio" class="obligatoria type-1" data-str="Si" name="pregunta_'.self::$id.'" data-value="1" data-type="1"  data-typev2="0" data-id="'.self::$id.'"/>'
	// 					.'</label>'
	// 					.'</fieldset>';
	// }


	public static function tipo1()
	{
		return '<fieldset class="tipo-1 pregunta">'
					.'<legend> <span class="translation" data-lang="es">'.self::$pregunta.'</span>  <span class="translation hidden" data-lang="en">'.self::$pregunta_en.'</span>  </legend>'
					.'<label class="fa-stack fa-3x t5-op3 opcion pt5">'
					.'<i class="far fa-circle fa-stack-2x"></i><strong class="fa-stack-1x">No</strong>'
				//	.'<img src="/images/encuestas/no.png"/>'
					.'<input type="radio" class="obligatoria type-1" data-str="No" name="pregunta_'.self::$id.'" data-value="0" data-type="1"  data-typev2="0" data-id="'.self::$id.'"/>'
					.'</label>'
					.'<label  class="fa-stack fa-3x t5-op10 opcion pt5">'
					.' <i class="far fa-circle fa-stack-2x"></i><strong class="fa-stack-1x">Si</strong>'
					//.'<img src="/images/encuestas/si.png"/>'
					.'<input type="radio" class="obligatoria type-1" data-str="Si" name="pregunta_'.self::$id.'" data-value="1" data-type="1"  data-typev2="0" data-id="'.self::$id.'"/>'
					.'</label>'
					.'</fieldset>';

	}





	// public static function tipo3()
	// {
	// 	return '<fieldset class="tipo-3">'
	// 		.'<legend> <span class="translation" data-lang="es">'.self::$pregunta.'</span>  <span class="translation hidden" data-lang="en">'.self::$pregunta_en.'</span>  </legend>'
	// 			.'<input type="text" class="type-3" name="pregunta_'.self::$id.'" data-type="3"  data-typev2="0" data-id="'.self::$id.'" autocomplete="off"/>'
	// 				.'</fieldset>';
	// }

	public static function tipo3()
	{
		return '<fieldset class="tipo-3 pregunta">'
			.'<legend> <span class="translation" data-lang="es">'.self::$pregunta.'</span>  <span class="translation hidden" data-lang="en">'.self::$pregunta_en.'</span>  </legend>'
				.'<input type="text" class="type-3" name="pregunta_'.self::$id.'" data-type="3"  data-typev2="0" data-id="'.self::$id.'" autocomplete="off"/>'
					.'</fieldset>';
	}




	// public static function tipo4($valor2 = null)
	// {
	//
	// 	$arr_textos = explode(',', self::$textos);
	// 	$arr_textos_en = explode(',', self::$textos_en);
	//
	// 	if(count($arr_textos_en) != 4){
	// 		$arr_textos_en = ['a', 'b', 'c', 'd'];
	// 	}
	//
	//
	// 	return '<fieldset class="tipo-4">'
	// 		.'<legend><span class="translation" data-lang="es">'.self::$pregunta.'</span>  <span class="translation hidden" data-lang="en">'.self::$pregunta_en.'</span> </legend>'
	//
	// 			.'<label> <span class="translation" data-lang="es"> '.$arr_textos[3].' </span> <span class="translation hidden" data-lang="en">'.$arr_textos_en[3].'</span> <input type="radio" data-str="'.$arr_textos[3].'" class="obligatoria type-4" name="pregunta_'.self::$id.'" data-value="0" data-type="4" data-typev2="'.$valor2.'" data-id="'.self::$id.'"/></label>'
	//
	// 			.'<label> <span class="translation" data-lang="es"> '.$arr_textos[2].' </span> <span class="translation hidden" data-lang="en">'.$arr_textos_en[2].'</span> <input type="radio" data-str="'.$arr_textos[2].'" class="obligatoria type-4" name="pregunta_'.self::$id.'" data-value="0.5" data-type="4" data-typev2="'.$valor2.'" data-id="'.self::$id.'"/></label>'
	//
	// 			.'<label> <span class="translation" data-lang="es"> '.$arr_textos[1].' </span> <span class="translation hidden" data-lang="en">'.$arr_textos_en[1].'</span> <input type="radio" data-str="'.$arr_textos[1].'" class="obligatoria type-4" name="pregunta_'.self::$id.'" data-value="0.75" data-type="4" data-typev2="'.$valor2.'" data-id="'.self::$id.'"/></label>'
	//
	// 			.'<label> <span class="translation" data-lang="es"> '.$arr_textos[0].' </span> <span class="translation hidden" data-lang="en">'.$arr_textos_en[0].'</span> <input type="radio" data-str="'.$arr_textos[0].'" class="obligatoria type-4" name="pregunta_'.self::$id.'" data-value="1" data-type="4" data-typev2="'.$valor2.'" data-id="'.self::$id.'"/></label></fieldset>';
	// }


	public static function tipo4($valor2 = null)
	{

		$arr_textos = explode(',', self::$textos);
		$arr_textos_en = explode(',', self::$textos_en);

		if(count($arr_textos_en) != 4){
			$arr_textos_en = ['a', 'b', 'c', 'd'];
		}


		return '<fieldset class="tipo-4 pregunta">'
			.'<legend><span class="translation" data-lang="es">'.self::$pregunta.'</span>  <span class="translation hidden" data-lang="en">'.self::$pregunta_en.'</span> </legend>'

				.'<label class="t4-op1 opcion pt4" data-classchecked="t4-op1-checked"> <span class="translation" data-lang="es"> '.$arr_textos[3].' </span> <span class="translation hidden" data-lang="en">'.$arr_textos_en[3].'</span> <input type="radio" data-str="'.$arr_textos[3].'" class="obligatoria type-4" name="pregunta_'.self::$id.'" data-value="0" data-type="4" data-typev2="'.$valor2.'" data-id="'.self::$id.'"/></label>'

				.'<label class="t4-op2 opcion pt4" data-classchecked="t4-op2-checked"> <span class="translation" data-lang="es"> '.$arr_textos[2].' </span> <span class="translation hidden" data-lang="en">'.$arr_textos_en[2].'</span> <input type="radio" data-str="'.$arr_textos[2].'" class="obligatoria type-4" name="pregunta_'.self::$id.'" data-value="0.5" data-type="4" data-typev2="'.$valor2.'" data-id="'.self::$id.'"/></label>'

				.'<label class="t4-op3 opcion pt4" data-classchecked="t4-op3-checked" > <span class="translation" data-lang="es"> '.$arr_textos[1].' </span> <span class="translation hidden" data-lang="en">'.$arr_textos_en[1].'</span> <input type="radio" data-str="'.$arr_textos[1].'" class="obligatoria type-4" name="pregunta_'.self::$id.'" data-value="0.75" data-type="4" data-typev2="'.$valor2.'" data-id="'.self::$id.'"/></label>'

				.'<label class="t4-op4 opcion pt4" data-classchecked="t4-op4-checked"> <span class="translation" data-lang="es"> '.$arr_textos[0].' </span> <span class="translation hidden" data-lang="en">'.$arr_textos_en[0].'</span> <input type="radio" data-str="'.$arr_textos[0].'" class="obligatoria type-4" name="pregunta_'.self::$id.'" data-value="1" data-type="4" data-typev2="'.$valor2.'" data-id="'.self::$id.'"/></label></fieldset>';
	}















	// public static function tipo5()
	// {
	//
	// 	return '<fieldset class="tipo-5">'
	// 		.'<legend><span class="translation" data-lang="es">'.self::$pregunta.'</span>  <span class="translation hidden" data-lang="en">'.self::$pregunta_en.'</span> </legend>'
	// 			.'<label>'
	// 			.'<img src="/images/encuestas/n1.png"/>'
	// 			.'<input type="radio" class="obligatoria type-5" data-str="1" name="pregunta_'.self::$id.'" data-value="1" data-type="5"  data-typev2="0" data-id="'.self::$id.'"/>'
	// 				.'</label>'
	// 				.'<label>'
	// 				.'<img src="/images/encuestas/n2.png"/>'
	// 				.'<input type="radio" class="obligatoria type-5" data-str="2" name="pregunta_'.self::$id.'" data-value="2" data-type="5"  data-typev2="0" data-id="'.self::$id.'"/>'
	// 					.'</label>'
	// 					.'<label>'
	// 					.'<img src="/images/encuestas/n3.png"/>'
	// 					.'<input type="radio" class="obligatoria type-5" data-str="3" name="pregunta_'.self::$id.'" data-value="3" data-type="5"  data-typev2="0" data-id="'.self::$id.'"/>'
	// 						.'</label>'
	// 						.'<label>'
	// 						.'<img src="/images/encuestas/n4.png"/>'
	// 						.'<input type="radio" class="obligatoria type-5" data-str="4" name="pregunta_'.self::$id.'" data-value="4" data-type="5"  data-typev2="0" data-id="'.self::$id.'"/>'
	// 							.'</label>'
	// 							.'<label>'
	// 							.'<img src="/images/encuestas/n5.png"/>'
	// 							.'<input type="radio" class="obligatoria type-5" data-str="5" name="pregunta_'.self::$id.'" data-value="5" data-type="5"  data-typev2="0" data-id="'.self::$id.'"/>'
	// 								.'</label>'
	// 								.'<label>'
	// 								.'<img src="/images/encuestas/n6.png"/>'
	// 								.'<input type="radio" class="obligatoria type-5" data-str="6" name="pregunta_'.self::$id.'" data-value="6" data-type="5"  data-typev2="0" data-id="'.self::$id.'"/>'
	// 									.'</label>'
	// 									.'<label>'
	// 									.'<img src="/images/encuestas/n7.png"/>'
	// 									.'<input type="radio" class="obligatoria type-5" data-str="7" name="pregunta_'.self::$id.'" data-value="7" data-type="5" data-typev2="0" data-id="'.self::$id.'"/>'
	// 										.'</label>'
	// 										.'<label>'
	// 										.'<img src="/images/encuestas/n8.png"/>'
	// 										.'<input type="radio" class="obligatoria type-5" data-str="8" name="pregunta_'.self::$id.'" data-value="8" data-type="5" data-typev2="0" data-id="'.self::$id.'"/>'
	// 											.'</label>'
	// 											.'<label>'
	// 											.'<img src="/images/encuestas/n9.png"/>'
	// 											.'<input type="radio" class="obligatoria type-5" data-str="9" name="pregunta_'.self::$id.'" data-value="9" data-type="5" data-typev2="0"data-id="'.self::$id.'"/>'
	// 												.'</label>'
	// 												.'<label>'
	// 												.'<img src="/images/encuestas/n10.png"/>'
	// 												.'<input type="radio" class="obligatoria type-5" data-str="10" name="pregunta_'.self::$id.'" data-value="10" data-type="5" data-typev2="0" data-id="'.self::$id.'"/>'
	// 													.'</label>'
	// 													.'</fieldset>';
	//
	// }


	public static function tipo5()
	{

		return '<fieldset class="tipo-5 pregunta">'
			.'<legend><span class="translation" data-lang="es">'.self::$pregunta.'</span>  <span class="translation hidden" data-lang="en">'.self::$pregunta_en.'</span> </legend>'
				.'<label class="fa-stack fa-3x t5-op1 opcion pt5">'
				.'<i class="far fa-circle fa-stack-2x"></i><strong class="fa-stack-1x">1</strong>'
				//.'<img src="/images/encuestas/n1.png"/>'
				.'<input type="radio" class="obligatoria type-5" data-str="1" name="pregunta_'.self::$id.'" data-value="1" data-type="5"  data-typev2="0" data-id="'.self::$id.'"/>'
					.'</label>'
					.'<label class="fa-stack fa-3x t5-op2 opcion pt5">'
					.'<i class="far fa-circle fa-stack-2x"></i><strong class="fa-stack-1x">2</strong>'
					//.'<img src="/images/encuestas/n2.png"/>'
					.'<input type="radio" class="obligatoria type-5" data-str="2" name="pregunta_'.self::$id.'" data-value="2" data-type="5"  data-typev2="0" data-id="'.self::$id.'"/>'
						.'</label>'
						.'<label class="fa-stack fa-3x t5-op3 opcion pt5">'
						.'<i class="far fa-circle fa-stack-2x"></i><strong class="fa-stack-1x">3</strong>'
					//	.'<img src="/images/encuestas/n3.png"/>'
						.'<input type="radio" class="obligatoria type-5" data-str="3" name="pregunta_'.self::$id.'" data-value="3" data-type="5"  data-typev2="0" data-id="'.self::$id.'"/>'
							.'</label>'
							.'<label class="fa-stack fa-3x t5-op4 opcion pt5">'
							.'<i class="far fa-circle fa-stack-2x"></i><strong class="fa-stack-1x">4</strong>'
							//.'<img src="/images/encuestas/n4.png"/>'
							.'<input type="radio" class="obligatoria type-5" data-str="4" name="pregunta_'.self::$id.'" data-value="4" data-type="5"  data-typev2="0" data-id="'.self::$id.'"/>'
								.'</label>'
								.'<label class="fa-stack fa-3x t5-op5 opcion pt5">'
								.' <i class="far fa-circle fa-stack-2x"></i><strong class="fa-stack-1x">5</strong>'
							//	.'<img src="/images/encuestas/n5.png"/>'
								.'<input type="radio" class="obligatoria type-5" data-str="5" name="pregunta_'.self::$id.'" data-value="5" data-type="5"  data-typev2="0" data-id="'.self::$id.'"/>'
									.'</label>'
									.'<label class="fa-stack fa-3x t5-op6 opcion pt5">'
									.' <i class="far fa-circle fa-stack-2x"></i><strong class="fa-stack-1x">6</strong>'
								//	.'<img src="/images/encuestas/n6.png"/>'
									.'<input type="radio" class="obligatoria type-5" data-str="6" name="pregunta_'.self::$id.'" data-value="6" data-type="5"  data-typev2="0" data-id="'.self::$id.'"/>'
										.'</label>'
										.'<label class="fa-stack fa-3x t5-op7 opcion pt5">'
										.'<i class="far fa-circle fa-stack-2x"></i><strong class="fa-stack-1x">7</strong>'
										//.'<img src="/images/encuestas/n7.png"/>'
										.'<input type="radio" class="obligatoria type-5" data-str="7" name="pregunta_'.self::$id.'" data-value="7" data-type="5" data-typev2="0" data-id="'.self::$id.'"/>'
											.'</label>'
											.'<label class="fa-stack fa-3x t5-op8 opcion pt5">'
											.'<i class="far fa-circle fa-stack-2x"></i><strong class="fa-stack-1x">8</strong>'
											//.'<img src="/images/encuestas/n8.png"/>'
											.'<input type="radio" class="obligatoria type-5" data-str="8" name="pregunta_'.self::$id.'" data-value="8" data-type="5" data-typev2="0" data-id="'.self::$id.'"/>'
												.'</label>'
												.'<label class="fa-stack fa-3x t5-op9 opcion pt5">'
												.'<i class="far fa-circle fa-stack-2x"></i><strong class="fa-stack-1x">9</strong>'
												//.'<img src="/images/encuestas/n9.png"/>'
												.'<input type="radio" class="obligatoria type-5" data-str="9" name="pregunta_'.self::$id.'" data-value="9" data-type="5" data-typev2="0"data-id="'.self::$id.'"/>'
													.'</label>'
													.'<label class="fa-stack fa-3x t5-op10 opcion pt5">'
													.'<i class="far fa-circle fa-stack-2x"></i><strong class="fa-stack-1x">10</strong>'
													//.'<img src="/images/encuestas/n10.png"/>'
													.'<input type="radio" class="obligatoria type-5" data-str="10" name="pregunta_'.self::$id.'" data-value="10" data-type="5" data-typev2="0" data-id="'.self::$id.'"/>'
														.'</label>'
														.'</fieldset>';

	}





	// public static function tipo6()
	// {
	// 	return '<fieldset class="tipo-6">'
	// 		.'<legend><span class="translation" data-lang="es">'.self::$pregunta.'</span>  <span class="translation hidden" data-lang="en">'.self::$pregunta_en.'</span> </legend>'
	// 			.'<input type="date" class="type-6" name="pregunta_'.self::$id.'" data-type="6"  data-typev2="0" data-id="'.self::$id.'" />'
	// 				.'</fieldset>';
	// }


	public static function tipo6()
	{
		return '<fieldset class="tipo-6 pregunta">'
			.'<legend><span class="translation" data-lang="es">'.self::$pregunta.'</span>  <span class="translation hidden" data-lang="en">'.self::$pregunta_en.'</span> </legend>'
				.'<input type="date" class="type-6" name="pregunta_'.self::$id.'" data-type="6"  data-typev2="0" data-id="'.self::$id.'" />'
					.'</fieldset>';
	}








	public static function tipo8()
	{
		return '<fieldset class="tipo-8 pregunta">'
			.'<legend>'.self::$pregunta.'</legend>'
				.'<label>'
				.'<img src="/images/encuestas/n0.png"/>'
				.'<input type="radio" class="obligatoria type-8" data-str="0" name="pregunta_'.self::$id.'" data-value="0" data-type="8"  data-typev2="0" data-id="'.self::$id.'"/>'
					.'</label>'
					.'<label>'
					.'<img src="/images/encuestas/n1.png"/>'
					.'<input type="radio" class="obligatoria type-8" data-str="1" name="pregunta_'.self::$id.'" data-value="1" data-type="8"  data-typev2="0" data-id="'.self::$id.'"/>'
						.'</label>'
						.'<label>'
						.'<img src="/images/encuestas/n2.png"/>'
						.'<input type="radio" class="obligatoria type-8" data-str="2" name="pregunta_'.self::$id.'" data-value="2" data-type="8"  data-typev2="0" data-id="'.self::$id.'"/>'
							.'</label>'
							.'<label>'
							.'<img src="/images/encuestas/n3.png"/>'
							.'<input type="radio" class="obligatoria type-8" data-str="3" name="pregunta_'.self::$id.'" data-value="3" data-type="8"  data-typev2="0" data-id="'.self::$id.'"/>'
								.'</label>'
								.'<label>'
								.'<img src="/images/encuestas/n4.png"/>'
								.'<input type="radio" class="obligatoria type-8" data-str="4" name="pregunta_'.self::$id.'" data-value="4" data-type="8"  data-typev2="0" data-id="'.self::$id.'"/>'
									.'</label>'
									.'<label>'
									.'<img src="/images/encuestas/n5.png"/>'
									.'<input type="radio" class="obligatoria type-8" data-str="5" name="pregunta_'.self::$id.'" data-value="5" data-type="8"  data-typev2="0" data-id="'.self::$id.'"/>'
										.'</label>'
										.'<label>'
										.'<img src="/images/encuestas/n6.png"/>'
										.'<input type="radio" class="obligatoria type-8" data-str="6" name="pregunta_'.self::$id.'" data-value="6" data-type="8"  data-typev2="0" data-id="'.self::$id.'"/>'
											.'</label>'
											.'<label>'
											.'<img src="/images/encuestas/n7.png"/>'
											.'<input type="radio" class="obligatoria type-8" data-str="7" name="pregunta_'.self::$id.'" data-value="7" data-type="8" data-typev2="0" data-id="'.self::$id.'"/>'
												.'</label>'
												.'<label>'
												.'<img src="/images/encuestas/n8.png"/>'
												.'<input type="radio" class="obligatoria type-8" data-str="8" name="pregunta_'.self::$id.'" data-value="8" data-type="8" data-typev2="0" data-id="'.self::$id.'"/>'
													.'</label>'
													.'<label>'
													.'<img src="/images/encuestas/n9.png"/>'
													.'<input type="radio" class="obligatoria type-8" data-str="9" name="pregunta_'.self::$id.'" data-value="9" data-type="8" data-typev2="0"data-id="'.self::$id.'"/>'
														.'</label>'
														.'<label>'
														.'<img src="/images/encuestas/n10.png"/>'
														.'<input type="radio" class="obligatoria type-8" data-str="10" name="pregunta_'.self::$id.'" data-value="10" data-type="8" data-typev2="0" data-id="'.self::$id.'"/>'
															.'</label>'
															.'</fieldset>';
	}


	// public static function tipo9()
	// {
	// 	$arr_textos = explode(',' , self::$textos );
	//
	// 	$arr_textos_en = (self::$textos_en == '') ? [] : explode(',', self::$textos_en);
	//
	// 	if(count($arr_textos_en) < 1){
	// 		for ($j=0; $j < count($arr_textos); $j++) {
	// 			$arr_textos_en[] = $arr_textos[$j];
	// 		}
	// 	}
	//
	// 	$html = '';
	//
	// 	$html .= '<fieldset class="tipo-9">'
	// 		.'<legend> <span class="translation" data-lang="es">'.self::$pregunta.'</span>  <span class="translation hidden" data-lang="en">'.self::$pregunta_en.'</span>  </legend>'
	// 			.'<select name="pregunta_'.self::$id.'" class="obligatoria type-9" data-type="9"  data-typev2="0" data-id="'.self::$id.'">'
	// 				.'<option value="">-selecciona-</option>';
	// 	for ($i=0; $i < count($arr_textos) ; $i++) {
	// 		$html .= '<option value="'.$arr_textos[$i].'" class="translation" data-lang="es">'.$arr_textos[$i].'</option>';
	// 		$html .= '<option value="'.$arr_textos[$i].'" class="translation hidden" data-lang="en">'.$arr_textos_en[$i].'</option>';
	// 	}
	// 	$html .='</select>'
	// 		.'</fieldset>';
	//
	// 	return $html;
	// }



	public static function tipo9()
	{
		$arr_textos = explode(',' , self::$textos );

		$arr_textos_en = (self::$textos_en == '') ? [] : explode(',', self::$textos_en);

		if(count($arr_textos_en) < 1){
			for ($j=0; $j < count($arr_textos); $j++) {
				$arr_textos_en[] = $arr_textos[$j];
			}
		}

		$html = '';

		$html .= '<fieldset class="tipo-9 pregunta">'
			.'<legend> <span class="translation" data-lang="es">'.self::$pregunta.'</span>  <span class="translation hidden" data-lang="en">'.self::$pregunta_en.'</span>  </legend>'
				.'<select name="pregunta_'.self::$id.'" class="obligatoria type-9" data-type="9"  data-typev2="0" data-id="'.self::$id.'">'
					.'<option value="">-selecciona-</option>';
		for ($i=0; $i < count($arr_textos) ; $i++) {
			$html .= '<option value="'.$arr_textos[$i].'" class="translation" data-lang="es">'.$arr_textos[$i].'</option>';
			$html .= '<option value="'.$arr_textos[$i].'" class="translation hidden" data-lang="en">'.$arr_textos_en[$i].'</option>';
		}
		$html .='</select>'
			.'</fieldset>';

		return $html;
	}


	// public static function tipo10()
	// {
	// 	$arr_textos = explode(',', self::$textos);
	// 	$size = count($arr_textos) + 1;
	//
	// 	$html = '';
	//
	//   $html .='<fieldset class="tipo-10">'
	// 		  	.'<legend> <span class="translation" data-lang="es">'.self::$pregunta.'</span>  <span class="translation hidden" data-lang="en">'.self::$pregunta_en.'</span>  </legend>'
	// 		  	.'<select name="pregunta_'.self::$id.'" size="'.$size.'" id="pregunta_2" class="obligatoria type-10" data-type="10" data-typev2="0" data-id="'.self::$id.'">';
	//
	// 				for ($i=0; $i < count($arr_textos) ; $i++) {
	// 					$html .= '<option data-showinput="0" value="'.$arr_textos[$i].'">'.$arr_textos[$i].'</option>';
	// 				}
	//
	// 	$html .='<option data-showinput="1" value="otro">otro</option></select>'
  //   			.'<input type="text" maxlength="100" placeholder="Nos podrías indicar cual otro" class="hidden input-text-type10" id="text_for_pregunta_'.self::$id.'"/>'
	// 				.'</fieldset>';
	//
	// 	return $html;
	//
	// }



	public static function tipo10()
	{
		$arr_textos = explode(',', self::$textos);
		$size = count($arr_textos) + 1;

		$html = '';

	  $html .='<fieldset class="tipo-10 pregunta">'
			  	.'<legend> <span class="translation" data-lang="es">'.self::$pregunta.'</span>  <span class="translation hidden" data-lang="en">'.self::$pregunta_en.'</span>  </legend>'
			  	.'<select name="pregunta_'.self::$id.'" size="'.$size.'" id="pregunta_2" class="obligatoria type-10" data-type="10" data-typev2="0" data-id="'.self::$id.'">';

					for ($i=0; $i < count($arr_textos) ; $i++) {
						$html .= '<option data-showinput="0" value="'.$arr_textos[$i].'">'.$arr_textos[$i].'</option>';
					}

		$html .='<option data-showinput="1" value="otro">otro</option></select>'
    			.'<input type="text" maxlength="100" placeholder="Nos podrías indicar cual otro" class="hidden input-text-type10" id="text_for_pregunta_'.self::$id.'"/>'
					.'</fieldset>';

		return $html;

	}



	public static function mostrarInputCorreo($mostrar = 1)
	{
		$html = '<fieldset class="correo-comentarios">';

			switch ($mostrar) {
				case 1:  /* solo correo*/
					$html .= '<input type="text" id="txt_correo" name="txt_correo" placeholder="Ingrese su correo para recibir una promoción(opcional)" autocomplete="off"/>';
					break;
				case 2:/* ambos correo y evidencia*/
				$html .=  '<input type="text" id="txt_correo" name="txt_correo" style="width:49%;float:right;" placeholder="Ingrese su correo para recibir una promoción(opcional)" autocomplete="off"/>'
				.'<label id="lb-img-evidencia" style="position:relative;float:left; display:block; width:49%;height:33px;border:1px solid rgba(0,0,0,0.4);">'
				.'<span style="display:block;font-size:0.75rem;padding:4px 20px 4px 2px;">¿Algún inconveniente? Adjunta una imagen</span>'
				.'<img style="position:absolute;top:3px;right:5px;max-height:26px;" src="/images/camicon2.png"/>'
				.'<input type="file" id="input_evidencia" style="display:none;"  multiple />'
				.'</label>';
				  break;
				case 3: /*solo evidencia*/
					$html .= '<label id="lb-img-evidencia" style="position:relative;float:left; display:block; width:100%;height:33px;border:1px solid rgba(0,0,0,0.4);">'
					.'<span style="display:block;font-size:0.75rem;padding:4px 20px 4px 2px;">¿Algún inconveniente? Adjunta una imagen</span>'
					.'<img style="position:absolute;top:3px;right:5px;max-height:26px;" src="/images/camicon2.png"/>'
					.'<input type="file" id="input_evidencia" style="display:none;"  multiple />'
					.'</label>';
			  	break;
			}

			$html .= '</fieldset>';
			return $html;
	}

	public static function mostrarInputComentarios()
	{
		return '<fieldset class="correo-comentarios">'
		.'<input type="text" id="txt_comentarios" name="txt_comentarios" placeholder="Comentarios(opcional)" autocomplete="off"/>'
		.'</fieldset>';
	}

	public static function mostrarBotonesFinal()
	{
		$html = '';
		$html .=  '<fieldset class="botones-final">';
		$html .= '<button class="btn-finalizar" id="btn-finalizar-encuesta" name="btn-finalizar-encuesta">FINALIZAR</button>'
			.'</fieldset>';

		return $html;
	}

	public static function sucursalDomicilioAsVendedor($arreglo)
	{
		$html = '<div id="modal-suc-domicilios" style="min-height:100vh;width:100%;position:fixed;top:0;left:0;background-color:rgba(0,0,0,0.7);z-index:1000;display:flex;justify-content:center;">
 		 <div class="contenedor" style="padding:3rem;background-color:white;max-width:500px;display:flex;flex-wrap:wrap;justify-content:space-around;">
		 	 <input type="hidden" value="" id="inp-sucursal-domicilio"/>
			 <p style="margin-bottom:1rem;">EN CUAL SUCURSAL ADQUIRISTE LOS ALIMENTOS</p>';
			 for ($i=0; $i < count($arreglo) ; $i++) {
				 $html .=	'<label style="margin-bottom:5px;padding:10px;background-color:#0658C9;color:#ffffff;">
						'.$arreglo[$i].'
						<input type="radio" value="'.$arreglo[$i].'" style="display:none;" name="sucursal-domicilio"/>
						</label>';
			 }

 		 $html .= '</div></div>';
		 return $html;
	}

	public static function pedirFolioTexto()
	{
			return '<div id="modal-pedir-folio" style="min-height:100vh;width:100%;position:fixed;top:0;left:0;background-color:rgba(0,0,0,0.7);z-index:1200;">
			<div style="margin:auto;margin-top:1rem;padding:3rem;background-color:white;max-width:500px;">
				<input type="hidden" value="" id="inp-pedir-folio"/>
				<p style="margin-bottom:1rem;">¿Cual es su número de expediente?</p>
				<small style="display:block;color:red;" id="txt-msj-pedir-folio"></small>
   			<input type="text" id="txt-pedir-folio" maxlength="15" style="display:block;width:100%;padding:5px;"/>
				<button style="margin-top:5px;padding:5px;background-color:white;color:black;border-style:ridge;" id="btn-pedir-folio">Continuar</button>
			</div>
		</div>';

	}




}
