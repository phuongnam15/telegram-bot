<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\Models\Bot;
use App\Services\BotService\BotService;

class SetTelegramWebhook extends Command
{
    protected $signature = 'telegram:set-webhook';
    protected $description = 'Set the Telegram bot webhook';
    protected $botService;
    public function __construct(BotService $botService)
    {
        parent::__construct();
        $this->botService = $botService;
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
        $getUrl = "https://api.telegram.org/bot{$token}/getWebhookInfo";

        try {
            $response = $client->get($getUrl);
            $webhookInfo = json_decode($response->getBody(), true);

            if ($webhookInfo['ok'] && $webhookInfo['result']['url'] === $webhookUrl) {
                $this->info('Webhook is already set with the same URL.');
                return;
            }

            // If different, remove old webhook first
            $this->botService->disableWebhook($token); // Assuming botService->disableWebhook() method handles webhook deletion

            // Set new webhook
            $setUrl = "https://api.telegram.org/bot{$token}/setWebhook";
            $response = $client->post($setUrl, [
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
            $this->error('Failed to check or set webhook: ' . $e->getMessage());
        }
    }
}
