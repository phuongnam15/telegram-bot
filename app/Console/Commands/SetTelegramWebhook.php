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

            if ($webhookInfo['ok'] && $webhookInfo['result']['url'] === $webhookUrl && !isset($webhookInfo['result']['last_error_date'])) {
                $this->info('Webhook is already set with the same URL.');
                return;
            }

            // If different, remove old webhook first
            $this->botService->disableWebhook($token); // Assuming botService->disableWebhook() method handles webhook deletion

            $allowedUpdates = [
                'message', // Nhận tin nhắn mới.
                'edited_message', // Nhận phiên bản chỉnh sửa của tin nhắn đã được bot biết đến.
                'channel_post', // Nhận bài đăng mới trong kênh.
                'edited_channel_post', // Nhận phiên bản chỉnh sửa của bài đăng trong kênh.
                'inline_query', // Nhận truy vấn nội tuyến mới.
                'chosen_inline_result', // Nhận kết quả truy vấn nội tuyến được người dùng chọn.
                'callback_query', // Nhận truy vấn callback mới.
                'shipping_query', // Nhận truy vấn vận chuyển mới (chỉ dành cho hóa đơn với giá linh hoạt).
                'pre_checkout_query', // Nhận truy vấn trước khi thanh toán (chứa thông tin chi tiết về thanh toán).
                'poll', // Nhận trạng thái mới của cuộc thăm dò ý kiến.
                'poll_answer', // Nhận câu trả lời của người dùng trong cuộc thăm dò ý kiến không ẩn danh.
                'my_chat_member', // Trạng thái thành viên của bot trong một cuộc trò chuyện được cập nhật.
                'chat_member', // Trạng thái của một thành viên trong một cuộc trò chuyện được cập nhật.
                'chat_join_request' // Nhận yêu cầu tham gia trò chuyện mới.
            ];

            // Set new webhook
            $setUrl = "https://api.telegram.org/bot{$token}/setWebhook";
            $response = $client->post($setUrl, [
                'form_params' => [
                    'url' => $webhookUrl,
                    'allowed_updates' => json_encode($allowedUpdates)
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
