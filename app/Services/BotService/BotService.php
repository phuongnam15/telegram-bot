<?php

namespace App\Services\BotService;

use App\Jobs\DeleteTelegramMessage;
use App\Models\AnalyticGroupMessage;
use App\Models\AnalyticGroupUser;
use App\Models\Bot;
use App\Models\BotCommandContent;
use App\Models\BotGroup;
use App\Models\BotUser;
use App\Models\Command;
use App\Models\ContentConfig;
use App\Models\GroupUser;
use App\Models\Key;
use App\Models\Password;
use App\Models\PhoneNumber;
use App\Models\ScheduleDeleteMessage;
use App\Models\TelegramGroup;
use App\Models\TelegramMessage;
use App\Models\User;
use App\Models\UserPassword;
use App\Services\_Abstract\BaseService;
use App\Services\_Exception\AppServiceException;
use App\Services\BinanceService\BinanceService;
use App\Services\BingxService\BingxService;
use App\Services\BitgetService\BitgetService;
use App\Services\BybitService\BybitService;
use App\Services\OkxService\OkxService;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;


class BotService extends BaseService
{
    protected $bitgetService;
    protected $bingxService;
    protected $binanceService;
    protected $bybitService;
    protected $okxService;
    public function __construct(
        BitgetService $bitgetService, 
        BingxService $bingxService, 
        BinanceService $binanceService, 
        BybitService $bybitService,
        OkxService $okxService
    )
    {
        $this->bitgetService = $bitgetService;
        $this->bingxService = $bingxService;
        $this->binanceService = $binanceService;
        $this->bybitService = $bybitService;
        $this->okxService = $okxService;
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
            DB::beginTransaction();
            $update = $request->all();

            // logger($update);

            // $this->checkIsUserMessage($update);
            // $this->checkJoinLeftGroup($update, $botId, $botToken, $adminId);
            // $this->checkCommandGroup($update, $botToken);

            if (array_key_exists('message', $update) || array_key_exists('chat_member', $update)) {
                $message = $update['chat_member'] ?? $update['message'];
                $chatId = $message['chat']['id'];

                // $this->checkNewMemberToSendGreeting($message, $adminId, $chatId, $botToken);
                $this->checkMessageContent($message, $chatId, $bot);
            }

            DB::commit();
            return 1;
        } catch (AppServiceException | \Exception $error) {
            DB::rollBack();
            logger($error->getMessage());
            logger($error->getLine());
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
        return DbTransactions()->addCallbackJson(function () use ($chatId, $data, $bot) {});
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
            // logger($data);

            if ($data['ok']) {
                $avatar = $this->getUserOrBotImage($token, $data['result']['id']);

                $bot = Bot::create([
                    'token' => $request->token,
                    "username" => $data['result']['username'],
                    "firstname" => $data['result']['first_name'],
                    "admin_id" => auth()->user()->id,
                    "telegram_id" => $data['result']['id'],
                    "avatar" => $avatar,
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
    public function updateBot($id)
    {
        return DbTransactions()->addCallbackJson(function () use ($id) {
            $admin = auth()->user();
            if (!$admin->telegram_id) {
                throw new AppServiceException('Set Telegram ID in your profile before active this feature');
            }

            $bot = Bot::find($id);

            if (!$bot) {
                throw new AppServiceException('Bot not found');
            }

            $bot->is_notify_mode = !$bot->is_notify_mode;
            $bot->save();

            return $bot;
        });
    }
    public function updateUserTradingPlatform($request)
    {
        return DbTransactions()->addCallbackJson(function () use ($request) {
            $botUser = BotUser::where(['bot_id' => $request->bot_id, 'user_id' => $request->user_id])->first();

            if (!$botUser) {
                throw new AppServiceException('Bot user not found');
            }

            $botUser->trading_platform = $request->trading_platform;
            $botUser->save();

            return $botUser;
        });
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
    public function listUser()
    {
        return DbTransactions()->addCallBackJson(function () {

            $bot = Bot::where('id', request()->bot_id)->first();

            $users = $bot->users()->with('keys');

            if (request()->has('keyword')) {
                $users = $users->where('firstname', 'like', '%' . request()->keyword . '%');
            }

            $users = $users->paginate(DEFAULT_PAGINATE);

            return $users;
        });
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
    public function getGroupImage($botToken, $chatId)
    {
        try {
            $client = new Client();
            $response = $client->get("https://api.telegram.org/bot{$botToken}/getChat", [
                'query' => [
                    'chat_id' =>  $chatId
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            if ($data['ok']) {
                $chat = $data['result'];
                $photo = null;

                if (isset($chat['photo'])) {
                    $photoFileId = $chat['photo']['big_file_id'];

                    // Get the file path
                    $fileResponse = $client->get("https://api.telegram.org/bot{$botToken}/getFile", [
                        'query' => [
                            'file_id' => $photoFileId
                        ]
                    ]);

                    $fileData = json_decode($fileResponse->getBody(), true);

                    if ($fileData['ok']) {
                        $filePath = $fileData['result']['file_path'];
                        $photo = "https://api.telegram.org/file/bot{$botToken}/{$filePath}";
                    }
                }

                return $photo;
            }

            return "";
        } catch (\Exception $error) {
            throw new AppServiceException($error->getMessage());
        }
    }
    public function checkJoinLeftGroup($update, $botId, $botToken, $adminId)
    {
        try {
            //check bot left or join group
            if (isset($update['my_chat_member'])) {

                $myChatMember = $update['my_chat_member'];
                $chatId = $myChatMember['chat']['id'];

                if (isset($myChatMember['new_chat_member']['status']) && ($myChatMember['new_chat_member']['status'] === 'left' || $myChatMember['new_chat_member']['status'] === 'kicked')) {

                    $telegramGroup = TelegramGroup::where('telegram_id', $chatId)->first();

                    if ($telegramGroup) {
                        BotGroup::where([
                            'bot_id' => $botId,
                            'group_id' => $telegramGroup->id
                        ])->delete();
                    }
                }

                if (isset($myChatMember['new_chat_member']['status']) && $myChatMember['new_chat_member']['status'] === 'member') {

                    $groupName = $myChatMember['chat']['username'];
                    $groupTitle = $myChatMember['chat']['title'];

                    $teleGroup = TelegramGroup::firstOrCreate(
                        ['telegram_id' => $chatId],
                        [
                            'name' => $groupName,
                            'title' => $groupTitle,
                            'telegram_id' => $chatId,
                            'admin_id' => $adminId
                        ]
                    );


                    if (!BotGroup::where(['group_id' => $teleGroup->id, 'bot_id' => $botId])->exists()) {

                        $teleGroup->avatar = $this->getGroupImage($botToken, $chatId);
                        $teleGroup->save();

                        BotGroup::create([
                            'group_id' => $teleGroup->id,
                            'bot_id' => $botId
                        ]);
                    }
                }
            }

            //check user left or join group
            if (isset($update['message'])) {
                $message = $update['message'];

                if ($message['from']['is_bot']) {
                    return;
                }

                $group = TelegramGroup::where('telegram_id', $message['chat']['id'])->first();

                if (!$group) {
                    return;
                }

                if (isset($message['left_chat_member']) && isset($message['left_chat_participant'])) {
                    if ($message['left_chat_member']['is_bot']) {
                        return;
                    }

                    $analyticGroupUser = AnalyticGroupUser::where([
                        'group_id' => $group->id,
                        'type' => AnalyticGroupUser::TYPE_LEFT
                    ])->whereBetween('created_at', [Carbon::today(), Carbon::now()])->first();

                    if ($analyticGroupUser) {
                        $analyticGroupUser->total += 1;
                        $analyticGroupUser->save();
                    } else {
                        AnalyticGroupUser::create([
                            'total' => 1,
                            'group_id' => $group->id,
                            'type' => AnalyticGroupUser::TYPE_LEFT
                        ]);
                    }
                }

                if (isset($message['new_chat_member']) && isset($message['new_chat_participant'])) {
                    if ($message['new_chat_member']['is_bot']) {
                        return;
                    }

                    //update amount user join during the day
                    $analyticGroupUser = AnalyticGroupUser::where([
                        'group_id' => $group->id,
                        'type' => AnalyticGroupUser::TYPE_JOIN
                    ])->whereBetween('created_at', [Carbon::today(), Carbon::now()])->first();

                    if ($analyticGroupUser) {
                        $analyticGroupUser->total += 1;
                        $analyticGroupUser->save();
                    } else {
                        AnalyticGroupUser::create([
                            'total' => 1,
                            'group_id' => $group->id,
                            'type' => AnalyticGroupUser::TYPE_JOIN
                        ]);
                    }

                    //save users and group_users
                    $user = User::firstOrCreate(
                        ['telegram_id' => $message['new_chat_member']['id']],
                        [
                            'username' => $message['new_chat_member']['username'] ?? "",
                            'firstname' => $message['new_chat_member']['first_name'] ?? "",
                            'lastname' => $message['new_chat_member']['last_name'] ?? "",
                            'telegram_id' => $message['new_chat_member']['id'],
                            'avatar' => $this->getUserOrBotImage($botToken, $message['new_chat_member']['id'])
                        ]
                    );

                    if ($group->list_ban) {
                        $listBan = json_decode($group->list_ban, true);
                        restrictChatMember($listBan, $group->telegram_id, $user->telegram_id, $group->ban_expired_at, $botToken);
                    }

                    $userGroup = GroupUser::where([
                        'user_id' => $user->id,
                        'group_id' => $group->id,
                    ])->first();
                    if (!$userGroup) {
                        GroupUser::create([
                            'user_id' => $user->id,
                            'group_id' => $group->id,
                        ]);
                    }
                }
            }
        } catch (\Exception $error) {
            logger($error->getLine());
            throw new AppServiceException($error->getMessage());
        }
    }
    public function getUserOrBotImage($botToken, $chatId)
    {
        try {
            $client = new Client();

            $response = $client->get("https://api.telegram.org/bot{$botToken}/getUserProfilePhotos", [
                'query' => [
                    'user_id' => $chatId,
                    'limit' => 1,
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            // logger($data);

            $fileUrl = null;

            if ($data['result']['total_count'] === 0) {
                return $fileUrl;
            }

            $fileId = $data['result']['photos'][0][0]['file_id'];

            $fileResponse = $client->get("https://api.telegram.org/bot{$botToken}/getFile", [
                'query' => [
                    'file_id' => $fileId
                ]
            ]);

            $data2 = json_decode($fileResponse->getBody(), true);
            $filePath = $data2['result']['file_path'];

            $fileUrl = "https://api.telegram.org/file/bot{$botToken}/{$filePath}";


            return $fileUrl;
        } catch (\Exception $error) {
            throw new AppServiceException($error->getMessage());
        }
    }
    public function checkIsUserMessage($update)
    {
        try {
            if (isset($update['message'])) {

                if (isset($update['message']['from']['is_bot']) && $update['message']['from']['is_bot']) {
                    return;
                }

                if (!isset($update['message']['text']) || isset($update['message']['new_chat_member']) || isset($update['message']['left_chat_member'])) {
                    return;
                }

                $group = TelegramGroup::where('telegram_id', $update['message']['chat']['id'])->first();

                if (!$group) {
                    return;
                }

                $startOfDay = Carbon::today();
                $now = Carbon::now();

                $analyticGroupMessage = AnalyticGroupMessage::where('group_id', $group->id)
                    ->whereBetween('created_at', [$startOfDay, $now])
                    ->first();

                if ($analyticGroupMessage) {
                    $analyticGroupMessage->total += 1;
                    $analyticGroupMessage->save();
                } else {
                    AnalyticGroupMessage::create([
                        'total' => 1,
                        'group_id' => $group->id
                    ]);
                }
            }
        } catch (AppServiceException | \Exception $error) {
            logger($error->getLine());
            throw new AppServiceException($error->getMessage());
        }
    }
    public function checkNewMemberToSendGreeting($message, $adminId, $chatId, $botToken)
    {
        try {
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
        } catch (AppServiceException | \Exception $error) {
            throw new AppServiceException($error->getMessage());
        }
    }
    public function checkMessageContent($message, $chatId, $bot)
    {
        try {
            if (isset($message['text'])) {
                $text = $message['text'];

                switch ($text) {
                    case '/start':
                        $this->startCommandDefaultHandler($message, $bot, $chatId);
                        return;
                    case '/trade':
                        $this->tradeCommanDefaultHandler($chatId, $bot);
                        return;
                    case '/me':
                        $this->meCommandDefaultHandler($chatId, $bot);
                        return;
                }
                if ($bot->is_notify_mode) {
                    if ($chatId == $bot->admin->telegram_id) {
                        $users = $bot->users->where('telegram_id', '!=', $bot->admin->telegram_id);
                        foreach ($users as $user) {
                            sendMessage($user->telegram_id, $bot->token, "<strong>👩‍🎤 From Admin</strong>" . "\n" . $text);
                        }
                    } else {
                        $user = User::where('telegram_id', $chatId)->first();
                        $username = $user->username === "" ? $user->firstname . $user->lastname : "@" . $user->username;
                        sendMessage($bot->admin->telegram_id, $bot->token, "<i>{$username}</i>" . "\n" . $text);
                    }
                } else {
                    $this->checkUserStatus($message, $chatId, $bot);
                }
            }
        } catch (AppServiceException | \Exception $error) {
            throw new AppServiceException($error->getMessage());
        }
    }
    public function startCommandDefaultHandler($message, $bot, $chatId)
    {
        try {
            $botId = $bot->id;
            $botToken = $bot->token;

            if (isset($message['from']['first_name'])) {
                $firstname = $message['from']['first_name'] ?? null;
            }
            if (isset($message['from']['last_name'])) {
                $lastname = $message['from']['last_name'] ?? null;
            }
            if (isset($message['from']['username'])) {
                $username = $message['from']['username'] ?? null;
            }

            $avatar = $this->getUserOrBotImage($botToken, $chatId);

            $user = User::firstOrCreate(
                ['telegram_id' => $chatId],
                [
                    'username' => $username ?? "",
                    'firstname' => $firstname ?? "",
                    'lastname' => $lastname ?? "",
                    'telegram_id' => $chatId,
                    'avatar' => $avatar
                ]
            );

            $botUser = BotUser::where([
                'user_id' => $user->id,
                'bot_id' => $botId
            ])->first();

            if (!$botUser) {
                BotUser::create([
                    'status' => 'start',
                    'user_id' => $user->id,
                    'bot_id' => $botId
                ]);
            } else {
                $botUser->status = 'start';
                $botUser->save();
            }

            $name = $firstname . " " . $lastname;
            sendMessage($chatId, $botToken, "👋 <strong>$name</strong>");
        } catch (\Exception $error) {
            throw new AppServiceException($error->getMessage());
        }
    }
    public function tradeCommanDefaultHandler($chatId, $bot)
    {
        try {
            $botId = $bot->id;
            $botToken = $bot->token;

            $botUser = BotUser::where('bot_id', $botId)->whereHas('user', function ($query) use ($chatId) {
                $query->where('telegram_id', $chatId);
            })->first();

            if ($botUser) {
                $botUser->status = 'trade';
                $botUser->save();
            }

            $client = new Client([
                'base_uri' => "https://api.telegram.org/bot{$botToken}/",
            ]);

            $client->post('sendMessage', [
                'json' => [
                    'text' => "🔄 Bạn vừa chuyển sang chế độ bot nhận đặt lệnh.\n📞 Vui lòng liên hệ admin để kích hoạt đặt lệnh.\n\n/start để về lại chế độ ban đầu.",
                    'chat_id' => $chatId
                ]
            ]);
        } catch (\Exception $error) {
            throw new AppServiceException($error->getMessage());
        }
    }
    public function meCommandDefaultHandler($chatId, $bot)
    {
        try {
            $botId = $bot->id;
            $botToken = $bot->token;

            $botUser = BotUser::where('bot_id', $botId)->whereHas('user', function ($query) use ($chatId) {
                $query->where('telegram_id', $chatId);
            })->first();

            $client = new Client([
                'base_uri' => "https://api.telegram.org/bot{$botToken}/",
            ]);

            $client->post('sendMessage', [
                'json' => [
                    'text' => "<strong>{$botUser->user->firstname} {$botUser->user->lastname}</strong>\n\n🧑‍🎤Username: {$botUser->user->username}\n🚀Telegram ID: {$botUser->user->telegram_id}\n⛺️Trade Mode: " . ($botUser->is_actived ? "active" : "inactive") . "\n🌺Trade Mode Expired At: {$botUser->expired_at}\n🌊Risk Tolerance: {$botUser->risk_tolerance}",
                    'chat_id' => $chatId,
                    'parse_mode' => 'HTML'
                ]
            ]);
        } catch (AppServiceException | \Exception $error) {
            throw new AppServiceException($error->getMessage());
        }
    }
    public function checkUserStatus($message, $chatId, $bot)
    }
    public function checkCommandGroup($update, $botToken)
    {
        try {
            if (isset($update['message'])) {
                $message = $update['message'];

                if (isset($message['chat'])) {
                    $type = $message['chat']['type'];

                    if ($type === 'supergroup' || $type === 'group') {

                        if (!isset($message['text'])) {
                            return;
                        }

                        if (in_array(explode(' ', $message['text'])[0], TelegramGroup::DEFAULT_COMMAND)) {

                            $arrayCommandValue = explode(' ', $message['text']);

                            if (count($arrayCommandValue) != 2) {
                                return;
                            }

                            $commandType = $arrayCommandValue[0];
                            $chatId = $message['chat']['id'];

                            $adminIds = $this->getChatAdministrators($botToken, $chatId);

                            if (!$adminIds) {
                                return;
                            }

                            if (!in_array($message['from']['id'], $adminIds)) {
                                return;
                            }

                            $expiredAt = convertBanExpireTime($arrayCommandValue[2]);

                            switch ($commandType) {
                                case '/ban':
                                    $result = banChatMember($chatId, $arrayCommandValue[1], $expiredAt, $botToken);
                                    break;
                                case '/unban':
                                    $result = unbanChatMember($chatId, $arrayCommandValue[1], $botToken);
                                    break;
                                case '/mute':
                                    $result = restrictChatMember([
                                        'can_send_messages' => false,
                                        'can_send_media_messages' => false,
                                        'can_send_polls' => false,
                                        'can_send_other_messages' => false,
                                        'can_add_web_page_previews' => false,
                                    ], $chatId, $arrayCommandValue[1], $expiredAt, $botToken);
                                    break;
                                case '/unmute':
                                    $result = restrictChatMember([
                                        'can_send_messages' => true,
                                        'can_send_media_messages' => true,
                                        'can_send_polls' => true,
                                        'can_send_other_messages' => true,
                                        'can_add_web_page_previews' => true,
                                    ], $chatId, $arrayCommandValue[1], $expiredAt, $botToken);
                                    break;
                                case '/nolink':
                                    $result = restrictChatMember([
                                        'can_add_web_page_previews' => false,
                                    ], $chatId, $arrayCommandValue[1], $expiredAt, $botToken);
                                    break;
                                case '/allowlink':
                                    $result = restrictChatMember([
                                        'can_add_web_page_previews' => true,
                                    ], $chatId, $arrayCommandValue[1], $expiredAt, $botToken);
                                    break;
                            }

                            $type = substr($commandType, 1);
                            if ($result) {
                                sendMessage($chatId, "<strong>$type</strong> <i>$arrayCommandValue[1]</i> success", $botToken);
                            } else {
                                sendMessage($chatId, "<strong>$type</strong> <i>$arrayCommandValue[1]</i> failed", $botToken);
                            }
                        }
                    }
                }
            }
        } catch (AppServiceException $error) {
            logger($error->getLine());
            throw new AppServiceException($error->getMessage());
        }
    }
    public function getChatAdministrators($botToken, $chatId)
    {
        $client = new Client();
        try {
            $response = $client->get("https://api.telegram.org/bot{$botToken}/getChatAdministrators", [
                'query' => [
                    'chat_id' => $chatId
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            if ($data['ok']) {
                return array_map(function ($item) {
                    return $item['user']['id'];
                }, array_filter($data['result'], function ($item) {
                    return in_array($item['status'], ['administrator', 'creator']);
                }));
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
    public function createOrder($vol, $input, $client, $chatId, $key, $platform)
    {
        try {
            $apiKey = $key->api_key;
            $secretKey = $key->secret_key;
            $passphrase = $key->passphrase;

            switch ($platform) {
                case 'bitget':
                    $this->bitgetService->createOrderBitget($vol, $input, $client, $chatId, $apiKey, $secretKey, $passphrase);
                    break;
                case 'bingx':
                    $this->bingxService->createOrderBingx($vol, $input, $client, $chatId, $apiKey, $secretKey);
                    break;
                case 'binance':
                    $this->binanceService->createOrderBinance($vol, $input, $client, $chatId, $apiKey, $secretKey);
                    break;
                case 'bybit':
                    $this->bybitService->createOrderBybit($vol, $input, $client, $chatId, $apiKey, $secretKey);
                    break;
                case 'okx':
                    $this->okxService->createOrderOkx($vol, $input, $client, $chatId, $apiKey, $secretKey, $passphrase);
                    break;
                default:
                    break;
            }
        } catch (\Exception $error) {
            throw new AppServiceException($error->getMessage());
        }
    }
}