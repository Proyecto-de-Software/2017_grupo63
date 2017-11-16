
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

	$botman->hears('turno/{fecha}', function ($bot, $name) {
	    $bot->reply('la fecha es: '.$fecha);
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


