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
         		$pagina =  isset($_GET['page']) ? $_GET['page'] : 1 ;
                $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : "";
                switch ($accion) {
         			case 'index':
                        
                        $pm = new PacienteModelo();
                        $pacientesPag = $pm->listar($pagina, $filtro);  
                        $datos['pacientes'] = $pacientesPag->getDatos();
                        $datos['paginationPath'] = "index.php?seccion=pacientes&action=index&filtro=$filtro&page="; 
                        $datos['lastPage'] = $pacientesPag->getTotal();
                        $datos['currentPage'] = $pacientesPag->getActual();
                        $datos['filtro'] = $filtro;
                        $plantilla = 'paciente_index.twig.html';
                        break;
         			case 'show':
         				
                     $pm = new PacienteModelo();
                   
                     $paciente = $pm->get_user($_GET['id']);
                     //$roles = $um->getRoles($_GET['id']);
                     $datos["paciente"] = $paciente;
                     //$datos["roles"] = $roles;
                     //f(strpos($_SERVER['HTTP_REFERER'], 'updateDB') !== false )
                        //$datos['volver'] = "index.php?seccion=userController&action=index&filtro=&page=1";
                     //else     
                       // $datos['volver'] = $_SERVER['HTTP_REFERER'];
                     
                     $plantilla = 'paciente_show.twig.html';
                     break;
                    case 'new':
                        $plantilla = 'paciente_new.twig.html';
                        break;
                    case 'newDB':                  
                        $pm = new PacienteModelo();
                        if ($pm->yaExistePaciente($_POST['numDoc'])) {
                            $datos['pacienteNuevo'] = $_POST;
                            $datos['error'] = 'Ese numero de documento ya esta registrado';
                            $plantilla = 'paciente_new.twig.html';
                        }
                       
                        else{
                            $pm->insertar($_POST);
                            $usuariosPag = $um->listar(1, '' );  
                        //$datos['usuarios'] = $usuariosPag->getDatos();
                        //$datos['lastPage'] = $usuariosPag->getTotal();
                        //$datos['currentPage'] = $usuariosPag->getActual();                        
                        //$datos['paginationPath'] = "index.php?seccion=userController&action=index&filtro=&page=";   
                            $plantilla = 'paciente_index.twig.html';
                        }
                        break;    
                    case 'update':
                     $pm = new PacienteModelo();
                     $paciente  = $pm->get_user($_GET['id']);
                     //$rolesUser = $um->getRoles($_GET['id']);
                     //$roles = $um->getRoles();
                     $datos["paciente"] = $paciente;
                     //$datos['rolesUser'] = $rolesUser;
                     //$datos["roles"] = $roles;               
                     $datos['volver'] = $_SERVER['HTTP_REFERER'];
                     $plantilla = 'paciente_update.twig.html';
                       break;
                      case 'updateDB':
                        $pm = new PacienteModelo(); 
                        $pm->editar($_POST);
                        //$pacientesPag = $pm->listar(1, '' );
                        //var_dump($_POST);
                        //$datos['pacientes'] = $pacientesPag->get_user();
                        //$datos['lastPage'] = $usuariosPag->getTotal();
                        //$datos['currentPage'] = $usuariosPag->getActual();                        
                        //$datos['paginationPath'] = "index.php?seccion=userController&action=index&filtro=&page="; 
                        $plantilla = 'paciente_index.twig.html';
                        break;   
                    case 'destroy':
                        //var_dump($_GET['username']);
                        $pm = new PacienteModelo();
                        $id =  $_GET['id'];
                                     
                        $pm->eliminar($id);
                        
                        $pacientesPag = $pm->listar(isset($_GET['page']) ? $_GET['page'] : 1 ); 
                        $datos['pacientes'] = $pacientesPag;
                        
                        $plantilla = 'paciente_index.twig.html';
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