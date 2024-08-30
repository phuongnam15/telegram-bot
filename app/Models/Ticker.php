<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticker extends Model
{
    use HasFactory;
    protected $table = 'tickers';
    protected $fillable = ['name', 'usdt', 'platform'];

    const BITGET = 'bitget';
    const BINGX = 'bingx';
    const BINANCE = 'binance';
    const BYBIT = 'bybit';
    const OKX = 'okx';
    const MEXC = 'mexc';
}
