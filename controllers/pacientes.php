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
                $filtroDoc = isset($_GET['docFiltro']) ? $_GET['docFiltro'] : 0 ;
                $filtroDocNum = isset($_GET['filtroDocNum']) ? $_GET['filtroDocNum'] : "" ;
                switch ($accion) {
         			case 'index':
                        
                        $pm = new PacienteModelo();
                        $pacientesPag = $pm->listar($pagina, $filtro, $filtroDoc, $filtroDocNum);  
                        $datos['pacientes'] = $pacientesPag->getDatos();
                        $datos['paginationPath'] = "index.php?seccion=pacientes&action=index&filtro=$filtro&page="; 
                        $datos['lastPage'] = $pacientesPag->getTotal();
                        $datos['currentPage'] = $pacientesPag->getActual();
                        $datos['filtro'] = $filtro;
                        $datos['filtroDoc'] = $filtroDoc;
                        $datos['filtroDocNum'] = $filtroDocNum;
                        $plantilla = 'paciente_index.twig.html';
                        $curlc = new CURLController();
                        $datos['obras'] = $curlc->obtenerDatos("obra-social");
                        $datos['documentos'] = $curlc->obtenerDatos("tipo-documento"); 
                        break;
         			case 'show':
             				
                         $pm = new PacienteModelo();
                       
                         $paciente = $pm->get_user($_GET['id']);
                         $curlc = new CURLController(); 
                         $paciente["obraSocial"] = $curlc->obtenerDato("obra-social/" . $paciente['obraSocial']);
                         $paciente["tipoDoc"] = $curlc->obtenerDato("tipo-documento/" . $paciente['tipoDoc']);
                         $datos["paciente"] = $paciente;
                         if(strpos($_SERVER['HTTP_REFERER'], 'DB') !== false )
                            $datos['volver'] = "index.php?seccion=userController&action=index&filtro=&page=1";
                         else     
                            $datos['volver'] = $_SERVER['HTTP_REFERER'];
                         
                         $plantilla = 'paciente_show.twig.html';
                         break;
                    case 'new':
                        $plantilla = 'paciente_new.twig.html';
                        $curlc = new CURLController();
                        $datos["docs"] = $curlc->obtenerDatos("tipo-documento");
                        $datos['obras'] = $curlc->obtenerDatos("obra-social");
                        $datos['volver'] = $_SERVER['HTTP_REFERER'];
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
                            $pacientesPag = $pm->listar(1, '' );  
                            $datos['pacientes'] = $pacientesPag->getDatos();
                            $datos['lastPage'] = $pacientesPag->getTotal();
                            $datos['currentPage'] = $pacientesPag->getActual();                        
                            $datos['paginationPath'] = "index.php?seccion=pacientes&action=index&filtro=&page=";   
                            $plantilla = 'paciente_index.twig.html';

                        }
                        break;    
                    case 'update':
                         $pm = new PacienteModelo();
                         $paciente  = $pm->get_user($_GET['id']);
                         $curlc = new CURLController();
                         $datos["docs"] = $curlc->obtenerDatos("tipo-documento");
                         $datos['obras'] = $curlc->obtenerDatos("obra-social");
                         $datos["paciente"] = $paciente;              
                         if(strpos($_SERVER['HTTP_REFERER'], 'DB') !== false )
                            $datos['volver'] = "index.php?seccion=userController&action=index&filtro=&page=1";
                         else     
                            $datos['volver'] = $_SERVER['HTTP_REFERER'];
                         $plantilla = 'paciente_update.twig.html';
                         break;
                    case 'updateDB':
                        $pm = new PacienteModelo(); 
                        $pm->editar($_POST);
                        $pacientesPag = $pm->listar($pagina, $filtro );
                        $datos['pacientes'] = $pacientesPag->getDatos();
                        $datos['lastPage'] = $pacientesPag->getTotal();
                        $datos['currentPage'] = $pagina;                        
                        $datos['paginationPath'] = "index.php?seccion=pacientes&action=index&filtro=$filtro&page="; 
                        $plantilla = 'paciente_index.twig.html';
                        break;   
                    case 'destroy':
                        //var_dump($_GET['username']);
                        $pm = new PacienteModelo();
                        $id =  $_GET['id'];              
                        $pm->eliminar($id);
                        
                        $pacientesPag = $pm->listar($pagina, ""); 
                        $datos['pacientes'] = $pacientesPag->getDatos();
                        $datos['lastPage'] = $pacientesPag->getTotal();
                        $datos['currentPage'] = $pagina;                        
                        $datos['paginationPath'] = "index.php?seccion=pacientes&action=index&filtro=&page=";
                        
                        $plantilla = 'paciente_index.twig.html';
                        break;
                    case 'grafico':
                            $plantilla = "estadistica.twig.html";
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