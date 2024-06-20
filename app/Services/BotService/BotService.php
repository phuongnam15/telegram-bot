<?php

namespace App\Services\BotService;

use App\Jobs\SendBotMessage;
use App\Models\ContentConfig;
use App\Models\TelegramGroup;
use App\Models\User;
use App\Services\_Abstract\BaseService;
use App\Services\_Exception\AppServiceException;
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

            if (array_key_exists('message', $update) == 1) {
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

                    $configIntro = ContentConfig::where(['kind' => 'introduce', 'is_default' => true])->first();

                    if ($configIntro) {
                        $type = $configIntro->type;
                        $media = $configIntro->media;
                        $content = preg_replace('/\s*<br>\s*/', "\n", $configIntro->content);
                        $buttons = $configIntro->buttons;

                        $parameter = [
                            "chat_id" => $chatId,
                            "caption" => $text . $content,
                            "parse_mode" => "HTML"
                        ];

                        if ($buttons) {
                            $parameter['reply_markup'] = $buttons;
                        }

                        if ($media) {
                            $parameter[$type] = fopen($media, 'r');
                        }

                        switch ($type) {
                            case 'text':
                                $parameter['text'] = $text . $content;
                                return Telegram::sendMessage($parameter);
                            case 'photo':
                                return Telegram::sendPhoto($parameter);
                            case 'video':
                                return Telegram::sendVideo($parameter);
                            default:
                                throw new AppServiceException('Type not found');
                        }
                    }
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

                    $configIntro = ContentConfig::where(['kind' => 'introduce', 'is_default' => true])->first();

                    if ($configIntro) {
                        $type = $configIntro->type;
                        $media = $configIntro->media;
                        $content = preg_replace('/\s*<br>\s*/', "\n", $configIntro->content);
                        $buttons = json_decode($configIntro->buttons, true);

                        $parameter = [
                            "chat_id" => $chatId,
                            "caption" => $content,
                            "parse_mode" => "HTML"
                        ];

                        $buttons = json_encode($buttons);

                        if ($buttons) {
                            $parameter['reply_markup'] = $buttons;
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
            //reply callback data
            if (array_key_exists('callback_query', $update) == 1) {
                return $this->replyCallback($updates['callback_query']['from']['id'], $updates['callback_query']['data']);
            }
            return 1;
        });
    }
    public function send($telegramIds, $configId)
    {
        $config = ContentConfig::where('id', $configId)->first();

        if (!$config) {
            throw new AppServiceException('Config not found');
        }

        $type = $config->type;
        $media = $config->media;
        $content = preg_replace('/\s*<br>\s*/', "\n", $config->content);
        $buttons = $config->buttons;

        $parameter = [
            "caption" => $content,
            "parse_mode" => "HTML"
        ];

        if ($buttons) {
            $parameter['reply_markup'] = $buttons;
        }


        foreach ($telegramIds as $telegramId) {
            $user = User::where('telegram_id', $telegramId)->first();
            $group = TelegramGroup::where('telegram_id', $telegramId)->first();

            if ($user || $group) {
                $parameter['chat_id'] = $telegramId;

                // if ($media) {
                //     $parameter[$type] = fopen($media, 'r');
                // }

                SendBotMessage::dispatch($parameter, $type, $content, $media);

            } else {
                return response()->json([
                    'message' => 'User not found'
                ]);
            }
        }
        return response()->json([
            'message' => 'Đã nhận yêu cầu, chờ gửi ...'
        ]);
    }
    public function replyCallback($chatId, $data)
    {
        //check callbackdata === config_name
        $config = ContentConfig::where('name', $data)->first();

        if ($config) {
            $type = $config->type;
            $media = $config->media;
            $content = preg_replace('/\s*<br>\s*/', "\n", $config->content);
            $buttons = json_decode($config->buttons, true);

            $parameter = [
                "chat_id" => $chatId,
                "caption" => $content,
                "parse_mode" => "HTML"
            ];

            $buttons = json_encode($buttons);

            if ($buttons) {
                $parameter['reply_markup'] = $buttons;
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
        }
    }
}
