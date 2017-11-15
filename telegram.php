
<?php
	require_once  'vendor/autoload.php';
	$bot_api_key  = '363828343:AAE_DQ4D5Mdcb10UqHWCQ5ZRQiAiILFe2fE';
	$bot_username = 'Grupo63bot';
	$bot = new \TelegramBot\Api\Client($bot_api_key);

	$bot->command('start', function ($message) use ($bot) {
	    $answer = 'hola soy Grupo63.';
	    $bot->sendMessage($message->getChat()->getId(), $answer);
	});

	$bot->command('reservar/{fecha}', function ($message) use ($bot) {
	    $answer = 'hola soy fecha.';
	    $bot->sendMessage($message->getChat()->getId(), $answer);
	});
	
	$bot->run();
?>


