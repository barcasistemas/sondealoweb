<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Session;
use Validator;

class MenuperfilController extends Controller
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
            'nombre_sucursal'       => 'required|max:50',
            'dir_sucursal'   => 'required|max:30',
            'tel_sucursal'       => 'required|integer'
          ]);

        if($validator->fails()){
          return back()->withErrors(['msg_error' => 'No valido']);
        }  

        $nom_sucursal = $request['nombre_sucursal'];
        $dir_sucursal = $request['dir_sucursal'];
        $tel_sucursal = $request['tel_sucursal'];
        $sucursal = Session::get('sucursal_fijada');
        $id_menu = 1;

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
            'nombre_sucursal'       => 'required|max:50',
          ]);

        if($validator->fails()){
          return back()->withErrors(['msg_error' => 'No valido']);
        }  

        $nom_sucursal = $request['nombre_sucursal'];
       // $dir_sucursal = $request['dir_sucursal'];
        $tel_sucursal = $request['tel_sucursal'];
        $eslang = $request['es_lang'];
        $enlang = $request['en_lang'];
        $encuestaswitch = $request['encuesta_switch'];
        $urlswitch = $request['url_switch'];
        $youtubeswitch = $request['youtube_switch'];
        $instaswitch = $request['insta_switch'];
        $tiktokswitch = $request['tiktok_switch'];
        $faceswitch = $request['facebook_switch'];
        $whatsappswitch = $request['whatsapp_switch'];
        $pageurl = $request['url_web'];
        $youtubeurl  = $request['url_youtube'];
        $instaurl = $request['url_instagram'];
        $tiktokurl = $request['url_tiktok'];
        $facebookurl = $request['url_facebook'];
        $whatsappurl = $request['num_whatsapp'];


        $sucursal = Session::get('sucursal_fijada');
        $id_menu = 1;

        /*
        echo $nom_sucursal;
        echo $tel_sucursal;
        echo $eslang;
        echo $enlang;
        echo $encuestaswitch;
        echo $urlswitch;
        echo $youtubeswitch;
        echo $instaswitch;
        echo $tiktokswitch;
        echo $faceswitch;
        echo $whatsappswitch;
        echo $pageurl;
        echo $youtubeurl;
        echo $instaurl;
        echo $tiktokurl;
        echo $facebookurl;
        echo $whatsappurl;
        */

        
        Menu::where('sucursal','=',$sucursal)->update(['name_comercial'=> $nom_sucursal, 
        'whatsapp_url' => $whatsappurl, 
        'es_lang' => $eslang,
        'en_lang' => $enlang,
        'encuesta_switch' => $encuestaswitch,
        'url_switch' => $urlswitch,
        'youtube_switch' => $youtubeswitch,
        'insta_switch' => $instaswitch,
        'tiktok_switch' => $tiktokswitch,
        'facebook_switch' => $faceswitch,
        'whatsapp_switch' => $whatsappswitch,
        'page_url' => $pageurl,
        'youtube_url' => $youtubeurl,
        'insta_url' => $instaurl,
        'tiktok_url' => $tiktokurl,
        'facebook_url' => $facebookurl]); 
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
