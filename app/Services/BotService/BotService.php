<?php

namespace App\Services\BotService;

use App\Jobs\DeleteTelegramMessage;
use App\Models\Bot;
use App\Models\ContentConfig;
use App\Models\ScheduleDeleteMessage;
use App\Models\TelegramGroup;
use App\Models\TelegramMessage;
use App\Models\User;
use App\Services\_Abstract\BaseService;
use App\Services\_Exception\AppServiceException;
use App\Services\_Trait\EntryServiceTrait;
use GuzzleHttp\Client;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


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
        try {
            $updates = Telegram::getWebhookUpdates();
            $update = json_decode($updates, true);

            if (array_key_exists('message', $update) == 1) {
                $message = $update['message'];

                //check group info
                if (array_key_exists('chat', $message) == 1) {
                    $chatId = $message['chat']['id'];
                    $name = $message['chat']['title'] ?? "";

                    TelegramGroup::firstOrCreate(
                        ['telegram_id' => $chatId],
                        [
                            'name' => $name,
                            'telegram_id' => $chatId
                        ]
                    );
                }

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
                                $response = Telegram::sendMessage($parameter);
                                break;
                            case 'photo':
                                $response = Telegram::sendPhoto($parameter);
                                break;
                            case 'video':
                                $response = Telegram::sendVideo($parameter);
                                break;
                            default:
                                throw new \Exception('Type not found');
                        }

                        $this->saveMessageAndScheduleDeletion($chatId, $response);
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
                        }

                        switch ($type) {
                            case 'text':
                                $parameter['text'] = $content;
                                $response = Telegram::sendMessage($parameter);
                                break;
                            case 'photo':
                                $response = Telegram::sendPhoto($parameter);
                                break;
                            case 'video':
                                $response = Telegram::sendVideo($parameter);
                                break;
                            default:
                                logger('Type not found');
                                $response = [];
                                break;
                        }
                        if ($response !== []) {
                            $this->saveMessageAndScheduleDeletion($chatId, $response);
                        }
                    } else {
                        logger('Config not found');
                    }
                }
            }
            //reply callback data
            if (array_key_exists('callback_query', $update) == 1) {
                return $this->replyCallback($updates['callback_query']['from']['id'], $updates['callback_query']['data']);
            }
            return 1;
        } catch (\Exception $error) {
            logger($error->getMessage());
        }
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

                if ($media) {
                    $parameter[$type] = fopen($media, 'r');
                }

                switch ($type) {
                    case 'text':
                        $parameter['text'] = $content;
                        $response = Telegram::sendMessage($parameter);
                        break;
                    case 'photo':
                        $response = Telegram::sendPhoto($parameter);
                        break;
                    case 'video':
                        $response = Telegram::sendVideo($parameter);
                        break;
                    default:
                        return response()->json([
                            'message' => 'Type not found'
                        ]);
                }

                $this->saveMessageAndScheduleDeletion($telegramId, $response);
            } else {
                return response()->json([
                    'message' => 'User not found'
                ]);
            }
        }
        return 1;
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
    public function saveBot($request)
    {
        try {
            $token = $request->token;

            $client = new Client();

            $response = $client->get("https://api.telegram.org/bot{$token}/getMe");

            $data = json_decode($response->getBody(), true);

            if ($data['ok']) {
                $bot = Bot::create([
                    'token' => $request->token,
                    "name" => $data['result']['username'],
                ]);

                return response()->json($bot);
            } else {
                return response()->json(['error' => $data['description']], $data['error_code']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function list()
    {
        $bots = Bot::paginate(DEFAULT_PAGINATE);

        return response()->json($bots);
    }
    public function activeBot($id)
    {
        $bot = Bot::find($id);

        if (!$bot) {
            return response()->json(['error' => 'Bot not found'], 404);
        }

        DB::beginTransaction();

        try {
            $bot->update(['status' => Bot::STATUS_ACTIVE]);

            Bot::where('id', '!=', $id)->update(['status' => Bot::STATUS_INACTIVE]);

            DB::commit();

            return response()->json($bot, 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Failed to update bot status', 'details' => $e->getMessage()], 500);
        }
    }
    public function delete($id)
    {
        $bot = Bot::find($id);

        if (!$bot) {
            return response()->json(['error' => 'Bot not found'], 404);
        }

        $bot->delete();

        return response()->json(['message' => 'Deleted bot']);
    }
    public function saveMessageAndScheduleDeletion($chatId, $response)
    {
        $scheduleDelay = ScheduleDeleteMessage::first();

        $telegramMessage = TelegramMessage::create([
            'chat_id' => $chatId,
            'message_id' => $response->getMessageId(),
            'sent_at' => Carbon::now()
        ]);

        DeleteTelegramMessage::dispatch($telegramMessage)->delay(now()->addMinutes($scheduleDelay->delay_time));
    }
}
