<?php 
	
	/**
	* 
	*/
	class Demografico extends Controller
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
         	//*elseif (!$this->tienePermiso("paciente_" . $accion)) 
         	{
         		$datos = $this->datosTwig(true);
         		$plantilla = 'noAutorizado.twig.html';
         	}*//
         	else
         	{
         		$datos = $this->datosTwig(true);
         		$pagina =  isset($_GET['page']) ? $_GET['page'] : 1 ;
                $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : "";
                switch ($accion) {
         			case 'show':
             			$dm = new DemograficModel();
                        $datos['demograficos']= $dm->showDemografic($_GET['id']);
                        $datos['idusuario']= $_GET['id'];
                        $plantilla = 'demografic_show.twig.html';
                        break;
                    case 'new':
                        $plantilla = 'demografic_new.twig.html';
                        $datos['volver'] = $_SERVER['HTTP_REFERER'];
                        $datos['paciente'] = $_GET['id'];
                        break;
                    case 'newDB':                  
                        $dm = new DemograficModel();
                        //var_dump($_POST);die();
                        $dm->insertDemografic($_POST);
                        $idDemografico = $dm->ultimoDemografic();
                        (new PacienteModelo())->agregarDemografic($_POST['paciente'],  $idDemografico);
                        $plantilla = "backend.twig.html";
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
                        case 'grafico':
                        $plantilla = "grafico.twig.html";
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