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
			
		
		switch (isset($_GET['seccion']) ? $_GET['seccion'] : '') {
			case 'pacientes':
				# code...
				require_once "controllers/pacientes.php";
				break;
			case 'login':
				require_once "controllers/login.php";
				break;
			default:
				# code...
				$plantilla = "frontHabilitado.twig.html";
				break;
		}
	}
		 
	
	
	require_once("controllers/twigClass.php");
	$twig = TwigClass::getTwig('templates');
	$template = $twig->loadTemplate($plantilla);
	//var_dump($_SERVER);
	$template->display($datos);
	
 ?>