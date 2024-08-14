<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticker extends Model
{
    use HasFactory;
    protected $table = 'tickers';
    protected $fillable = ['name', 'usdt', 'usd', 'perp'];
}
