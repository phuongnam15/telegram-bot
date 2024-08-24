<?php

namespace Database\Seeders;

use App\Models\Ticker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class TickerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $responseBitget = Http::get("https://api.bitget.com/api/v2/mix/market/tickers?productType=USDT-FUTURES");

        $dataBitget = array_map(function($symbol) {
            $a = str_replace('USDT', '', $symbol);
            return [
                "name" => $a,
                "usdt" => $symbol,
                "platform" => Ticker::BITGET,
            ];
        }, array_column(json_decode($responseBitget->body(), true)['data'], 'symbol'));

        $timestamp = round(microtime(true) * 1000);
        $responseBingx = Http::get("https://open-api.bingx.com/openApi/swap/v2/quote/contracts?timestamp={$timestamp}");
        $dataBingx = array_map(function($symbol) {
            $a = str_replace('-USDT', '', $symbol);
            return [
                "name" => $a,
                "usdt" => $symbol,
                "platform" => Ticker::BINGX,
            ];
        }, array_column(json_decode($responseBingx->body(), true)['data'], 'symbol'));

        Schema::disableForeignKeyConstraints();
        DB::table('tickers')->truncate();
        
        Ticker::insert($dataBitget);
        Ticker::insert($dataBingx);

        Schema::enableForeignKeyConstraints();
    }
}
