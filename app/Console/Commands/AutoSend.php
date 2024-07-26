<?php

namespace App\Console\Commands;

use App\Jobs\SendBotMessage;
use App\Models\Bot;
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
    protected $signature = 'app:auto-send';
    protected $description = 'Auto send';
    protected $botService;
    function __construct(BotService $botService)
    {
        parent::__construct();
        $this->botService = $botService;
    }
    public function handle()
    {
        try {
            $bots = Bot::where('status', Bot::STATUS_ACTIVE)->get();

            foreach ($bots as $bot) {
                $scheduleConfig = $bot->scheduleConfig;
                $scheduleGroupConfig = $bot->scheduleGroupConfig;

                $configIds = ContentConfig::where('kind', '!=', ContentConfig::KIND_INTRO)
                    ->where('kind', '!=', ContentConfig::KIND_BUTTON)
                    ->where('kind', '!=', ContentConfig::KIND_START)
                    ->where('admin_id', $bot->admin_id)
                    ->pluck('id')
                    ->toArray();

                if (count($configIds) == 0) {
                    continue;
                }

                if ($scheduleConfig && $scheduleConfig->status == ScheduleConfig::STATUS_ON) {
                    $lastDateTime = Carbon::parse($scheduleConfig->lastime);
                    $timeToCompare = $lastDateTime->addMinutes($scheduleConfig->time);

                    if (now() > $timeToCompare) {
                        logger('Send to user');
                        $scheduleConfig->lastime = now();
                        $scheduleConfig->save();

                        $userTelegramIds = User::where('admin_id', $bot->admin_id)->pluck('telegram_id')->toArray();

                        foreach ($userTelegramIds as $telegramId) {
                            $randomConfigId = Arr::random($configIds);
                            SendBotMessage::dispatch([$telegramId], $randomConfigId, $bot->token)->onQueue('botSendMessage');
                        }
                    }
                }

                if ($scheduleGroupConfig && $scheduleGroupConfig->status == ScheduleGroupConfig::STATUS_ON) {
                    $lastDateTimeGroup = Carbon::parse($scheduleGroupConfig->lastime);
                    $timeToCompareGroup = $lastDateTimeGroup->addMinutes($scheduleGroupConfig->time);

                    if (now() > $timeToCompareGroup) {
                        logger('Send to group');
                        $scheduleGroupConfig->lastime = now();
                        $scheduleGroupConfig->save();

                        $groupTelegramIds = TelegramGroup::where('admin_id', $bot->admin_id)->pluck('telegram_id')->toArray();

                        foreach ($groupTelegramIds as $telegramId) {
                            $randomConfigId = Arr::random($configIds);
                            SendBotMessage::dispatch([$telegramId], $randomConfigId, $bot->token)->onQueue('botSendMessage');
                        }
                    }
                }
            }

            $this->info('Auto send successfully!');
        } catch (\Exception $e) {
            logger($e->getMessage());
        }
    }
}
