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
                $tipo = (isset($_GET['tipo'])) ? $_GET['tipo'] : "" ;
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
                        $datos['usuarioId'] = $_SESSION['userID'];
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
                        $datos['usuarioId'] = $_SESSION['userID'];
                        $plantilla = 'historia_index.twig.html';
                        break;
                        
                    case 'new':
                        $plantilla = 'historia_new.twig.html';
                        $datos['volver'] = $_SERVER['HTTP_REFERER'];
                        $datos['pacienteid'] = $pacienteid;
                        $_SESSION['pacienteid'] = $pacienteid;
                        break;
                    case 'newDB':    
                        //var_dump($_POST['paciente']) ;die();             
                        $hm = new HistorialModel();
                        $_POST['usuarioCarga'] = (int)$_SESSION['userID'];
                        $hm->insertarHistoria($_POST);
                        $historiasPag = $hm->listar($pagina, $_SESSION['pacienteid'], $desde, $hasta);  
                        $datos['historias'] = $historiasPag->getDatos();
                        $datos['paginationPath'] = "index.php?seccion=historiaController&action=index&desde=$desde&hasta=$hasta&pacienteid=$pacienteid&page="; 
                        $datos['lastPage'] = $historiasPag->getTotal();
                        $datos['currentPage'] = $historiasPag->getActual();
                        $datos['desde'] = $desde;
                        $datos['hasta'] = $hasta;
                        $datos['pacienteid'] = $pacienteid;
                        $datos['usuarioId'] = $_SESSION['userID'];
                        $plantilla = 'historia_index.twig.html';
                        break; 

                    case 'update':
                        $hm = new HistorialModel();
                        $pediatraH = isset($_GET['pediatraH']) ? $_GET['pediatraH'] : 0 ;
                        if ($pediatraH != $_SESSION['userID']) {
                            $plantilla = 'noAutorizado.twig.html';
                        } else {
                            $historia = $hm->get_historia($_GET['id']);
                            $datos['historia']= $hm->get_historia($_GET['id']); 
                            $datos['volver'] = $_SERVER['HTTP_REFERER'];
                            $_SESSION['pacienteid'] = $pacienteid;
                            $plantilla = 'historia_update.twig.html';
                        }
                        
                        break;
                      case 'updateDB':
                        $hm = new HistorialModel(); 
                        $hm->editar($_POST);
                        $historiasPag = $hm->listar($pagina, $_SESSION['pacienteid'], $desde, $hasta);  
                        $datos['historias'] = $historiasPag->getDatos();
                        $datos['paginationPath'] = "index.php?seccion=historiaController&action=index&desde=$desde&hasta=$hasta&pacienteid=$pacienteid&page="; 
                        $datos['lastPage'] = $historiasPag->getTotal();
                        $datos['currentPage'] = $historiasPag->getActual();
                        $datos['desde'] = $desde;
                        $datos['hasta'] = $hasta;
                        $datos['pacienteid'] = $pacienteid;
                        $datos['usuarioId'] = $_SESSION['userID'];
                        $plantilla = 'historia_index.twig.html';
                        break;
                      case 'grafico':
                              $plantilla = 'curvaPeso.twig.html';
                              $datos['paciente'] = $pacienteid; 
                              switch ($tipo) {
                                  case 'peso':
                                      $plantilla = 'curvaPeso2.twig.html';
                                      break;
                                  case 'ppc':
                                      $plantilla = 'curvaPPC.twig.html';
                                      break;
                                  case 'talla':
                                      $plantilla = 'curvaTalla.twig.html';
                                      break;  
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