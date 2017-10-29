<?php 

	/**
	* 
	*/
	class Listable extends ConexionABD
	{
		
		function __construct()
		{
			parent::__construct();
		}
	
		public function getLimitOffset($table, $pagina, $where, $args, $select = '*', $alias = "" )
		{
			$dc = new DatosConfig();
			(int)$limite = $dc->paginas();
			(int)$offset = ($pagina - 1) * $limite;
			$sql = "SELECT " . $select . " FROM $table WHERE " . $alias ."borrado = 0 " . $where ;
			$consulta = $this->base->prepare($sql);
			$total = $consulta->execute($args); 
			$total = $consulta->rowCount();
			$paginasTotales = ceil($total / $limite);
			$pp = new ParametrosPag($limite, $offset, $paginasTotales);
			return $pp;
		}

		public function getDatosPara($table, $limit, $offset, $where, $args, $select = '*', $alias = "")
		{
			$sql = "SELECT ". $select  ." FROM $table WHERE " .$alias ."borrado = 0 ". $where ." LIMIT $offset, $limit " ;
			$this->base->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
			$consulta = $this->darConexion()->prepare($sql);
			var_dump($sql);
			$consulta->execute($args);
			return $consulta->fetchAll();
		}

		public function acomodarASql($fecha)
        {
          $fechaSQL = explode("/", $fecha); 
          return str_replace(" ", "", implode("-", $this->acomodar($fechaSQL)));
        }

     public function acomodarDeSql($fechaSQL)
        { 
          $fecha = explode("-", $fechaSQL); 
          return str_replace(" ", "", implode("/", $this->acomodar($fecha)));
        }
		
        public function acomodarFecha($fecha)
        {
        	$fecha = explode("-", $fecha); 
        	return str_replace(" ", "", implode("-", $this->acomodar($fecha)));
        }
        public function acomodar($arregloF){

        	$aux = $arregloF[0];
        	$arregloF[0] = $arregloF[2];
        	$arregloF[2] = $aux;
        	return str_replace(" ", "", $arregloF);
        }
	}

	/**
	* 
	*/
	class ParametrosPag
	{
		
		private $paginasTotales;
		private $limit;
		private $offset;

		function __construct($limit, $offset, $paginasTotales)
		{
			$this->limit = $limit;
			$this->offset = $offset;
			$this->paginasTotales = $paginasTotales;
		}

		public function getLimit()
		{
			return $this->limit;
		}
		
		public function setLimit($limit)
		{
			$this->limit = $limit;
		}
		
		public function getOffset()
		{
			return $this->offset;
		}
		
		public function setOffset($offset)
		{
			$this->offset = $offset;
		}
		
		public function getPaginasTotales()
		{
			return $this->paginasTotales;
		}
		
		public function setPaginasTotales($paginasTotales)
		{
			$this->paginasTotales = $paginasTotales;
		}
	}
 ?>