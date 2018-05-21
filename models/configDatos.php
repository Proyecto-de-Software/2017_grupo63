<?php 
	require_once "conexionModelo.php";
	
	class DatosConfig extends ConexionABD  	
	{
		
		
		function __construct()
		{
			parent::__construct();	
		}
		
		public function obtenerInfo()
		{
			
			$sql = "SELECT * FROM configuracion";
			$this->base-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$consulta = $this->base->prepare($sql);
			$consulta->execute();
			$datos = $consulta->fetch(PDO::FETCH_ASSOC);
			return $datos;	
		}

		public function editar($datos)
		{

			$sql = " UPDATE configuracion SET titulo = :titulo, descripcion = :descripcion, mail = :mail, paginado = :paginado, habilitado = :habilitado " ;
			$consulta = $this->base->prepare($sql);
			$consulta-> bindParam(':titulo', $datos['titulo'], PDO::PARAM_STR,256);
			$consulta-> bindParam(':descripcion', $datos['descripcion'], PDO::PARAM_STR,256);
			$consulta-> bindParam(':mail', $datos['mail'], PDO::PARAM_STR,256);
			$consulta-> bindParam(':paginado', $datos['paginado'], PDO::PARAM_INT);
			$consulta-> bindParam(':habilitado', $datos['habilitado'], PDO::PARAM_INT);
			$consulta->execute();
		}

		public function paginas()
		{
			$stm = $this->base->prepare("SELECT paginado FROM configuracion");
			$stm->execute();
			$numero=$stm->fetch(PDO::FETCH_NUM);
			return $numero[0];
		}
	}
 ?>