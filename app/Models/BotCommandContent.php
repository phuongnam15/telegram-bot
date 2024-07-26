<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotCommandContent extends Model
{
    use HasFactory;
    protected $table = 'bot_command_content';
    protected $fillable = ['bot_id', 'command_id', 'content_id'];

    function bot()
    {
        return $this->belongsTo(Bot::class, 'bot_id', 'id');
    }
    function command()
    {
        return $this->belongsTo(Command::class, 'command_id', 'id');
    }
    function content()
    {
        return $this->belongsTo(ContentConfig::class, 'content_id', 'id');
    }
}
