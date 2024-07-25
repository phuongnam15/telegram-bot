<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleDeleteMessage extends Model
{
    use HasFactory;
    protected $table = 'schedule_delete_message';
    protected $fillable = [
        'delay_time',
        'bot_id',
        'status',
        'admin_id'
    ];
}
