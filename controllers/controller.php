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
	}
 ?>