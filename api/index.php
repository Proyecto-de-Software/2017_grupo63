<?php 
	require_once '../vendor/autoload.php';
	require_once '../models/conexionModelo.php';
	require_once '../models/turnosModelo.php';
	require_once '../models/listables.php';
	require_once '../models/pacienteModelo.php';
	require_once '../vendor/autoload.php';
	require '../vendor/slim/slim/Slim/App.php';

	use \Psr\Http\Message\ServerRequestInterface as Request;
	use \Psr\Http\Message\ResponseInterface as Response;

	$config = [
	    'settings' => [
	        'displayErrorDetails' => true,
	    ],
	];
	
	$app = new \Slim\App($config);
	
	$app->get('/turnos/{fecha}', function ($request, $response, $args) {
	   	//$response->withStatus(200);
		//$res->setBody(json_encode($meses));
		$turnosM = new TurnosModelo();
		if (!$turnosM->verificarFecha($args['fecha'])) {
			$response->withHeader('Content-Type', 'text/plain');
			$response->getBody()->write("Formato de fecha no valido. ");
			$response->getBody()->write("El formato de fecha debe ser dd-mm-aaaa");
			return $response;
		}
		
		return $response->withJson($turnosM->turnos($args['fecha']), 200);
	});

	$app->post('/turnos', function ($request, $response, $args) {
		   	
		   	$data = $request->getParsedBody();
		   	$dni = $data['dni'];
		   	$fecha = $data['fecha'];
		   	$hora = $data['hora'];
		  	$turnosM = new TurnosModelo();
			$response->withHeader('Access-Control-Allow-Origin', "*");
			$response->withHeader('Content-Type', 'text/plain');
			if (!$turnosM->verificarFecha($fecha) || !$turnosM->verificarHora($hora)) {
				$response->getBody()->write("Formato de fecha o de hora no son validos. ");
				$response->getBody()->write("El formato de fecha debe ser dd-mm-aaaa y el de la hora debe ser hh:mm entre las 8 y las 20");
				return $response;
			}
			if ($turnosM->yaPaso($fecha)) {
				$response->getBody()->write("La fecha del turno no puede ser anterior al dia actual.");
				return $response;
			}
			if ($turnosM->estaOcupado($fecha, $hora)) {
				$response->getBody()->write("Ese turno no se encuentra disponible.");
				return $response;
			}
			$idturno = $turnosM->reservar($dni, $fecha, $hora);
			if ($idturno == 0) {
				$response->getBody()->write("Su turno no se pudo reservar.");
				return $response;	
			}
			$response->getBody()->write("Su identificacion de turno es " . $idturno);
			return $response; 	
	});
	
	$app->get('/graficos/{id}', function ($request, $response, $args) {
		$pm = new PacienteModelo();
		return $response->withJson($pm->datosGrafico($args['id']), 200);
	});
	
	$app->run();
 ?>