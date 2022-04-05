<?php

	
class Conexion
{

			private $server;
			private $user;
			private $password;
			private $database;
			private $conexion;
			private $port;

	     function __construct() 
		{
			//parent::__construct();

			$listadatos = $this->datosConexion();

			foreach ($listadatos as $key => $value) {
				$this->server= 	 $value['server'];
				$this->user= 	 $value['user'];
				$this->password= $value['password'];
				$this->database= $value['database'];
				$this->port= $value['port'];
			}

			$this->conexion = new mysqli($this->server,$this->user,$this->password,$this->database,$this->port);
			
			if($this->conexion->connect_errno)
			{
					echo "Algo va mal con conexion";
					die();
			}

		}


		private function datosConexion()
		{

			$direccion = dirname(__FILE__);
			$jsondata = file_get_contents($direccion."/"."config");
			return json_decode($jsondata, true);
		}


		private function convertir_Utp8($array)
		{
			array_walk_recursive($array, function(&$item,$key){
				if(!mb_detect_encoding($item,'utf-8',true)){
					$item = utf8_encode($item);
				}

			});
			return $array;
		}

		public function obtener_Datos($slqstr){
			$results = $this->conexion->query($slqstr);
			$result_Array= array();
			foreach ($results as $key) {
				$result_Array[] = $key;
			}
			return $this->convertir_Utp8($result_Array);
		}

		public function nonQuery($slqstr){
			$results = $this->conexion->query($slqstr);	
			return $this->conexion->affected_rows;
		}
		
		public function nonQueryId($slqstr){
			$results = $this->conexion->query($slqstr);

			$filas = $this->conexion->affected_rows;

			if($filas >= 1)
			{
					return $this->conexion->insert_id;
			}else{
				return 0;
			}
		}

		//Ecriptar contrase;a
		protected function Encriptar($string)
		{
			return md5($string);
		}


}

?>