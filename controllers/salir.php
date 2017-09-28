<?php 
	if (!isset($_SESSION)) {
		session_start();
	}
	session_destroy();
	unset($error);
	require_once "twigDatos.php";
	require_once("twigClass.php");
	$datos = datosTwig::getInstance();
	$datos = $datos->datosConfig();
	$twig = TwigClass::getTwig(' ');
	if ($datos['habilitado'] == 1) {
		$plantilla = 'frontHabilitado.twig.html';
	}
	else {
		$plantilla = 'frontDeshabilitado.twig.html';
	}
	$template = $twig->loadTemplate($plantilla);
	$template->display($datos);
 ?>