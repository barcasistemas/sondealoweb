<?php

namespace App\Utilidades;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Session;

class MenuHTML
{
  public static function getMenu()
  {
    if(Session::get('poder') == 1)
    {
      return self::adminMenu();
    }

    $html_menu = '';
    $html_inner_configuracion = '';
    if(Session::get('f6') == 1){
      $html_inner_configuracion .= self::getHtmlFacultadSeis();
    }
    if(Session::get('f3') == 1){
      $html_inner_configuracion .= self::getHtmlFacultadTres();
    }
    if(Session::get('f4') == 1){
      $html_inner_configuracion .= self::getHtmlFacultadCuatro();
    }
    if(Session::get('f5') == 1){
      $html_inner_configuracion .= self::getHtmlFacultadCinco();
    }

    $html_menu = '<li class="sidebar-search">'
    .'<span class="text-primary"><strong></strong></span>'
    .'</li>'
    .'<li>'
    .'<a><i class="fa fa-cogs fa-fw"></i> <span class="d-none" data-show="hover">  Configuración<span class="fa arrow"></span> </span> </a>'
    .'<ul class="nav nav-second-level d-none" data-show="hover">'
    .$html_inner_configuracion
    .'</ul>'
    .'</li>';

    if(Session::get('f1') == 1){
      $html_menu .= self::getHtmlFacultadUno();
    }
    if(Session::get('f8') == 1){
      $html_menu .= self::getHtmlFacultadOcho();
    }
    if(Session::get('f9') == 1){
      $html_menu .= self::getHtmlFacultadNueve();
    }
    if(Session::get('f7') == 1){
      $html_menu .= self::getHtmlFacultadSiete();
    }
    return $html_menu;
  }

  public static function getSelectSucursales($sucursales = array())
  {
    $sucursal_fijada = (Session::get('sucursal_fijada')) ?? 'no seleccionado';

    $select_apertura = '<div>'
    .'<nav aria-label="breadcrumb">'
    .'<ol class="breadcrumb">'
    .'<li class="breadcrumb-item text-uppercase"><a href="#">SUCURSAL</a></li>'
    .'<li class="breadcrumb-item text-uppercase active"  aria-current="page">'.$sucursal_fijada.'</li>'
    .'</ol>'
    .'</nav>'
    .'<div class="row">'
    .'<div class="col-md-3">'
    .'<select class="form-control form-control-sm" id="sucursal">'
    .'<option value="-1">- cambiar sucursal -</option>';

   $select_cierre = '</select>'
   .'</div>'
   .'</div>'
   .'</div>';

   $opciones = '';
   foreach ($sucursales as $sucursal)
   {
     if($sucursal->sucursal != $sucursal_fijada){
       $opciones .= '<option value="'.$sucursal->sucursal.'">'.$sucursal->sucursal.'</option>';
     }
   }
   return $select_apertura.$opciones.$select_cierre;
  }



  public static function adminMenu()
  {

  //  \App::setLocale(Session::get('lang'));

    $menu_ruta       = Route('mostrar_menu_qr');
    $menu_str        = trans('menu.menu-qr');


    $asignacion_vendedores_ruta = Route('vendedores');
    $asignacion_vendedores_str = trans('menu.asignacion-vendedores');


    $usuarios_adicionales_ruta = Route('mostrar_usuarios');
    $usuarios_adicionales_str = trans('menu.usuarios-adicionales');

    $reportes_ruta = Route('mostrar_reportes');
    $reportes_str = trans('menu.reportes');


    if(Session::get('plan') == 2){

      $menu_ruta = '#';
      $menu_str  .= '<small style="color:#ff0000;"> [ '.trans('menu.actualiza-plan').' ]</small>';

      $asignacion_vendedores_ruta ="#";
      $asignacion_vendedores_str .= '<small style="color:#ff0000;">  [ '.trans('menu.actualiza-plan').' ]</small>';

      $usuarios_adicionales_ruta = "#";
      $usuarios_adicionales_str .= '<small style="color:#ff0000;">  [ '.trans('menu.actualiza-plan').' ]</small>';

      $reportes_ruta = "#";
      $reportes_str .= '<small style="color:#ff0000;">  [ '.trans('menu.actualiza-plan').' ]</small>';
    }

    return '<li class="sidebar-search">'
    .'<span class="text-primary d-none" data-show="hover"><strong></strong></span>'
    .'</li>'
    .'<li>'
    .'<a><i class="fa fa-cogs fa-fw"></i>  <span class="d-none" data-show="hover">  '.trans('menu.configuracion').' <span class="fa arrow"></span></span>   </a>'
    .'<ul class="nav nav-second-level d-none" data-show="hover">'
    .'<li>'
    .'<a href="'.Route('mostrar_sucursales').'"><i class="fa fa-home fa-fw"></i> '.trans('menu.sucursales').'</a>'
    .'</li>'
    .'<li>'
    .'<a href="'.Route('mostrar_encuesta').'"><i class="fa fa-pencil-square-o fa-fw"></i> '.trans('menu.encuesta').' </a>'
    .'</li>'
    .'<li>'
    .'<a href="'.$asignacion_vendedores_ruta.'"><i class="fa fa-users fa-fw"></i> '.$asignacion_vendedores_str.'</a>'
    .'</li>'
    .'<li>'
    .'<a href="'.Route('mostrar_promociones').'"><i class="fa fa-bullhorn fa-fw"></i> '.trans('menu.publicidad').'</a>'
    .'</li>'
    .'<li>'
    .'<a href="'.Route('mostrar_cupones').'"><i class="fa fa-gift fa-fw"></i> '.trans('menu.cupones').'</a>'
    .'</li>'
    .'<li>'
    .'<a href="'.$usuarios_adicionales_ruta.'"><i class="fa fa-user-plus fa-fw"></i> '.$usuarios_adicionales_str.'</a>'
    .'</li>'
    .'<li>'
    .'<a href="'.Route('generar_qr_encuesta_mesa').'"><i class="fa fa-qrcode fa-fw"></i> '.trans('menu.qr-encuesta').'</a>'
    .'</li>'
    .'<li>'
    ."<a href=\"$menu_ruta\"><i class=\"fa fa-qrcode fa-fw\"></i>$menu_str</a>"
    .'</li>'
    .'</ul>'
    .'</li>'
    .'<li>'
    .'<a href="'.$reportes_ruta.'"><i class="fa fa-bar-chart-o fa-fw"></i>  <span class="d-none" data-show="hover"> '.$reportes_str.'  </span> </a>'
    .'</li>'
    .'<li>'
    .'<a href="'.Route('mostrar_alertas').'"><i class="fa fa-exclamation-triangle fa-fw"></i> <span class="d-none" data-show="hover"> '.trans('menu.alertas').' </span>  </a>'
    .'</li>'
    .'<li>'
    .'<a href="'.Route('mostrar_historial_cupones').'"><i class="fa fa-history fa-fw"></i> <span class="d-none" data-show="hover"> '.trans('menu.historial-cupones').' </span>  </a>'
    .'</li>'
    .'<li>'
    .'<a href="'.Route('mostrar_validacion_cupones').'"><i class="fa fa-scissors fa-fw"></i> <span class="d-none" data-show="hover"> '.trans('menu.validar-cupones').' </span>  </a>'
    .'</li>'
    .'<li>'
    .'<a href="'.Route('mostrar_correos_clientes').'"><i class="fa fa-envelope fa-fw"></i> <span class="d-none" data-show="hover"> '.trans('menu.correos-clientes').' </span>  </a>'
    //.'</li>'
    //.'<li>'
    //.'<a href="'.Route('mostrar_metodo_pago').'"><i class="fa fa-credit-card fa-fw"></i> Metodo de pago</a>'

    
    .'<li>'
    .'<a><i class="fa fa-picture-o fa-fw"></i>  <span class="d-none" data-show="hover">  Galería Menú <span class="fa arrow"></span></span>   </a>'
    .'<ul class="nav nav-second-level d-none" data-show="hover">'

    .'<li>'
    .'<a href="'.Route('menu_perfil').'"><i class="fa fa-sliders fa-fw"></i> Perfil</a>'
    .'</li>'

    .'<li>'
    .'<a href="'.Route('menu_categorias').'"><i class="fa fa-th-large fa-fw"></i> Categorias</a>'
    .'</li>'

    .'<li>'
    .'<a href="'.Route('menu_diseno').'"><i class="fa fa-paint-brush fa-fw"></i> Diseño</a>'
    .'</li>'

    .'<li>'
    .'<a href="'.Route('menu_codigoqr').'"><i class="fa fa-qrcode fa-fw"></i> QR</a>'
    .'</li>'

    .'</li>'
    .'</ul>';
  }






  public static function getHtmlFacultadUno()
  {
      return '<li><a href="'.Route('mostrar_reportes').'"><i class="fa fa-bar-chart-o fa-fw"></i> <span class="d-none" data-show="hover">  Reportes </span> </a></li>'
           .'<li><a href="'.Route('mostrar_historial_cupones').'"><i class="fa fa-history fa-fw"></i> <span class="d-none" data-show="hover"> Historial de cupones </span> </a></li>';
  }
  public static function getHtmlFacultadTres()
  {
      return '<li><a href="'.Route('vendedores').'"><i class="fa fa-users fa-fw"></i> Asignación de vendedores</a></li>';
  }
  public static function getHtmlFacultadCuatro()
  {
      return '<li><a href="'.Route('mostrar_promociones').'"><i class="fa fa-bullhorn fa-fw"></i> Publicidad</a></li>';
  }
  public static function getHtmlFacultadCinco()
  {
      return '<li><a href="'.Route('mostrar_cupones').'"><i class="fa fa-gift fa-fw"></i> Cupones</a></li>';
  }
  public static function getHtmlFacultadSeis()
  {
      return '<li><a href="'.Route('mostrar_encuesta').'"><i class="fa fa-pencil-square-o fa-fw"></i> Encuesta</a></li>';
  }
  public static function getHtmlFacultadSiete()
  {
      return '<li><a href="'.Route('mostrar_correos_clientes').'"><i class="fa fa-envelope fa-fw"></i> <span class="d-none" data-show="hover"> Correos clientes </span>  </a></li>';
  }
  public static function getHtmlFacultadOcho()
  {
      return '<li><a href="'.Route('mostrar_alertas').'"><i class="fa fa-exclamation-triangle fa-fw"></i> <span class="d-none" data-show="hover"> Alertas </span> </a></li>';
  }
  public static function getHtmlFacultadNueve()
  {
      return '<li><a href="'.Route('mostrar_validacion_cupones').'"><i class="fa fa-scissors fa-fw"></i> <span class="d-none" data-show="hover"> Valida cupones </span></a></li>';
  }
  public static function getMenuTour()
  {
    $sucursales = DB::table('sucursales')->select('sucursal')->where('identificador', Session::get('identificador'))->distinct()->get();

    $sucursal_fijada = (Session::get("sucursal_fijada") != '') ? Session::get("sucursal_fijada") : md5('123msjw');

    $pregunta_uno = DB::table('cuestionario')->select('pregunta')->where(['sucursal' => $sucursal_fijada , 'id'=> 1])->first();

    $html_bool_encuesta = '';
    if($pregunta_uno)
    {
       $html_bool_encuesta = (substr(mb_strtolower($pregunta_uno->pregunta, 'UTF-8'), 0, 8 ) != 'pregunta') ? "<label style=\"float:right;font-size:2.5rem;\" class=\"fa fa-check-circle-o text-success\"></label>" : '';
    }
    else{
      $html_bool_encuesta  = '';
    }

    $html_bool_sucursal = (count($sucursales) > 0) ? "<label style=\"float:right;font-size:2.5rem;\" class=\"fa fa-check-circle-o text-success\"></label>" : '';

    $html ='';
    switch (Session::get('plan')) {
      case 2:
        $html .= '<li class="sidebar-search">'
            .'<span class="text-primary"><strong>Plan gratuito</strong></span>'
            ."<li style=\"padding:10px;position:relative;font-size:1.5rem;\">1.- Configura tu sucursal $html_bool_sucursal</li>"
            .'</li>'
            ."<li style=\"padding:10px;position:relative;font-size:1.5rem;\">2.- Configura tu encuesta $html_bool_encuesta</li>"
            .'</li>'
            ."<li style=\"padding:10px;position:relative;font-size:1.5rem;\">3.- Qr de encuesta </li>"
            .'</li>';
        break;
      default:
        $html .= '<li class="sidebar-search">'
            .'<span class="text-primary"><strong>Plan Premium</strong></span>'
            ."<li style=\"padding:10px;position:relative;font-size:1.5rem;\">1.- Configura tu sucursal $html_bool_sucursal</li>"
            .'</li>'
            ."<li style=\"padding:10px;position:relative;font-size:1.5rem;\">2.- Configura tu encuesta $html_bool_encuesta</li>"
            .'</li>'
            ."<li style=\"padding:10px;position:relative;font-size:1.5rem;\">3.- Qr de encuesta </li>"
            .'</li>';
        break;
    }


    return $html;


    // return '<li><img src="/images/infografia_sondealo.jpg" style="margin-top:20px;max-width:90%;margin-left:5%;"/></li>';
  }



  public static function getMenuAdministracionSondealo()
  {
    return '<li class="sidebar-search">'
    .'<span class="text-primary d-none" data-show="hover"><strong>Administración sondealo</strong></span>'
    .'</li>'
    .'<li>'
    .'<a href="'.Route('adm_usuarios_totales').'"><i class="fa fa-users fa-fw"></i> <span class="d-none" data-show="hover"> Usuarios plataforma</span></a>'
    .'</li>'
    .'<li>'
    .'<a href="'.Route('adm_encuestas_recientes').'"><i class="fa fa-sort-amount-desc fa-fw"></i> <span class="d-none" data-show="hover"> Encuestas reporte</span></a>'
    .'</li>';
  }
}
