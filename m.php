<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Entities\ChatMember;

class CallbackqueryCommand extends SystemCommand
{
    protected $name = 'callbackquery';
    protected $description = 'Handle callback query';
    protected $version = '1.0.0';

    public function execute(): ServerResponse
    {
        $callback_query = $this->getCallbackQuery();
        $chat_id = $callback_query->getMessage()->getChat()->getId();
        $user_id = $callback_query->getFrom()->getId();
        $data = $callback_query->getData();

        if ($data === 'check_subscription') {
            $channel = '@IOOffi';
            $member_status = Request::getChatMember([
                'chat_id' => $channel,
                'user_id' => $user_id
            ])->getResult()->getStatus();

            if (in_array($member_status, ['member', 'administrator', 'creator'])) {
                $callback_query->answer([
                    'text' => 'You are subscribed!'
                ]);

                $this-> main_menu($chat_id);
            } else {
                $callback_query->answer([
                    'text' => '❌ Must join all channels'
                ]);

                Request::editMessageText([
                    'chat_id' => $chat_id,
                    'message_id' => $callback_query->getMessage()->getMessageId(),
                    'text' => '❌ Must join all channels'
                ]);
            }
        } elseif ($data === 'ask_question') {
            // Handle ask_question callback
            $conversation = new Conversation($user_id, $chat_id, 'support');
            $conversation->start();

            return Request::sendMessage([
                'chat_id' => $chat_id,
                'text' => 'Please enter your question:'
            ]);
        }

        return Request::emptyResponse();
    }

    private function main_menu($chat_id)
    {
        $keyboard = new InlineKeyboard([
            ['text' => '💰 Balance', 'callback_data' => 'balance'],
            ['text' => '👫 Referral', 'callback_data' => 'referral']
        ], [
            ['text' => '🎁 Bonus', 'callback_data' => 'bonus'],
            ['text' => '💲Withdraw', 'callback_data' => 'withdraw']
        ], [
            ['text' => '⚙️Set wallet', 'callback_data' => 'set_wallet'],
            ['text' => '📞Support', 'callback_data' => 'support']
        ]);

        Request::sendMessage([
            'chat_id' => $chat_id,
            'text' => '▶️ Refer and Earn Cash',
            'reply_markup' => $keyboard
        ]);
    }
}
