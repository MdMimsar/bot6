<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Entities\ChatMember;

class GenericmessageCommand extends SystemCommand
{
    protected $name = 'genericmessage';
    protected $description = 'Handle generic message';
    protected $version = '1.0.0';

    public function execute(): ServerResponse
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $user_id = $message->getFrom()->getId();
        $text = trim($message->getText(true));

        // Get conversation data
        $conversation = new Conversation($user_id, $chat_id, $this->getName());
        $notes = &$conversation->notes;
        !is_array($notes) && $notes = [];

        if (strpos($text, '/set_wallet') === 0) {
            $wallet = trim(substr($text, 12)); 
            $notes['PaytmWallet'] = $wallet;
            $conversation->update();
            return Request::sendMessage([
                'chat_id' => $chat_id,
                'text' => "✅ Paytm wallet address set to: $wallet"
            ]);
        }

        if (strpos($text, '/withdraw') === 0) {
            $balance = $notes['balance'] ?? 0;
            $wallet = $notes['PaytmWallet'] ?? null;

            if (!$wallet) {
                return Request::sendMessage([
                    'chat_id' => $chat_id,
                    'text' => '❌ Wallet not set'
                ]);
            } elseif ($balance < 20) {
                return Request::sendMessage([
                    'chat_id' => $chat_id,
                    'text' => '❌ You need at least 100 ₹ to withdraw!'
                ]);
            } else {
                // Assuming you will handle the withdrawal amount input here
                return Request::sendMessage([
                    'chat_id' => $chat_id,
                    'text' => '📤 Enter amount in ₹'
                ]);
            }
        }

        // ... other command handlers (bonus, referral, support)

        return Request::emptyResponse();
    }
}
