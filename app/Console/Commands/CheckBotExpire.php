<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Bot;
use App\Services\BotService\BotService;

class CheckBotExpire extends Command
{
    protected $signature = 'bot:check-expire';
    protected $description = 'Check bot expire';
    protected $botService;
    public function __construct(BotService $botService)
    {
        parent::__construct();
        $this->botService = $botService;
    }
   
    public function handle()
    {
        $bots = Bot::where('status', Bot::STATUS_ACTIVE)->get();

        foreach ($bots as $bot) {
            if ($bot->expired_at < now()) {
                $bot->status = Bot::STATUS_INACTIVE;
                $bot->save();
                $this->botService->disableWebhook($bot->token);
            }
        }
    }
}
