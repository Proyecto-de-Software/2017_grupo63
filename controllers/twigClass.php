<?php 
		
		$raiz = $_SERVER['DOCUMENT_ROOT'];
		require_once("$raiz/vendor/twig/twig/lib/Twig/Autoloader.php"); //en el servidor local agregar la carpeta del proyecto luego de $raiz 
		abstract class TwigClass {

	    private static $twig;

	    public static function getTwig($direccion) {

	        if (!isset(self::$twig)) {

	            $path = $_SERVER['CONTEXT_DOCUMENT_ROOT'] . "/templates";
	            Twig_Autoloader::register();
	            $loader = new Twig_Loader_Filesystem("$path");
	            
	            self::$twig = new Twig_Environment($loader); //, array('cache' => '../cache')
	        }
	        return self::$twig;
	    }

	    public static function mostrarLogueado($plantilla, $datosExtra)
	    {
	 		$datos = datosTwig::getInstance();
			$datos = $datos->datosLogueado();
			$datos = 	array_merge($datos, $datosExtra);
			$twig = self::getTwig();
			$template = $twig->loadTemplate($plantilla);
			$template->display($datos);   	
	    }

	}
 ?>