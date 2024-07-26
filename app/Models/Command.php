<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Command extends Model
{
    use HasFactory;
    protected $table = 'commands';
    protected $fillable = ['command'];
    
    public function bots() {
        return $this->belongsToMany(Bot::class, 'bot_command_content', 'command_id', 'bot_id');
    }
}
