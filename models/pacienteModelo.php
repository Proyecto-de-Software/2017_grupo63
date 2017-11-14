<?php 

	class PacienteModelo extends Listable
	{

		function __construct()
		{
			parent::__construct();	
		}

		public function listar( $pagina, $filtro, $filtroDocT, $filtroDocN)
		{
			$filtro = htmlspecialchars($filtro);
			$filtroDocT = htmlspecialchars($filtroDocT);
			$filtroDocN = htmlspecialchars($filtroDocN);
			$args = array();
			$where = '';
			if (!empty($filtro)) {
				$filtroSQL = "%" . $filtro . "%";
				$where = "AND (apellido LIKE :filtroSQL OR nombre LIKE :filtroSQL) ";
				$args[':filtro'] = "$filtro";
				$args[':filtroSQL'] = "$filtroSQL";
			}
			if ($filtroDocT != 0) {
				$where = $where . "AND (tipoDoc = :unTipoDoc) ";
				$args['unTipoDoc'] = $filtroDocT;
			}
			if ($filtroDocN != "") {
				$where = $where . "AND (numDoc = :unNumeroDoc)";
				$args['unNumeroDoc'] = $filtroDocN;
			}
			$pp = $this->getLimitOffset("paciente", $pagina, $where, $args);
			$usuarios = $this->getDatosPara('paciente', $pp->getLimit(), $pp->getOffset(), $where, $args);
			$usuariosAcom = array();
			foreach ($usuarios as $usuario) {
				$fecha = $this->acomodarDeSql($usuario['nacimiento']);
				$usuario['nacimiento'] = $fecha;
				$usuariosAcom[] = $usuario;
			}
			$datosPag = new ConsultaPag($pagina, $pp->getPaginasTotales(), $usuariosAcom);
			return $datosPag;
		}

		public function eliminar($id){
        $consulta = $this->base->prepare('UPDATE paciente SET borrado = 1 WHERE id = :unId');
		$consulta-> bindParam(':unId', $id, PDO::PARAM_INT);
		$consulta->execute();
		}

		public function get_user($id) {
			$sql2 = "SELECT p.id, p.apellido, p.nombre, p.nacimiento, p.tipoDoc, p.numDoc,p.domicilio, p.telefono, 
			p.obraSocial, p.datos_demograficos_id, genero FROM paciente p WHERE p.id = :unId ";
			$consulta = $this->base->prepare($sql2);
           	$consulta-> bindParam(':unId', $id, PDO::PARAM_INT);
			$consulta->execute();
         	$paciente = $consulta->fetch();
         	$paciente['nacimiento'] = $this->acomodarDeSql($paciente['nacimiento']);
            return $paciente;

        }

        public function editar($paciente)
		{
			
			$sql = ('UPDATE  `paciente`  SET `nombre` =:unNombre , `apellido` =:unApellido, `nacimiento` =:unNacimiento, `genero` =:unGenero, `tipoDoc` = :unTipoDoc ,`numDoc` = :unNumDoc ,  `domicilio` = :unDomicilio ,  `telefono` = :unTelefono ,  `obraSocial` = :unObraSocial 
			WHERE `id` =:unId ');
			
			$consulta = $this->base->prepare($sql);
			$fechaSQL = $this->acomodarASql($paciente['nacimiento']);
			$consulta-> bindParam(':unNombre', $paciente['nombre'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unApellido', $paciente['apellido'], PDO::PARAM_STR, 256);
			
			$consulta-> bindParam(':unNacimiento', $fechaSQL, PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unGenero', $paciente['genero'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unTipoDoc', $paciente['tipoDoc'], PDO::PARAM_INT);
			$consulta-> bindParam(':unNumDoc', $paciente['numDoc'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unDomicilio', $paciente['domicilio'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unTelefono', $paciente['telefono'], PDO::PARAM_INT);
			$consulta-> bindParam(':unObraSocial', $paciente['obraSocial'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unId', $paciente['id'], PDO::PARAM_INT);	

			$consulta->execute();
			
			//$this->quitarRoles($paciente['id']);
			//foreach ($paciente['roles'] as $rol) {
			//	$this->asignarRol($paciente['id'], $rol);
			//}

		}


			public function yaExistePaciente($paciente)
		{
			$consulta = $this->base->prepare('SELECT * FROM paciente WHERE numDoc = :unNumDoc and borrado = 0');
			$consulta-> bindParam(':unNumDoc', $paciente, PDO::PARAM_STR, 256);
			return $consulta->rowCount() > 0 ;
		}
	
		public function insertar($paciente)
		{
			//var_dump($paciente);die();
			$sql = ('INSERT INTO `paciente` (`nombre`, `apellido`, `nacimiento`, `genero`, `tipoDoc`, `numDoc`, `domicilio`, `telefono`,`obraSocial`) 
				VALUES (:unNombre, :unApellido, :unNacimiento, :unGenero, :unTipoDoc, :unNumDoc, :unDomicilio, :unTelefono, :unObraSocial)');
			
			$consulta = $this->base->prepare($sql);
			
			$fechaSQL = $this->acomodarASql($paciente['nacimiento']);
			$consulta-> bindParam(':unNombre', $paciente['nombre'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unApellido', $paciente['apellido'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unNacimiento', $fechaSQL, PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unGenero', $paciente['genero'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unTipoDoc', $paciente['tipoDoc'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unNumDoc', $paciente['numDoc'], PDO::PARAM_INT);
			$consulta-> bindParam(':unDomicilio', $paciente['domicilio'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unTelefono', $paciente['telefono'], PDO::PARAM_INT);
			$consulta-> bindParam(':unObraSocial', $paciente['obraSocial'], PDO::PARAM_STR, 256);
			$consulta->execute();
			
			//$idUser = $this->base->lastInsertId();
			//foreach ($usuario['roles'] as $rol) {
			//	$this->asignarRol($idUser, $rol);
			//}
		}
		public function ultimoUsuario(){
			$sql = 'SELECT MAX(id) AS id FROM paciente';
			$consulta = $this->base->prepare($sql);
			 $consulta->execute();
			 $result = $consulta->fetch();
       		 return $result;
		}

		public function agregarDemografic($idPaciente, $idDemografico) {
        $sql = ('UPDATE  paciente  SET id_historia = :demografic_id
            WHERE id =:unId');
        $consulta = $this->base->prepare($sql);
        $consulta->bindParam(':demografic_id', $idDemografico[0], PDO::PARAM_INT, 256);
        $consulta->bindParam(':unId', $idPaciente, PDO::PARAM_INT, 256);
        $consulta->execute();

    }
    
    public function agregarHistorial($idPaciente, $idDemografico) {
        $sql = ('UPDATE  paciente  SET id_historia = :idDemografico
            WHERE id =:unId');
        $consulta = $this->base->prepare($sql);
        $consulta->bindParam(':idDemografico', $idDemografico[0], PDO::PARAM_INT, 256);
        $consulta->bindParam(':unId', $idPaciente, PDO::PARAM_INT, 256);
        $consulta->execute();

    }
    public function sacarDemografic($idPaciente, $idDemografico) {
        $sql = ('UPDATE  paciente  SET datos_demograficos_id = :demografic_id
            WHERE id =:unId');
        $consulta = $this->base->prepare($sql);
        $consulta->bindParam(':demografic_id', $idDemografico, PDO::PARAM_INT, 256);
        $consulta->bindParam(':unId', $idPaciente, PDO::PARAM_INT, 256);
        $consulta->execute();

    }   
        	
	}		
 ?>