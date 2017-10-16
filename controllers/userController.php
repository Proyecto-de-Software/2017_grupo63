<?php 
	
	/**
	* 
	*/
	class UserController extends Controller
	{
		
		private static $instance;

        public static function getInstance() {

            if (!isset(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance;
        }
        
        private function __construct() {
            
        }
	
        public function trabajar($accion)
         {
         	if (!$this->estaLogueado()) 
         	{
         		$datos = $this->datosTwig(false);
         		$datos['error'] = 'Esta seccion es solo para usuarios registrados';	
         		$plantilla = 'login.twig.html';
         	}
         	elseif (!$this->tienePermiso("usuario_" . $accion)) 
         	{
         		$datos = $this->datosTwig(true);
         		$plantilla = 'noAutorizado.twig.html';
         	}
         	else
         	{
         		$datos = $this->datosTwig(true);
         		switch ($accion) {
         			case 'index':
                        $um = new UsuarioModelo();
                        $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : '' ;
                        $usuariosPag = $um->listar(isset($_GET['page']) ? $_GET['page'] : 1, $filtro );  
                        $datos['usuarios'] = $usuariosPag->getDatos();
                        $datos['lastPage'] = $usuariosPag->getTotal();
                        $datos['currentPage'] = $usuariosPag->getActual();
                        
                        $datos['filtro'] = $filtro; 
                        $datos['paginationPath'] = "index.php?seccion=userController&action=index&filtro=$filtro&page=";
                        $plantilla = 'usuario_index.twig.html';
                        break;
         			case 'new':
         				$plantilla = 'usuario_new.twig.html';
                     $um = new UsuarioModelo();
                     $roles = $um->getRoles();
                     $datos["roles"] = $roles;
                     $datos['volver'] = $_SERVER['HTTP_REFERER'];
         				break;
                  case 'show':
                     $um = new UsuarioModelo();
                   
                     $usuario  = $um->get_user($_GET['id']);
                     $roles = $um->getRoles($_GET['id']);
                     $datos["user"] = $usuario;
                     $datos["roles"] = $roles;
                     if(strpos($_SERVER['HTTP_REFERER'], 'DB') !== false )
                        $datos['volver'] = "index.php?seccion=userController&action=index&filtro=&page=1";
                     else     
                        $datos['volver'] = $_SERVER['HTTP_REFERER'];
                     
                     $plantilla = 'usuario_show.twig.html';
                     break;
                  case 'destroy':
                        //var_dump($_GET['username']);
                        $um = new UsuarioModelo();              
                        $um->eliminar($_GET['id']);
                        $filtro = "";
                        $usuariosPag = $um->listar(1, $filtro );  
                        $datos['usuarios'] = $usuariosPag->getDatos();
                        $datos['lastPage'] = $usuariosPag->getTotal();
                        $datos['currentPage'] = 1;
                        
                        $datos['filtro'] = $filtro; 
                        $datos['paginationPath'] = "index.php?seccion=userController&action=index&filtro=$filtro&page=";
                        $plantilla = 'usuario_index.twig.html';  
                        break;
                   case 'update':
                     $um = new UsuarioModelo();
                     $usuario  = $um->get_user($_GET['id']);
                     $rolesUser = $um->getRoles($_GET['id']);
                     $roles = $um->getRoles();
                     $datos["user"] = $usuario;
                     $datos['rolesUser'] = $rolesUser;
                     $datos["roles"] = $roles;               
                     if(strpos($_SERVER['HTTP_REFERER'], 'DB') !== false )
                        $datos['volver'] = "index.php?seccion=userController&action=index&filtro=&page=1";
                     else     
                        $datos['volver'] = $_SERVER['HTTP_REFERER'];
                     $plantilla = 'usuario_update.twig.html';
         			   break;
                  case 'updateDB':
                        $um = new UsuarioModelo();   
                        $um->editar($_POST);
                        $usuariosPag = $um->listar(1, '' );  
                        $datos['usuarios'] = $usuariosPag->getDatos();
                        $datos['lastPage'] = $usuariosPag->getTotal();
                        $datos['currentPage'] = $usuariosPag->getActual();                        
                        $datos['paginationPath'] = "index.php?seccion=userController&action=index&filtro=&page="; 
                        $plantilla = 'usuario_index.twig.html';
                        break;   
                  case 'newDB':                  
         				$um = new UsuarioModelo();
         				if ($um->yaExisteUsuario($_POST['username'])) {
         					$datos['usuarioNuevo'] = $_POST;
         					$datos['error'] = 'Ese nombre de usuario ya esta registrado';
         					$plantilla = 'usuario_new.twig.html';
         				}
         				elseif ($um->yaExisteCorreo($_POST['email'])) {
         					
         					$datos['usuarioNuevo'] = $_POST;
         					$datos['error'] = 'Ese correo electronico ya esta registrado';
         					$plantilla = 'usuario_new.twig.html';
         				}
         				else{
         					$um->insertar($_POST);
         					$usuariosPag = $um->listar(1, '' );  
                        $datos['usuarios'] = $usuariosPag->getDatos();
                        $datos['lastPage'] = $usuariosPag->getTotal();
                        $datos['currentPage'] = $usuariosPag->getActual();                        
                        $datos['paginationPath'] = "index.php?seccion=userController&action=index&filtro=&page=";	
							   $plantilla = 'usuario_index.twig.html';
         				}
         				break;
         			default:
         				$plantilla = "backend.twig.html";
                              
         				break;
         		}
         	}
         	$this->mostrarVista($plantilla, $datos);
         } 

	}




 ?>