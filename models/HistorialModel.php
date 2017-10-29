<?php 

	class HistorialModel extends Listable
	{

		function __construct()
		{
			parent::__construct();	
		}
		public function listar( $pagina, $paciente, $desde, $hasta)
		{
			$args['id'] = $paciente;
			$where = 'AND p.id = :id AND borradoHis = 0';
			if ($desde != "") {
				$desde = $this->acomodarASql($desde);
				$where = $where . " AND fecha > :desde";
				$args['desde'] = $desde;
			}
			if ($hasta != "") {
				$hasta = $this->acomodarASql($hasta);
				$where = $where . " AND fecha < :hasta";
				$args['hasta'] = $hasta;
			}
			$tabla = "paciente p INNER JOIN historia h ON h.id_paciente = p.id INNER JOIN usuario u ON u.id = usuarioCarga";
			$select = "h.id, h.fecha, u.first_name, u.last_name ";
			$alias = "p.";
			$pp = $this->getLimitOffset($tabla, $pagina, $where, $args, $select, $alias);
			$historias = $this->getDatosPara($tabla, $pp->getLimit(), $pp->getOffset(), $where, $args, $select, $alias);
			$historiasUP = array();
			foreach ($historias as $historia) {
				$historia['fecha'] = $this->acomodarDeSql($historia['fecha']);
				$historiasUP[] = $historia;
			}
			$datosPag = new ConsultaPag($pagina, $pp->getPaginasTotales(), $historiasUP);
			return $datosPag;
		}
		public function showHistory($id){
			//var_dump($id);die();
            $sql = "SELECT h.id, h.fecha, peso, vacunas, vacunaObservacion, maduracion, maduracionObservacion, examenFisico, examenFisicoObservacion,
            pc, ppc, talla, alimentacion, observacionGeneral, nacimiento, u.first_name, u.last_name 
            FROM paciente p INNER JOIN historia h ON p.id = h.id_paciente INNER JOIN usuario u on u.id =  h.usuarioCarga WHERE h.id = :unId";
            $consulta = $this->base->prepare($sql);
            $consulta-> bindParam(':unId', $id, PDO::PARAM_INT, 11);
            $consulta->execute();
            $historia = $consulta->fetch();
            $nac = strtotime($this->acomodarFecha($historia['nacimiento']));
            $diaH = strtotime ($this->acomodarFecha($historia['fecha']));	
            $segs = ($diaH - $nac  );
            $historia['fecha'] = $this->acomodarDeSql($historia['fecha']);
            $historia['edad'] = floor($segs / (60 * 60 * 24 * 365));
            return $historia;

		}

	
		public function eliminar($id){
        $consulta = $this->base->prepare('UPDATE historia SET borradoHis = 1 WHERE id = :unId');
		$consulta-> bindParam(':unId', $id, PDO::PARAM_INT);
		$consulta->execute();
		}

		public function get_historia($id) {
			$sql = 'SELECT * FROM historia WHERE id = :unId AND borrado = 0';
			$consulta = $this->base->prepare($sql);
           	$consulta-> bindParam(':unId', $id, PDO::PARAM_INT);
			$consulta->execute();
         	$historia = $consulta->fetch();
            return $historia;

        }

        public function editar($historia)
		{
			
			$sql = ('UPDATE  `historia`  SET fecha =:fecha , peso = :peso, vacunas =:vacunas, vacunaObservacion =:vacunaObservacion, 
			maduracion = :maduracion, maduracionObservacion = :maduracionObservacion, examenFisico = :examenFisico, examenFisicoObservacion = 
			:examenFisicoObservacion, pc = :pc, ppc = :ppc, talla = :talla, alimentacion = :alimentacion, observacionGeneral = :observacionGeneral
		    WHERE `id` =:id ');
			
			$consulta = $this->base->prepare($sql);
			$historia['fecha'] = $this->acomodarASql($historia['fecha']);
			$consulta->execute($historia);

		}


			public function yaExistehistoria($historia)
		{
			$consulta = $this->base->prepare('SELECT * FROM historia WHERE numDoc = :unNumDoc and borrado = 0');
			$consulta-> bindParam(':unNumDoc', $historia, PDO::PARAM_STR, 256);
			return $consulta->rowCount() > 0 ;
		}
	
		public function insertarHistoria($historia)
		{
			$sql = ('INSERT INTO `historia` (`fecha`, `peso`, `vacunas`, `vacunaObservacion`, `maduracion`, `maduracionObservacion`, `examenFisico`,`examenFisicoObservacion`,`pc`,`ppc`,`talla`,`alimentacion`,`observacionGeneral`,`usuarioCarga`,`id_paciente`) 
				VALUES (:unaFecha, :unPeso, :unasVacunas, :unVacunaObservacion, :unaMaduracion, :unaMaduracionObservacion, :unExamenFisico, :unExamenFisicoObservacion, :unPc, :unPpc, :unaTalla, :unaAlimentacion, :unaObservacionGeneral, :unUsuario, :unIdPaciente)');
			
			$consulta = $this->base->prepare($sql);
			$fecha = $this->acomodarASql($historia['fecha']);
			$consulta-> bindParam(':unaFecha', $fecha, PDO::PARAM_STR, 256 );
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
			$consulta-> bindParam(':unUsuario', $historia['usuarioCarga'], PDO::PARAM_INT);
			$consulta-> bindParam(':unIdPaciente', $historia['pacienteid'], PDO::PARAM_STR, 256);
			//$consulta-> bindParam(':unId', $historia['id'], PDO::PARAM_INT);
			$consulta->execute();
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