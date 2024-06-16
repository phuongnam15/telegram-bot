<?php

namespace App\Services\BotService;

use App\Models\ContentConfig;
use App\Models\User;
use App\Services\_Abstract\BaseService;
use App\Services\_Exception\AppServiceException;
use App\Services\_Trait\EntryServiceTrait;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Objects\InputMedia\InputMediaPhoto;


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
            $message = $update['message'];

            //new chat member
            if (isset($update['message']['new_chat_members'])) {
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
            //start bot
            if (isset($update['message']) && $update['message']['text'] == '/start') {
                $firstName = $message['from']['first_name'];
                $lastName = $message['from']['last_name'];
                $chatId = $message['chat']['id'];

                User::firstOrCreate(
                    ['telegram_id' => $chatId],
                    [
                        'status' => 'new_chat_member',
                        'name' => $firstName . ' ' . $lastName,
                        'telegram_id' => $chatId
                    ]
                );
            }
            return 1;
        });
    }
    public function send($telegramId, $configId)
    {
        $user = User::where('telegram_id', $telegramId)->first();
        $config = ContentConfig::where('id', $configId)->first();

        if ($user && $config) {
            $type = $config->type;
            $telegramId = $user->telegram_id;
            $keyboard = [];
            $media = $config->media;
            $content = str_replace('\n', "\n", $config->content);
            $buttons = $config->buttons;

            $parameter = [
                "chat_id" => $telegramId,
                "caption" => $content,
                "parse_mode" => "HTML"
            ];

            if ($buttons) {
                $buttons = json_decode($buttons);
                foreach ($buttons as $key => $link) {
                    $keyboard[] = [['text' => $key, 'url' => $link]];
                }

                $parameter['reply_markup'] = json_encode(['inline_keyboard' => $keyboard]);
            }

            if($media) {
                $parameter[$type] = fopen($media, 'r');
                // $parameter[$type] = fopen(asset("storage/media/" . $media), 'r');
            }


            switch ($type) {
                case 'text':
                    $parameter['text'] = $content;
                    return Telegram::sendMessage($parameter);
                case 'photo':
                    return Telegram::sendPhoto($parameter);
                case 'video':
                    return Telegram::sendVideo($parameter);
                default:
                    throw new AppServiceException('Type not found');
            }

        } else {
            throw new AppServiceException('User or config not found');
        }
    }
}
