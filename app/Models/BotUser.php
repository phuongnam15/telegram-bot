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
        'api_key',
        'secret_key',
        'passphrase',
        'expired_at',
        'risk_tolerance',
    ];

    const ACTIVE = 1;
    const INACTIVE = 0;

    public function bot()
    {
        return $this->belongsTo(Bot::class, 'bot_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
