<?php

namespace App\Observers;

use App\Models\Bot;
use App\Services\BotService\BotService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class BotObserver
{
    protected $botService;
    public function __construct(BotService $botService)
    {
        $this->botService = $botService;
    }
    public function updated(Bot $bot)
    {
        $this->botService->disableWebhook($bot->token);

        if ($bot->status == Bot::STATUS_ACTIVE) {

            Cache::put('active_bot_token', $bot->token);

            // Cập nhật cấu hình động
            config([
                'telegram.bots.mybot.token' => $bot->token,
            ]);
        }

        // Thiết lập webhook
        Artisan::call('telegram:set-webhook');
    }
}
