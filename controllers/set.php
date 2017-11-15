<?php
// Load composer
$bot_api_key  = '363828343:AAE_DQ4D5Mdcb10UqHWCQ5ZRQiAiILFe2fE';
$bot_username = 'Grupo63bot';
$hook_url = 'https://grupo63.proyecto2017.linti.unlp.edu.ar/telegram.php?action=hookTelegram';

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    // Set webhook
    $result = $telegram->setWebhook($hook_url);
    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // log telegram errors
    echo $e->getMessage();
}