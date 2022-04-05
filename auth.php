<?php

	require_once "Clases/auth.class.php";
	require_once "Clases/respuestas.class.php";

$_auth = new auth;   // Autenticador

$_respuestas = new respuestas;

	if($_SERVER['REQUEST_METOD'] = "POST"){

		//Recibe los Datos
		$postbody = file_get_contents("php://input");

		//Enviamos los datos al manejador
		$datosArray= $_auth->login($postbody);
		//print_r(json_encode($datosArray));

		//Devolvemos respuesta
		header('Content-Typer: application/json');

		if (isset($datosArray["result"]["error_id"])){

			$responsecode= $datosArray["result"]["error_id"];
		http_response_code($responsecode);

		}else{

			http_response_code(200);
		}
		echo json_encode($datosArray);

	}else{

		header('Content-Typer: application/json');
		$datosArray = $_respuestas->error_405();
		echo json_encode($datosArray);
	}
	
?> 