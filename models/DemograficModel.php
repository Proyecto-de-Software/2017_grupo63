<?php
	
	class DemograficModel extends Listable
	{
		
		function __construct()
		{
			parent::__construct();	
		}

		public function insertDemografic($demografic)
		{
			$sql = ('INSERT INTO `datos_demograficos` (`heladera`, `electricidad`, `mascota`, `tipo_vivienda_id`, `tipo_calefaccion_id`, `tipo_agua_id`) 
				VALUES (:unaHeladera, :unaElectricidad, :unaMascota, :unaTipo_vivienda_id, :unaTipo_calefaccion_id, :unaTipo_agua_id)');
			
			$consulta = $this->base->prepare($sql);
			
			$consulta-> bindParam(':unaHeladera', $demografic['radioheladera'], PDO::PARAM_INT, 11);
			$consulta-> bindParam(':unaElectricidad', $demografic['radioelectricidad'], PDO::PARAM_INT, 11);
			$consulta-> bindParam(':unaMascota', $demografic['radiomascota'], PDO::PARAM_STR, 11);
			$consulta-> bindParam(':unaTipo_vivienda_id', $demografic['opcionvivienda'], PDO::PARAM_INT, 11);
			$consulta-> bindParam(':unaTipo_calefaccion_id', $demografic['opcioncalefaccion'], PDO::PARAM_INT, 11);
			$consulta-> bindParam(':unaTipo_agua_id', $demografic['opcionagua'], PDO::PARAM_INT, 11);
			$consulta->execute();

		}

		public function showDemografic($id){
            $sql = 'SELECT * FROM paciente INNER JOIN datos_demograficos ON paciente.datos_demograficos_id = datos_demograficos.id  WHERE paciente.id = :unId';
            $consulta = $this->base->prepare($sql);
               $consulta-> bindParam(':unId', $id, PDO::PARAM_INT, 11);
            $consulta->execute();
             $paciente = $consulta->fetch();
            return $paciente;

		}

		public function update($datosDem)
		{
			var_dump($datosDem);
			$sql = "UPDATE datos_demograficos SET heladera = :heladera , electricidad = :electricidad,mascota= :mascota,
				 tipo_vivienda_id = :opcionvivienda, tipo_calefaccion_id = :opcioncalefaccion, tipo_agua_id = :opcionagua WHERE id = :id";
			$consulta = $this->base->prepare($sql);
			$consulta->execute($datosDem);
		}

		public function ultimoDemografic(){
			$sql = 'SELECT MAX(id) AS id FROM datos_demograficos';
			$consulta = $this->base->prepare($sql);
			 $consulta->execute();
			 $result = $consulta->fetch();
       		 return $result;
		}

	}

  ?>