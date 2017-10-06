<?php 

	class UsuarioModelo2 extends ConexionABD
	{

		function __construct()
		{
			parent::__construct();	
		}

		public function listar()
		{
			$sql = "SELECT * FROM usuario " ;
			$consulta = $this->base->prepare($sql);
			$consulta->execute();
			$usuarios = $consulta-> fetchAll();
			return $usuarios;
		}
	}		
 ?>