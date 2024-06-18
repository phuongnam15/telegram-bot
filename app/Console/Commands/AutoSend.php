<?php

namespace App\Console\Commands;

use App\Jobs\SendBotMessage;
use App\Models\ContentConfig;
use App\Models\ScheduleConfig;
use App\Models\User;
use App\Services\BotService\BotService;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class AutoSend extends Command
{
    protected $botService;
    function __construct(BotService $botService)
    {
        parent::__construct();
        $this->botService = $botService;
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto send';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $scheduleConfig = ScheduleConfig::first();
        if ($scheduleConfig->status == ScheduleConfig::STATUS_ON) {

            $lastDateTime = new Carbon($scheduleConfig->lastime);
            $lastDateTime->addMinutes($scheduleConfig->time);

            if (now() > $lastDateTime) {
                $userTelegramId = User::all()->pluck('telegram_id')->toArray();
                $configIds = ContentConfig::all()->pluck('id')->toArray();
                $id = Arr::random($configIds);
    
                // $this->botService->send($userTelegramId, $id);
                SendBotMessage::dispatch($userTelegramId, $id);
            }

        }
    }
}
