<?php

namespace App\Console\Commands;

use App\Models\Bot;
use App\Models\TelegramGroup;
use App\Services\BotService\BotService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateAvatarTelegramEntities extends Command
{
    protected $service;
    function __construct(BotService $botService)
    {
        parent::__construct();
        $this->service = $botService;
    }
    protected $signature = 'tele:update-avatar';
    protected $description = 'Update Avatr Telegram Entities';
    public function handle()
    {
        try {
            DB::beginTransaction();

            $bots = Bot::get();
            $groups = TelegramGroup::get();

            foreach ($bots as $bot) {
                $avatar = $this->service->getUserOrBotImage($bot->token, $bot->telegram_id);
                $bot->avatar = $avatar;
                $bot->save();
            }

            foreach ($groups as $group) {
                $bots = $group->bots;

                if($bots->isEmpty()) {
                    continue;
                }

                $avatar = $this->service->getGroupImage($bots[0]->token, $group->telegram_id);
                $group->avatar = $avatar;
                $group->save();
            }

            DB::commit();
            $this->info('Update avatar successfully');

        } catch (\Exception $e) {
            logger($e->getMessage());
            DB::rollBack();
            $this->error($e->getMessage());
        }
    }
}
