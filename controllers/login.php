<?php 
	//require_once("vendor/twig/twig/lib/Twig/Autoloader.php");
	require_once "twigDatos.php";
	require_once "models/configDatos.php";
	require_once "models/conexionModelo.php";
	
	
	$datos = datosTwig::getInstance();
	$datos = $datos->datosConfig();
	
	if ($datos['habilitado'] == 0) {
			$plantilla = "frontDeshabilitado.twig.html";
			
	}
	elseif ( $_GET['action'] == "showLoginForm" ) {
		$plantilla = "login.twig.html";
	}
	else{
		$plantilla = "login.twig.html";
		if (isset($_POST['usuario']) && isset($_POST['contrasena'])) {
			# code...
			require_once "../models/conexionModelo.php";
			require_once "../models/usuarioModelo.php";
			$model = new UsuarioModelo();
	 		$usuario = 	$model->loguearse($_POST);
	 		if (!empty($usuario)) {
	 			if ($usuario['activo'] == '1') {
	 				unset($datos['error']);		
					session_start();	
		 			$datos['usuario'] = $usuario['username']; 
		 			$_SESSION['usuario'] = $datos['usuario'];
		 			$_SESSION['activo'] = $usuario['activo'];
		 			$plantilla = 'backend.twig.html';
									
				}
				else{				
					$datos['error'] = 'USTED NO SE ENCUENTRA HABILITADO PARA INGRESAR AL SITIO';
				}

			}
			else{
				$datos['error'] = 'El nombre de usuario o la contraseña no son correctos';
			}
		}
		else{
			$datos['error'] = 'Complete todos los campos';
		}
	}	
	
	/*require_once("twigClass.php");
	$twig = TwigClass::getTwig('templates');
	$template = $twig->loadTemplate($plantilla);
	$template->display($datos);*/



 ?>