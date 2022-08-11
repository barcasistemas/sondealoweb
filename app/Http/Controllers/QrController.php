<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Validator;
use Session;

class QrController extends Controller
{
    /*ajax*/
    public function generarQR(Request $request)
    {
      $validator = Validator::make($request->only('mesa'), ['mesa' => 'required|string']);
      if($validator->fails()){
        return response()->json(['status' => 422, 'msg' => 'No valido']);
      }

      $sucursal = Session::get('sucursal_fijada');
      $mesa = $request->mesa;

      $url = "https://sondealo.com/sitio/qr-encuesta/$sucursal/$mesa";

      $qr_code_xl = (string)QrCode::errorCorrection('H')->size(280)->generate($url);

      $html_qr_xl = '<div class="qrv">'
        .'<p>Encuesta<br/>'.$sucursal.'</p>'
        .'<div class="cont-m-qr qr-lg">'
        .$qr_code_xl
        .'</div>'
        .'<p>'.$mesa.'</p>'
        .'<p class="small" style="visibility:hidden;">CUÉNTANOS TU EXPERIENCIA</p>'
        .'</div>';

      $qr_code_sm = (string)QrCode::errorCorrection('H')->size(150)->generate($url);

      $html_qr_sm = '<div class="qrv">'
        .'<p>Encuesta<br/>'.$sucursal.'</p>'
        .'<div class="cont-m-qr qr-sm">'
        .$qr_code_sm
        .'</div>'
        .'<p>'.$mesa.'</p>'
        .'<p class="small" style="visibility:hidden;">CUÉNTANOS TU EXPERIENCIA</p>'
        .'</div>';

      return response()->json(['status' => 200, 'msg' => 'success', 'qr_xl' => $html_qr_xl, 'qr_sm' => $html_qr_sm]);

    }
}
