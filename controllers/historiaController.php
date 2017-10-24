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
         			case 'show':
             			$dm = new historiaModel();
                        $datos['historia']= $dm->showHistory($_GET['id']);
                        $datos['id']= $_GET['id'];
                        $plantilla = 'historia_show.twig.html';
                        break;
                    case 'new':
                        $plantilla = 'historia_new.twig.html';
                        $datos['volver'] = $_SERVER['HTTP_REFERER'];
                        //var_dump($_POST);DIE();
                        $datos['paciente'] = $_GET['id'];
                        break;
                    case 'newDB':    
                        //var_dump($_POST['paciente']) ;die();             
                        $dm = new HistorialModel();
                        $dm->insertarHistoria($_POST);
                         $idDemografico = $dm->ultimoDemografic();
                        (new PacienteModelo())->agregarHistorial($_POST['paciente'],  $idDemografico);
                        
                        $plantilla = "paciente_index.twig.html";
                        break; 

                    case 'update':
                     $dm = new DemograficModel();
                     $datos['demografico']= $dm->showDemografic($_GET['id']);
                     $datos['volver'] = $_SERVER['HTTP_REFERER'];
                     $plantilla = 'demografic_update.twig.html';
                       break;
                      case 'updateDB':
                        $dm = new DemograficModel(); 
                        $dm->update($_POST);
                        $plantilla = "backend.twig.html";
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