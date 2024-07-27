<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\Models\Bot;
use App\Services\BotService\BotService;

class SetWebhook extends Command
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
        $bots = Bot::where('status', Bot::STATUS_ACTIVE)->get();

        if ($bots->isEmpty()) {
            return;
        }

        foreach($bots as $bot) {
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
    
                if ($webhookInfo['ok'] && $webhookInfo['result']['url'] === $webhookUrl && !isset($webhookInfo['result']['last_error_date'])) {
                    $this->info('Webhook is already set with the same URL.');
                    return;
                }
    
                $this->botService->disableWebhook($token);
    
                $setUrl = "https://api.telegram.org/bot{$token}/setWebhook";
                $response = $client->post($setUrl, [
                    'form_params' => [
                        'url' => rtrim($webhookUrl, '/') . '/' . $bot->id,
                        'allowed_updates' => json_encode(ALLOW_UPDATE)
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
}