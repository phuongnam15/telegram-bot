<?php

namespace App\Services\BotService;

use App\Services\_Abstract\BaseService;
use App\Services\_Trait\EntryServiceTrait;
use Telegram\Bot\Laravel\Facades\Telegram;


class BotService extends BaseService
{
    use EntryServiceTrait {
        createFromArray as createFromArrayTrait;
        updateFromRequest as updateFromRequestTrait;
    }

    public function __construct()
    {
    }

    public function webhook()
    {
        return DbTransactions()->addCallBackJson(function () {
            $updates = Telegram::getWebhookUpdates();
            $update = json_decode($updates, true);
            logger($update);
            
            //new chat member
            if (isset($update['message']['new_chat_members'])) {
                $message = $update['message'];
                $firstName = $message['new_chat_member']['first_name'];
                $lastName = $message['new_chat_member']['last_name'];
                $chatId = $message['chat']['id'];

                $text = "Chào mừng <strong>{$firstName} {$lastName}</strong> đến với group!";

                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => $text,
                    "parse_mode" => "HTML"
                ]);
            }
            return 1;
        });
    }
}
