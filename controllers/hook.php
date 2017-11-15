<?php

$bot_api_key  = '363828343:AAE_DQ4D5Mdcb10UqHWCQ5ZRQiAiILFe2fE';
$bot_username = 'Grupo63bot';
 $website = "https://grupo63.proyecto2017.linti.unlp.edu.ar/telegram.php?action=hookTelegram".$bot_api_key;

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    // Handle telegram webhook request
    //$result = Request::sendMessage(['chat_id' => $chat_id, 'text' => 'Your utf8 text 😜 ...']);
    
    //var_dump(expression)
    //file_put_contents(filename, data)
    $telegram->handle();
    $update = file_get_contents($website."/getupdates");
    //$update = file_get_contents("php://input");
    $updateArray = json_decode($update, TRUE);
    $chatId = end($updateArray ["result"])["message"]["chat"]["id"];

    $text = end($updateArray["result"])["message"]["text"];
    echo "hola";
    var_dump($chatId);
    var_dump($text);die();

} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Silence is golden!
    // log telegram errors
    echo $e->getMessage();
}