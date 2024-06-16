<?php

namespace App\Console\Commands;

use App\Models\ContentConfig;
use App\Models\User;
use App\Services\BotService\BotService;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

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
        $userTelegramId = User::all()->pluck('telegram_id')->toArray();
        $configIds = ContentConfig::all()->pluck('id')->toArray();
        $id = Arr::random($configIds);

        foreach ($userTelegramId as $telegramId) {
            $this->botService->send($telegramId, $id);
        }
    }
}
