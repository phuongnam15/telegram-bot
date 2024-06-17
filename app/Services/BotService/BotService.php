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
            logger($update);
            $message = $update['message'];

            $name = "";

            //new chat member
            if (isset($message['new_chat_members'])) {

                if (isset($message['new_chat_member']['first_name'])) {
                    $name .= $message['new_chat_member']['first_name'] . " ";
                }
                if (isset($message['new_chat_member']['last_name'])) {
                    $name .= $message['new_chat_member']['last_name'];
                }
                if ($name === '') {
                    $name = $message['new_chat_member']['username'];
                }

                $chatId = $message['chat']['id'];

                $text = "Chào mừng <strong>{$name}</strong> đến với group!\n\n";

                $configIntro = ContentConfig::where('kind', 'introduce')->first();

                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => $text . $configIntro->content,
                    "parse_mode" => "HTML"
                ]);

                $name = '';
            }
            //start bot
            if (isset($message['text']) && $message['text'] == '/start') {

                if (isset($message['from']['first_name'])) {
                    $name .= $message['from']['first_name'] . " ";
                }
                if (isset($message['from']['last_name'])) {
                    $name .= $message['from']['last_name'];
                }
                if ($name === '') {
                    $name = $message['from']['username'];
                }

                $chatId = $message['chat']['id'];

                User::firstOrCreate(
                    ['telegram_id' => $chatId],
                    [
                        'status' => 'new_chat_member',
                        'name' => $name,
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
                // $buttons = json_decode($buttons);
                // foreach ($buttons as $key => $link) {
                //     $keyboard[] = [['text' => $key, 'url' => $link]];
                // }

                $parameter['reply_markup'] = $buttons;
                // $parameter['reply_markup'] = json_encode([
                //     "inline_keyboard" => [
                //         [
                //             [
                //                 "text" => "Visit Website",
                //                 "url" => "https://www.example.com"
                //             ],
                //         ],
                //         [
                //             [
                //                 "text" => "Reply",
                //                 "callback_data" => "reply_action"
                //             ]

                //         ],
                //         [
                //             [
                //                 "text" => "Alert",
                //                 "callback_data" => "alert_action"
                //             ],
                //         ],
                //         [
                //             [
                //                 "text" => "Switch to inline",
                //                 "switch_inline_query" => "query_data"
                //             ]
                //         ],
                //     ],
                //     "keyboard" => [
                //         [
                //             [
                //                 "text" => "Simple Button"
                //             ],
                //         ],
                //         [
                //             [
                //                 "text" => "Contact",
                //                 "request_contact" => true
                //             ]

                //         ],
                //         [
                //             [
                //                 "text" => "Location",
                //                 "request_location" => true
                //             ]
                //         ]
                //     ],
                //     "resize_keyboard" => true
                // ]);
                // logger($parameter['reply_markup']);
            }

            if ($media) {
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
