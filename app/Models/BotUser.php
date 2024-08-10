<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotUser extends Model
{
    use HasFactory;
    protected $table = 'bot_users';
    protected $fillable = [
        'bot_id',
        'user_id',
        'status',
        'is_actived',
        'token',
        'expired_at',
        'point_limit',
    ];

    const ACTIVE = 1;
    const INACTIVE = 0;
}
