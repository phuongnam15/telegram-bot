<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPassword extends Model
{
    use HasFactory;
    protected $table = 'user_password';
    protected $fillable = ['telegram_id', 'password'];
}
