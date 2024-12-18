<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class ValoresController extends Controller
{
    public function updateValores(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'preguntas'    => 'required|integer|min:1',
        'evidencia'    => 'required|min:0|max:1',
        'no_contestar' => 'required|min:0|max:1',
        'correo'       => 'required|min:0|max:1',
        'comentario'   => 'required|min:0|max:1',
        'suc'          => 'required|string',
	'mover_top'      => 'required|min:0|max:1',
        'siempre_alerta' => 'required|min:0|max:1'
        ]);

        if($validator->fails()){
          return response()->json(['status'=> 422, 'msg' => 'No valido']);
        }

        $sucursal = mb_strtolower(session()->get('sucursal_fijada'), 'UTF-8');
       
       $id_sucursal = DB::table('sucursales')->select('id')->where('sucursal', $sucursal)->first()->id;

       DB::table('sucursales')->where('id', $id_sucursal)->update([
         'notificacion_comentario' => $request->siempre_alerta,
         'emailcomentarios_top'    => $request->mover_top
       ]);


        DB::table('valores')->where(['sucursal' => $sucursal, 'id' => 1 ])->update([
          'valor' =>  $request['preguntas']
        ]);

        $conteo_preguntas_bd = DB::table('cuestionario')->selectRaw("count(id) as 'conteo'")
        ->whereRaw("sucursal = '$sucursal' AND valor != 2")->first()->conteo;

        $inicio = (int)$request['preguntas'];

        if($inicio != $conteo_preguntas_bd)
        {
            if( (int)$request['preguntas'] < $conteo_preguntas_bd )
            {
              for($i=$inicio+1;$i<=$conteo_preguntas_bd;$i++)
              {
                DB::table('cuestionario')->where(['sucursal' => $sucursal, 'id'=> $i])
                ->update(['valor' => 2, 'valor2' => 0, 'textos'=> '']);
              }
            }
            else
            {
              for($j=$conteo_preguntas_bd+1;$j<=$inicio;$j++)
              {
                  DB::table('cuestionario')->where(['sucursal' => $sucursal, 'id' => $j])->update([
                    'pregunta' => 'Pregunta '.$j,
                    'valor'    => 0,
                    'valor2'   => 0,
                    'textos'   => ''
                  ]);
              }
            }
        }

        DB::table('valores')->where(['sucursal' => $sucursal, 'id' => 2 ])->update([
          'valor' =>  $request['no_contestar']
        ]);

        DB::table('valores')->where(['sucursal' => $sucursal, 'id' => 3 ])->update([
          'valor' =>  $request['correo']
        ]);

        DB::table('valores')->where(['sucursal' => $sucursal, 'id' => 4 ])->update([
          'valor' =>  $request['comentario']
        ]);

        DB::table('valores')->where(['sucursal' => $sucursal, 'id' => 6 ])->update([
          'valor' =>  $request['evidencia']
        ]);

        return response()->json(['status' => 200, 'msg' => 'Valores actualizados con éxito']);
    }
}

