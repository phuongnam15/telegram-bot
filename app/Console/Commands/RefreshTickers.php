<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RefreshTickers extends Command
{
    protected $signature = 'refresh:tickers';

    protected $description = 'Refresh Tickers';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Artisan::call('db:seed', [
            '--class' => 'TickerSeeder',
        ]);
    }
}
