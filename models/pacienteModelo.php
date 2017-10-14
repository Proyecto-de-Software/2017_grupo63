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


	}		
 ?>