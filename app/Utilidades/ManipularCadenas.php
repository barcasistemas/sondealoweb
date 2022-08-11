<?php
namespace App\Utilidades;

class ManipularCadenas
{
  public static $abecedarioNumeros = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l',
  'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z','A', 'B', 'C', 'D', 'E', 'F',
  'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
  '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '_');


  public static $palabrasAlerta = array('propina', 'exijio', 'exigiendo', 'exigio', 'acoso', 'acosar', 'acosa', 'acosando', 'molestando', 'acosaba', 'pesimo', 'pésimo', 'malo', 'mal', 'lento', 'fatal',
  'feo', 'falla', 'fallando', 'fallar', 'fallo', 'falló', 'mala', 'malos', 'malas', 'pesima', 'pesimas', 'pésimas', 'pesimos', 'pésimos', 'lentos', 'lenta', 'lentas', 'tarda', 'tardan', 'tardaron',
  'tardaban', 'pelo', 'pelos', 'cabello', 'cabellos', 'falta', 'faltan', 'falto', 'faltó', 'faltaba', 'faltaron', 'seco', 'reseco', 'congelado', 'congelada','frío','frio', 'asco', 'tardada', 'tardado',
  'sucio', 'sucios', 'sucia', 'sucias', 'mosca', 'moscas', 'mosquito', 'mosquitos', 'mosco', 'moscos', 'cucaracha', 'cucarachas', 'desabrida', 'desabridas', 'desabrido', 'desabridos', 'duro',
  'duros', 'dura', 'duras', 'quemado', 'quemados', 'quemada', 'quemadas', 'kemado', 'kemados', 'kemada', 'kemadas', 'volumen', 'porcion', 'porciones', 'tardados', 'tardadas', 'incomodo',
  'incómodo', 'incomodos', 'incómodos', 'incomoda', 'incómoda', 'incomodas', 'incómodas', 'peor', 'peores', 'nunca', 'salado', 'salada', 'sal', 'crudo', 'crudos', 'cruda', 'crudas', 'menos',
  'excepto', 'exepto', 'excepcion', 'excepción', 'exepcion', 'exepción', 'pero', 'embargo', 'mejorar', 'mejoraran', 'faltaban', 'poco', 'poca', 'ofrecer', 'no', 'viejo', 'vieja',
  'presion', 'incluir', 'habia', 'mucha', 'limpieza', 'insecto', 'salio', 'diferente', 'deberia', 'debería', 'pero', 'horrible', 'pongan', 'excesiv', 'exesiv', 'excesib', 'exesib',
  'agregar', 'cuidar', 'incomible', 'huele', 'ojala', 'ojalá', 'solo', 'calor', 'recoger', 'basura', 'mas', 'gotea', 'hechado', 'echado', 'perder', 'pequeño', 'pequeña',
  'pequeno', 'pequena', 'pequeñito', 'pequeñita', 'pequenito', 'pequenita', 'olvidan', 'olvidaron', 'olviden', 'van', 'tamaño', 'tamano', 'necesitan', 'ruido', 'demasiado',
  'chiquito', 'molesto', 'molesta', 'rechina', 'fuerte', 'equivoco', 'equivocaron', 'equivoca', 'desagradable', 'sin', 'tardaro', 'insipido', 'insípido', 'tiempo', 'ay', 'fria',
  'frias', 'fría', 'frías', 'abusivo', 'lent0', 'oxidado', 'oxidada', 'caducado', 'caducada', 'ignorar', 'ignoro', 'ignorando', 'ignoraron', 'harto', 'hartan', 'harte', 'sugerencia', 'sugerir',
   'asqueroso', 'asquerosa', 'asquerosos', 'asquerosas', 'grosero', 'grosera', 'groseros', 'groseras', 'groseria', 'grosería', 'groserías', 'groserias','maleducado', 'maleducada',
   'espera', 'esperar', 'esperando', 'ineficiente', 'insuficiente', 'esperamos', 'más', 'tacto',
   'acceso', 'informacion', 'accesar', 'rampa', 'rampas', 'decaido', 'decaído', 'atencion', 'atención', 'bajado', 'puntual', 'puntualidad', 'imputual', 'impuntualidad', 'impuntuales',
   'puntuales', 'tiempos', 'largo', 'largos', 'larga', 'largas',
   'cafeteria', 'cafetería', 'baños', 'baño', 'mal', 'padel', 'alberca', 'chapoteadero', 'juegos', 'jardin', 'jardín',
   'cancha', 'mantenimiento', 'salon', 'area', 'areas', 'servicio', 'baile', 'aire', 'curso', 'cursos', 'instalaciones',
    'descuidado', 'descuidadas', 'descuidada', 'descuidados', 'tenis', 'futbol', 'fut', 'academia', 'luz', 'foco', 'lampara', 'lamparas', 'botes');

  public static function decodeEmoticons($src)
	{
		$replaced = preg_replace("/\\\\u([0-9A-F]{1,4})/i", "&#x$1;", $src);
		$result = mb_convert_encoding($replaced, "UTF-16", "HTML-ENTITIES");
		$result = mb_convert_encoding($result, 'utf-8', 'utf-16');
		return $result;
	}

  public static function cadenaAleatoria($longitud = 10)
  {
    $sizeArr = count(self::$abecedarioNumeros);
    $cadena  = '';
    for($i=1;$i<=$longitud;$i++)
    {
      $random = random_int(0, $sizeArr - 1);
      $cadena .= self::$abecedarioNumeros[$random];
    }
    return $cadena;
  }

  public static function buscarAlerta($cadena = null)
  {
    if($cadena == null or $cadena == ''){
      return false;
    }


    $cadena = json_decode($cadena, JSON_UNESCAPED_UNICODE);


     $pattern = "/[,\s.\"]/";
     $arreglo_comentario = preg_split($pattern, $cadena);

    //$arreglo_comentario = explode(' ', $cadena);



    for($i=0;$i<count($arreglo_comentario);$i++)
    {
      $palabra = mb_strtolower($arreglo_comentario[$i], 'UTF-8');

      for($j=0;$j<count(self::$palabrasAlerta);$j++)
      {
        if(self::$palabrasAlerta[$j] == $palabra)
        {
          return true;
        }
      }
    }
    return false;

  }

  public static function getExtension($str)
  {
		$strExp = explode(".", $str);
		return end($strExp);
  }
  public static function getColorPromedio($valor)
  {
    $clase_css = '';
    if($valor >= 9.5)
    {
      $clase_css = 'darkgreen';
    }
    elseif($valor >= 9.0)
    {
      $clase_css = 'lime';
    }
    elseif($valor >= 8.5)
    {
      $clase_css = 'yellow';
    }
    elseif($valor >= 8.0)
    {
      $clase_css = 'orange';
    }
    else{
      $clase_css = 'red';
    }
    return $clase_css;
  }

  public static function getMultiplicador($valor, $valor2)
  {
      $multip = 0;

      if ($valor == 0) {
          $multip = 10;
      } elseif ($valor == 1) {
          $multip = 100;
      } elseif ($valor == 3) {
          $multip = 0;
      } elseif ($valor == 4 && $valor2 == 1) {
          $multip = 10;
      } elseif ($valor == 4 && $valor2 == 0) {
          $multip = 0;
      } elseif ($valor == 5 || $valor == 8) {
          $multip = 1;
      }
      return $multip;
  }

  public static function obtenerFechaFormateada($fecha)
  {
    $fecha = strtotime($fecha);

    $a = date('Y', $fecha);
    $m = date('n', $fecha);
    $d = date('d', $fecha);
    $h = date('G:i:s', $fecha);

    $diasemana   = date('w', $fecha);
    $diassemanaN = array("Domingo", "Lunes", "Martes", "Miércoles",
                "Jueves", "Viernes", "Sábado");
    $mesesN = array(1 => "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
            "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    return $diassemanaN[$diasemana] . ", $d de " . $mesesN[$m] . " del $a, $h";
  }

}
