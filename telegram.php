<?php
	require_once  'vendor/autoload.php';
	$bot_api_key  = '363828343:AAE_DQ4D5Mdcb10UqHWCQ5ZRQiAiILFe2fE';
	use Mpociot\BotMan\BotManFactory;
	use Mpociot\BotMan\BotMan;
	$config = [
	   'telegram_token' => $bot_api_key,
	];

	// create an instance
	$botman = BotManFactory::create($config);

	$botman->hears('turnos {fecha}', function ($bot, $fecha) {
		$options = array(
          'http'=>array(
            'method'=>"GET",
            'header'=>"Content-Type: application/json\r\n" .
                      "Accept: */*\r\n" .
                      "Connection: Keep-Alive"
          )
        );
        $context = stream_context_create($options);
		$turnos = file_get_contents("https://grupo63.proyecto2017.linti.unlp.edu.ar/web/app.php/turnos/". $fecha, false, $context);
		$temporal = json_decode($turnos);
		if (!is_null($temporal)) {
			 $turnos = json_decode($turnos, true);
			 if (empty($turnos)) {
			  		$respuesta = "No contamos con turnos disponibles.";
			} 
			else {
			  		
					 $respuesta = "Los turnos disponibles son:";
					 foreach ($turnos as $turno) {
					 	$respuesta .=  " ".$turno;
					 	if (next($turnos)==true) {$respuesta .= ",";}
					 	else{$respuesta .= ".";}	
					 }
			  	}
			  	 	
		}
		else{$respuesta = $turnos;}	    
	    $bot->reply($respuesta);
	});

	$botman->hears('reservar {dni} {fecha} {hora}', function ($bot, $dni, $fecha, $hora) {
		$data = array ('dni' => $dni, 'fecha' => $fecha, 'hora' => $hora);
		/*$data = http_build_query($data);
		$opciones = array(
		  'http'=>array(
		    'method'=>"POST",
		    'header'=> "Content-type: application/x-www-form-urlencoded",
		    'content'=>$data
		  )
		);
		$contexto = stream_context_create($opciones);
		try {
			$idTurno = fopen('https://grupo63.proyecto2017.linti.unlp.edu.ar/api/index.php/turnos', 'r', false, $contexto);
			fpassthru($idTurno);
			$respuesta = $idTurno;
			fclose($idTurno);	    
			
		} catch (Exception $e) {
			$respuesta	= $e->getMessage();
		}
	    $bot->reply($respuesta);*/
	    $ch = curl_init('https://grupo63.proyecto2017.linti.unlp.edu.ar/web/app.php/turnos');
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$respuesta = curl_exec($ch);
		curl_close($ch);
		$bot->reply($respuesta);
	});

	$botman->listen();
	/*$bot_username = 'Grupo63bot';
	$bot = new \TelegramBot\Api\Client($bot_api_key);

	$bot->command('start', function ($message) use ($bot) {
	    $answer = 'hola soy Grupo63.';
	    $bot->sendMessage($message->getChat()->getId(), $answer);

	});

	$bot->command('turnos', function ($message) use ($bot) {
	    $answer = 'hola soy Grupo63.';
	    $bot->sendMessage($message->getChat()->getId(), $answer);

	});
	
	$bot->command('command1', function ($message) use ($bot) {
	    $answer = 'hola soy Grupo64.';
	    $bot->sendMessage($message->getChat()->getId(), $answer);
	}); 

	
	$bot->run();*/

?>


