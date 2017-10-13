<?php 

	/**
	* 
	*/
	class Controller
	{
		
		public function datosTwig($logueado)
		{
			$datos = datosTwig::getInstance();
			if ($logueado) {
				$datos = $datos->datosLogueado();
			} else {
				$datos = $datos->datosConfig();
			}
			return $datos;
		}

		public function mostrarVista($plantilla, $datos)
		{
			$twig = TwigClass::getTwig('templates');
			$template = $twig->loadTemplate($plantilla);
			$template->display($datos);
		}
		
		public function estaLogueado()
		{
			if (!isset($_SESSION)){
				session_start();
			}
			$log= isset($_SESSION['usuario']);
			if (!$log) {
				session_destroy();
			}
			return $log;		
		}

		public function tienePermiso($modulo)
		{
			# Implementar despues
			$modulo = explode("DB", $modulo);

			if (!isset($_SESSION)) session_start();	
			return in_array($modulo[0], $_SESSION['permisos']);
		}
	}
 ?>