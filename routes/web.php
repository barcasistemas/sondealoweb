<?php

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*RUTAS LIBRES*/
Route::get('/', function(){ return redirect()->route('mostrar_login'); });

Route::get('/entrar', 'ViewController@login')->name('mostrar_login');

Route::post('/web/autenticar', 'Auth\LoginController@loginW')->name('autenticar_web');

Route::get('/salir', 'SessionController@cerrarSesion')->name('cerrar_sesion');

Route::get('/restablecer', 'ViewController@mostrarRestablecerCuenta')->name('mostrar_restablecer_cuenta');

Route::post('/peticion/restablecer', 'PasswordResetController@enviarPeticion')->name('generar_peticion_restablecer');

Route::get('/restablecer/aplicar/{token}', 'ViewController@aplicarResetPassword')->name('aplicar_restablecer_password');

Route::post('/restablecer/pass', 'PasswordResetController@restablecimientoPassword')->name('restablecer_password_enlace');

Route::post('/registrar', 'UserController@storeTitular')->name('registrar_usuario');

Route::get('/registro/{tipo?}', function($tipo = null){
  $plan_id = ( ! $tipo) ? 56 : $tipo;
  return view('registro', compact('plan_id'));
})->name('mostrar_registro');


Route::get('/set-lang/{lang}', function($lang){
  if( ! in_array($lang, ['es', 'en'])){
    return abort(400);
  }
  Session::put('lang' , $lang);
  return back();

})->name('set_lenguaje');


Route::get('/menu-multiple-button/{sucursal?}', 'ViewController@menuMultipleButtons');


/* rutas menu dinamico */
Route::get('/menu-lang/{sucursal}', 'ViewController@selectLang');
Route::get('/menu-categorias/{sucursal}','ViewController@menuSelectCategorias');
Route::get('/menu-seccion/{sucursal}/{id}','ViewController@menuSecciones');


/*rutas protegidas con sesion */
Route::group(['middleware' => ['lang.middleware',  'usersession' ] ], function(){

  Route::get('/home/{sucursal?}', 'ViewController@home')->name('mostrar_home');

  Route::get('/vendedores/{sucursal?}', 'ViewController@vendedores')->name('vendedores');
  /*ajax*/
  Route::post('/sucursal/fijar', 'SessionController@setSesionSucursalAjax')->name('fijar_sesion_sucursal');

  Route::post('/vendedor/nuevo', 'VendedorController@store')->name('nuevo_vendedor');

  Route::get('/sucursales/{sucursal?}', 'ViewController@sucursales')->name('mostrar_sucursales');

  Route::get('/alertas/{sucursal?}', 'ViewController@alertas')->name('mostrar_alertas');

  Route::post('/sucursal/nuevo', 'SucursalController@store')->name('nueva_sucursal');

  Route::get('/promociones/{sucursal?}', 'ViewController@promociones')->name('mostrar_promociones');
  /*ajax*/
  Route::post('/promocion/cambiar', 'ImagenController@cambiarPromocion')->name('cambiar_promocion');
  /*ajax*/
  Route::post('/vendedor/info', 'VendedorController@getInfoVendedor')->name('info_vendedor');
  /*ajax*/
  Route::post('/vendedor/actualizar', 'VendedorController@updateVendedor')->name('actualizar_vendedor');
  /*ajax*/
  Route::post('/vendedor/eliminar', 'VendedorController@removeVendedor')->name('eliminar_vendedor');

  Route::get('/encuesta/{sucursal?}', 'ViewController@encuesta')->name('mostrar_encuesta');
  /*ajax*/
  Route::post('/encuesta/pregunta/reorden', 'PreguntaController@preguntasReorden')->name('reordenar_preguntas_encuesta');
  /*ajax*/
  Route::post('/valores/actualizar', 'ValoresController@updateValores')->name('actualizar_valores');
  /*ajax*/
  Route::post('/encuesta/copiar', 'PreguntaController@copiarPreguntas')->name('copiar_encuesta');
  /*ajax*/
  Route::post('/encuesta/pregunta/actualizar', 'PreguntaController@guardarEdicion')->name('actualizar_pregunta');

  Route::get('/usuarios/{sucursal?}', 'ViewController@usuarios')->name('mostrar_usuarios');

  Route::post('/usuario/nuevo', 'UserController@store')->name('nuevo_usuario');
  /*ajax*/
  Route::post('/usuario/actualizar', 'UserController@update')->name('actualizar_usuario');
  /*ajax*/
  Route::post('/usuario/actualizar/facultades', 'UserController@updateFacultades')->name('actualizar_facultades_usuario');
  /*ajax*/
  Route::post('/usuario/eliminar', 'UserController@delete')->name('eliminar_usuario');

  Route::get('/ajustes', 'ViewController@ajustes')->name('mostrar_ajustes');

  Route::get('/correos/{sucursal?}', 'ViewController@correos')->name('mostrar_correos_clientes');

  Route::get('/reportes/{sucursal?}/{desde?}/{hasta?}', 'ViewController@reportes')->name('mostrar_reportes');
  /*ajax*/
  Route::post('/reporte/graph/NPS', 'PreguntaController@getInfoNPS')->name('info_nps');
  /*ajax*/
  Route::post('/reporte/comentarios', 'PreguntaController@getInfoComentarios')->name('info_comentarios');
  /*ajax*/
  Route::post('/reporte/encuesta/detalle', 'PreguntaController@getInfoDetalleEncuesta')->name('info_encuesta_detalle');
  /*ajax*/
  Route::post('/reporte/encuestas', 'PreguntaController@getEncuestas')->name('info_encuestas');

  Route::get('/cupones_historial/{sucursal?}/{consulta_tipo?}', 'ViewController@cuponesHistorial')->name('mostrar_historial_cupones');

  Route::get('/cupones_validar/{sucursal?}', 'ViewController@cuponesValidar')->name('mostrar_validacion_cupones');

  Route::post('/cupon/validar', 'CuponController@validarCupon')->name('validar_cupon');

  Route::get('/cupones/{sucursal?}', 'ViewController@cupones')->name('mostrar_cupones');
  /*ajax*/
  Route::post('/cupon/cambiar', 'ImagenController@cambiarCupon')->name('cambiar_cupon');
  /*ajax*/
  Route::post('/cupon/actualizar', 'CuponController@actualizarInformacion')->name('actualizar_info_cupon');

  Route::get('/qr_menu/{sucursal?}', 'ViewController@menuQr')->name('mostrar_menu_qr');

  Route::post('/qr_menu/subir', 'ArchivoController@subirMenu')->name('subir_menu_qr');
  /*ajax*/
  Route::post('/sucursal/info', 'SucursalController@getSucursalInfo')->name("info_sucursal");
  /*ajax*/
  Route::post('/sucursal/update', 'SucursalController@update')->name('actualizar_sucursal');
  /*ajax*/
  Route::post('/vendedor/promedios', 'VendedorController@getPromedios')->name('info_promedios_vendedores');
  /*ajax*/
  Route::post('/logo/update', 'ImagenController@cambiarLogo')->name('actualizar_imagen_logo');
  /*ajax*/
  Route::post('/encuesta/plantilla_aplicar', 'PreguntaController@aplicarPlantilla')->name('aplicar_plantilla_encuesta');

  Route::post('/usuario/ajustes/cambio_pass', 'UserController@updatePassword')->name('actualizar_contrasenia_usuario');
  /*ajax*/
  Route::post('/usuario/actualizar/info', 'UserController@updateUserInfo')->name('actualizar_informacion_usuario');
  /*ajax*/
  Route::post('/sucursal/delete', 'SucursalController@delete')->name('eliminar_sucursal');
  /*ajax*/
  Route::post('/usuario/reset/byadmin', 'UserController@resetPasswordByAdmin')->name('restablecer_pass_by_admin');

  Route::get('/pago/{sucursal_url?}', 'ViewController@metodoPago')->name('mostrar_metodo_pago');

  Route::get('/fin-configuracion--', 'ViewController@finTour')->name('final_tour');
  /*ajax*/
  Route::post('/promociones/copiar', 'ImagenController@copiarPromociones')->name('copiar_promociones');
  /*ajax*/
  Route::post('/cupones/copiar', 'ImagenController@copiarCupones')->name('copiar_cupones');

  Route::get('/qrencuesta/{sucursal?}', 'ViewController@getQrEncuestaMesa')->name('generar_qr_encuesta_mesa');
  /*ajax*/
  Route::post('/qrencuesta/getQr', 'QrController@generarQR')->name('get_qr');


  /*ajax*/
  Route::post('/encuesta/personalizar', 'EncuestaController@personalizar')->name('encuesta_personalizar');


  /* ------------ REPORTE DE EXCEL ------------------*/
  Route::get('/excel/clientes/correos/{sucursal}', 'ExcelController@getCorreosClientes')->name('reporte_correos_clientes');

  Route::get('/excel/reporte/{sucursal}/{desde}/{hasta}', 'ExcelController@getReporteExcel')->name('reporte_excel_global');




  /*------------- MENU INTERACTIVO ------------------*/
  Route::get('/galeria_menu/{sucursal?}/{id_menu?}', 'ViewController@mostrarGaleriaImagenesMenu')->name('galeria_menu');
  /*ajax*/
  Route::post('/menu-item-delete', 'MenuItemController@delete')->name('menu_item_delete');
  Route::post('/menu-categoria-store', 'MenuItemController@storeCategoria')->name('menu_categoria_store');
  Route::post('/menu-item-store', 'MenuItemController@store')->name('menu_item_store');

  Route::get('/menu-item-get/{id?}', 'MenuItemController@get')->name('menu_item_get');
  /*ajax*/
  Route::post('/menu-item-update}', 'MenuItemController@update')->name('menu_item_update');

  Route::get('/menu-perfil/{sucursal?}/{id_menu?}','ViewController@creaPerfilMenu')->name('menu_perfil');
  Route::get('/menu-categorias/{sucursal?}/{id_menu?}','ViewController@creaCategoriasMenu')->name('menu_categorias');
  Route::get('/menu-diseno/{sucursal?}/{id_menu?}','ViewController@creaDisenoMenu')->name('menu_diseno');
  Route::get('/menu-codigoqr/{sucursal?}/{id_menu?}','ViewController@creaCodigoQRMenu')->name('menu_codigoqr');
});



/*pre visualizacion de encuesta*/
Route::get('/preview/{sucursal}', 'ViewController@preview');

Route::get('/qr-encuesta/{sucursal}/{mesa?}', 'ViewController@encuestaWebLibreQr')->name('encuesta_web_qr_libre');

Route::post('/qr-encuesta/guardar', 'EncuestaController@store')->name('guardar_encuesta_qr');

Route::get('/wa-encuesta/{key}', 'ViewController@encuestaWhatsapp');

Route::post('/wa-encuesta/cambia-estado', 'EncuestaController@cambiarEstado')->name('cambiar_estado_encuesta_wa');




/* ============ RUTAS ADMINISTRACION SONDEALO ===============*/
Route::group(['middleware' => 'useradministracion'], function(){

  Route::get('/admin/home', 'Administracion\ViewAdminController@homeAdministracion')->name('adm_mostrar_home');

  Route::get('/admin/usuarios-totales', 'Administracion\ViewAdminController@usuariosTotales')->name('adm_usuarios_totales');

  Route::post('/admin/usuario/plan/detalle', 'PlanController@detallePlanUsuario')->name('info_detalle_plan_usuario');

  Route::post('/admin/usuario/eliminar-cuenta', 'UserController@deleteCuenta')->name('eliminar_cuenta_usuario');
  /*excel*/
  Route::get('/admin/excel/reporte/usuario-plataforma', 'ExcelController@getUsuariosPlataforma')->name('reporte_excel_usuarios_plataforma');

  Route::get('/admin/encuestas-recientes/{desde?}/{hasta?}', 'Administracion\ViewAdminController@encuestasRecientes')->name('adm_encuestas_recientes');

});
