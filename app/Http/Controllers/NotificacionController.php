<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    const API_ACCESS_KEY = 'AAAAryQC3Bo:APA91bGXnR7fvPEW5kovmSJj_ZspYRCEnfWT3DGpn3fW1Ro6OzJWo6ybKF8n_HOdB-b6fKUOF6ajg5R7nmOf4gJL3oM617vqfCqw-qxUCGanibHSUQy8N5vMx7jJMLEThxjKKh1ObAtM';

    public static function send($comentario, $sucursal, $tokens)
    {
        if(count($tokens) < 1){
          return;
        }

        $msg = array(
            'body'  => $comentario,
            'title' => $sucursal,
            'icon'  => 'myicon',
            'sound' => 'mySound',
            'badge' => '1',
        );
        $fields = array(
            'registration_ids' => $tokens,
            'notification'     => $msg
        );
        $headers = array(
            'Authorization: key=' . self::API_ACCESS_KEY,
            'Content-Type: application/json',
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_exec($ch);
        curl_close($ch);
    }

}
