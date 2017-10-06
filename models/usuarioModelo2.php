<?php 
require_once ('Usuario.php');
	class UsuarioModelo2 extends ConexionABD
	{

		function __construct()
		{
			parent::__construct();	
		}

    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

		public function listar()
		{
			$sql = "SELECT * FROM usuario " ;
			$consulta = $this->base->prepare($sql);
			$consulta->execute();
			$usuarios = $consulta-> fetchAll();
			return $usuarios;
		}

		public function show() {
        //var_dump($validacion); die();
        echo self::getTwig()->render('altaUsuario.twig.html',$datos);
        
        
    }
	}		
 ?>