<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotTradingSlat extends Model
{
    use HasFactory;
    protected $table = 'bot_trading_stats';
    protected $fillable = [
        'bot_id',
        'total_trading_command',
        'total_trading_failed',
    ];
}
