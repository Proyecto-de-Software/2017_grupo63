
<?php
	require_once  'vendor/autoload.php';
	$bot_api_key  = '363828343:AAE_DQ4D5Mdcb10UqHWCQ5ZRQiAiILFe2fE';
	$bot_username = 'Grupo63bot';
	$bot = new \TelegramBot\Api\Client($bot_api_key);

	$bot->command('start', function ($message) use ($bot) {
	    $answer = 'hola soy Grupo63.';
	    var_dump($message);die();
	    $bot->sendMessage($message->getChat()->getId(), $answer);
	}); 
	// $bot->command('command1', function ($message) use ($bot) {
	//     $answer = '.';
	//     $bot->sendMessage($message->getChat()->getId(), $answer);
	// }); 
	$bot->run();
?>


