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
		
		public function listar($pagina)
		{
			$dc = new DatosConfig();
			(int)$limite = $dc->paginas();
			(int)$offset = ($pagina - 1) * $limite;
			$sql = "SELECT * FROM usuario WHERE borrado = 0 " ;
			$consulta = $this->base->prepare($sql);
			$total = $consulta->execute(); 
			$total = $consulta->rowCount();
			$paginasTotales = ceil($total / $limite);
			var_dump($total);
			//$consulta->closeCursor();
			//$consulta = null;
			$consulta = $this->base->prepare($sql . "LIMIT $limite OFFSET $offset");	
			var_dump($consulta);
			$usuarios = $consulta->execute();
			var_dump($usuarios);
			$datosPag = $usuarios->fetchAll();
			$datosPag = new ConsultaPag($pagina, $paginasTotales, $usuarios);
			return $datosPag;
		}

		public function yaExisteUsuario($usuario)
		{
			$consulta = $this->base->prepare('SELECT * FROM usuario WHERE username = :unNombre and borrado = 0');
			$consulta-> bindParam(':unNombre', $usuario, PDO::PARAM_STR, 256);
			$consulta->execute();
			return $consulta->rowCount() > 0 ;
		}
	
		public function yaExisteCorreo($correo)
		{
			$consulta = $this->base->prepare('SELECT * FROM usuario WHERE email = :unCorreo and borrado = 0');
			$consulta-> bindParam(':unCorreo', $unCorreo, PDO::PARAM_STR, 256);
			$consulta->execute();
			return $consulta->rowCount() > 0 ;
		}
		
		public function insertar($usuario)
		{
			
			$sql = ('INSERT INTO `usuario` (`email`, `username`, `password`, `activo`, `first_name`, `last_name`) 
				VALUES (:unMail, :unUsuario, :unPass, 1, :unNombre, :unApellido)');
			
			$consulta = $this->base->prepare($sql);
			
			$consulta-> bindParam(':unMail', $usuario['email'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unUsuario', $usuario['username'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unPass', $usuario['password'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unNombre', $usuario['first_name'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unApellido', $usuario['last_name'], PDO::PARAM_STR, 256);
			$consulta->execute();

		}
	}		
 ?>