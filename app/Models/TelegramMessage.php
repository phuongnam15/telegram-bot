<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramMessage extends Model
{
    use HasFactory;
    protected $table = 'telegram_messages';
    protected $fillable = [
        'chat_id', 
        'message_id', 
        'sent_at'
    ];
}