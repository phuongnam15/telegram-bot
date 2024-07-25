<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\TelegramMessage;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class DeleteTelegramMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $telegramMessage;
    protected $botToken;

    public function __construct(TelegramMessage $telegramMessage, $botToken)
    {
        $this->telegramMessage = $telegramMessage;
        $this->botToken = $botToken;
    }

    public function handle()
    {
        $client = new Client([
            'base_uri' => "https://api.telegram.org/bot{$this->botToken}/",
        ]);

        $response = $client->post("deleteMessage", [
            'json' => [
                'chat_id' => $this->telegramMessage->chat_id,
                'message_id' => $this->telegramMessage->message_id,
            ]
        ]);

        if ($response->getStatusCode() == 200) {
            $this->telegramMessage->delete();
        } else {
            Log::error('Failed to delete Telegram message', [
                'chat_id' => $this->telegramMessage->chat_id,
                'message_id' => $this->telegramMessage->message_id,
                'response' => (string) $response->getBody(),
            ]);
        }
    }
}
