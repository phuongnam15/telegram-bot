<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\Models\Bot;

class SetTelegramWebhook extends Command
{
    protected $signature = 'telegram:set-webhook';
    protected $description = 'Set the Telegram bot webhook';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $bot = Bot::where('status', Bot::STATUS_ACTIVE)->first();

        if (!$bot) {
            $this->error('No active bot found.');
            return;
        }

        $token = $bot->token;
        $webhookUrl = env('TELEGRAM_WEBHOOK_URL');

        if (!$webhookUrl) {
            $this->error('TELEGRAM_WEBHOOK_URL is not set in the .env file.');
            return;
        }

        $client = new Client();
        $url = "https://api.telegram.org/bot{$token}/setWebhook";

        try {
            $response = $client->post($url, [
                'form_params' => [
                    'url' => $webhookUrl
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            if ($data['ok']) {
                $this->info('Webhook set successfully.');
            } else {
                $this->error('Failed to set webhook: ' . $data['description']);
            }
        } catch (\Exception $e) {
            $this->error('Failed to set webhook: ' . $e->getMessage());
        }
    }
}
