<?php 
	/**
	* 
	*/
	class Configuracion extends Controller
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
         	elseif (!$this->tienePermiso("config_" . $accion)) 
         	{
         		$datos = $this->datosTwig(true);
         		$plantilla = 'noAutorizado.twig.html';
         	}
         	else
         	{
         		$datos = $this->datosTwig(true);
         		switch ($accion) {
         			case 'show':
         				$plantilla = "configuracion.twig.html";
         				$model = new DatosConfig();
         				$datos['config'] = $model->obtenerInfo();
         				$datos['config']['paginado'] = (int) $datos['config']['paginado'];
         				$datos['volver'] = "index.php";
         				break;
         			
         			case 'update':
         				$plantilla = "backend.twig.html";
         				$model = new DatosConfig();
         				$model->editar($_POST);
         				break;
         			default:
         				
         				break;
         		}

         	}	
        	$this->mostrarVista($plantilla, $datos);
        }
	}

 ?>