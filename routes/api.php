<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/




/*nuevo reporte movil*/
Route::get('/reporte-movil/{sucursal}/{desde}/{hasta}', 'ViewController@reporteMovil');

Route::post('/reporte-movil/general', 'ReporteMovilController@general')->name('reporte_movil_general');

Route::post('/reporte-movil/comentarios', 'ReporteMovilController@comentarios')->name('reporte_movil_comentarios');

Route::post('/reporte-movil/encuestas', 'ReporteMovilController@encuestas')->name('reporte_movil_encuestas');

Route::post('/reporte-movil/vendedores', 'ReporteMovilController@vendedores')->name('reporte_movil_vendedores');

Route::post('/reporte-movil/encuesta-detalle', 'ReporteMovilController@encuestaDetalle')->name('reporte_movil_encuesta_detalle');

Route::post('/reporte-movil/preguntas', 'ReporteMovilController@preguntas')->name('reporte_movil_preguntas');


Route::post('/reporte-movil/alertas', 'ReporteMovilController@getHtmlAlertas');




/*----------------solo bernini--------------------*/
Route::post('/reporte-movil/formulario-vendedor', 'ReporteMovilController@getInfoChartsFormularioVendedor')->name('reporte_movil_formulario_vendedor');
/*------------------------------------------------*/



/*menu en tab*/
Route::get('/menu-tab/{sucursal}', 'ViewController@menuTab');



/*promedios sucursales*/
Route::post('/mv-promedios-sucursales', 'SucursalController@promediosSucursalesMovil');
/*auth movil*/
Route::get('/auth-movil/{user}/{password}', 'UserController@authMovil')->name('autenticar_movil');

/* Login en la tablet*/ 
Route::post('/sucursal-entrar', 'SucursalController@autenticarSucursal');


/*obtenemos los promedios diarios app administrador*/
Route::post('/sucursales-promedios', 'SucursalController@promedioDiario');

/*reporteador guardar token firebase*/
Route::post('/firebasetoken-save', 'UserController@guardarFirebaseToken');



/* 
    ------ app registro ------- 
*/

//registrar usuario nuevo
Route::post('/mv-usuario-nuevo', 'UserController@storeMovil');
// registrar sucursal
Route::post('/mv-sucursal-nuevo', 'SucursalController@storeMovil');
// obtener las preguntas
Route::post('/mv-preguntas-get', 'PreguntaController@getPreguntasMovil');

/* 
    ------- fin app registro --------
*/







/* ---------- app menu ---------------- */

Route::get('/menu-app-imagenes/{sucursal}',  'SucursalController@getAppMenuImages');
Route::get('/menu-app-categorias/{sucursal}', 'SucursalController@getAppMenuCatego');
Route::get('/menu-app-logo/{sucursal}', 'SucursalController@getLogoMenu');











//
