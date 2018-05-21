<?php 
	/**
	* 
	*/
	class ConsultaPag 
	{
		
		private $actual;
		private $total;
		private $datos; 

		function __construct($actual, $total, $datos) {
			$this->actual = $actual;
			$this->total = $total;
			$this->datos = $datos;
		} 
		
		public function getActual()
		{
			return $this->actual;
		}
		
		public function getTotal()
		{
			return $this->total;
		}
		
		public function getDatos()
		{
			return $this->datos;
		}
	}
 ?>