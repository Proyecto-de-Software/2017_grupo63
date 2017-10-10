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
		
		public function listar($pagina, $filtro)
		{
			$filtro = htmlspecialchars($filtro);
			$args = array();
			$where = '';
			if ((strtolower($filtro) == 'activo') || ((strtolower($filtro) == 'bloqueado'))) {
				$valor = strtolower($filtro) == 'activo' ? 1 : 0 ;
				$where = "AND activo = :filtro";
				$args[':filtro'] = $valor;
			}
			elseif (!empty($filtro)) {
				$filtro = '%' . $filtro . '%';
				$where = "AND (username LIKE :filtro OR last_name LIKE :filtro OR first_name LIKE :filtro)";
				$args[':filtro'] = $filtro;
			}
			$pp = $this->getLimitOffset("usuario", $pagina, $where, $args);
			$usuarios = $this->getDatosPara('usuario', $pp->getLimit(), $pp->getOffset(), $where, $args);
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
			$idUser = $this->base->lastInsertId();
			foreach ($usuario['roles'] as $rol) {
				$this->asignarRol($idUser, $rol);
			}
		}
 	 	
 	 	public function editar($usuario)
		{
			
			$sql = ('UPDATE  `usuario`  SET `email` =:unMail , `username` =:unUsuario, `password` =:unPass, `first_name` =:unNombre, `last_name` =:unApellido WHERE `username` =:unUsuario ');
			
			$consulta = $this->base->prepare($sql);
			
			$consulta-> bindParam(':unMail', $usuario['email'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unUsuario', $usuario['username'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unPass', $usuario['password'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unNombre', $usuario['first_name'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unApellido', $usuario['last_name'], PDO::PARAM_STR, 256);
			$consulta->execute();
			/*
			$idUser = $this->base->lastInsertId();
			
			foreach ($usuario['roles'] as $rol) {
				$this->asignarRol($idUser, $rol);
			}
			*/

		}
 	 	public function eliminar($username){
        $consulta = $this->base->prepare('DELETE FROM `usuario` WHERE username = :unUsername');
		$consulta-> bindParam(':unUsername', $username, PDO::PARAM_STR, 256);
		$consulta->execute();

   
 	 }
	



		public function get_user($id) {
			$sql = 'SELECT * FROM usuario WHERE id = :unId AND borrado = 0';
			$consulta = $this->base->prepare($sql);
           	$consulta-> bindParam(':unId', $id, PDO::PARAM_INT);
			$consulta->execute();
         	$usuario = $consulta->fetch();
            return $usuario;

        }


        public function getRoles($id = 0)
        {
        	$sql = 'SELECT * FROM rol';
        	$args = array();
        	if ($id != 0) {
        		$sql = 'SELECT rol.nombre FROM usuario as u 
				INNER JOIN usuario_tiene_rol as utr ON u.id = utr.usuario_id 
				INNER JOIN rol ON rol.id = utr.rol_id WHERE u.id = :unId ';
        		$args['unId'] = $id;
        	}
        	
        	$consulta = $this->base->prepare($sql);
			$consulta->execute($args);
         	$roles = $consulta->fetchAll();
            return $roles;
        }

        public function asignarRol($usuario, $rol)
        {
        	$consulta = $this->darConexion()->prepare("INSERT INTO usuario_tiene_rol (usuario_id, rol_id) VALUES ($usuario, $rol)");
        	$consulta->execute();
        }
	}		
 ?>