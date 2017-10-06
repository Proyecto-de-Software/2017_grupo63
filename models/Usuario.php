	<?php
	require_once('conexionModelo.php');
	class Usuario {
	    	public $id;
			public $email;
			public $username;
			public $password;
			public $activo;
			public $first_name;
			public $last_name;
			public $borrado;

			function __construct($array){
	    	// 	if (count($array)<=1){
	     //  			 $this->id= $array;
	     //  			 var_dump($array); die();
	    	// }else{
	     	 //var_dump($array); die(); 
	        	$this->id= $array['id'];
	        	$this->email= $array['email'];
	        	$this->username= $array['username'];
	        	$this->password= $array['password'];
	        	$this->activo= $array['activo'];
	        	$this->first_name= $array['first_name'];
	        	$this->last_name= $array['last_name'];
	        	$this->borrado= $array['borrado'];
	        	//echo "hola";
	    		}
			public function __get($property) {
      			  if (property_exists($this, $property)) {
           		 return $this->$property;
        	}
   			 }

			public function __set($property, $value) {
       			 if (property_exists($this, $property)) {
            		$this->$property = $value;
        		}
   			 }

		   public function guardar() {
				
	     	$conexion = ConexionABD::darConexion();
	     	
			try{
	        $consulta = $conexion->prepare("INSERT INTO usuario (id, email, username, password, activo, first_name, last_name,borrado) VALUES ('',?,?,?,?,?,?,?)");
	        
	         $consulta->bindParam(1, $this->email);
	         $consulta->bindParam(2, $this->username);
	         $consulta->bindParam(3, $this->password);
	         $consulta->bindParam(4, $this->activo);
	         $consulta->bindParam(5, $this->first_name);
	         $consulta->bindParam(6, $this->last_name);
	         $consulta->bindParam(7, $this->borrado);
	         $this->id = $conexion->lastInsertId();
	         if ( $this->id != "") {
	            $this->ok = true;
	         }else{
	           $this->ok = false;
	        }
	     }
	        catch(PDOException $e){
	         echo "ERROR : " . $e->getMessage();  
	      
	       }
	      $result = true;
	      //desconectar($conn);
	      return $result; 
	      //$conexion = null;

	 	}  
}
