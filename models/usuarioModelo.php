<?php 

	class UsuarioModelo extends ConexionABD
	{

		function __construct()
		{
			parent::__construct();	
		}

		public function loguearse($data)
		{
			$consulta = $this->base->prepare('SELECT * FROM usuario where username = :nombre and password = :pass AND borrado = 0');
			
			$consulta-> bindParam(':nombre', $data['usuario'], PDO::PARAM_STR, 50);
			$consulta-> bindParam(':pass', $data['contrasena'], PDO::PARAM_STR, 50 );
			$consulta->execute();//(array($user));
			$usuarios = $consulta-> fetch();
			return $usuarios;
		}
	}		
 ?>