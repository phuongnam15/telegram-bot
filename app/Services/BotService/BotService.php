<?php

namespace App\Services\BotService;

use App\Jobs\DeleteTelegramMessage;
use App\Models\AdminUser;
use App\Models\Bot;
use App\Models\ContentConfig;
use App\Models\Password;
use App\Models\PhoneNumber;
use App\Models\ScheduleDeleteMessage;
use App\Models\TelegramGroup;
use App\Models\TelegramMessage;
use App\Models\User;
use App\Models\UserPassword;
use App\Repositories\ScheduleConfig\IScheduleConfigRepo;
use App\Repositories\ScheduleDeleteMessage\IScheduleDeleteMessageRepo;
use App\Repositories\ScheduleGroupConfig\IScheduleGroupConfigRepo;
use App\Services\_Abstract\BaseService;
use App\Services\_Exception\AppServiceException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Arr;


class BotService extends BaseService
{
    public function __construct()
    {
    }

    public function webhook($request, $botId)
    {
        $bot = Bot::find($botId);
        if (!$bot) {
            return response()->json(['error' => 'Bot not found'], 404);
        }
        $botToken = $bot->token;
        $adminId = $bot->admin_id;

        try {
            $update = $request->all();
            $client = new Client([
                'base_uri' => "https://api.telegram.org/bot{$botToken}/",
            ]);

            if (array_key_exists('message', $update) || array_key_exists('chat_member', $update)) {
                $message = $update['chat_member'] ?? $update['message'];
                $chatId = $message['chat']['id'];
                $chatType = $message['chat']['type'] ?? null;

                // Check group info
                if (in_array($chatType, ['group', 'supergroup', 'channel'])) {
                    $name = $message['chat']['title'] ?? $message['chat']['username'] ?? "";

                    TelegramGroup::firstOrCreate(
                        ['telegram_id' => $chatId],
                        [
                            'name' => $name,
                            'telegram_id' => $chatId,
                            'admin_id' => $adminId
                        ]
                    );
                }

                $name = "";

                // New chat member
                if (isset($message['new_chat_member'])) {
                    if (!isset($message['new_chat_member']['status'])) {
                        return;
                    } elseif ($message['new_chat_member']['status'] !== 'member') {
                        return;
                    }

                    $newChatMember = $message['new_chat_member']['user'] ?? $message['new_chat_member'];
                    $firstName = $newChatMember['first_name'] ?? '';
                    $lastName = $newChatMember['last_name'] ?? '';
                    $username = $newChatMember['username'] ?? '';

                    $name = trim("$firstName $lastName");
                    $name = $name ?: $username;

                    $text = "Chào mừng <strong>{$name}</strong> đến với group!\n\n";

                    $configIntro = ContentConfig::where([
                        'kind' => ContentConfig::KIND_INTRO,
                        'is_default' => true,
                        'admin_id' => $adminId
                    ])->first();

                    if ($configIntro) {
                        $this->send([$chatId], $configIntro->id, $botToken, $text);
                    }
                }

                // Check message
                if (isset($message['text'])) {
                    // Start bot
                    if ($message['text'] == '/start') {
                        if (isset($message['from']['first_name'])) {
                            $name .= $message['from']['first_name'] . " ";
                        }
                        if (isset($message['from']['last_name'])) {
                            $name .= $message['from']['last_name'];
                        }
                        if ($name === '') {
                            $name = $message['from']['username'];
                        }

                        $user = User::firstOrCreate(
                            ['telegram_id' => $chatId],
                            [
                                'status' => 'start',
                                'name' => $name,
                                'telegram_id' => $chatId,
                            ]
                        );

                        $adminUserExists = AdminUser::where([
                            'user_id' => $user->id, 
                            'admin_id' => $adminId
                        ])->exists();
                        if (!$adminUserExists) {
                            AdminUser::create([
                                'user_id' => $user->id,
                                'admin_id' => $adminId,
                            ]);
                        }

                        $configIntro = ContentConfig::where([
                            'kind' => ContentConfig::KIND_START,
                            'is_default' => true,
                            'admin_id' => $adminId
                        ])->first();

                        if ($configIntro) {
                            $this->send([$chatId], $configIntro->id, $botToken);
                        }
                    } else {
                        // Check status of user
                        $user = User::where('telegram_id', $chatId)->first();

                        if ($user && ($user->status !== 'start')) {
                            $status = $user->status;

                            switch ($status) {
                                case "check_password":
                                    $password = $message['text'];

                                    if (Password::where('password', $password)->exists()) {
                                        $userPass = UserPassword::where(['telegram_id' => $chatId, 'password' => $password])->first();

                                        if ($userPass) {
                                            if (Carbon::now()->diffInMinutes($userPass->updated_at) > PASS_VALID_TIME) {
                                                $text = PhoneNumber::inRandomOrder()->first()->phone_number;
                                                $user->status = 'start';
                                                $user->save();
                                                $userPass->updated_at = Carbon::now();
                                                $userPass->save();
                                            } else {
                                                $text = "Mật khẩu không chính xác.";
                                            }
                                        } else {
                                            $text = PhoneNumber::inRandomOrder()->first()->phone_number;
                                            $user->status = 'start';
                                            $user->save();
                                            UserPassword::create([
                                                'telegram_id' => $chatId,
                                                'password' => $password,
                                            ]);
                                        }
                                    } else {
                                        $text = "Mật khẩu không chính xác.";
                                    }

                                    $client->post('sendMessage', [
                                        'json' => [
                                            'chat_id' => $chatId,
                                            'text' => $text
                                        ]
                                    ]);

                                    break;
                            }
                        }
                    }
                }
            }
            // Reply callback data
            if (array_key_exists('callback_query', $update)) {
                return $this->replyCallback($update['callback_query']['from']['id'], $update['callback_query']['data'], $bot);
            }
            return 1;
        } catch (\Exception $error) {
            logger($error->getMessage());
        }
    }
    public function send($telegramIds, $configId, $botToken, $text = "")
    {
        try {
            $config = ContentConfig::where('id', $configId)->first();

            if (!$config) {
                return response()->json([
                    'message' => 'Config not found'
                ]);
            }

            $type = $config->type;
            $media = $config->media;
            $content = preg_replace('/\s*<br>\s*/', "\n", $config->content);
            $buttons = $config->buttons;

            $parameter = [
                "caption" => $text . $content,
                "parse_mode" => "HTML"
            ];

            if ($buttons) {
                $parameter['reply_markup'] = $buttons;
            }

            $client = new Client([
                'base_uri' => "https://api.telegram.org/bot{$botToken}/"
            ]);

            foreach ($telegramIds as $telegramId) {
                $user = User::where('telegram_id', $telegramId)->first();
                $group = TelegramGroup::where('telegram_id', $telegramId)->first();

                if ($user || $group) {
                    $parameter['chat_id'] = $telegramId;

                    try {
                        $multipart = [
                            [
                                'name'     => 'chat_id',
                                'contents' => $telegramId
                            ],
                            [
                                'name'     => 'caption',
                                'contents' => $text . $content
                            ],
                            [
                                'name'     => 'parse_mode',
                                'contents' => 'HTML'
                            ]
                        ];

                        if ($buttons) {
                            $multipart[] = [
                                'name'     => 'reply_markup',
                                'contents' => $buttons
                            ];
                        }

                        if ($media) {
                            $multipart[] = [
                                'name'     => $type,
                                'contents' => fopen($media, 'r')
                            ];
                        }

                        switch ($type) {
                            case 'text':
                                $parameter['text'] = $text . $content;
                                $response = $client->post('sendMessage', [
                                    'json' => $parameter
                                ]);
                                break;
                            case 'photo':
                                $response = $client->post('sendPhoto', [
                                    'multipart' => $multipart
                                ]);
                                break;
                            case 'video':
                                $response = $client->post('sendVideo', [
                                    'multipart' => $multipart
                                ]);
                                break;
                            default:
                                return response()->json([
                                    'message' => 'Type not found'
                                ]);
                        }

                        $this->saveMessageAndScheduleDeletion($telegramId, json_decode($response->getBody(), true), $botToken);
                    } catch (\Exception $e) {
                        logger($e->getMessage());
                    }
                } else {
                    return response()->json([
                        'message' => 'User not found'
                    ]);
                }
            }
            return response()->json([
                'message' => 'Messages sent successfully'
            ]);
        } catch (\Exception $error) {
            logger($error->getMessage());
            return response()->json([
                'message' => 'An error occurred'
            ], 500);
        }
    }
    public function replyCallback($chatId, $data, $bot)
    {
        return DbTransactions()->addCallbackJson(function () use ($chatId, $data, $bot) {

            $adminId = $bot->admin_id;
            if ($data === 'get_phone_number') {

                $ids = ContentConfig::where([
                    'name' => $data,
                    'admin_id' => $adminId
                ])->get()->pluck('id')->toArray();

                if (count($ids) === 0) {
                    return 1;
                }

                $config = ContentConfig::where([
                    'id' => Arr::random($ids),
                    'admin_id' => $adminId
                ])->first();
            } else {
                $config = ContentConfig::where([
                    'name' => $data,
                    'admin_id' => $adminId
                ])->first();
            }


            if ($config) {
                $this->send([$chatId], $config->id, $bot->token);

                //update status user by callbackdata
                if ($data === 'get_phone_number') {
                    User::where('telegram_id', $chatId)->update(['status' => 'check_password']);
                }
            }

            return 1;
        });
    }
    public function saveBot($request)
    {
        return DbTransactions()->addCallbackJson(function () use ($request) {
            $token = $request->token;

            if (Bot::where('token', $token)->exists()) {
                return response()->json(['error' => 'Bot already exists'], 400);
            }

            $client = new Client();

            $response = $client->get("https://api.telegram.org/bot{$token}/getMe");

            $data = json_decode($response->getBody(), true);

            if ($data['ok']) {
                $bot = Bot::create([
                    'token' => $request->token,
                    "name" => $data['result']['username'],
                    "admin_id" => auth()->user()->id
                ]);

                ScheduleDeleteMessage::create([
                    'bot_id' => $bot->id,
                    'delay_time' => 5,
                    'admin_id' => auth()->user()->id
                ]);

                return $bot;
            } else {
                throw new AppServiceException($data['description']);
            }
        });
    }
    public function list()
    {
        $bots = Bot::where('admin_id', auth()->user()->id)->get();

        return response()->json($bots);
    }
    public function activeBot($id, $request)
    {
        $bot = Bot::find($id);

        if (!$bot) {
            return response()->json(['error' => 'Bot not found'], 404);
        }

        DB::beginTransaction();

        try {
            $bot->update([
                'status' => Bot::STATUS_ACTIVE,
                'expired_at' => Carbon::now()->addMonths($request->month_qty)
            ]);

            // Set new webhook
            $client = new Client();
            $webhookUrl = env('TELEGRAM_WEBHOOK_URL');

            if (!$webhookUrl) {
                throw new AppServiceException('TELEGRAM_WEBHOOK_URL is not set in the .env file.');
            }

            $response = $client->post("https://api.telegram.org/bot{$bot->token}/setWebhook", [
                'form_params' => [
                    'url' => rtrim($webhookUrl, "/") . "/" . $bot->id,
                    'allowed_updates' => json_encode(ALLOW_UPDATE)
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            if (!$data['ok']) {
                throw new AppServiceException('Failed to set webhook: ' . $data['description']);
            }

            DB::commit();

            return response()->json($bot, 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Failed to update bot status', 'details' => $e->getMessage()], 500);
        }
    }
    public function delete($id)
    {
        $bot = Bot::where(['admin_id' => auth()->user()->id, 'id' => $id])->first();

        if (!$bot) {
            return response()->json(['error' => 'Bot not found'], 404);
        }

        $this->disableWebhook($bot->token);

        $bot->delete();

        return response()->json(['message' => 'Deleted bot']);
    }
    public function saveMessageAndScheduleDeletion($chatId, $response, $botToken)
    {
        $bot = Bot::where('token', $botToken)->first();
        $scheduleDelay = ScheduleDeleteMessage::where('bot_id', $bot->id)->first();

        if ($scheduleDelay->status === "off") {
            return;
        }

        $telegramMessage = TelegramMessage::create([
            'chat_id' => $chatId,
            'message_id' => $response['result']['message_id'],
            'sent_at' => Carbon::now(),
            'bot_id' => $bot->id
        ]);

        DeleteTelegramMessage::dispatch($telegramMessage, $botToken)->delay(now()->addMinutes($scheduleDelay->delay_time))->onQueue('deleteBotMessage');
    }
    public function disableWebhook($token)
    {
        $client = new Client();
        $url = "https://api.telegram.org/bot{$token}/deleteWebhook";

        try {
            $response = $client->post($url);

            $data = json_decode($response->getBody(), true);

            if ($data['ok']) {
                return response()->json(['message' => 'Webhook deleted successfully']);
            } else {
                return response()->json(['error' => 'Failed to delete webhook', 'details' => $data['description']], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete webhook', 'details' => $e->getMessage()], 500);
        }
    }
    public function listScheduleBot($id)
    {
        return DbTransactions()->addCallBackJson(function () use ($id) {

            $bot = Bot::with([
                'scheduleDeleteMessage',
                'scheduleConfig',
                'scheduleGroupConfig'
            ])->find($id);

            return $bot;
        });
    }
}
