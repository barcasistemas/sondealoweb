<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuItem;
use App\Models\MenuitemImage;
use Session;
use Validator;

class MenuplatillosController extends Controller
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
        $validator = Validator::make($request->all(), [
            'nombre_platillo'       => 'required|max:30'
          ]);

        if($validator->fails()){
          return back()->withErrors(['msg_error' => 'No valido']);
        }  

        $id_categoria = $request['id_categoria'];
        $nombre_platillo = $request['nombre_platillo'];
        $sucursal = Session::get('sucursal_fijada');
        $ingredientes_platillo = $request['ingredientes_platillo'];
        $precio_platillo = $request['precio_platillo'];
        $recomen_platillo = $request['recomen_platillo'];
        $url_video = $request['url_video'];
        $id_item = $request['id_platillo'];
        $url_img = "https://www.sondealo.com/sitio/images/menu/".$sucursal."_".$id_item.".jpg";

        MenuItem::insert(['id_categoria' => $id_categoria, 'nombre' => $nombre_platillo, 'precio' => $precio_platillo , 'ingredientes' => $ingredientes_platillo, 'recomen' => $recomen_platillo, 'url_video' => $url_video]);

        MenuitemImage::insert(['id_item' => $id_item, 'ruta_servidor' => $url_img]);

        return back();
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
            'nombre_platillo'       => 'required|max:30'
          ]);

        if($validator->fails()){
          return back()->withErrors(['msg_error' => 'No valido']);
        }  

        $id_platillo = $request['id_platillo'];
        $id_categoria = $request['id_categoria'];
        $nombre_platillo = $request['nombre_platillo'];
        $sucursal = Session::get('sucursal_fijada');
        $ingredientes_platillo = $request['ingredientes_platillo'];
        $precio_platillo = $request['precio_platillo'];
        $recomen_platillo = $request['recomen_platillo'];
        $url_video = $request['url_video'];

        MenuItem::where('id', $id_platillo)->update(['nombre'=> $nombre_platillo, 'ingredientes' => $ingredientes_platillo, 'precio' => $precio_platillo , 'recomen' => $recomen_platillo, 'url_video' => $url_video]);

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
