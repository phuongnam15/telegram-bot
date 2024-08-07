<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalyticGroupUser extends Model
{
    use HasFactory;
    protected $table = 'analytic_group_user';
    protected $fillable = ['total', 'group_id', 'type'];
    const TYPE_LEFT = 'left';
    const TYPE_JOIN = 'join';
    public function group()
    {
        return $this->belongsTo(TelegramGroup::class, 'group_id');
    }
}
