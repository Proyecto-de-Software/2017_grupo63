<?php 
	/**
	* requerir en el controlador que se crea un modelo
	*/
	
	abstract class ConexionABD
	{
		const USERNAME = "grupo63";
    	const PASSWORD = "ZTJhNTVlODlhMDk0";
		const HOST ="localhost";
		const DB = "grupo63";
		var $base;	

		function __construct()
		{	
			    $u=self::USERNAME;
        	$p=self::PASSWORD;
        	$db=self::DB;
        	$host=self::HOST;
        	$this->base = new PDO("mysql:dbname=$db;host=$host", $u, $p);
        	$this->base->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		
		public static function darConexion()
		{
			$u=self::USERNAME;
        	$p=self::PASSWORD;
        	$db=self::DB;
        	$host=self::HOST;
        	$connection = new PDO("mysql:dbname=$db;host=$host", $u, $p);
        	$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        	return $connection;
		}
	}
	

	 // public function queryList($sql, $args, $mapper){
  //       $connection = $this->getConnection();
  //       $stmt = $connection->prepare($sql);
  //       $stmt->execute($args);
  //       $list = [];
  //       while($element = $stmt->fetch()){
  //           $list[] = $mapper($element);
  //       }
  //       return $list;
  //   }


  //   public function queryListAll($sql){
  //       $connection = $this->getConnection();
  //       $stmt = $connection->prepare($sql);
  //       $stmt->execute();
  //       $element = $stmt->fetchAll();   
  //       return $element;
  //   }

  //   public function queryGet($sql,$id){
  //       $connection = $this->getConnection();
  //       $stmt = $connection->prepare($sql);
  //       $stmt->execute(array($id));
  //       $element = $stmt->fetchAll();
       
  //       return $element;
  //   }

  //    public function queryLogin($sql,$usr,$pass){
  //       $connection = $this->getConnection();         
  //       $stmt = $connection->prepare($sql);
  //       $stmt->execute(array($usr, $pass));
  //       $result = $stmt->fetch();
  //       if ($result[0] != "") {
            
  //            return $result;
  //       }else{
  //           $result= false;
  //           return $result;
  //       }   
       
       
  //   }

 ?>