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

         				break;
                  case 'editar':
                     $plantilla = 'usuario_update.twig.html';
                    

         				$datos['volver'] = $_SERVER['HTTP_REFERER'];

                     break;
                  case 'show':
                     $um = new UsuarioModelo();
                   
                     $usuario  = $um->get_user($_GET['id']);
                     $roles = $um->getRoles($_GET['id']);
                     $datos["user"] = $usuario;
                     $datos["roles"] = $roles;
                     $datos['volver'] = $_SERVER['HTTP_REFERER'];
                     $plantilla = 'usuario_show.twig.html';
                     break;
                  case 'eliminar':
                        //var_dump($_GET['username']);
                        $um = new UsuarioModelo();
                        $username =  $_GET['username'];              
                        $um->eliminar($username);
                        $datos['usuarios'] = $um->listar();  
                        $plantilla = 'usuario_index.twig.html';
                        break;
                   case 'update':
                     $um = new UsuarioModelo();
                     //var_dump($_POST['username']);die();
                     $um->editar($_POST);
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
         					$datos['usuarios'] = $um->listar();	
							$plantilla = 'usuario_index.twig.html';
         				}
         				break;
         			default:
         				# code...
         				
         				break;
         		}
         	}
         	$this->mostrarVista($plantilla, $datos);
         } 

	}




 ?>