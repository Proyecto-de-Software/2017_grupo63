<?php 
	/**
	* 
	*/
	class CURLController 
	{
		public function obtenerDatos($url)
		{
			/*$ch = curl_init("https://api-referencias.proyecto2017.linti.unlp.edu.ar/" . $url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$datos = curl_exec($ch);
			curl_close($ch);
			$datos = json_decode($datos, true);*/
			$datos = $this->obtener($url);
			$acomodado = array();
			foreach ($datos as $dato) {
				$acomodado[$dato['id']] = $dato['nombre'];
			}
			return $acomodado;
		}

		public function obtenerDato($url)
		{
			$dato = $this->obtener($url);
			return $dato["nombre"];
		}
	
		private function obtener($url)
		{
			$ch = curl_init("https://api-referencias.proyecto2017.linti.unlp.edu.ar/" . $url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$datos = curl_exec($ch);
			curl_close($ch);
			$datos = json_decode($datos, true);
			return $datos;
		}
	}
 ?>