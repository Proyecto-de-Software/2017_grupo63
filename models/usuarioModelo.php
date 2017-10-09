<?php 

	class UsuarioModelo extends Listable
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
			$pp = $this->getLimitOffset("usuario", $pagina);
			$usuarios = $this->getDatosPara('usuario', $pp->getLimit(), $pp->getOffset());
			$datosPag = new ConsultaPag($pagina, $pp->getPaginasTotales(), $usuarios);
			return $datosPag;
		}

		public function yaExisteUsuario($usuario)
		{
			$consulta = $this->base->prepare('SELECT * FROM usuario WHERE username = :unNombre and borrado = 0');
			$consulta-> bindParam(':unNombre', $usuario, PDO::PARAM_STR, 256);
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
 	 	
 	 	public function eliminar($username){
        $consulta = $this->base->prepare('DELETE FROM `usuario` WHERE username = :unUsername');
		$consulta-> bindParam(':unUsername', $username, PDO::PARAM_STR, 256);
		$consulta->execute();
     	//$sql = ('DELETE FROM `usuario` WHERE username = :unUsername');
     	 //$consulta = $this->base->prepare($sql);
     	//$consulta-> bindParam(':unUsuario', $username, PDO::PARAM_STR, 256);
       
       
       
       
         //$this->id = $conexion->lastInsertId();
      	//$result = true;
      //desconectar($conn);
       //return $result; 
      //$conexion = null;
   
 	 }
	

		public function get_user($username) {

			$consulta = $this->base->prepare('SELECT * FROM `usuario` WHERE username = :unUsername');
           	$consulta-> bindParam(':unUsername', $username, PDO::PARAM_STR, 256);
		

           $consulta->execute();
           return $consulta;
        }




	}		
 ?>