<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bot extends Model
{
    use HasFactory;

    protected $table = 'bots';
    protected $fillable = [
        'telegram_id',
        'username',
        'firstname',
        'token',
        'status',
        'admin_id',
        'expired_at',
        'avatar',
        'is_notify_mode'
    ];
    const STATUS_ACTIVE = true;
    const STATUS_INACTIVE = false;
    const NOTI_MODE_ON = 1;
    const NOTI_MODE_OFF = 0;
    const MAP_DAY = [
        100000 => 30,
        200000 => 90,
        300000 => 180,
        400000 => 365,
    ];

    function scheduleDeleteMessage()
    {
        return $this->hasOne(ScheduleDeleteMessage::class, 'bot_id', 'id');
    }
    function scheduleGroupConfig()
    {
        return $this->hasOne(ScheduleGroupConfig::class, 'bot_id', 'id');
    }
    function scheduleConfig()
    {
        return $this->hasOne(ScheduleConfig::class, 'bot_id', 'id');
    }
    function commands()
    {
        return $this->belongsToMany(Command::class, 'bot_command_content', 'bot_id', 'command_id');
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'bot_users', 'bot_id', 'user_id')->withPivot('id', 'status', 'is_actived', 'api_key', 'secret_key', 'passphrase', 'expired_at', 'risk_tolerance', 'created_at');
    }
    public function groups()
    {
        return $this->belongsToMany(TelegramGroup::class, 'bot_groups', 'bot_id', 'group_id');
    }
    public function admin()
    {
        return $this->belongsTo(AdminModel::class, 'admin_id', 'id');
    }
}
