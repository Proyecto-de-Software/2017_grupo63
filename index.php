<?php 
	
	require_once("controllers/twigClass.php");
	require_once ("controllers/controller.php");
	require_once ("controllers/twigDatos.php");
	//require_once ("controllers/userController.php");
	
	

	

	require_once ("models/configDatos.php");
	require_once ("models/conexionModelo.php");
	require_once ("models/pacienteModelo.php");
	//require_once ("models/Usuario.php");
	require_once ("models/usuarioModelo.php");
	


	require_once("vendor/twig/twig/lib/Twig/Autoloader.php");
	
	$datos = datosTwig::getInstance();
	$datos = $datos->datosConfig();
	
	if ($datos['habilitado'] == 0) {
			$plantilla = "frontDeshabilitado.twig.html";
			$controller = new controller;
			$controller->mostrarVista($plantilla, $controller->datosTwig(false));
	}
	else{
			
		//var_dump($_GET['seccion']);
		switch (isset($_GET['seccion']) ? $_GET['seccion'] : '') {
			case 'userController':
				# code...
				require_once "controllers/userController.php";
				$userController = UserController::getInstance();
				$userController->trabajar(isset($_GET['action']) ? $_GET['action'] : '' );
				break;
			case 'pacientes':
				# code...
				require_once "controllers/pacientes.php";
				break;
			case 'login':
				require_once "controllers/login.php";
				$loginController = Login::getInstance();
				$loginController->loguearse(isset($_GET['action']) ? $_GET['action'] : '' );
				break;
			default:
				# code...
				$plantilla = "frontHabilitado.twig.html";
				$controller = new controller;
				$controller->mostrarVista($plantilla, $controller->datosTwig(false));			
				break;
		}
	}
		 
	
	
	
	
	
 ?>