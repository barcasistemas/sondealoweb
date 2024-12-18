<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuCategoria;
use Session;
use Validator;

class MenucategoriasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_categoria'       => 'required|max:30'
          ]);

        if($validator->fails()){
          return back()->withErrors(['msg_error' => 'No valido']);
        }  

        $id_categoria = $request['id_categoria'];
        $nombre_categoria = $request['nombre_categoria'];
        $sucursal = Session::get('sucursal_fijada');
        $id_video = $request['id_video'];
        $switch_id = ["switch1","switch2","switch3","switch4","switch5","switch6","switch7","switch8","switch9","switch10","switch11","switch12"];
        $switch_val = $request[$switch_id[$id_categoria-1]];

        MenuCategoria::where(['sucursal' => $sucursal ,'id' => $id_categoria])->update(['nombre'=> $nombre_categoria, 'id_video' => $id_video ,'state' => $switch_val]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
