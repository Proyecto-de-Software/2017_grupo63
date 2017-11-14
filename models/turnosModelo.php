<?php 
	/**
	* 
	*/
	class TurnosModelo extends ConexionABD
	{
		
		function __construct()
		{
			parent::__construct();
		}
		
		public function turnos($date)
		{
			$turnos = array();
			$fecha = new DateTime('20-01-2001');
			$fecha->add(new DateInterval('PT7H30M'));
			for ($i=1; $i <= 24; $i++) { 
				$turno = new DateTime($fecha->format('d-m-Y H:i:s'));
				$turno->add(new DateInterval("PT" . 30 * $i ."M"));
				$turnos[] = $turno->format('H:i:s') ;
			}
			$ocupados = $this->turnosOcupados($date);
			foreach ($ocupados as $ocupado) {
				//unset($turnos[array_search($ocupado['hora'], $turnos)]);
				array_splice($turnos, array_search($ocupado['hora'], $turnos), 1);
			}
			//array_values($turnos);
			return $turnos;
		}
		
		public function verificarFecha($date)
		{
			$fecha = explode("-", $date);
			if (count($fecha) != 3) {
				return false;
			}
			return checkdate($fecha[1], $fecha[0], $fecha[2]);
		}

		public function turnosOcupados($fecha)
		{
			$fechaSQL = new Listable();
			$fechaSQL = $fechaSQL->acomodarFecha($fecha);
			$inicio = new DateTime($fechaSQL);
			$inicio->add(new DateInterval('PT8H'));
			$inicio = $inicio->format('Y-m-d H:i:s');
			$fin = new DateTime($fechaSQL);
			$fin->add(new DateInterval('PT20H'));
			$fin = $fin->format('Y-m-d H:i:s');
			$sql = "SELECT DATE_FORMAT(fecha,'%H:%i:%s') hora FROM turno WHERE fecha BETWEEN '$inicio' AND '$fin'";
			$consulta = $this->base->prepare($sql);
			$consulta->execute();
			return $consulta->fetchAll();
		}

		public function reservar($dni, $fecha, $hora)
		{
			$turno = new DateTime($fecha . " " . $hora);
			//$tiempo  = explode(":", $hora);
			//$turno->add(new DateInterval('PT'. $tiempo[0].'H'. $tiempo[1]. 'M'));
			$sql = "INSERT INTO turno (fecha, dni) VALUES (:fecha, :dni)" ; 
			$consulta = $this->base->prepare($sql);
			$turnoF = $turno->format('Y-m-d H:i:s');
			$consulta->bindParam(':fecha', $turnoF);
			$consulta->bindParam(':dni', $dni);
			$consulta->execute();
			return $this->base->lastInsertId();
		}

		public function verificarHora($time)
		{
			$time = explode(":", $time);
			if (count($time) != 2) {
				echo "hola";
				return false;
			}
			$hora = $time[0];
			$minuto = $time[1];
			if ($hora <= 7 || $hora >= 20 ) {
				return false;
			}
			if ($minuto != '00' && $minuto != '30') {
				return false;
			}
			return true;
		}

		public function estaOcupado($fecha, $hora)
		 {	
		 	$sql = "SELECT * FROM turno WHERE fecha = :turno";
		 	$consulta = $this->base->prepare($sql);
			$turno = new DateTime($fecha . " " . $hora);
			$turnoSql = $turno->format('Y-m-d H:i:s');
			$consulta->bindParam(':turno', $turnoSql);
		 	$consulta->execute();
		 	$ocupado = $consulta->fetch();
		 	return !empty($ocupado);
		 } 
		
		public function yaPaso($fecha)
		{
			$hoy = Date("d-m-Y");
			$fecha = Date($fecha);
			/*var_dump($hoy);
			var_dump(strtotime($hoy));
			var_dump($fecha);
			var_dump(strtotime($fecha));
			var_dump($hoy - $fecha);
			die();*/
			return strtotime($hoy) >= strtotime($fecha);
		}
	}

 ?>