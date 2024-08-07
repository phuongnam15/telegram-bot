<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalyticGroupMessage extends Model
{
    use HasFactory;
    protected $table = 'analytic_group_message';
    protected $fillable = ['total', 'group_id'];
    public function group()
    {
        return $this->belongsTo(TelegramGroup::class, 'group_id');
    }
}
