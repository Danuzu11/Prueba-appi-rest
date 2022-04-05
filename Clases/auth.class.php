<?php

	require_once "Conexion/Conexion.php";
	require_once "respuestas.class.php";

class auth extends Conexion{
		
		public function login($json)
		{
			$_respuestas = new respuestas;
			$datos = json_decode($json,true);

			if (!isset($datos['usuario']) || !isset($datos['password'])) {
				//error en los campos
				return $_respuestas->error_400();
			} else {
				//todo esta bien
				$usuario = $datos['usuario'];
				$password = $datos['password'];
				$password = parent::Encriptar($password);
				$datos = $this->Obtener_datos_usuario($usuario);

				if ($datos) {
					// Si existe el correo
					if ($password == $datos[0]['Password']) {
						//SI LA CONTRASE;A ES CORRECTA

						if ($datos[0]['Estado'] == "Activo") {
							// SI EL USUARIO ESTA ACTIVO
							$verificar = $this->insertarToken($datos[0]['Usuarioid']);
							if($verificar) {
								$result = $_respuestas->response;
								$result["result"] = array(
										"token" => $verificar
								);
								return $result;
							}else{
								return $_respuestas->error_500("Error interno");
							} 

							
						}else{
							// SI EL USUARIO NO ESTA ACTIVO

							return $_respuestas->error_200("Usuario esta inactivo");
						}
					
					} else {
						// SI LA CONTRASE;A ES INCORRECTA
						return $_respuestas->error_200("El password es incorrecto");
					}
					
				} else {
					// Si no existe el correo
					return $_respuestas->error_200("El usuario $usuario no existe");
				}
				
			}
			

		}

		private function Obtener_datos_usuario($correo)
		{
		
			$query = "SELECT Usuarioid,Password,Estado FROM usuarios WHERE Usuario = '$correo'" ;
			$datos = parent::obtener_Datos($query);

			if (isset($datos[0]["Usuarioid"])) {
				return $datos;
			} else {
				return 0;
			}
		}

		private function insertarToken($usuarioid)
		{
			$val= true;
			$token = bin2hex(openssl_random_pseudo_bytes(16,$val));
			$date = date("Y-m-d H:i");
			$estado = "Activo";
            $query = "INSERT INTO usuarios_token (Usuarioid,Token,Estado,Fecha)VALUES('$usuarioid','$token','$estado','$date')";
            $verificar = parent::nonQuery($query);
            if ($verificar) {
            	return $token;
            }else{
            	return 0 ;
            }
		}
		
	
}

?>