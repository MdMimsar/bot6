<?php
require 'vendor/autoload.php';

use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Exception\TelegramException;

// Replace with your actual bot token
$TOKEN = '7521192992:AAG6c1FpDVQ02BU-y0iiDxqXDF2tBqvS58A';

try {
    // Create Telegram API object
    $telegram = new Telegram($TOKEN);
    $telegram->addCommandsPath(__DIR__ . '/Commands');

    // Handle telegram updates
    $telegram->handle();
} catch (TelegramException $e) {
    // Log telegram errors
    echo $e->getMessage();
}
