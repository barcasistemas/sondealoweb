<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuItem;
use Validator;
use Session;
use File;
use DB;

class MenuItemController extends Controller
{
    public function storeCategoria(Request $request)
    {
        $validator = Validator::make($request->only('name_categoria'),[
            'name_categoria' => 'required|string|max:30',
        ]);

        if($validator->fails()){
            return back()->withErrors(['msg_error' => 'Valores no validos']);
        }

        $insert = DB::table('categorias_menu')->insert([
            'id_menu' => $request->menu_seleccionado,
            'sucursal' => Session::get('sucursal_fijada'),
            'nombre' => $request->name_categoria
        ]);

        if(!$insert){
            return back()->withErrors(['msg_error' => 'No se pudo completar la operación intenta más tarde']);
        }

        Session::flash('msg_success', 'Categoría dada de alta con éxito');
        return back();

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:30',      
            'categoria' => 'required|integer|min:1',           
            'image'     => 'required|image|mimes:jpg,png,jpeg|max:256',      
            'ingredientes' => 'required|string|max:255',           
            'precio'       => 'required|numeric|min:1'   
        ]);
        if($validator->fails()){
            return back()->withErrors(['msg_error' => 'Valores no validos']);
        }

        $insertItem = MenuItem::create([
            "id_categoria" => $request->categoria, 
            "nombre"       => $request->name,
            "ingredientes"  => $request->ingredientes,
            "precio"        => $request->precio
        ]);

        if(!$insertItem){
            return back()->withInput()->withErrors(['msg_error' => 'Intenta más tarde']);
        }

        $extensionImg = $request->image->extension();
        $nombreImg = Session::get('sucursal_fijada').'_'.$insertItem->id.'.'.$extensionImg;


        $insertImg = DB::table('menu_imagenes_items')->insert([
            'id_item' => $insertItem->id,
            'ruta_servidor' => "https://www.sondealo.com/sitio/images/menu/$nombreImg"
        ]);

        if(!$insertImg){
            MenuItem::where('id', $insertItem->id)->delete();
            return back()->withInput()->withErrors(['msg_error' => 'Intenta más tarde']);
        }

        $request->image->move(public_path().'/images/menu/', $nombreImg);

        Session::flash('msg_success', 'Operación completada con éxito');
        return back();        
    }



    public function delete(Request $request)
    {
        $validator = Validator::make($request->only('item'), [
            'item' => 'required|integer|min:1'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 422, 'msg' => 'Información no valida'
            ]);
        }

        $imagenes = DB::table('menu_imagenes_items')->where('id_item', $request->item)->get();

        if($imagenes->count())
        {
            foreach($imagenes as $imagen)
            {
                $arr_ruta_img = explode('/', $imagen->ruta_servidor);
                $str_nombre_img  = $arr_ruta_img[count($arr_ruta_img)-1];

                if(File::exists(public_path().'/images/menu/'.$str_nombre_img))
                {
                    File::delete(public_path().'/images/menu/'.$str_nombre_img);
                }
            }
        }

        $delete = MenuItem::where(['id' => $request->item])->delete();
      
        if(!$delete){
            return response()->json([
                'status' => 400, 'msg' => 'No se pudo completar la operación'
            ]);
        }
        return response()->json([
            'status' => 200, 'msg' => 'Operación completada con éxito'
        ]);        

    }


    public function get($id)
    {
        if(!is_numeric($id)){
            return response()->json([
                'status' => 422, 'msg' => 'No valido'
            ]);
        }

        $item = MenuItem::where('id', $id)->first();
        if(!$item){
            return response()->json([
                'status' => 404, 'msg' => 'No existe'
            ]);
        }

        $item->imagenes = DB::table('menu_imagenes_items')->select('id', 'ruta_servidor')
        ->where('id_item', $id)->get();

        return response()->json([
            'status' => 200, 'msg' => 'success', 'item' => $item
        ]); 

    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'key'          => 'required|integer|min:1',         
            'nombre'       => 'required|string|max:255',            
            'categoria'    => 'required|integer|min:1',               
            'precio'       => 'required|numeric',            
        ]); 

        if($validator->fails()){
            return response()->json([
                'status' => 422, 'msg' => 'No valido', 'err' => $validator->errors()
            ]);
        }


        MenuItem::where('id', $request->key)->update([
            'id_categoria' => $request->categoria,
            'nombre'       => $request->nombre,
            'ingredientes' => $request->ingredientes,
            'precio'       => $request->precio
        ]);

        if($request->hasFile('img'))
        {
            $imagenDB = DB::table('menu_imagenes_items')->select('ruta_servidor')
            ->where('id', $request->img_key)->first();

            if($imagenDB)
            {
                $arrRutaImg = explode('/', $imagenDB->ruta_servidor);
                $nombreImg = $arrRutaImg[count($arrRutaImg)-1];
    
                if(File::exists(public_path().'/images/menu/'.$nombreImg))
                {
                    File::delete(public_path().'/images/menu/'.$nombreImg);
                }
    
                $request->img->move(public_path().'/images/menu/', $nombreImg);                
            }
        }

        return response()->json([
            'status' => 200, 'msg' => 'Operación completada con éxito, espera....', 
        ]); 


    }

}
