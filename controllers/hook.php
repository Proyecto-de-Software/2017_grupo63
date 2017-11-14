<?php
// Load composer
require  '../vendor/autoload.php';

$bot_api_key  = '363828343:AAE_DQ4D5Mdcb10UqHWCQ5ZRQiAiILFe2fE';
$bot_username = 'Grupo63bot';

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    // Handle telegram webhook request
    //$result = Request::sendMessage(['chat_id' => $chat_id, 'text' => 'Your utf8 text 😜 ...']);
    
    //var_dump(expression)
    //file_put_contents(filename, data)
    $telegram->handle();

} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Silence is golden!
    // log telegram errors
    // echo $e->getMessage();
}