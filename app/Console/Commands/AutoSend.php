<?php

namespace App\Console\Commands;

use App\Jobs\SendBotMessage;
use App\Models\ContentConfig;
use App\Models\ScheduleConfig;
use App\Models\ScheduleGroupConfig;
use App\Models\TelegramGroup;
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
        $scheduleGroupConfig = ScheduleGroupConfig::first();

        $configIds = ContentConfig::where('kind', '!=', ContentConfig::KIND_INTRO)
        ->where('kind', '!=', ContentConfig::KIND_BUTTON)
        ->where('kind', '!=', ContentConfig::KIND_START) 
        ->pluck('id')->toArray();

        if(count($configIds) == 0) {
            return;
        }

        if ($scheduleConfig->status == ScheduleConfig::STATUS_ON) {

            $lastDateTime = Carbon::parse($scheduleConfig->lastime);
            $timeToCompare = $lastDateTime->addMinutes($scheduleConfig->time);

            if (now() > $timeToCompare) {
                $scheduleConfig->lastime = now();
                $scheduleConfig->save();

                $userTelegramIds = User::all()->pluck('telegram_id')->toArray();

                foreach ($userTelegramIds as $telegramId) {
                    $randomConfigId = Arr::random($configIds);
                    SendBotMessage::dispatch([$telegramId], $randomConfigId)->onQueue('botSendMessage');
                }
            }
        }

        if ($scheduleGroupConfig->status == ScheduleGroupConfig::STATUS_ON) {
            $lastDateTimeGroup = Carbon::parse($scheduleGroupConfig->lastime);
            $timeToCompareGroup = $lastDateTimeGroup->addMinutes($scheduleGroupConfig->time);

            if (now() > $timeToCompareGroup) {
                $scheduleGroupConfig->lastime = now();
                $scheduleGroupConfig->save();

                $groupTelegramIds = TelegramGroup::all()->pluck('telegram_id')->toArray();

                foreach ($groupTelegramIds as $telegramId) {
                    $randomConfigId = Arr::random($configIds);
                    SendBotMessage::dispatch([$telegramId], $randomConfigId)->onQueue('botSendMessage');
                }
            }
        }
    }
}
