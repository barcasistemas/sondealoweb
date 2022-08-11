<?php
namespace App\Http\Middleware;

use Closure;
use Session;

class UserSession
{
	public $vistas_a_revisar = array();
	public $vistas_solo_tour = array();

	public function __construct()
	{
		/*se especifican en un arreglo los nombres de las rutas a revisar, de acuerdo a cada facultad */
		 $this->vistas_a_revisar = array(
			'f1'  => array('mostrar_reportes', 'mostrar_historial_cupones'), /*historial cupones, reportes */
			'f2'  => array(), /*enviar encuesta por whatsapp*/
			'f3'  => array('vendedores'), /*Asignación de vendedores*/
			'f4'  => array('mostrar_promociones'), /*publicidad*/
			'f5'  => array('mostrar_cupones'), /*cupones*/
			'f6'  => array('mostrar_encuesta'), /*encuestas*/
			'f7'  => array('mostrar_correos_clientes'), /*correos clientes*/
			'f8'  => array('mostrar_alertas'), /*alertas*/
			'f9'  => array('mostrar_validacion_cupones'), /*validar cupones*/
			/*'f10' => array() asignara repartidores {no usado}*/
    );


		$this->vistas_solo_tour = (Session::get('plan') == 2) ? ['mostrar_sucursales', 'mostrar_encuesta', 'generar_qr_encuesta_mesa', 'final_tour']
														:	array('mostrar_sucursales',
																		'mostrar_encuesta',
																		'generar_qr_encuesta_mesa',
																		// 'vendedores',
																		// 'mostrar_promociones',
																		// 'mostrar_cupones',
																		// 'mostrar_usuarios',
																	  'final_tour');

	}

	public function handle($request, Closure $next)
	{
		session_start();
		if (isset($_SESSION['session']))
		{
			 if(!Session::has('user') ){
				 return redirect()->route('mostrar_login')->withErrors(['error_msg' => 'La sesión ha expirado']);
			 }

			 /*--------- solo dejamos pasar poder = 1 y 2 ------------*/
			 if(Session::get('poder') == 3){  return abort(403);   }
			 /*------------------------------------------------------*/

			 /*obtenemos el nombre de la ruta*/
			 $nombre_ruta =  $request->route()->getName();
			 /* --------------------------------------------------------------------------------------------------- */
			 /* -------------------------  VALIDACION RUTAS TOUR -------------------------------------------------- */
			 /* --------------------------------------------------------------------------------------------------- */
			 /*si la cuenta es de reciente creacion obligamos a que solo tenga acceso a las rutas para configuración*/
			 if(Session::get('int_tour') == 1)
			 {
				 $str_class_tour      = get_class($request->route()->getController());
				 $arr_path_class_tour = explode('\\',$str_class_tour);
				 $class_tour          = $arr_path_class_tour[count($arr_path_class_tour)-1];

				 if($class_tour !== "ViewController"){
					 return $next($request);
				 }
				 elseif(in_array($nombre_ruta, $this->vistas_solo_tour))
				 {
					 return $next($request);
				 }
				 return redirect()->route($this->vistas_solo_tour[0]);
			 }
			 /* --------------------------------------------------------------------------------------------------- */
			 /* --------------------------------------------------------------------------------------------------- */

			 /*si es administrador se muestra cualquier vista*/
			 if(Session::get('poder') == 1){
				 return $next($request);
			 }

			 /*el home y ajustes son rutas protegidas a las que cualquier usuario tiene acceso*/
			 if($nombre_ruta == 'mostrar_home' or $nombre_ruta == 'mostrar_ajustes'){
				 return $next($request);
			 }
			 /*las rutas que validamos son las del "ViewController" en caso de que la ruta actual
			  no pertenecezca a este la dejamos pasar*/
			 $str_class=get_class($request->route()->getController());
			 $arr_path_class = explode('\\',$str_class);
			 $class = $arr_path_class[count($arr_path_class)-1];
			 if($class !== "ViewController"){
				 return $next($request);
			 }
			 /*-----------------------------------------------------*/
			 /* comienza la validacion de usuario no administrador  */
			 /* obtenemos las llaves del arreglo de rutas a revisar */
			 $keys = array_keys($this->vistas_a_revisar);
			 /*recorremos el arreglo de rutas a revisar*/
			 for ($i=0; $i < count($this->vistas_a_revisar); $i++){
				 /*si en el arreglo actual, esta la vista actual*/
				 if(in_array($nombre_ruta, $this->vistas_a_revisar[$keys[$i]] )){
					 /*verificamos con la sesion si el usuario tiene la facultad en 1*/
					 	if((int)Session::get($keys[$i]) == 1){
							return $next($request);
						}
				 }
			 }
			 abort(403); /*en caso de no tener la facultad en 1 lanzamos abort con el estado 403*/
		}
		return redirect()->route('mostrar_login')->withErrors(['error_msg' => 'Inicia Sesión']);
	}
}
