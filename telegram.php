
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

	$botman->hears('turnos/{fecha}', function ($bot, $fecha) {
		$respuesta = file_get_contents("https://grupo63.proyecto2017.linti.unlp.edu.ar/api/index.php/turnos/". $fecha);
		if (!is_string($respuesta)) {
			 $respuesta = json_decode($respuesta);
		}	    
	    $bot->reply($respuesta);
	});

	$botman->hears('reservar/{fecha}/{hora}', function ($bot, $fecha, $hora) {
		$data = array ('fecha' => $fecha, 'hora' => $hora);
		$data = http_build_query($data);
		$opciones = array(
		  'http'=>array(
		    'method'=>"POST",
		    'content'=>$data
		  )
		);
		$contexto = stream_context_create($opciones);
		try {
			$idTurno = fopen('https://grupo63.proyecto2017.linti.unlp.edu.ar/api/index.php/turnos', 'r', false, $contexto);
			fpassthru($idTurno);
			$respuesta = $idTurno;
			fclose($respuesta);	    
			
		} catch (Exception $e) {
			$respuesta	= $e->getMessage();
		}
	    $bot->reply($respuesta);
	});

	$botman->listen();
	/*$bot_username = 'Grupo63bot';
	$bot = new \TelegramBot\Api\Client($bot_api_key);

	$bot->command('start', function ($message) use ($bot) {
	    $answer = 'hola soy Grupo63.';
	    var_dump($message);die();
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


