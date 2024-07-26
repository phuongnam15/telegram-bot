<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleGroupConfig extends Model
{
    use HasFactory;
    protected $table = 'schedule_group_config';
    protected $fillable = [
        'status',
        'time',
        'lastime',
        'bot_id',
        'admin_id'
    ];
    const STATUS_ON = "on";
    const STATUS_OFF = "off";
    
}
