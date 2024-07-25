<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bot extends Model
{
    use HasFactory;

    protected $table = 'bots';
    protected $fillable = [
        'name',
        'token',
        'status',
        'admin_id',
        'expired_at'
    ];
    const STATUS_ACTIVE = true;
    const STATUS_INACTIVE = false;

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
}
