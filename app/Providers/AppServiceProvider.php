<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\_Transaction\TransactionService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\App;
use App\Models\Bot;
use App\Observers\BotObserver;
use Illuminate\Support\Facades\Artisan;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->singleton("app.transactions", function () {
            return new TransactionService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Bot::observe(BotObserver::class);
        
        App::booted(function () {
            $activeBot = Bot::where('status', Bot::STATUS_ACTIVE)->first();
            if ($activeBot) {
                Cache::put('active_bot_token', $activeBot->token);
                config(['telegram.bots.mybot.token' => $activeBot->token]);

                Artisan::call('telegram:set-webhook');
            }
        });
    }
}
