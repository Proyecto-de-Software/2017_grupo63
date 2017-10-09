<?php 
	
	/**
	* 
	*/
	class PacienteController extends Controller
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
         	elseif (!$this->tienePermiso("paciente_" . $accion)) 
         	{
         		$datos = $this->datosTwig(true);
         		$plantilla = 'noAutorizado.twig.html';
         	}
         	else
         	{
         		$datos = $this->datosTwig(true);
         		switch ($accion) {
         			case 'index':
                        $pm = new PacienteModelo();
                        $pacientesPag = $pm->listar(isset($_GET['page']) ? $_GET['page'] : 1 );  
                        $datos['pacientes'] = $pacientesPag->getDatos();
                        $datos['paginationPath'] = "index.php?seccion=pacienteController&action=index&page="; 
                        $datos['lastPage'] = $usuariosPag->getTotal();
                        $datos['currentPage'] = $usuariosPag->getActual();
                        $plantilla = 'usuario_index.twig.html';
                        break;
         			case 'new':
         				$plantilla = 'usuario_new.twig.html';
         				break;
                    case 'eliminar':
                        //var_dump($_GET['username']);
                        $um = new UsuarioModelo();
                        $username =  $_GET['username'];              
                        $um->eliminar($username);
                        $datos['usuarios'] = $um->listar();  
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