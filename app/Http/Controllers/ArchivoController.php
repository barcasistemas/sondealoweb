<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use File;
use Session;

class ArchivoController extends Controller
{
  public function subirMenu(Request $request)
  {
    $validator = Validator::make($request->all(), ['si' => 'required|integer|min:1',
    'menu' => 'required|mimes:pdf|max:15360', 'sn' => 'required|string']);
    if($validator->fails()){
      return back()->withErrors(['error_msg' => 'No valido']);
    }

    $url_bd = "https://sondealo.com/sitio/menu_qr/m_".$request['sn'].'.pdf';

    if(File::exists(public_path().'/menu_qr/m_'.$request['sn'].'.pdf')){
      File::delete(public_path().'/menu_qr/m_'.$request['sn'].'.pdf');
    }
    $img = $request->file('menu');

    $img->move(public_path().'/menu_qr/' , 'm_'.$request['sn'].'.pdf');

    DB::table('sucursales')->where('id', $request['si'])->update(['url_menu' => $url_bd]);

    Session::flash('msg_success', 'Menú actualizado con éxito');
    return back();
  }
}
