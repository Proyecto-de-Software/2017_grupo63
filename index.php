<?php 
	
	require_once("controllers/twigClass.php");
	require_once ("controllers/controller.php");
	require_once ("controllers/twigDatos.php");
	//require_once ("controllers/userController.php");
	
	

	

	require_once ("models/configDatos.php");
	require_once ("models/conexionModelo.php");
	require_once ("models/listables.php");
	require_once ("models/pacienteModelo.php");
	//require_once ("models/Usuario.php");
	require_once ("models/usuarioModelo.php");
	require_once ("models/consultaPag.php");
	require_once("models/DemograficModel.php");
	require_once("models/HistorialModel.php");

	require_once("vendor/twig/twig/lib/Twig/Autoloader.php");
	
	$datos = datosTwig::getInstance();
	$datos = $datos->datosConfig();
	
	if ($datos['habilitado'] == 0) {
			$plantilla = "frontDeshabilitado.twig.html";
			$controller = new controller;
			$controller->mostrarVista($plantilla, $controller->datosTwig(false));
	}
	else{
		$seccion = isset($_GET['seccion']) ? $_GET['seccion'] : '' ;	
		
		if (!isset($_SESSION)) session_start();
		$action = isset($_GET['action']) ? $_GET['action'] : '' ;
		if ( $seccion == '' && isset($_SESSION['usuario'])) $seccion = "registrado";
		
		switch ($seccion) {
			case 'historiaController':
				# code...
				require_once "controllers/historiaController.php";
				$historiaController = HistoriaController::getInstance();
				$historiaController->trabajar($action);
				break;
			case 'userController':
				# code...
				require_once "controllers/userController.php";
				$userController = UserController::getInstance();
				$userController->trabajar($action);
				break;
			case 'pacientes':
				require_once "controllers/pacientes.php";

				$pacientes = PacienteController::getInstance();
				$pacientes->trabajar(isset($_GET['action']) ? $_GET['action'] : '' );

				//$pacienteController = pacientes::getInstance();
				//$pacienteController->trabajar($action);

				break;
			case 'login':
				require_once "controllers/login.php";
				$loginController = Login::getInstance();
				$loginController->loguearse($action);
				break;
			case 'registrado':
				$plantilla = "backend.twig.html";
				$controller = new controller;
				$controller->mostrarVista($plantilla, $controller->datosTwig(true));
				break;
			case 'configController':
				require_once "controllers/configuracion.php";
				$configController = Configuracion::getInstance();
				$configController->trabajar($action);
				break;
			case 'demografico':
				require_once "controllers/demografico.php";
				$demografico = Demografico::getInstance();
				$demografico->trabajar($action);
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