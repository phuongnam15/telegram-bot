<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Bot;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\App;

class TelegramConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        App::booted(function () {
            $this->configureTelegram();
        });
    }
    protected function configureTelegram()
    {
        $activeBotToken = Cache::get('active_bot_token', env('TELEGRAM_BOT_TOKEN', '6618205269:AAFKAsIcFvHyYAD6RLitdIq1mmr-l3HocTc'));

        config([
            'telegram.bots.mybot.token' => $activeBotToken,
        ]);
    }
}
