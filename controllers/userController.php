<?php 
	require_once("vendor/twig/twig/lib/Twig/Autoloader.php");
	require_once ("twigDatos.php");
	require_once ("models/configDatos.php");
	require_once ("models/conexionModelo.php");
	require_once ("models/usuarioModelo2.php");
	require_once ("models/Usuario.php");
	

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

				$pm = new UsuarioModelo2();
				$datos['usuarios'] = $pm->listar();	
				$plantilla = 'usuario_index.twig.html';
			}
			if ($_GET['action'] == 'new'){

				$view = new UsuarioModelo2();
				//$view->show(); Directamente me manejo con la $plantilla de abajo de todo el controller
				
				$plantilla = 'usuario_new.twig.html';
			}
			
			if ($_GET['action'] == 'create'){
				     
				    
				    $datos = array('id'=>$_POST['id'],'email'=>$_POST['email'],'username'=>$_POST['username'],'password'=>$_POST['password'],'activo'=>$_POST['activo'],'first_name'=>$_POST['first_name'],'last_name'=>$_POST['last_name'],'borrado'=>$_POST['borrado']);
				    //var_dump($datos);
         			 $user = new Usuario($datos); //El arreglo con los datos está llegando al Usuario
         			 $user->guardar();
         			 $pm = new UsuarioModelo2();
					$datos['usuarios'] = $pm->listar();	
         			 $plantilla = 'usuario_index.twig.html';
           			 


			
       
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