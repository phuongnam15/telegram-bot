<?php

namespace App\Console\Commands;

use App\Models\Bot;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class BankCheck extends Command
{
    protected $signature = 'app:bank-check';
    protected $description = 'Command description';
    public function handle()
    {
        $res = Http::get(env("API_ACB", "https://api.web2m.com/historyapiacbv3/phuongn@M99/12515801/3752CCBB-E108-CD54-216D-16D640567292"));
        
        $status = json_decode($res, true)['status'];

        if(!$status) {
            return;
        }

        $data = json_decode($res, true)['transactions'];
        
        logger($data);

        foreach ($data as $value) {

            if (Str::contains($value['description'], BANK_STATEMENT)) {

                $botId = str_replace(BANK_STATEMENT, "", explode(".", $value['description'])[3]);

                $bot = Bot::find($botId);

                if ($bot) {
                    $days = Bot::MAP_DAY[$value['amount']];
                    $bot->update([
                        'status' => Bot::STATUS_ACTIVE,
                        'expired_at' => now()->addDays($days)
                    ]);
                }
            }
        }
    }
}
