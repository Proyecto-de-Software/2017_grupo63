<?php 

    class Login extends Controller {
        
        private static $instance;

        public static function getInstance() {

            if (!isset(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance;
        }
        
        private function __construct() {
            
        }
        
        
        public function loguearse($accion){
            
            
            $plantilla = 'login.twig.html';
            switch ($accion) {
                case 'showLoginForm':
                    # code...
                    
                    $datos = $this->datosTwig(false);
                    break;
                case 'check':
                    //var_dump($_POST);
                     
                    $error = $this->chequear();
                        
                    if ($error == '') 
                    {
                        $plantilla = 'backend.twig.html';
                        $datos = $this->datosTwig(true);
                        if (isset($datos['error'])) {
                            unset($datos['error']);
                        }
                        
                    } 
                    else 
                    {
                        $datos = $this->datosTwig(false);
                        $datos['error'] = $error;
                    }
                    
                    break;
                case 'salir':
                    	# code...
                    	$datos = $this->datosTwig(false);
                    	$plantilla = 'frontHabilitado.twig.html';
                    	if (!isset($_SESSION)){
							session_start();
						}
						session_destroy();
                    	break;    
                default:
                    # code...
                    break;
            }
            $datos['volver'] = $_SERVER['HTTP_REFERER'];
            $this->mostrarVista($plantilla, $datos);
        }
        
        private function chequear()
        {
            if (isset($_POST['usuario']) && isset($_POST['contrasena'])) 
            {
                $model = new UsuarioModelo();
                $usuario =  $model->loguearse($_POST);
                if (!empty($usuario)) 
                {
                    if ($usuario['activo'] == '1') 
                    {        
                             
                        $_SESSION['usuario'] = $usuario['username'];
                        $_SESSION['activo'] = $usuario['activo'];
                        $_SESSION['userID'] = $usuario['id'];
                        $_SESSION['permisos'] = $model->getPermisos($usuario['id']);
                        $error = '';

                                        
                    }
                    else
                    {               
                        $error = 'USTED NO SE ENCUENTRA HABILITADO PARA INGRESAR AL SITIO';
                    }

                }
                else
                {
                    $error = 'El nombre de usuario o la contraseña no son correctos';
                }
            }
            else
            {
                $error = 'Complete todos los campos';
            }       
            return $error;
        }

         
    } 
 ?>