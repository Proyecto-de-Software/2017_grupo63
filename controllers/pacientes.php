<?php 
	require_once("../vendor/twig/twig/lib/Twig/Autoloader.php");
	require_once "twigDatos.php";
	require_once "../models/configDatos.php";
	require_once "../models/conexionModelo.php";
	require_once "../models/pacienteModelo.php";
	
	$datos = datosTwig::getInstance();
	$datos = $datos->datosConfig();
	$plantilla = 'frontDeshabilitado.twig.html';
	if ($datos['habilitado'] == 1 ) {
		
		session_start();
		if (isset($_SESSION['usuario'])) {
			# code...
			/*if (no tiene permisos) { aca chequear por los permisos 
				# code...
				$plantilla = 'noAutorizado.twig.html';
			} lo siguiente tendria que ir en un else*/
			if ($_GET['action'] == 'index'){
				$pm = new PacienteModelo();
				$datos['pacientes'] = $pm->listar();	
				$plantilla = 'paciente_index.twig.html';
			}
		}
		else {
			$plantilla = 'login.twig.html';
		}
	}

	require_once("twigClass.php");
	$twig = TwigClass::getTwig('templates');
	$template = $twig->loadTemplate($plantilla);
	$template->display($datos);


 ?>