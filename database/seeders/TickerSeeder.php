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
        $response = Http::get("https://api.bitget.com/api/v2/mix/market/tickers?productType=USDT-FUTURES");

        $data = array_map(function($symbol) {
            $a = str_replace('USDT', '', $symbol);
            return [
                "name" => $a,
                "usdt" => $symbol,
                "usd" => $a . 'USD',
                "perp" => $a . 'PERP',
            ];
        }, array_column(json_decode($response->body(), true)['data'], 'symbol'));

        Schema::disableForeignKeyConstraints();
        DB::table('tickers')->truncate();
        
        Ticker::insert($data);

        Schema::enableForeignKeyConstraints();
    }
}
