<?php

namespace App\Utilidades;

class PreviewEncuesta
{
  public static $pregunta;
  public static $textos;


  public static function logo($url=null)
  {
    $arr_url = explode('/', $url);
    return '<img src="/images/logo/'.$arr_url[1].'" style="position:absolute;top:15px;right:15px;max-height:80px;max-width:100px;"/>';
  }

  public static function tipo0()
  {
    return "<fieldset class=\"tipo-0\">"
    ."<legend>".self::$pregunta."</legend>"
      ."<img src=\"/images/encuestas/emojibad.png\"/>"
      ."<img src=\"/images/encuestas/emojiregular.png\"/>"
      ."<img src=\"/images/encuestas/emojiwell.png\"/>"
      ."<img src=\"/images/encuestas/emojigood.png\"/>"
    ."</fieldset>";
  }

  public static function tipo1()
  {
    return "<fieldset class=\"tipo-1\">"
      ."<legend>".self::$pregunta."</legend>"
      ."<img src=\"/images/encuestas/no.png\"/>"
      ."<img src=\"/images/encuestas/si.png\"/>"
      ."</fieldset>";
  }

  public static function tipo3()
  {
    return '<fieldset class="tipo-3">'
					.'<legend>'.self::$pregunta.'</legend>'
					.'<input type="text"/>'
				  .'</fieldset>';
  }
  
  public static function tipo4()
  {
    $arr_textos = explode(',', self::$textos);

    return '<fieldset class="tipo-4">'
          .'<legend>'.self::$pregunta.'</legend>'
  				.'<button>'.$arr_textos[3].'</button>'
  				.'<button>'.$arr_textos[2].'</button>'
  				.'<button>'.$arr_textos[1].'</button>'
  				.'<button>'.$arr_textos[0].'</button>'
  				.'</fieldset>';
  }
  public static function tipo5()
  {
    return '<fieldset class="tipo-5">'
					.'<legend>'.self::$pregunta.'</legend>'
					.'<img src="/images/encuestas/n1.png"/>'
					.'<img src="/images/encuestas/n2.png"/>'
					.'<img src="/images/encuestas/n3.png"/>'
					.'<img src="/images/encuestas/n4.png"/>'
					.'<img src="/images/encuestas/n5.png"/>'
					.'<img src="/images/encuestas/n6.png"/>'
					.'<img src="/images/encuestas/n7.png"/>'
					.'<img src="/images/encuestas/n8.png"/>'
				  .'<img src="/images/encuestas/n9.png"/>'
					.'<img src="/images/encuestas/n10.png"/>'
				  .'</fieldset>';
  }

  public static function tipo6()
  {
    return '<fieldset class="tipo-6">'
					 .'<legend>'.self::$pregunta.'</legend>'
				   .'<input type="date"/>'
			     .'</fieldset>';
  }

  public static function tipo8()
  {
    return '<fieldset class="tipo-5">'
          .'<legend>'.self::$pregunta.'</legend>'
          .'<img src="/images/encuestas/n0.png"/>'
          .'<img src="/images/encuestas/n1.png"/>'
          .'<img src="/images/encuestas/n2.png"/>'
          .'<img src="/images/encuestas/n3.png"/>'
          .'<img src="/images/encuestas/n4.png"/>'
          .'<img src="/images/encuestas/n5.png"/>'
          .'<img src="/images/encuestas/n6.png"/>'
          .'<img src="/images/encuestas/n7.png"/>'
          .'<img src="/images/encuestas/n8.png"/>'
          .'<img src="/images/encuestas/n9.png"/>'
          .'<img src="/images/encuestas/n10.png"/>'
          .'</fieldset>';
  }

  public static function tipo9()
  {
       $arr_textos = explode(',' , self::$textos );

       $html = '';

       $html .= '<fieldset class="tipo-9">'
  					.'<legend>'.self::$pregunta.'</legend>'
  					.'<select>'
  					.'<option>-selecciona-</option>';
  						for ($i=0; $i < count($arr_textos) ; $i++) {
                $html .= '<option>'.$arr_textos[$i].'</option>';
              }
				$html .='</select>'
			  .'</fieldset>';

        return $html;
  }

  public static function tipo10()
  {
    $arr_textos = explode(',' , self::$textos );
    $size = count($arr_textos) + 1;

    $html = '';

    $html .= '<fieldset class="tipo-10">'
         .'<legend>'.self::$pregunta.'</legend>'
         .'<select size="'.$size.'" style="width:100%;max-width:450px;overflow:hidden;font-size:0.8rem;padding:3px;">';
         for ($i=0; $i < count($arr_textos) ; $i++) {
             $html .= '<option>'.$arr_textos[$i].'</option>';
           }
     $html .='<option>Otro</option></select>'
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
            .'<input type="text" placeholder="Comentarios(opcional)"/>'
            .'</fieldset>';
  }

  public static function mostrarBotonesFinal($boolean_no_deseo_contestar)
  {
    $html = '';
    $html .=  '<fieldset class="botones-final">';
    if($boolean_no_deseo_contestar)
    {
      $html .= '<button class="btn-no-contestar">NO DESEO CONTESTAR</button>';
    }
		$html .= '<button class="btn-finalizar">FINALIZAR</button>'
		    	.'</fieldset>';

    return $html;
  }

}
