<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleConfig extends Model
{
    use HasFactory;
    protected $table = 'schedule_config';
    protected $fillable = [
        'status',
        'time',
        'lastime'
    ];
    const STATUS_ON = "on";
    const STATUS_OFF = "off";
    
}
