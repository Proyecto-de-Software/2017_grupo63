<?php 
	
	/**
	* 
	*/
	class HistoriaController extends Controller
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
         	elseif (!$this->tienePermiso("historia_" . $accion)) 
         	{
         		$datos = $this->datosTwig(true);
         		$plantilla = 'noAutorizado.twig.html';
         	}
         	else
         	{
         		$datos = $this->datosTwig(true);
         		$pagina =  isset($_GET['page']) ? $_GET['page'] : 1 ;
                $desde = isset($_GET['desde']) ? $_GET['desde'] : "";
                $hasta = isset($_GET['hasta']) ? $_GET['hasta'] : "";
                $pacienteid = (isset($_GET['pacienteid'])) ? $_GET['pacienteid'] : "" ;
                switch ($accion) {
         			case 'index':
                        $hm = new HistorialModel();
                        $historiasPag = $hm->listar($pagina, $pacienteid, $desde, $hasta);  
                        $datos['historias'] = $historiasPag->getDatos();
                        $datos['paginationPath'] = "index.php?seccion=historiaController&action=index&desde=$desde&hasta=$hasta&pacienteid=$pacienteid&page="; 
                        $datos['lastPage'] = $historiasPag->getTotal();
                        $datos['currentPage'] = $historiasPag->getActual();
                        $datos['desde'] = $desde;
                        $datos['hasta'] = $hasta;
                        $datos['pacienteid'] = $pacienteid;
                        $plantilla = 'historia_index.twig.html';
                        break;
                    case 'show':
             			$hm = new HistorialModel();
                        $datos['historia']= $hm->showHistory($_GET['id']);
                        $datos['volver'] = "index.php?seccion=historiaController&action=index&desde=$desde&hasta=$hasta&pacienteid=$pacienteid&page=$pagina";
                        $plantilla = 'historia_show.twig.html';
                        break;
                    case 'destroy':
                        //var_dump($_GET['username']);
                        $hm = new HistorialModel();              
                        $hm->eliminar($_GET['id']); 
                        $historiasPag = $hm->listar($pagina, $pacienteid, $desde, $hasta);  
                        $datos['historias'] = $historiasPag->getDatos();
                        $datos['paginationPath'] = "index.php?seccion=historiaController&action=index&desde=$desde&hasta=$hasta&pacienteid=$pacienteid&page="; 
                        $datos['lastPage'] = $historiasPag->getTotal();
                        $datos['currentPage'] = $historiasPag->getActual();
                        $datos['desde'] = $desde;
                        $datos['hasta'] = $hasta;
                        $datos['pacienteid'] = $pacienteid;
                        $plantilla = 'historia_index.twig.html';
                        break;
                        
                    case 'new':
                        $plantilla = 'historia_new.twig.html';
                        $datos['volver'] = $_SERVER['HTTP_REFERER'];
                        $datos['pacienteid'] = $pacienteid;
                        break;
                    case 'newDB':    
                        //var_dump($_POST['paciente']) ;die();             
                        $hm = new HistorialModel();
                        $_POST['usuarioCarga'] = (int)$_SESSION['userID'];
                        $hm->insertarHistoria($_POST);
                        $pacienteid = $_POST['pacienteid'];
                        $pacientesPag = $hm->listar($pagina, $pacienteid, $desde, $hasta);  
                        $datos['historias'] = $pacientesPag->getDatos();
                        $datos['paginationPath'] = "index.php?seccion=historiaController&action=index&desde=$desde&hasta=$hasta&pacienteid=$pacienteid&page="; 
                        $datos['lastPage'] = $pacientesPag->getTotal();
                        $datos['currentPage'] = $pacientesPag->getActual();
                        $datos['desde'] = $desde;
                        $datos['hasta'] = $hasta;
                        $datos['pacienteid'] = $pacienteid;
                        $plantilla = 'historia_index.twig.html';
                        break; 

                    case 'update':
                        $hm = new HistorialModel();
                        $datos['historia']= $hm->get_historia($_GET['id']); 
                        $datos['volver'] = $_SERVER['HTTP_REFERER'];
                        $plantilla = 'historia_update.twig.html';
                        break;
                      case 'updateDB':
                        $hm = new HistorialModel(); 
                        $hm->editar($_POST);
                        $historiasPag = $hm->listar($pagina, $pacienteid, $desde, $hasta);  
                        $datos['historias'] = $historiasPag->getDatos();
                        $datos['paginationPath'] = "index.php?seccion=historiaController&action=index&desde=$desde&hasta=$hasta&pacienteid=$pacienteid&page="; 
                        $datos['lastPage'] = $historiasPag->getTotal();
                        $datos['currentPage'] = $historiasPag->getActual();
                        $datos['desde'] = $desde;
                        $datos['hasta'] = $hasta;
                        $datos['pacienteid'] = $pacienteid;
                        $datos['volver'] = "index.php?seccion=historiaController&action=index&desde=$desde&hasta=$hasta&pacienteid=$pacienteid&page=$pagina";
                        $plantilla = 'historia_index.twig.html';
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