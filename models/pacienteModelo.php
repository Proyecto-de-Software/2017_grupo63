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
        $sql = ('UPDATE  paciente  SET datos_demograficos_id = :demografic_id
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
		
		public function datosCurvaPeso($paciente, $nacimiento)
		{
			$nac = explode("/", $nacimiento);
			$treceSemanas = date("Y-m-d", mktime(0, 0, 0, $nac[1]  , $nac[0] + 91, $nac[2]));
			return $this->datosCurva($paciente, $treceSemanas, "peso");
		}	    	
		
		public function datosCurvaTalla($paciente, $nacimiento)
		{
			$nac = explode("/", $nacimiento);
			$dosAnos = date("Y-m-d", mktime(0, 0, 0, $nac[1]  , $nac[0], $nac[2] + 2));
			return $this->datosCurva($paciente, $dosAnos, "talla");
		}

		public function datosCurvaPPC($paciente, $nacimiento)
		{
			$nac = explode("/", $nacimiento);
			$treceSemanas = date("Y-m-d", mktime(0, 0, 0, $nac[1]  , $nac[0] + 91, $nac[2]));
			return $this->datosCurva($paciente, $treceSemanas, "ppc");
		}

		public function datosCurva($paciente, $lapso, $colum)
		{
			$sql = "SELECT fecha, $colum FROM historia h INNER JOIN paciente p ON p.id = h.id_paciente WHERE fecha < :fecha AND id_paciente = :pac 
			AND fecha > nacimiento AND borradoHis = 0 ORDER BY fecha";
			$consulta = $this->base->prepare($sql);
			$consulta->bindParam(":fecha", $lapso);
			$consulta->bindParam(":pac", $paciente);
			$consulta->execute();
			$datos =  $consulta->fetchAll(PDO::FETCH_ASSOC);
			$datosJS = array();
			foreach ($datos as $dato) {
				$arreglo = array();
				$fechaJS = $this->sacarUnMes($dato['fecha']);
				$arreglo[] = $fechaJS;
				$arreglo[] = (int)$dato["$colum"];
				$datosJS[] = $arreglo;
			}
			return $datosJS;
		}

		public function datosGrafico($paciente)
		{
			//var_dump($paciente);
			//die();
			$grafico= array();
			$peso['name'] = "PESO";
			$peso['data'] = $this->datosCurvaPeso($paciente);
			$talla['name'] = "TALLA";
			$talla['data'] = $this->datosCurvaTalla($paciente);
			$ppc['name'] = "PPC";
			$ppc['data'] = $this->datosCurvaPPC($paciente);
			$grafico[] = $peso;
			$grafico[] = $talla;
			$grafico[] = $ppc;
			return $grafico;
		}

		public function sacarUnMes($fecha)
			{	
				$fechaJS = explode("-", $fecha);
				//$fechaJS[1] = (int) $fechaJS[1] - 1;
				return implode(",", $fechaJS);
			}

		public function curvaPeso($pacienteID)
		{
			$paciente = $this->get_user($pacienteID);
			$datos['name']	= $paciente['nombre'] . " " . $paciente['apellido'];
			$datos['data'] = $this->datosCurvaPeso($pacienteID, $paciente['nacimiento']);
			if ($paciente['genero'] == 'masculino') {
				$datosMedia = array(3.3, 3.5, 3.8, 4.1, 4.4, 4.7, 4.9, 5.2, 5.4, 5.6, 5.8, 6, 6.2, 6.4);
				$datosMin =   array(2.5, 2.6, 2.8, 3.1, 3.4, 3.6, 3.8, 4.1, 4.3, 4.4, 4.6, 4.8, 4.9, 5.1);
				$datosMax =   array(4.6, 4.8, 5.1, 5.5, 5.9, 6.3, 6.6, 6.9, 7.2, 7.4, 7.7, 7.9, 8.1, 8.3);
			} else {
				$datosMedia = array(3.2, 3.3, 3.6, 3.8, 4.1, 4.3, 4.6, 4.8, 5  , 5.2, 5.4, 5.5, 5.7, 5.8);
				$datosMin =   array(2.4, 2.5, 2.7, 2.9, 3.1, 3.3, 3.5, 3.7, 3.8, 4.0, 4.1, 4.3, 4.4, 4.5);
				$datosMax =   array(4.2, 4.4, 4.7, 5  , 5.4, 5.7, 6.0, 6.2, 6.5, 6.7, 6.9, 7.1, 7.3, 7.5);
			}
			
			$curvas = $this->datosCurvaTrece($paciente['nacimiento'], $datosMin, $datosMedia, $datosMax); 
			$curvas[] = $datos;
			return $curvas;
		}		

		public function curvaPPC($pacienteID)
		{
			$paciente = $this->get_user($pacienteID);
			$datos['name']	= $paciente['nombre'] . " " . $paciente['apellido'];
			$datos['data'] = $this->datosCurvaPPC($pacienteID, $paciente['nacimiento']);
			if ($paciente['genero'] == 'masculino') {
				$datosMedia = array(34.5, 35.2, 35.9, 36.5, 37.1, 37.6, 38.1, 38.5, 38.9, 39.2, 39.6, 39.9, 40.2, 40.5);
				$datosMin =   array(31.5, 32.3, 33.1, 33.8, 34.4, 34.9, 35.3, 35.8, 36.1, 36.5, 36.8, 37.2, 37.8, 37.5);
				$datosMax =   array(38.4, 38.9, 39.5, 40.1, 40.7, 41.2, 41.7, 42.1, 42.5, 42.9, 43.2, 43.5, 43.9, 44.2);
			} else {
				$datosMedia = array(33.9, 34.6, 35.2, 35.8, 36.4, 36.8, 37.3, 37.7, 38, 38.4, 38.7, 39, 39.3, 39.5);
				$datosMin =   array(30.2, 31, 31.7, 32.3, 32.8, 33.2, 33.6, 34, 34.3, 34.6, 34.9, 35.2, 35.4, 35.7);
				$datosMax =   array(37.5, 38.2, 38.8, 39.4, 40, 40.5, 40.9, 41.4, 41.7, 42.1, 42.4, 42.8, 43.1, 43.4);
			}
			$curvas = $this->datosCurvaTrece($paciente['nacimiento'], $datosMin, $datosMedia, $datosMax); 
			$curvas[] = $datos;
			return $curvas;
		}

		public function curvaTalla($pacienteID)
		{
			$paciente = $this->get_user($pacienteID);
			$datos['name']	= $paciente['nombre'] . " " . $paciente['apellido'];
			$datos['data'] = $this->datosCurvaTalla($pacienteID, $paciente['nacimiento']);
			if ($paciente['genero'] == 'masculino') {
				$datosMedia = array(49.9, 54.7, 58.4, 61.4, 63.9, 65.9, 67.6, 69.2, 70.6, 72, 73.3, 74.5, 75.7, 76.9, 78, 79.1, 80.2, 81.2, 82.3, 83.2, 84.2, 85.1, 86, 86.9, 87.8);
				$datosMin =   array(45.5, 50.2, 53.8, 56.7, 59, 61, 62.6, 64.1, 65.5, 66.8, 68, 69.1, 70.2, 71.3, 72.3, 73.3, 74.2, 75.1, 76, 76.8, 77.7, 78.4, 79.2, 80, 80.7);
				$datosMax =   array(55.7, 60.7, 64.6, 67.7, 70.3, 72.4, 74.2, 75.9, 77.4, 78.9, 80.3, 81.7, 83.1, 84.4, 85.7, 87.0, 88.2, 89.4, 90.6, 91.8, 92.9, 94.0, 95.1, 96.2, 97.3);
			} else {
				$datosMedia = array(49.1, 53.7, 57.1, 59.8, 62.1, 64.0, 65.7, 67.3, 68.7, 70.1, 71.5, 72.8, 74.0, 75.2, 76.4, 77.5, 78.6, 79.7, 80.7, 81.7, 82.7, 83.7, 84.6, 85.5, 86.4);
				$datosMin =   array(44.8, 49.1, 52.3, 54.9, 57.1, 58.9, 60.5, 61.9, 63.2, 64.5, 65.7, 66.9, 68.0, 69.1, 70.1, 71.1, 72.1, 73.0, 74.0, 74.8, 75.7, 76.5, 77.3, 78.1, 78.9);
				$datosMax =   array(54.9, 59.7, 63.4, 66.3, 68.8, 70.9, 72.7, 74.4, 76.1, 77.6, 79.1, 80.6, 82.0, 83.3, 84.7, 86.0, 87.2, 88.5, 89.7, 90.9, 92.0, 93.1, 94.2, 95.3, 96.4);
			}
			$curvas = $this->datosCurvaDos($paciente['nacimiento'], $datosMin, $datosMedia, $datosMax); 
			$curvas[] = $datos;
			return $curvas;
		}

		private function datosCurvaTrece($nacimiento, $datosMin, $datosMedia, $datosMax)
		{
			$media = array('name' => 'media', 'color' => 'green');
			$max = array('name' => 'maximo', 'color' => 'red');
			$min = array('name' => 'minimo', 'color' => 'red');
			$todos = array();
			 
			$nac = explode("/", $nacimiento);
			$fecha = date("Y-m-d", mktime(0, 0, 0, $nac[1]  , $nac[0], $nac[2]));
			$semana = 7;
			for ($i=0; $i < 14; $i++) { 
				$arregloMed = array();
				$arregloMed[] = $this->sacarUnMes($fecha);
				$arregloMed[] = $datosMedia[$i];
				$arregloMax = array();
				$arregloMax[] = $this->sacarUnMes($fecha);
				$arregloMax[] = $datosMax[$i];
				$arregloMin = array();
				$arregloMin[] = $this->sacarUnMes($fecha);
				$arregloMin[] = $datosMin[$i];
				$fecha = date("Y-m-d", mktime(0, 0, 0, $nac[1]  , $nac[0] + $semana , $nac[2]));
				$min["data"][] = $arregloMin;
				$media["data"][] = $arregloMed;
				$max["data"][] = $arregloMax;
				$semana += 7;
			}
			$todos[] = $min;
			$todos[] = $media;
			$todos[] = $max;
			return $todos;
		}
		
		private function datosCurvaDos($nacimiento, $datosMin, $datosMedia, $datosMax)
		{
			$media = array('name' => 'media', 'color' => 'green');
			$max = array('name' => 'maximo', 'color' => 'red');
			$min = array('name' => 'minimo', 'color' => 'red');
			$todos = array();
			$nac = explode("/", $nacimiento);
			$fecha = date("Y-m-d", mktime(0, 0, 0, $nac[1]  , $nac[0], $nac[2]));
			for ($i=0; $i < 25; $i++) { 
				$arregloMed = array();
				$arregloMed[] = $this->sacarUnMes($fecha);
				$arregloMed[] = $datosMedia[$i];;
				$arregloMax = array();
				$arregloMax[] = $this->sacarUnMes($fecha);
				$arregloMax[] = $datosMax[$i];
				$arregloMin = array();
				$arregloMin[] = $this->sacarUnMes($fecha);
				$arregloMin[] = $datosMin[$i];;
				$fecha = date("Y-m-d", mktime(0, 0, 0, $nac[1] + $i , $nac[0], $nac[2]));
				$min["data"][] = $arregloMin;
				$media["data"][] = $arregloMed;
				$max["data"][] = $arregloMax;
			}
			$todos[] = $min;
			$todos[] = $media;
			$todos[] = $max;
			return $todos;
		}
	
	}		
 ?>