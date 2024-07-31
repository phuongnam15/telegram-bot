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
        'title'
    ];
}
