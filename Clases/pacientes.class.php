<?php

	require_once "Conexion/Conexion.php";
	require_once "respuestas.class.php";	

// PRUEBA GIIIIIIIIIIIIIIIT
	// AGONIAAAAAAAAAAA
class pacientes extends Conexion
{
		private $table= "pacientes";
		private $pacienteid = "";		
		private $dni = "";
		private $nombre = "";
		private $direccion = "";
		private $codigoPostal = "";
		private $genero = "";
		private $telefono = "";
		private $fechaNacimiento = "0000-00-00";
		private $correo = "";

		//funcion para mostrar pacientes por paginas
		public function lista_pacientes($pagina = 1)
		{
			$inicio = 0;
			$cantidad = 3;

			if($pagina > 1){
				$inicio = ($cantidad * ($pagina-1)) ;
				//$cantidad = $cantidad ;
			}
			$query = "SELECT PacienteId,Nombre,DNI,telefono,Correo FROM ". $this->table . " limit $inicio,$cantidad";
			$datos = parent::obtener_Datos($query);
			return ($datos);
		}	

		public function obtener_paciente($id){
			$query = "SELECT * FROM " . $this->table . " WHERE PacienteId = '$id'";
			return parent::obtener_Datos($query);
 
		}

		public function post($json){

			$_respuestas= new respuestas;

			$datos= json_decode($json,true);

			if(!isset($datos['nombre']) || !isset($datos['dni'])  || !isset($datos['correo']))
			{
				return $_respuestas->error_400();
			}else{
				$this->nombre = $datos['nombre'];
				$this->dni = 	$datos['dni'];
				$this->correo = $datos['correo'];				
				// Validaciones de los demas datos
				if(isset($datos['telefono'])){ $this->telefono = $datos['telefono'];}
				if(isset($datos['direccion'])){ $this->direccion = $datos["direccion"];}
				if(isset($datos['codigoPostal'])){ $this->codigoPostal = $datos['codigoPostal'];}
				if(isset($datos['genero'])){ $this->genero = $datos['genero'];}
				if(isset($datos['fechaNacimiento'])){ $this->fechaNacimiento = $datos['fechaNacimiento'];}

				$resp = $this->insertar_paciente();

				if ($resp) {
					$respuesta = $_respuestas->response;
					$respuesta["result"] = array(
							"pacienteId" => $resp
					);
					return $respuesta;

				} else {

					return $_respuestas->error_500();
				}
				

			}
		}	

		private function insertar_paciente()
		{
				$query = "INSERT INTO ". $this->table . " (DNI,Nombre,Direccion,CodigoPostal,Telefono,Genero,FechaNacimiento,Correo)
				values
				('" . $this->dni . "','" . $this->nombre . "','" . $this->direccion . "','" . $this->codigoPostal . "','" . $this->telefono . "','" . 
				$this->genero . "','" . $this->fechaNacimiento . "','" . $this->correo . "')" ; 
				
				$resp = parent::nonQueryId($query);
				if ($resp) {
					return $resp;
				} else {
					return 0;
				}
		}


		public function put($json){

			$_respuestas= new respuestas;

			$datos= json_decode($json,true);

			if(!isset($datos['pacienteId']))
			{
				return $_respuestas->error_400();
			}else{

				$this->pacienteid = $datos['pacienteId'];			
				// Validaciones de los demas dapacienteID
				if(isset($datos['nombre'])){ $this->nombre = $datos['nombre'];}
				if(isset($datos['dni'])){ $this->dni = $datos["dni"];}
				if(isset($datos['correo'])){ $this->correo = $datos['correo'];}
				if(isset($datos['telefono'])){ $this->telefono = $datos['telefono'];}
				if(isset($datos['direccion'])){ $this->direccion = $datos["direccion"];}
				if(isset($datos['codigoPostal'])){ $this->codigoPostal = $datos['codigoPostal'];}
				if(isset($datos['genero'])){ $this->genero = $datos['genero'];}
				if(isset($datos['fechaNacimiento'])){ $this->fechaNacimiento = $datos['fechaNacimiento'];}

				$resp = $this-> modificar_paciente();


				if ($resp) {
					$respuesta = $_respuestas->response;
					$respuesta["result"] = array(
							"pacienteId" => $this->pacienteid
					);
					return $respuesta;

				} else {

					return $_respuestas->error_500();
				}
				
			}
		}	

		private function modificar_paciente()
		{
				$query = "UPDATE ". $this->table ." SET Nombre ='".$this->nombre."',Direccion = '".$this->direccion."',DNI = '".$this->dni."', CodigoPostal = '".$this->codigoPostal."', Telefono = '".$this->telefono."', Genero = '".$this->genero."', FechaNacimiento = '".$this->fechaNacimiento."', Correo = '".$this->correo."' WHERE PacienteId = '".$this->pacienteid."'"; 
				
				$resp = parent::nonQuery($query);
				if ($resp >=1 ) {
					return $resp;
				} else {
					return 0;
				}
		}

		public function delete($json){

			$_respuestas= new respuestas;

			$datos= json_decode($json,true);

			if(!isset($datos['pacienteId']))
			{
				return $_respuestas->error_400();
			}else{

				$this->pacienteid = $datos['pacienteId'];			
				
				$resp = $this-> eliminar_paciente();


				if ($resp) {
					$respuesta = $_respuestas->response;
					$respuesta["result"] = array(
							"pacienteId" => $this->pacienteid
					);
					return $respuesta;

				} else {

					return $_respuestas->error_500();
				}
				
			}
		}	

		private function eliminar_paciente(){

			$query = "DELETE FROM ". $this->table . " WHERE PacienteId= '" . $this->pacienteid . "'";
			$resp = parent::nonQuery($query);
			if ($resp >=1 ) {
					return $resp;
			} else {
					return 0;
			}

		}

		
}

?>
