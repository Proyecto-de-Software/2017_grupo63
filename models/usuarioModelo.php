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
		
		public function listar($pagina, $filtro, $filtroH)
		{
			$filtro = htmlspecialchars($filtro);
			$args = array();
			$where = '';
			if ($filtroH < 2) {
				$where = "AND activo = :filtroH ";
				$args[':filtroH'] = $filtroH;
			}
			if (!empty($filtro)) {
				$filtro = '%' . $filtro . '%';
				$where = $where . "AND (username LIKE :filtro OR last_name LIKE :filtro OR first_name LIKE :filtro)";
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
			
			$sql = ('UPDATE  `usuario`  SET `email` =:unMail , `username` =:unUsuario, `first_name` =:unNombre, `last_name` =:unApellido, activo = :unEstado 
			WHERE `id` =:unId ');
			
			$consulta = $this->base->prepare($sql);
			
			$consulta-> bindParam(':unMail', $usuario['email'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unUsuario', $usuario['username'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unId', $usuario['id'], PDO::PARAM_INT);
			$consulta-> bindParam(':unNombre', $usuario['first_name'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unApellido', $usuario['last_name'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unEstado', $usuario['activo'], PDO::PARAM_INT);
			$consulta->execute();
			
			$this->quitarRoles($usuario['id']);
			foreach ($usuario['roles'] as $rol) {
				$this->asignarRol($usuario['id'], $rol);
			}

		}
 	 	
 	 	public function eliminar($id){
        $consulta = $this->base->prepare('UPDATE usuario SET borrado = 1 WHERE id = :unId');
		$consulta-> bindParam(':unId', $id, PDO::PARAM_INT);
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
        		$sql = 'SELECT rol.id, rol.nombre FROM usuario as u 
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
		
		public function quitarRoles($user)
		{
			$consulta = $this->darConexion()->prepare("DELETE FROM usuario_tiene_rol WHERE usuario_id = $user");
			$consulta->execute();
		}
	
		public function getPermisos($userId)
		{
			$sql = "SELECT p.nombre FROM usuario as u INNER JOIN usuario_tiene_rol as utr ON u.id = utr.usuario_id 
			INNER JOIN rol ON rol.id = utr.rol_id INNER JOIN rol_tiene_permiso as rtp ON rol.id = rtp.rol_id 
			INNER JOIN permiso as p ON p.id = rtp.permiso_id WHERE u.id = $userId GROUP BY p.nombre";
			$consulta = $this->base->prepare($sql);
			$consulta->execute();
			$datosArray = $consulta->fetchAll();
			$datos = array();
			foreach ($datosArray as $dato) {
				$datos[] = $dato[0];
			}
			return $datos;
		}
	}		
 ?>