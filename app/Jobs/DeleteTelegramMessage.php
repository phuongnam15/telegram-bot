<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\TelegramMessage;
use Telegram\Bot\Laravel\Facades\Telegram;

class DeleteTelegramMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $telegramMessage;

    public function __construct(TelegramMessage $telegramMessage)
    {
        $this->telegramMessage = $telegramMessage;
    }

    public function handle()
    {
        Telegram::deleteMessage([
            'chat_id' => $this->telegramMessage->chat_id,
            'message_id' => $this->telegramMessage->message_id,
        ]);

        $this->telegramMessage->delete();
    }
}
