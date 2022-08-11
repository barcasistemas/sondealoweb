<?php

namespace App\Http\Controllers;

use App\Utilidades\ManipularCadenas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Validator;

class PreguntaController extends Controller
{
    public static $PREGUNTAS_RECOMENDADAS_SONDEALO = array(
        "Es la primera vez que nos visitas",
        "Lo visitó el gerente",
        "Te ofrecieron alguna promoción",
        "Calidad de los alimentos",
        "Relación calidad-precio",
        "Calidad de bebidas",
        "Servicio en general",
        "Trato del mesero",
        "Disponibilidad del menú",
        "Ambiente del lugar",
        "Limpieza del lugar",
        "Que tanto nos recomendarías, siendo el 10 lo más alto",
        "Cómo te enteraste de nosotros",
        "Porque nos visitaste el día de hoy",
        "En qué áreas crees que podríamos mejorar",
    );

    public static $columnas_calificaciones_eval = array('eval', 'eval2', 'eval3', 'eval4', 'eval5', 'eval6', 'eval7',
        'eval8', 'eval9', 'eval10', 'eval11', 'eval12', 'eval13');

    public static $columnas_calificaciones_p = array(
        'p1', 'p2', 'p3', 'p4', 'p5', 'p6', 'p7', 'recomen', 'p9', 'p10', 'p11', 'p12', 'p13');

    public static $colores_preguntas = array(
        'tipo-0' => array('excelente' => '#2c6903', 'bueno' => '#63b503', 'regular' => '#feae02', 'malo' => '#cf0505'),
        'tipo-1' => array('si' => '#2c6903', 'no' => '#cf0505'),
        'tipo-4' => array('#2c6903', '#63b503', '#feae02', '#cf0505'),
        'tipo-5' => array('1' => '#ca0805', '2' => '#e31010', '3' => '#fa2323', '4' => '#fede02', '5'  => '#fec002',
            '6'                   => '#feae02', '7' => '#6fca05', '8' => '#63b503', '9' => '#337b04', '10' => '#2c6903'),
        'tipo-9' => array('#2c6903', '#63b503', '#6fca05', '#feae02', '#fede02', '#fa2323', '#ca0805'),
    );

    public static $preguntasPlantillas = array(
        'restaurantes'       => [
            'icono' => 'fa fa-cutlery', 'preguntas' => array(
                array('pregunta' => "Es la primera vez que nos visitas", 'tipo' => 1, 'textos' => ''),
                array('pregunta' => "Trato del mesero", 'tipo' => 0, 'textos' => ''),
                array('pregunta' => "Ambiente del lugar", 'tipo' => 0, 'textos' => ''),
                array('pregunta' => "Calidad de los alimentos", 'tipo' => 0, 'textos' => ''),
                array('pregunta' => "Relación calidad-precio", 'tipo' => 0, 'textos' => ''),
                array('pregunta' => "Te ofrecieron alguna promoción", 'tipo' => 1, 'textos' => ''),
                array('pregunta' => "Lo visitó el gerente", 'tipo' => 1, 'textos' => ''),
                array('pregunta' => "Cómo te enteraste de nosotros", 'tipo' => 4, 'textos' => 'Facebook,instagram,Espectacular,Ubicación'),
                array('pregunta' => "Que tanto nos recomendarías, siendo el 10 lo más alto", 'tipo' => 5, 'textos' => ''),
            ), 'adjuntar_evidencia' => 0, 'seccion_final_top' => 0, 'sin_preguntas_obligatorias' => 0, 'siempre_notificacion' => 0,
        ],
        'hoteles'            => [
            'icono' => 'fa fa-bed', 'preguntas' => array(
                array('pregunta' => "¿Es la primera vez que te hospedas?", 'tipo' => 1, 'textos' => ''),
                array('pregunta' => "Atención en recepción", 'tipo' => 0, 'textos' => ''),
                array('pregunta' => "Servicio a la habitación", 'tipo' => 0, 'textos' => ''),
                array('pregunta' => "Limpieza de las instalaciones", 'tipo' => 0, 'textos' => ''),
                array('pregunta' => "Calidad de las instalaciones", 'tipo' => 0, 'textos' => ''),
                array('pregunta' => "Disponibilidad de habitaciones", 'tipo' => 0, 'textos' => ''),
                array('pregunta' => "¿Cómo te enteraste de nosotros?", 'tipo' => 9, 'textos' => 'Google,Booking,Trivago,TripAdvisor,Facebook,Instagram,Ubicación,Recomendación'),
                array('pregunta' => "¿Qué nos recomendarías para mejorar?", 'tipo' => 3, 'textos' => ''),
                array('pregunta' => "¿Qué probable es que nos recomiendes? ", 'tipo' => 5, 'textos' => ''),
            ), 'adjuntar_evidencia' => 0, 'seccion_final_top' => 0, 'sin_preguntas_obligatorias' => 0, 'siempre_notificacion' => 0,
        ],
        'ambiente-laboral'   => [
            'icono' => 'fa fa-users', 'preguntas' => array(
                array('pregunta' => "Su nombre(opcional)", 'tipo' => 3, 'textos' => ''),
                array('pregunta' => "¿Cuál sería un motivo por el cual usted decidiría cambiar de empleo?", 'tipo' => 9, 'textos' => 'mejor sueldo,mejores instalaciones,mejor ambiente de trabajo'),
                array('pregunta' => "¿Qué deberíamos de mejorar en el servicio de comedor?", 'tipo' => 3, 'textos' => ''),
                array('pregunta' => "¿Cuál es el estado fisico de los baños?", 'tipo' => 0, 'textos' => ''),
                array('pregunta' => "¿Se le ha porporcionado material de trabajo y herramientas adecuadas?", 'tipo' => 1, 'textos' => ''),
                array('pregunta' => "¿Atención de su supervisor?", 'tipo' => 0, 'textos' => ''),
                array('pregunta' => "¿Estado del transporte de la empresa?", 'tipo' => 4, 'textos' => 'Pésimo,Regular,Bueno,Excelente'),
            ), 'adjuntar_evidencia' => 0, 'seccion_final_top' => 0, 'sin_preguntas_obligatorias' => 0, 'siempre_notificacion' => 0,
        ],
        'quejas-sugerencias' => [
            'icono' => 'fa fa-exclamation-circle', 'preguntas' => array(
                array('pregunta' => "Su nombre(opcional)", 'tipo' => 3, 'textos' => ''),
            ), 'adjuntar_evidencia' => 1, 'seccion_final_top' => 1, 'sin_preguntas_obligatorias' => 1, 'siempre_notificacion' => 1,
        ],
    );

    public function preguntasReorden(Request $request)
    {
        $validator = Validator::make($request->only('preguntas'), ['preguntas' => 'required|array|min:2']);
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'msg' => 'No Valido']);
        }
        $arr_preguntas = $request['preguntas'];

        $sucursal = mb_strtolower($arr_preguntas[0]['suc'], 'UTF-8');

        DB::table('cuestionario')->where('sucursal', $sucursal)->update(['valor' => 2, 'pregunta' => '', 'valor2' => 0, 'textos' => '']);

        for ($i = 0; $i < count($arr_preguntas); $i++) {
            DB::table('cuestionario')->where(['sucursal' => $sucursal, 'id' => $arr_preguntas[$i]['id']])
                ->update([
                    'pregunta' => $arr_preguntas[$i]['descripcion'],
                    'valor'    => $arr_preguntas[$i]['tipo'],
                    'valor2'   => $arr_preguntas[$i]['v2'],
                    'textos'   => $arr_preguntas[$i]['textos'],
                ]);
        }

        DB::table('valores')->where(['sucursal' => $sucursal, 'id' => 1])->update(['valor' => count($arr_preguntas)]);

        return response()->json(['status' => 200, 'msg' => 'Preguntas actualizadas con éxito']);
    }

    public function copiarPreguntas(Request $request)
    {
        $validator = Validator::make($request->all(), ['copiar_desde' => 'required|string|max:50', 'copiar_destino' => 'required|string|max:50']);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'msg' => 'No valido']);
        }
        $sucursal_desde   = $request['copiar_desde'];
        $sucursal_destino = $request['copiar_destino'];

        /*desactivamos todas las preguntas del destino*/
        DB::table('cuestionario')->where('sucursal', $sucursal_destino)->update(['valor' => 2, 'pregunta' => '', 'valor2' => 0, 'textos' => '']);

        //obtenemos las preguntas a replicar
        $preguntas_desde = DB::table('cuestionario')->select('id', 'pregunta', 'valor', 'valor2', 'textos')
            ->whereRaw("sucursal = '$sucursal_desde' AND valor != 2 ")->orderBy('id', 'ASC')->get();

        if (count($preguntas_desde) < 3) {
            return response()->json(['status' => 204, 'msg' => 'Sin información']);
        }

        $count_preguntas_desde = count($preguntas_desde);

        for ($i = 0; $i < $count_preguntas_desde; $i++) {
            $pregunta = ($preguntas_desde[$i]->pregunta == '') ? "pregunta" . $preguntas_desde[$i]->id : $preguntas_desde[$i]->pregunta;
            $valor    = ($preguntas_desde[$i]->valor == '') ? 0 : $preguntas_desde[$i]->valor;
            $valor2   = ($preguntas_desde[$i]->valor2 == '') ? 0 : $preguntas_desde[$i]->valor2;

            DB::table('cuestionario')->where(['sucursal' => $sucursal_destino, 'id' => $preguntas_desde[$i]->id])
                ->update([
                    'pregunta' => $pregunta,
                    'valor'    => $valor,
                    'valor2'   => $valor2,
                    'textos'   => $preguntas_desde[$i]->textos,
                ]);
        }
        //se actualiza tabla valores
        DB::table('valores')->where(['sucursal' => $sucursal_destino, 'id' => 1])->update(['valor' => $count_preguntas_desde]);

        return response()->json(['status' => 200, 'msg' => 'Copiado con éxito']);
    }

    public function guardarEdicion(Request $request)
    {
        $validator = Validator::make($request->all(), ['s' => 'required|string|max:50',
            'id'                                               => 'required|integer|min:1', 'pregunta' => 'required|string|min:1|max:255', 'tipo' => 'required|integer',
            'opciones'                                         => 'present|array']);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'msg' => 'No valido']);
        }

        $sucursal = $request['s'];
        $id       = (int) $request['id'];
        $pregunta = $request['pregunta'];
        $tipo     = (int) $request['tipo'];

        $opciones = '';

        if ($tipo == 4 or $tipo == 9) {
            $opciones = implode(',', $request['opciones']);
            $opciones = mb_strtolower($opciones, 'UTF-8');
        }

        $update = DB::table('cuestionario')->where(['sucursal' => $sucursal, 'id' => $id])
            ->update([
                'pregunta' => $pregunta,
                'valor'    => $tipo,
                'valor2'   => 0,
                'textos'   => $opciones,
            ]);

        if (!$update) {
            return response()->json(['status' => 204, 'msg' => 'No se pudo actualizar']);
        }

        return response()->json(['status' => 200, 'msg' => 'Actualizado con éxito, espera ....']);
    }

    public static function preguntasPromedios($sucursal_url, $desde, $hasta)
    {
        /*traemos las preguntas que usaremos*/
        $preguntas = DB::select(DB::raw(
            "SELECT id, pregunta, valor, valor2 FROM cuestionario WHERE sucursal='$sucursal_url'
        AND valor != 2 AND valor != 3 AND valor != 4 AND valor != 8 AND valor != 6 AND valor != 9 AND valor != 10
        UNION ALL
        SELECT id, pregunta, valor, valor2 FROM cuestionario WHERE sucursal='$sucursal_url' AND valor = 4 AND valor2=1
        ORDER BY id ASC"));

        if (count($preguntas) > 0) {
            $query_raw_select = "";
            $contador         = 0;
            /*recorremos el arreglo de preguntas*/
            foreach ($preguntas as $pregunta) {
                $eval          = '';
                $multiplicador = 1;

                /*obtenemos el multiplicador*/
                if ($pregunta->valor == 0 or $pregunta->valor == 4) {
                    $multiplicador = 10;
                }
                if ($pregunta->valor == 1) {
                    $multiplicador = 100;
                }
                /*obtenemos el nombre de cada columna, para ir armando la consulta*/
                $eval = self::$columnas_calificaciones_eval[((int) $pregunta->id) - 1];
                $query_raw_select .= "ROUND( (AVG($eval))*$multiplicador , 1 ) AS 'p" . ($contador + 1) . "'";

                if ($contador < count($preguntas) - 1) {
                    $query_raw_select .= ' , ';
                }
                $contador++;
            }

            /*obtenemos el promedio de acuerdo a la columna*/
            $promedios = DB::table('calificaciones')->selectRaw($query_raw_select)
                ->whereRaw("sucursal ='$sucursal_url' AND fec BETWEEN '$desde' AND '$hasta' ")->get();

            $suma_promedio     = 0;
            $contador_promedio = 0;

            /*una vez obtenido el promedio de la pregunta, recorremos nuevamente las preguntas*/
            /*para asignar a cada una su promedio y color de barra a mostrar*/
            for ($i = 0; $i < count($preguntas); $i++) {
                $signo = ($preguntas[$i]->valor == 1) ? ' %' : '';

                $clase_css = '';
                $promedio  = 0;
                $promedio  = $promedios[0]->{'p' . ($i + 1)};

                $clase_css = ManipularCadenas::getColorPromedio($promedio);

                if ($preguntas[$i]->valor == 1) {
                    $clase_css = 'blue';
                }

                if ($preguntas[$i]->valor != 1 and $preguntas[$i]->valor != 9) {
                    $suma_promedio += $promedio;
                    $contador_promedio++;
                }
                $preguntas[$i]->promedio = $promedio;
                $preguntas[$i]->css      = $clase_css;
                $preguntas[$i]->signo    = $signo;
            }
            /*calculamos el promedio general*/
            $promedio_general = ($contador_promedio != 0) ? $suma_promedio / $contador_promedio : 0;

            $preguntas[0]->promedios      = number_format($promedio_general, 1, '.', '');
            $preguntas[0]->css_prom_gnral = ManipularCadenas::getColorPromedio($promedio_general);
        }

        return $preguntas;
    }

    public static function getInfoCharts($sucursal_url, $desde = null, $hasta = null)
    {
        $preguntas = DB::table('cuestionario')->select('id', 'pregunta', 'valor', 'textos')
            ->whereRaw("sucursal='$sucursal_url'  AND valor != 2 AND valor != 3 AND valor != 8 AND valor != 6")->orderBy('id', 'ASC')->get();

        $total_encuestas_contestadas = DB::table('calificaciones')->selectRaw("count(*) as 'total'")
            ->whereRaw("sucursal ='$sucursal_url' and fec between '$desde' and '$hasta'")->first()->total;

        $multiplicador = ($total_encuestas_contestadas > 0) ? 100 / $total_encuestas_contestadas : 1;

        for ($i = 0; $i < count($preguntas); $i++) {
            $id   = $preguntas[$i]->id;
            $tipo = $preguntas[$i]->valor;

            $columna        = self::$columnas_calificaciones_p[($id - 1)];
            $query_pregunta = DB::table('calificaciones')->selectRaw("$columna as 'opcion', count(*) as 'conteo'")
                ->whereRaw("sucursal ='$sucursal_url' and fec between '$desde' and '$hasta'")->groupBy($columna)->get();

            /*se reinicia por cada iteracion*/
            $arreglo_colores = array();
            $arreglo_valores = array();
            $arreglo_labels  = array();

            if ($tipo == 0 or $tipo == 1 or $tipo == 5) {
                $arreglo_tipo_0_1_5 = array();
                /*seleccionamos el arreglo de acuerdo al tipo de pregunta*/
                $arreglo_tipo_0_1_5 = self::$colores_preguntas['tipo-' . $tipo];
                foreach ($query_pregunta as $p) {
                    $opcion_lowercase = mb_strtolower($p->opcion, 'UTF-8');

                    if (array_key_exists($opcion_lowercase, $arreglo_tipo_0_1_5)) {
                        $arreglo_colores[] = $arreglo_tipo_0_1_5[$opcion_lowercase];
                        $arreglo_labels[]  = $p->opcion;
                        $arreglo_valores[] = number_format($multiplicador * $p->conteo, 2, '.', '');
                    }
                }
            }

            if ($tipo == 4 or $tipo == 9 or $tipo == 10) /*inicio if 4 y 9*/ {
                $textos_arreglo = explode(',', $preguntas[$i]->textos);

                if (count($textos_arreglo) > 0) {
                    for ($y = 0; $y < count($textos_arreglo); $y++) {
                        $temp               = $textos_arreglo[$y];
                        $textos_arreglo[$y] = mb_strtolower($temp, 'UTF-8');
                    }
                }

                $arreglo_tipo_4 = self::$colores_preguntas['tipo-4'];
                $arreglo_tipo_9 = self::$colores_preguntas['tipo-9'];
                //recorremos los resultados de la consulta en la base de datos
                foreach ($query_pregunta as $preg) {
                    //recorremos los textos que la pregunta tiene guardados
                    for ($j = 0; $j < count($textos_arreglo); $j++) {
                        //si existe una coincidencia entre una opcion guardada en los textos y una de las consultadas en la BD
                        //usamos el indice actual para seleccionar el color en tal indice
                        if (mb_strtolower(trim($preg->opcion), 'UTF-8') == mb_strtolower(trim($textos_arreglo[$j]), 'UTF-8')) {
                            //insertamos el color en el arreglo de colores
                            $arreglo_colores[] = ($tipo == 4) ? $arreglo_tipo_4[$j] : $arreglo_tipo_9[$j];
                            $arreglo_labels[]  = $preg->opcion;
                            $arreglo_valores[] = number_format($multiplicador * $preg->conteo, 2, '.', '');
                        }

                    }

                    /*en caso de traer una opcion que no concuerda con ninguno de los textos, se inserta con el color sondealo*/
                    if (!in_array($preg->opcion, $arreglo_labels)) {
                        $arreglo_colores[] = '#0658c9';
                        $arreglo_labels[]  = $preg->opcion;
                        $arreglo_valores[] = number_format($multiplicador * $preg->conteo, 2, '.', '');
                    }

                }
            } /*fin if 4 y 9*/

            for ($k = 0; $k < count($arreglo_labels); $k++) {

                $label_concat = (trim($arreglo_labels[$k]) == '') ? 'No contesto, ' . $arreglo_valores[$k] . '%'
                : $arreglo_labels[$k] . ', ' . $arreglo_valores[$k] . '%';
                $arreglo_labels[$k] = $label_concat;
            }

            $preguntas[$i]->colores = $arreglo_colores;
            $preguntas[$i]->labels  = $arreglo_labels;
            $preguntas[$i]->valores = $arreglo_valores;
        }

        return $preguntas;
    }

    public function getInfoNPS(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sucursal' => 'required|string|max:30', 'desde' => 'required|date_format:"Y-m-d H:i:s"',
            'hasta'    => 'required|date_format:"Y-m-d H:i:s"']);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'msg' => 'No valido']);
        }

        $sucursal = $request['sucursal'];
        $desde    = $request['desde'];
        $hasta    = $request['hasta'];

        $check_if_nps = DB::table('cuestionario')->select('id', 'pregunta')->where(['valor' => 8, 'sucursal' => $sucursal])->first();

        if (!$check_if_nps) {
            return response()->json(['status' => 204, 'msg' => 'empty']);
        }

        $id_pregunta = $check_if_nps->id;
        $pregunta    = $check_if_nps->pregunta;

        $detractores = DB::table('calificaciones')->selectRaw("count(eval$id_pregunta) AS 'conteo'")
            ->whereRaw("sucursal='$sucursal' AND eval$id_pregunta = 1 AND fec BETWEEN '$desde' AND '$hasta'")->first()->conteo;

        $pasivos = DB::table('calificaciones')->selectRaw("count(eval$id_pregunta) AS 'conteo'")
            ->whereRaw("sucursal='$sucursal' AND eval$id_pregunta = 2 AND fec BETWEEN '$desde' AND '$hasta'")->first()->conteo;

        $promotores = DB::table('calificaciones')->selectRaw("count(eval$id_pregunta) AS 'conteo'")
            ->whereRaw("sucursal='$sucursal' AND eval$id_pregunta = 3 AND fec BETWEEN '$desde' AND '$hasta'")->first()->conteo;

        $sumatoria = 0;
        $nps       = 0;

        $sumatoria = $detractores + $pasivos + $promotores;
        $nps       = $promotores - $detractores;
        $nps       = $nps / $sumatoria;
        $nps       = $nps * 100;
        $nps       = number_format($nps, 2, '.', '');

        return response()->json(['status' => 200, 'msg' => 'success',
            'info' => array('pregunta' => $pregunta, 'promotores' => $promotores, 'detractores' => $detractores,
            'pasivos' => $pasivos, 'nps' => $nps),
        ]);
    }

    public function getInfoComentarios(Request $request)
    {
        $validator = Validator::make($request->all(), ['sucursal' => 'required|string|max:30',
            'desde' => 'required|date_format:"Y-m-d H:i:s"', 'hasta' => 'required|date_format:"Y-m-d H:i:s"', 'limite_i' => 'required|integer']);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'msg' => 'No valido']);
        }

        $sucursal = $request['sucursal'];
        $desde    = $request['desde'];
        $hasta    = $request['hasta'];

        $limite_inferior = $request['limite_i'];
        $limite_superior = 20;

        $comentarios = DB::table('calificaciones')->select('id', 'folio', 'fecha', 'comentarios')
            ->whereRaw("fec BETWEEN '$desde' AND '$hasta' AND sucursal='$sucursal' AND comentarios !='' AND comentarios != '\"\"'")
            ->orderBy('id', 'DESC')->offset($limite_inferior)->limit($limite_superior)->get();

        if (count($comentarios) < 1) {
            return response()->json(['status' => 204, 'msg' => 'Sin comentarios']);
        }

        $arreglo_comentarios = array();

        for ($i = 0; $i < count($comentarios); $i++) {
            if (substr($comentarios[$i]->comentarios, 0, 1) == '"') {
                $comentario = ManipularCadenas::decodeEmoticons($comentarios[$i]->comentarios);
                //$comentarios[$i]->comentarios = substr($comentario, 1, -1);
                $comentarios[$i]->comentarios = $comentario;
            }

            $bool_alerta = false;
            $css         = '';
            $bool_alerta = ManipularCadenas::buscarAlerta($comentarios[$i]->comentarios);

            if ($bool_alerta) {
                $css = 'alerta';
            }
            $comentarios[$i]->css = $css;
        }
        return response()->json(['status' => 200, 'msg' => 'success', 'info' => [$comentarios]]);
    }

    public function getEncuestas(Request $request)
    {
        $validator = Validator::make($request->all(), ['sucursal' => 'required|string|max:30',
            'desde'                                                   => 'required|date_format:"Y-m-d H:i:s"', 'hasta' => 'required|date_format:"Y-m-d H:i:s"', 'limite_i' => 'required|integer']);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'msg' => 'No valido']);
        }

        $sucursal = $request['sucursal'];
        $desde    = $request['desde'];
        $hasta    = $request['hasta'];

        $limite_inferior = $request['limite_i'];
        $limite_superior = 20;

        $preguntas = DB::table('cuestionario')->select('pregunta')
            ->whereRaw("sucursal = '$sucursal' AND valor != 2")->orderBy('id', 'ASC')->get();
        $size_preguntas = count($preguntas);

        $arr_campos_calificaciones = array_slice(self::$columnas_calificaciones_p, 0, $size_preguntas);
        $str_campos_calificaciones = implode(',', $arr_campos_calificaciones);

        $select_raw = "id, fecha, folio, mesa, mesero, $str_campos_calificaciones , correo, comentarios";

        $encuestas = DB::table('calificaciones')->selectRaw($select_raw)
            ->whereRaw("fec BETWEEN '$desde' AND '$hasta' AND  sucursal='$sucursal'")->orderBy('id', 'DESC')
            ->offset($limite_inferior)->limit($limite_superior)->get();

        if (count($encuestas) < 1) {
            return response()->json(['status' => 204, 'msg' => 'Sin información']);
        }

        $arr_orden = array();

        for ($i = 0; $i < count($encuestas); $i++) 
        {
            $arreglo_index           = array();
            $arreglo_index['fecha']  = $encuestas[$i]->fecha;
            $arreglo_index['ticket'] = $encuestas[$i]->folio;
            $arreglo_index['mesa']   = $encuestas[$i]->mesa;
            $arreglo_index['mesero'] = $encuestas[$i]->mesero;

            $check_evidencia = DB::table('evidencia')->select('id', 'ruta_evidencia_1', 'ruta_evidencia_2')
                ->where('id_encuesta', $encuestas[$i]->id)->first();

            $evidencia_1 = '';
            $evidencia_2 = '';

            if ($check_evidencia) {
                $evidencia_1 = $check_evidencia->ruta_evidencia_1;
                $evidencia_2 = $check_evidencia->ruta_evidencia_2;
            }
            $arreglo_index['file1'] = $evidencia_1;
            $arreglo_index['file2'] = $evidencia_2;

            $arreglo_pregunta_respuesta = array();

            for ($j = 0; $j < count($preguntas); $j++) {
                $pregunta  = $preguntas[$j]->pregunta;
                $respuesta = $encuestas[$i]->{$arr_campos_calificaciones[$j]};

                $arreglo_pregunta_respuesta['p' . ($j + 1)] = $pregunta;
                $arreglo_pregunta_respuesta['r' . ($j + 1)] = $respuesta;
            }

            $arreglo_index['preguntas'] = [$arreglo_pregunta_respuesta];
            $arreglo_index['correo']    = $encuestas[$i]->correo;

            if ($encuestas[$i]->comentarios == '""' or trim($encuestas[$i]->comentarios) == '') {
                $encuestas[$i]->comentarios = '';
            }
            $comentario = $encuestas[$i]->comentarios;

            if (substr($comentario, 0, 1) == '"') {
                $comentario = ManipularCadenas::decodeEmoticons($encuestas[$i]->comentarios);
                $comentario = substr($comentario, 1, -1);
            }

            $arreglo_index['comentario'] = $comentario;

            array_push($arr_orden, $arreglo_index);
        }
        return response()->json(['status' => 200, 'msg' => 'success', 'info' => $arr_orden]);
    }

    public function getInfoDetalleEncuesta(Request $request)
    {
        $validator = Validator::make($request->all(), ['encuesta' => 'required|integer|min:1', 'sucursal' => 'required|string|max:30']);
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'msg' => 'No valido']);
        }

        $sucursal    = $request['sucursal'];
        $encuesta_id = $request['encuesta'];

        $preguntas = DB::table('cuestionario')->select('pregunta')->whereRaw("sucursal='$sucursal' AND valor != 2")
            ->orderBy('id', 'ASC')->get();

        $size_preguntas = count($preguntas);

        $arr_campos_calificaciones = array_slice(self::$columnas_calificaciones_p, 0, $size_preguntas);
        $str_campos_calificaciones = implode(',', $arr_campos_calificaciones);

        $select_raw = "id, fecha, folio, mesa, mesero, $str_campos_calificaciones , correo, comentarios";

        $encuesta = DB::table('calificaciones')->selectRaw($select_raw)->where('id', $encuesta_id)->first();
        if (!$encuesta) {
            return response()->json(['status' => 204, 'msg' => 'La encuesta no existe']);
        }

        $arreglo_final_encuesta = array();

        $arreglo_final_encuesta['fecha']  = $encuesta->fecha;
        $arreglo_final_encuesta['ticket'] = $encuesta->folio;
        $arreglo_final_encuesta['mesa']   = $encuesta->mesa;
        $arreglo_final_encuesta['mesero'] = $encuesta->mesero;

        $check_evidencia = DB::table('evidencia')->select('id', 'ruta_evidencia_1', 'ruta_evidencia_2')
            ->where('id_encuesta', $encuesta->id)->first();

        $evidencia_1 = '';
        $evidencia_2 = '';

        if ($check_evidencia) {
            $evidencia_1 = $check_evidencia->ruta_evidencia_1;
            $evidencia_2 = $check_evidencia->ruta_evidencia_2;
        }
        $arreglo_final_encuesta['file1'] = $evidencia_1;
        $arreglo_final_encuesta['file2'] = $evidencia_2;

        $arreglo_preguntas = array();

        for ($i = 0; $i < count($arr_campos_calificaciones); $i++) {
            $arreglo_preguntas['p' . ($i + 1)] = $preguntas[$i]->pregunta;
            $arreglo_preguntas['r' . ($i + 1)] = $encuesta->{$arr_campos_calificaciones[$i]};
        }

        $arreglo_final_encuesta['preguntas'] = [$arreglo_preguntas];
        $arreglo_final_encuesta['correo']    = $encuesta->correo;

        $comentario = $encuesta->comentarios;

        if (substr($comentario, 0, 1) == '"') {
            $comentario = ManipularCadenas::decodeEmoticons($encuesta->comentarios);
            $comentario = substr($comentario, 1, -1);
        }

        $arreglo_final_encuesta['comentario'] = $comentario;

        return response()->json(['status' => 200, 'msg' => 'success', 'info' => $arreglo_final_encuesta]);
    }

    public function aplicarPlantilla(Request $request)
    {
        $validator = Validator::make($request->all(), ['sucursal' => 'required|string|max:50', 'plantilla' => 'required|string']);
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'msg' => 'No valido']);
        }

        if (!array_key_exists($request['plantilla'], self::$preguntasPlantillas)) {
            return response()->json(['status' => 404, 'msg' => 'La plantilla no existe']);
        }

        $dataPlantilla = self::$preguntasPlantillas[$request['plantilla']];

        $preguntas = $dataPlantilla['preguntas'];

        $size_preguntas = (Session::get('plan') == 2 && count($preguntas) > 5) ? 5 : count($preguntas);

        DB::table('valores')->where(['sucursal' => $request['sucursal'], 'id' => 1])->update(['valor' => $size_preguntas]);
        DB::table('valores')->where(['sucursal' => $request['sucursal'], 'id' => 2])->update(['valor' => 0]);
        DB::table('valores')->where(['sucursal' => $request['sucursal'], 'id' => 6])->update(['valor' => $dataPlantilla['adjuntar_evidencia']]);

        DB::table('cuestionario')->where(['sucursal' => $request['sucursal']])->update([
            'pregunta' => '',
            'valor'    => 2,
            'valor2'   => 0,
            'textos'   => '',
        ]);

        $id_sucursal = DB::table('sucursales')->select('id')->where('sucursal', $request['sucursal'])->first()->id;

        DB::table('sucursales')->where('id', $id_sucursal)->update([
            'notificacion_comentario'    => $dataPlantilla['siempre_notificacion'],
            'sin_preguntas_obligatorias' => $dataPlantilla['sin_preguntas_obligatorias'],
            'emailcomentarios_top'       => $dataPlantilla['seccion_final_top'],
        ]);

        for ($i = 0; $i < $size_preguntas; $i++) {
            $pregunta = $preguntas[$i]['pregunta'];
            $tipo     = $preguntas[$i]['tipo'];
            $textos   = $preguntas[$i]['textos'];

            DB::table('cuestionario')->where(['sucursal' => $request['sucursal'], 'id' => ($i + 1)])->update([
                'pregunta' => $pregunta,
                'valor'    => $tipo,
                'textos'   => $textos,
            ]);
        }
        return response()->json(['status' => 200, 'msg' => 'Actualizado con éxito']);
    }


    public function getPreguntasMovil(Request $request)
    {
        $validator = Validator::make($request->only('sucursal'), [
            'sucursal' => 'required|string'
        ]);

        if ($validator->fails()){
            return response()->json([
            'errors' => ['msg' => 'Información no valida' ]
            ], 422);
        }

        $preguntas = DB::table('cuestionario')
        ->selectRaw("id, pregunta, valor AS 'tipo', textos AS 'opciones'")
        ->where('sucursal', $request->sucursal)
        ->where('valor', '!=', 2)
        ->orderBy('id', 'ASC')
        ->get();

        if(!$preguntas->count()){
            return response()->json([
                'errors' => ['msg' => "No hay preguntas para la sucursal $request->sucursal" ]
            ], 404);
        }

        return response()->json([
            'errors' => ['msg' => '' ],
            'success' => ['msg' => 'success', 'preguntas' => $preguntas]
        ], 200);
    }
}
