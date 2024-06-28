<?php

namespace App\Jobs;

use App\Services\BotService\BotService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendBotMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $telegramIds;
    protected $contentId;
    /**
     * Create a new job instance.
     */
    public function __construct($telegramIds, $contentId)
    {
        $this->telegramIds = $telegramIds;
        $this->contentId = $contentId;
    }

    /**
     * Execute the job.
     */
    public function handle(BotService $botService): void
    {
        $botService->send1($this->telegramIds, $this->contentId);
    }
}
