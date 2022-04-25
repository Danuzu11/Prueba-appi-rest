<?php
	
	require_once "Clases/respuestas.class.php";
	require_once "Clases/pacientes.class.php";	
//Clases de los pacientes
$_respuestas = new respuestas;
$_pacientes = new pacientes;


	switch ($_SERVER['REQUEST_METHOD']) {
		case 'GET':

			If(isset($_GET["page"])){
				$pagina = $_GET["page"];
				$listaPacientes= $_pacientes->lista_pacientes($pagina);
				
				header("Content-Type: application/json");
				echo json_encode($listaPacientes);
				http_response_code(200);

			}else{
				if(isset($_GET["id"]))
				{
					$pacienteId= $_GET["id"];
					$datosPaciente= $_pacientes->obtener_paciente($pacienteId);
					header("Content-Type: application/json");
					echo json_encode($datosPaciente);
					http_response_code(200);					
				}
			}

			break;
		case 'POST':
		//	RECIBIMOS LOS DATOS ENVIADOS
			$postBody = file_get_contents("php://input");
			//ENVIAMOS ESTO AL MANEJADOR
			$datosArray= $_pacientes->post($postBody);
			//Devolvemos respuesta
	
			header('Content-Typer: application/json');

			if (isset($datosArray["result"]["error_id"])){

				$responsecode= $datosArray["result"]["error_id"];
				http_response_code($responsecode);

			}else{

				http_response_code(200);
			}

			echo json_encode($datosArray);

			break;

		case 'PUT':
			//	RECIBIMOS LOS DATOS ENVIADOS
			$postBody = file_get_contents("php://input");
			//ENVIAMOS DATOS AL MANEJADOR
			$datosArray= $_pacientes->put($postBody);

			header('Content-Typer: application/json');

			if (isset($datosArray["result"]["error_id"])){

				$responsecode= $datosArray["result"]["error_id"];
				http_response_code($responsecode);

			}else{

				http_response_code(200);
			}

			echo json_encode($datosArray);

			break;

		case 'DELETE':
						$postBody = file_get_contents("php://input");
			//ENVIAMOS DATOS AL MANEJADOR
			$datosArray= $_pacientes->delete($postBody);

			header('Content-Typer: application/json');

			if (isset($datosArray["result"]["error_id"])){

				$responsecode= $datosArray["result"]["error_id"];
				http_response_code($responsecode);

			}else{

				http_response_code(200);
			}

			echo json_encode($datosArray);
			break;
		
		default:
				header('Content-Typer: application/json');
				$datosArray = $_respuestas->error_405();
				echo json_encode($datosArray);
			break;
	}

?>
