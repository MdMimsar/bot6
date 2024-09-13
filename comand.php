<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class StartCommand extends UserCommand
{
    protected $name = 'start';
    protected $description = 'Start command';
    protected $usage = '/start';
    protected $version = '1.0.0';

    public function execute(): ServerResponse
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $user_id = $message->getFrom()->getId();

        $keyboard = new InlineKeyboard([
            ['text' => '✅CHECK', 'callback_data' => 'check_subscription']
        ]);

        $text = "🛡 Subscribe to our channels to start earning: \n\n➤ @IOOffi\n\n☑️ Done Subscribed! Click ✅CHECK";

        return Request::sendMessage([
            'chat_id' => $chat_id,
            'text' => $text,
            'reply_markup' => $keyboard
        ]);
    }
}
