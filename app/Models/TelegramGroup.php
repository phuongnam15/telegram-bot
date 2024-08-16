<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramGroup extends Model
{
    use HasFactory;
    protected $table = 'telegram_groups';
    protected $fillable = [
        'telegram_id',
        'name',
        'admin_id',
        'avatar',
        'title',
        'list_ban',
        'ban_expired_at',
    ];
    const DEFAULT_COMMAND = [
        '/ban',
        '/unban',
        '/mute',
        '/unmute',
        '/nolink',
        '/allowlink'
    ];
    public function bots()
    {
        return $this->belongsToMany(Bot::class, 'bot_groups', 'group_id', 'bot_id');
    }
    public function analyticMessages()
    {
        return $this->hasMany(AnalyticGroupMessage::class, 'group_id');
    }
    public function analyticUsers()
    {
        return $this->hasMany(AnalyticGroupUser::class, 'group_id');
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'group_users', 'group_id', 'user_id');
    }
}
