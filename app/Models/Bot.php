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
        'status'
    ];
    const STATUS_ACTIVE = true;
    const STATUS_INACTIVE = false;
}
