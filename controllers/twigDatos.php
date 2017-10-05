<?php 
	$raiz = $_SERVER['DOCUMENT_ROOT'];
	require_once ("$raiz/models/configDatos.php");
	class datosTwig 
	{
		private static $singleton;
		

		public static function getInstance() {

	        if (!self::$singleton instanceof self ) {
	        	self::$singleton = new self;   
	        }
	        return self::$singleton;
	    }
	    public function datosConfig(){
	    	
	    	$datos = new DatosConfig();
	    	$datos = $datos->obtenerInfo();
	    	$datos = $datos;
	    	$datos['url'] = "https://" . "$_SERVER[HTTP_HOST]" . "/";
	    	// en servidor local remplazar la linea de arriba con:	
	    	//$datos['url'] = "http://" . "$_SERVER[HTTP_HOST]" . '/grupo63/';
	    	return $datos;
	    }

	   	public function datosLogueado()
	   	{
	   		
	   		if (!isset($_SESSION)) { session_start();}
	   		$datos = self::datosConfig();
	   		$datos['usuario'] = $_SESSION['usrName'];
	   		$datos['rol'] = $_SESSION['rol'];
	   		return $datos;
	   	}
	}
 ?>