<?php 

	class HistorialModel extends Listable
	{

		function __construct()
		{
			parent::__construct();	
		}
		public function listar( $pagina, $filtro)
		{
			$filtro = htmlspecialchars($filtro);
			$args = array();
			$where = '';
			if (!empty($filtro)) {
				$filtroSQL = "%" . $filtro . "%";
				$where = "AND (apellido LIKE :filtroSQL OR nombre LIKE :filtroSQL OR tipoDoc LIKE :filtro OR paciente.numDoc = :filtro)";
				$args[':filtro'] = "$filtro";
				$args[':filtroSQL'] = "$filtroSQL";
			}
			$pp = $this->getLimitOffset("paciente", $pagina, $where, $args);
			$usuarios = $this->getDatosPara('paciente', $pp->getLimit(), $pp->getOffset(), $where, $args);
			$datosPag = new ConsultaPag($pagina, $pp->getPaginasTotales(), $usuarios);
			return $datosPag;
		}
		public function showHistory($id){
			//var_dump($id);die();
            $sql = 'SELECT * FROM paciente INNER JOIN historia ON paciente.id_historia = historia.id  WHERE paciente.id = :unId';
            $consulta = $this->base->prepare($sql);
               $consulta-> bindParam(':unId', $id, PDO::PARAM_INT, 11);
            $consulta->execute();
             $paciente = $consulta->fetch();
            return $paciente;

		}


	
		public function eliminar($id){
        $consulta = $this->base->prepare('UPDATE historia SET borrado = 1 WHERE id = :unId');
		$consulta-> bindParam(':unId', $id, PDO::PARAM_INT);
		$consulta->execute();
		}

		public function get_user($id) {
			$sql = 'SELECT * FROM historia WHERE id = :unId AND borrado = 0';
			$consulta = $this->base->prepare($sql);
           	$consulta-> bindParam(':unId', $id, PDO::PARAM_INT);
			$consulta->execute();
         	$historia = $consulta->fetch();
            return $historia;

        }

        public function editar($historia)
		{
			
			$sql = ('UPDATE  `historia`  SET `nombre` =:unNombre , `apellido` =:unApellido, `nacimiento` =:unNacimiento, `genero` =:unGenero, `numDoc` = :unTipoDoc ,  `domicilio` = :unDomicilio ,  `telefono` = :unTelefono ,  `obraSocial` = :unObraSocial 
			WHERE `id` =:unId ');
			
			$consulta = $this->base->prepare($sql);
			
			$consulta-> bindParam(':unNombre', $historia['nombre'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unApellido', $historia['apellido'], PDO::PARAM_STR, 256);
			
			$consulta-> bindParam(':unNacimiento', $historia['nacimiento'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unGenero', $historia['genero'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unTipoDoc', $historia['numDoc'], PDO::PARAM_INT);
			$consulta-> bindParam(':unDomicilio', $historia['domicilio'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unTelefono', $historia['telefono'], PDO::PARAM_INT);
			$consulta-> bindParam(':unObraSocial', $historia['obraSocial'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unId', $historia['id'], PDO::PARAM_INT);	

			$consulta->execute();
			
			//$this->quitarRoles($historia['id']);
			//foreach ($historia['roles'] as $rol) {
			//	$this->asignarRol($historia['id'], $rol);
			//}

		}


			public function yaExistehistoria($historia)
		{
			$consulta = $this->base->prepare('SELECT * FROM historia WHERE numDoc = :unNumDoc and borrado = 0');
			$consulta-> bindParam(':unNumDoc', $historia, PDO::PARAM_STR, 256);
			return $consulta->rowCount() > 0 ;
		}
	
		public function insertarHistoria($historia)
		{
			//var_dump($historia);die();
			$sql = ('INSERT INTO `historia` (`fecha`, `edad`, `peso`, `vacunas`, `vacunaObservacion`, `maduracion`, `maduracionObservacion`, `examenFisico`,`examenFisicoObservacion`,`pc`,`ppc`,`talla`,`alimentacion`,`observacionGeneral`,`usuarioCarga`,`id_paciente`) 
				VALUES (:unaFecha, :unaEdad, :unPeso, :unasVacunas, :unVacunaObservacion, :unaMaduracion, :unaMaduracionObservacion, :unExamenFisico, :unExamenFisicoObservacion, :unPc, :unPpc, :unaTalla, :unaAlimentacion, :unaObservacionGeneral, :unUsuario, :unIdPaciente)');
			
			$consulta = $this->base->prepare($sql);
			
			$consulta-> bindParam(':unaFecha', $historia['fecha'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unaEdad', $historia['edad'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unPeso', $historia['peso'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unasVacunas', $historia['vacunas'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unVacunaObservacion', $historia['vacunaObservacion'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unaMaduracion', $historia['maduracion'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unaMaduracionObservacion', $historia['maduracionObservacion'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unExamenFisico', $historia['examenFisico'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unExamenFisicoObservacion', $historia['examenFisicoObservacion'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unPc', $historia['pc'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unPpc', $historia['ppc'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unaTalla', $historia['talla'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unaAlimentacion', $historia['alimentacion'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unaObservacionGeneral', $historia['observacionGeneral'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unUsuario', $historia['usuarioCarga'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unIdPaciente', $historia['paciente'], PDO::PARAM_STR, 256);
			//$consulta-> bindParam(':unId', $historia['id'], PDO::PARAM_INT);
			$consulta->execute();
			
			//$idUser = $this->base->lastInsertId();
			//foreach ($usuario['roles'] as $rol) {
			//	$this->asignarRol($idUser, $rol);
			//}
		}
		public function ultimoUsuario(){
			$sql = 'SELECT MAX(id) AS id FROM historia';
			$consulta = $this->base->prepare($sql);
			 $consulta->execute();
			 $result = $consulta->fetch();
       		 return $result;
		}

		public function agregarDemografic($idhistoria, $idDemografico) {
        $sql = ('UPDATE  historia  SET datos_demograficos_id = :demografic_id
            WHERE id =:unId');
        $consulta = $this->base->prepare($sql);
        $consulta->bindParam(':demografic_id', $idDemografico[0], PDO::PARAM_INT, 256);
        $consulta->bindParam(':unId', $idhistoria, PDO::PARAM_INT, 256);
        $consulta->execute();

    }
    public function sacarDemografic($idhistoria, $idDemografico) {
        $sql = ('UPDATE  historia  SET datos_demograficos_id = :demografic_id
            WHERE id =:unId');
        $consulta = $this->base->prepare($sql);
        $consulta->bindParam(':demografic_id', $idDemografico, PDO::PARAM_INT, 256);
        $consulta->bindParam(':unId', $idhistoria, PDO::PARAM_INT, 256);
        $consulta->execute();

    }

    public function ultimoDemografic(){
			$sql = 'SELECT MAX(id) AS id FROM historia';
			$consulta = $this->base->prepare($sql);
			 $consulta->execute();
			 $result = $consulta->fetch();
       		 return $result;
		}
		
	}		
 ?>