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
	}		
 ?>