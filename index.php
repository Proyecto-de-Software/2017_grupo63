<?php 
	
	
	require_once("vendor/twig/twig/lib/Twig/Autoloader.php");
	require_once "controllers/twigDatos.php";
	require_once "models/configDatos.php";
	require_once "models/conexionModelo.php";
	
	
	$datos = datosTwig::getInstance();
	$datos = $datos->datosConfig();
	
	if ($datos['habilitado'] == 0) {
			$plantilla = "frontDeshabilitado.twig.html";
			
	}
	else{
		$plantilla = "frontHabilitado.twig.html";	
	}
		 
	
	
	require_once("controllers/twigClass.php");
	$twig = TwigClass::getTwig('templates');
	$template = $twig->loadTemplate($plantilla);
	//var_dump($_SERVER);
	$template->display($datos);
	
 ?>