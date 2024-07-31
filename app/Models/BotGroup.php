<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotGroup extends Model
{
    use HasFactory;
    protected $table = 'bot_groups';
    protected $fillable = [
        'group_id',
        'bot_id'
    ];
}
