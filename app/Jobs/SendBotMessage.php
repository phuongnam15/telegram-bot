<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Telegram\Bot\Laravel\Facades\Telegram;

class SendBotMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $parameter;
    protected $type;
    protected $content;
    protected $media;
    /**
     * Create a new job instance.
     */
    public function __construct($parameter, $type, $content, $media)
    {
        $this->parameter = $parameter;
        $this->type = $type;
        $this->content = $content;
        $this->media = $media;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if($this->media) {
            $this->parameter[$this->type] = fopen($this->media, 'r');
        }

        switch ($this->type) {
            case 'text':
                $this->parameter['text'] = $this->content;
                Telegram::sendMessage($this->parameter);
                break;
            case 'photo':
                Telegram::sendPhoto($this->parameter);
                break;
            case 'video':
                Telegram::sendVideo($this->parameter);
                break;
            default:
                break;
        }
    }
}
