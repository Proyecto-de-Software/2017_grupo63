<?php 

	class PacienteModelo extends Listable
	{

		function __construct()
		{
			parent::__construct();	
		}

		public function listar()
		{
			$sql = "SELECT * FROM paciente where borrado = 0" ;
			$consulta = $this->base->prepare($sql);
			$consulta->execute();
			$pacientes = $consulta-> fetchAll();
			return $pacientes;
		}

		public function eliminar($id){
        $consulta = $this->base->prepare('UPDATE paciente SET borrado = 1 WHERE id = :unId');
		$consulta-> bindParam(':unId', $id, PDO::PARAM_INT);
		$consulta->execute();
		}

		public function get_user($id) {
			$sql = 'SELECT * FROM paciente WHERE id = :unId AND borrado = 0';
			$consulta = $this->base->prepare($sql);
           	$consulta-> bindParam(':unId', $id, PDO::PARAM_INT);
			$consulta->execute();
         	$paciente = $consulta->fetch();
            return $paciente;

        }

        public function editar($paciente)
		{
			
			$sql = ('UPDATE  `paciente`  SET `nombre` =:unNombre , `apellido` =:unApellido, `nacimiento` =:unNacimiento, `genero` =:unGenero, `numDoc` = :unTipoDoc ,  `domicilio` = :unDomicilio ,  `telefono` = :unTelefono ,  `obraSocial` = :unObraSocial 
			WHERE `id` =:unId ');
			
			$consulta = $this->base->prepare($sql);
			
			$consulta-> bindParam(':unNombre', $paciente['nombre'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unApellido', $paciente['apellido'], PDO::PARAM_STR, 256);
			
			$consulta-> bindParam(':unNacimiento', $paciente['nacimiento'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unGenero', $paciente['genero'], PDO::PARAM_STR, 256);
			$consulta-> bindParam(':unTipoDoc', $paciente['numDoc'], PDO::PARAM_INT);
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
	}		
 ?>